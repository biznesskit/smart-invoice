<?php

namespace App\Helpers;

use App\Helpers\Utilities;
use App\Jobs\Etims\AcknowledgeEtimsPurchase;
use App\Jobs\Etims\StoreClassificationCode;
use App\Jobs\Etims\SyncStockMaster;
use App\Jobs\WebHooks\InvoiceTransmitedWebHookJob;
use App\Jobs\WebHooks\StockItemTransmittedWebHookJob;
use App\Models\Invoice;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Customer;
use App\Models\ImportItem;
use App\Models\Insurance;
use App\Models\Item;
use App\Models\ItemCompositionItem;
use App\Models\Landlord\Bank;
use App\Models\Landlord\Country;
use App\Models\Landlord\Currency;
use App\Models\Landlord\ItemClassification;
use App\Models\Landlord\Locale;
use App\Models\Landlord\Notice;
use App\Models\Landlord\RefundReason;
use App\Models\Landlord\TaxCode;
use App\Models\Purchase;
use App\Models\StockInOut;
use App\Models\StockInOutItem;
use App\Models\StockMaster;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ETIMSHelper
{

    public static function initialize_branch(Branch $branch)
    {
        $data = [
            "tpin" => $branch->kra_pin,
            "bhfId" => $branch->branch_code,
            "dvcSrlNo" => $branch->device_serial_number
        ];



        if( $branch->solution_type == 'vsdc' )
            $response = self::sendGuzzleRequest($branch, '/initializer/selectInitInfo', $data);
        else
            $response = self::sendGuzzleRequest($branch, '/selectInitOsdcInfo', $data);

        if (is_null($response)) return;
        if (! count((array)$response)) return;
        if (! isset($response->resultCd)) return;

        if ($response->resultCd == "902")
            $branch->etims_credentials_validated = 1;

        elseif ($response->resultCd == "000") {
            $resData = $response->data ? (isset($response->data['info']) ? $response->data['info'] : null) : null;
            $branch->etims_credentials_validated = 1;
            $branch->cmc_key = isset($resData['cmcKey']) ?  $resData['cmcKey'] : null;
            $branch->scu_id = isset($resData['sdcId']) ?  $resData['sdcId'] : null;
        } else  $branch->etims_credentials_validated = NULL;

        $branch->save();
        return $response;
    }

    public static function getCodeList(Branch $branch, $subMonths = 72)
    {
        $date = Carbon::today()->subMonth($subMonths)->format('Ymd');
        $data = ["lastReqDt" => $date . "000000"];



        if( $branch->solution_type == 'vsdc' )
            $response = self::sendGuzzleRequest($branch, '/code/selectCodes', $data);
        else
            $response = self::sendGuzzleRequest($branch, '/selectCodeList', $data);

        if (empty($response)) return Log::error("No valid response received");
        if ($data =  $response->data)
            if ($classList =  $data['clsList'])
                self::updateCodesList($classList);

        // return $response;
    }



    public static function noticesSearch(Branch $branch, $subHours = 72)
    {

        $date = Carbon::today()->subHours($subHours ? $subHours : 72)->format('Ymd');

        $data = ["lastReqDt" => $date . "000000"];



        if( $branch->solution_type == 'vsdc' )
            $response = self::sendGuzzleRequest($branch, '/notices/selectNotices', $data);
        else
            $response = self::sendGuzzleRequest($branch, '/selectNoticeList', $data);


        self::updateNotices($response);
    }

    private static function updateNotices(Object $response)
    {
        $data = $response->data ? $response->data : null;
        $receivedNotices = $data ? (isset($data['noticeList']) ? $data['noticeList'] : []) : [];
        Notice::destroy(Notice::all());
        if (! count($receivedNotices)) return;
        foreach ($receivedNotices as $receivedNotice):
            $notice = new Notice();
            $receivedNotice = (object) $receivedNotice;
            $notice->notice_number     = $receivedNotice->noticeNo;
            $notice->title             = $receivedNotice->title;
            $notice->contents          = $receivedNotice->cont;
            $notice->detail_url        = $receivedNotice->dtlUrl;
            $notice->registration_name = $receivedNotice->regrNm;
            $notice->registration_date = $receivedNotice->regDt;
            $notice->save();
        endforeach;
    }

    public static function ItemClassList(Branch $branch, $subMonths = 72)
    {

        $date = Carbon::today()->subMonth($subMonths)->format('Ymd');

        $data = ["lastReqDt" => $date . "000000"];



        if( $branch->solution_type == 'vsdc' )
            $response = self::sendGuzzleRequest($branch, '/itemClass/selectItemsClass', $data);
        else
            $response = self::sendGuzzleRequest($branch, '/selectItemClsList', $data);

        if ($data =  $response->data)
            if ($itemClassList =  $data['itemClsList'])
                StoreClassificationCode::dispatch($itemClassList, $branch);

        return $response;
    }

    public static function itemSearch(Branch $branch,$subMonths = 72)
    {
        $date = Carbon::today()->sub('month', $subMonths)->format('Ymd');

        $data = ["lastReqDt" => $date . "000000"];




        if( $branch->solution_type == 'vsdc' )
            $response = self::sendGuzzleRequest($branch, '/items/selectItems', $data);
        else
            $response = self::sendGuzzleRequest($branch, '/selectItemList', $data);

        // Log::info("Item list " . json_encode($response));

        return $response;
    }


    public static function getBranchList($subMonths = 72)
    {
        $date = Carbon::today()->subMonth($subMonths)->format('Ymd');

        $data = ["lastReqDt" => $date . "000000"];

        $branch = Branch::first();

        if( $branch->solution_type == 'vsdc' )
            $response = self::sendGuzzleRequest($branch, '/branches/selectBranches', $data);
        else
            $response = self::sendGuzzleRequest($branch, '/selectBhfList', $data);

        if (is_null($response))  return;
        if (! count((array)$response))  return;
        return $response->resultCd == '000' ?  self::updateBranches($response->data) : null;
    }


    public static function getCustomerList($subMonths = 72)
    {
        $date = Carbon::today()->subMonth($subMonths)->format('Ymd');

        $data = ["lastReqDt" => $date . "000000",'custmTpin'=>'2694010404'];

        $branch = Branch::first();

        if( $branch->solution_type == 'vsdc' )
            $response = self::sendGuzzleRequest($branch, '/customers/selectCustomer', $data);
        else
            $response = self::sendGuzzleRequest($branch, '/selectCustomer', $data);

        return $response;
    }

    private static function updateBranches($responseData)
    {
        if (!isset($responseData['bhfList'])) return;
        $company = Company::first();
        foreach ($responseData['bhfList'] as $branch):
            $data = [
                'company_id' => $company->id,
                'kra_pin' => $branch['tpin'],
                'slug' => Utilities::getModelSlug($branch['bhfNm'], 'branch'),
                'branch_code' => $branch['bhfId'],
                'tracking_number' => $branch['bhfId'],
                'name' => $branch['bhfNm'],
                'branch_status_code' => $branch['bhfSttsCd'],
                'county_name' => $branch['prvncNm'],
                'subcounty_name' => $branch['dstrtNm'],
                'tax_locality_name' => $branch['sctrNm'],
                'location' => $branch['locDesc'],
                'manager_name' => $branch['mgrNm'],
                'phone' => $branch['mgrTelNo'],
                'email' => $branch['mgrEmail'],
                'is_headquater' => $branch['hqYn'] == 'Y' ? 1 : 0,
            ];
            Branch::updateOrCreate(['branch_code' => $branch['bhfId']], $data);
        endforeach;
    }

    public static function saveNewCustomer(Customer $customer, Branch $branch)
    {

        $data = [
            "tpin" => $branch->kra_pin,
            "bhfId" => $branch->branch_code,
            "custNo" => str_starts_with($customer->phone, '260') ? '0' . substr($customer->phone, 3) : $customer->phone,
            "custTpin" => $customer->kra_pin,
            "custNm" => $customer->name,
            "adrs" => $customer->location,
            "telNo" => str_starts_with($customer->phone, '260') ? '0' . substr($customer->phone, 3) : $customer->phone,
            "email" => $customer->email,
            "faxNo" => null,
            "useYn" => "Y",
            "remark" => null,
            "regrId" => $customer->id,
            "regrNm" => $customer->name,
            "modrId" => $customer->id,
            "modrNm" => $customer->name,
        ];
        // Log::info('Transmitting customer....');




        if( $branch->solution_type == 'vsdc' )
            $response = self::sendGuzzleRequest($branch, '/branches/saveBrancheCustomers', $data);
        else
            $response = self::sendGuzzleRequest($branch, '/saveBhfCustomer', $data);

        if ($response->resultCd == "000"){
            $customer->update(['synced_to_etims'=>1]);
            // TransmissionQueueManager::processQueue();

            // WebHooksHelper::sendCustomerTransmittedSuccess($customer, $branch->kra_pin);
           }

        return $response;
    }

    public static function saveNewUser(User $user, Branch $branch)
    {

        $data = [
            "tpin" => $branch->kra_pin,
            "bhfId" => $branch->branch_code,

            "userId" =>  $user->id,
            "userNm" => $user->username,
            "pwd" => Hash::make($user->password),
            "adrs" => $branch->name,
            "cntc" => $user->contact,
            "authCd" => $user->authority_code,
            "remark" => $user->remark,
            "useYn" => $user->used_unused ? $user->used_unused : "Y",
            "regrId" => $user->id,
            "regrNm" => $user->username,
            "modrId" => $user->id,
            "modrNm" => $user->username,
        ];
        // Log::info('Transmitting User...');


        if( $branch->solution_type == 'vsdc' )
            $response = self::sendGuzzleRequest($branch, '/branches/saveBrancheUser', $data);
        else
            $response = self::sendGuzzleRequest($branch, '/saveBhfUser', $data);

        if ($response->resultCd == "000"){
             $user->update(['synced_to_etims'=>1]);
            // TransmissionQueueManager::processQueue();
            //  WebHooksHelper::sendUserTransmittedSuccess($user, $branch->kra_pin);
            }

        return $response;
    }

    public static function saveBranchInsuarance(Insurance $insurance, Branch $branch)
    {

        $data = [
            "tpin" => $branch->kra_pin,
            "bhfId" => $branch->branch_code,
            "cmcKey" => $branch->cmc_key,
            "isrccCd" => $insurance->insurance_company_code,
            "isrccNm" => $insurance->insurance_company_name,
            "isrcRt" => $insurance->insurance_premium_rate,
            "useYn" => $insurance->used_unused ? $insurance->used_unused : "Y",
            "regrId" => $insurance->id,
            "regrNm" => $insurance->insurance_company_name,
            "modrId" => $insurance->id,
            "modrNm" => $insurance->insurance_company_name
        ];



        if( $branch->solution_type == 'vsdc' )
            $response = self::sendGuzzleRequest($branch, '/branches/saveBrancheInsurances', $data);
        else
            $response = self::sendGuzzleRequest($branch, '/saveBhfInsurance', $data);
        // Log::alert("Branch insurance " . json_encode($response));
        return $response;
    }

    public static function insertStockInputOut(StockInOut $stockInOut, Branch $branch, User $staff)
    {


        $customerBranchCode = $stockInOut->customer_branch_code ? $stockInOut->customer_branch_code : $stockInOut->dispatching_branch_code;
        $customerBranchName = $stockInOut->customer_branch_name ? $stockInOut->customer_branch_name : $stockInOut->dispatching_branch_name;


        $data = [
            "tpin" => $branch->kra_pin,
            "bhfId" => $branch->branch_code,
            "sarNo" => $stockInOut->stored_and_released_number, //// should be sequential (stored and released number)
            "orgSarNo" => $stockInOut->original_stored_and_released_number ? $stockInOut->original_stored_and_released_number : 0, // original stored and released number
            "regTyCd" =>  $stockInOut->registration_type_code  ? $stockInOut->registration_type_code : "M", //registration type M (manual) A (automatic)
            "custTpin" => $stockInOut->customer_kra_pin,
            "custNm" => $customerBranchName, //from invoice customer
            "custBhfId" => $customerBranchCode,
            "sarTyCd" => $stockInOut->stored_and_released_type_code,
            "ocrnDt" => $stockInOut->occured_date_time,
            "totItemCnt" => count($stockInOut->items),
            "totTaxblAmt" => sprintf("%.4f",   $stockInOut->total_taxable_amount),
            "totTaxAmt" => sprintf("%.4f",   $stockInOut->total_tax_amount),
            "totAmt" => sprintf("%.4f",   $stockInOut->total_amount),
            "remark" => $stockInOut->remark,
            "regrId" => $staff->id,
            "regrNm" => ucwords($staff->first_name),
            "modrId" => $staff->id,
            "modrNm" => ucwords($staff->first_name),
            "itemList" => self::generateStockInputOutputItemsList($stockInOut->items),
        ];

        Log::info('Transmitting stock I/O');

        try {
            //code...
            if( $branch->solution_type == 'vsdc' )
                $response = self::sendGuzzleRequest($branch, '/stock/saveStockItems', $data);
            else
                $response = self::sendGuzzleRequest($branch, '/insertStockIO', $data);

            if (isset($response->resultCd) && $response->resultCd == "000"):
                $stockInOut->update(['synced_at' => now(), "synced_to_etims" =>1 ]);
                TransmissionQueueManager::markJobAsCompleted($stockInOut);

                WebHooksHelper::sendStockIOTransmittedSuccess ($stockInOut, $branch->kra_pin);
            else:
                TransmissionQueueManager::markJobAsFailed($stockInOut, $response);
            endif;

        Log::info(json_encode($response));

        return $response;
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);
            TransmissionQueueManager::markJobAsFailed($stockInOut, $th);
        }


    }
    public static function getImportItemList(Branch $branch, $subMonths = 72)
    {
        $date = Carbon::today()->sub('month', $subMonths)->format('Ymd');
        $data = [
            "tpin" => $branch->kra_pin,
            "bhfId" => $branch->branch_code,
            "lastReqDt" => $date . "000000"
        ];



        if( $branch->solution_type == 'vsdc' )
            $response = self::sendGuzzleRequest($branch, '/imports/selectImportItems', $data);
        else
            $response = self::sendGuzzleRequest($branch, '/selectImportItemList', $data);

        self::createImport($response, $branch);
        return $response;
    }

    private static function createImport($importData, Branch $branch)
    {
        if (is_null($importData))  return;
        if (! count((array)$importData))  return;
        if ($importData->resultCd !== '000' || !$importData->data) return;

        $import = $branch->imports()->create([
            'import_number' => time(),
            'date' => $importData->resultDt
        ]);

        $importItems = isset($importData->data['itemList']) ? $importData->data['itemList'] : [];
        $declarationNumber = null;
        foreach ($importItems as $item) :
            $unitPrice = 0;
            $quantity = 0;
            if (isset($item['invcFcurAmt']) && isset($item['qty']) && isset($item['invcFcurExcrt'])) :
                $quantity = $item['qty'];
                $buyingPrice = $item['invcFcurAmt'] / $quantity;
                $unitPrice = $buyingPrice * $item['invcFcurExcrt'];
                $unitPrice = is_numeric($unitPrice) ? number_format((float)$unitPrice, 2, '.', '') : $unitPrice;
            endif;

            $taskCode = isset($item['taskCd']) ? $item['taskCd'] : null;
            $declarationNumber = isset($item['dclNo']) ? $item['dclNo'] : null;
            $declarationDate = isset($item['dclDe']) ? $item['dclDe'] : null;
            $itemExists = ImportItem::where('task_code', $taskCode)->where('declaration_number', $declarationNumber)->where('declaration_date', $declarationDate)->first();

            if ($itemExists) continue;

            $import->items()->create([
                'task_code' => $taskCode,
                'declaration_date' => $declarationDate,
                'item_sequence' => isset($item['itemSeq']) ? $item['itemSeq'] : null,
                'declaration_number' => $declarationNumber,
                'hs_code' => isset($item['hsCd']) ? $item['hsCd'] : null,
                'product_name' => isset($item['itemNm']) ? $item['itemNm'] : null,
                'item_name' => isset($item['itemNm']) ? $item['itemNm'] : null,
                'import_status_code' => isset($item['imptItemsttsCd']) ? $item['imptItemsttsCd'] : null,
                'origin_nation_code' => isset($item['orgnNatCd']) ? $item['orgnNatCd'] : null,
                'export_nation_code' => isset($item['exptNatCd']) ? $item['exptNatCd'] : null,
                'packaging' => isset($item['pkg']) ? $item['pkg'] : null,
                'packaging_unit_code' => isset($item['pkgUnitCd']) ? $item['pkgUnitCd'] : null,
                'quantity' => isset($item['qty']) ? $item['qty'] : null,
                'quantity_unit_code' => isset($item['qtyUnitCd']) ? $item['qtyUnitCd'] : null,
                'gross_weight' => isset($item['totWt']) ? $item['totWt'] : null,
                'net_weight' => isset($item['netWt']) ? $item['netWt'] : null,
                'supplier_name' => isset($item['spplrNm']) ? $item['spplrNm'] : null,
                'agent_name' => isset($item['agntNm']) ? $item['agntNm'] : null,
                'invoice_foreign_amount' => isset($item['invcFcurAmt']) ? $item['invcFcurAmt'] : null,
                'invoice_foreign_currency' => isset($item['invcFcurCd']) ? $item['invcFcurCd'] : null,
                'invoice_foreign_currency_exchange_rate' => isset($item['invcFcurExcrt']) ? $item['invcFcurExcrt'] : null,
                'buying_price' => $unitPrice,
                'item_total' => $unitPrice * $quantity,
            ]);
        endforeach;

        $import = $import->refresh();


        if (!$import->items()->count())
            $import->delete();
        else
            $import->update(['declaration_number' => $declarationNumber]);
    }

    public static function updateImportItem(array $data, Branch $branch)
    {

        $data = [
            "tpin" => $branch->kra_pin,
            "bhfId" => $branch->branch_code,
            "taskCd" => $data['task_code'],
            "dclDe" => $data['declaration_date'],
            "itemSeq" => $data['item_sequence'],
            "hsCd" => $data['hs_code'],
            "itemClsCd" => $data['item_classification_code'],
            "itemCd" => $data['item_code'],
            "imptItemSttsCd" => $data['import_item_status_code'],
            "remark" => isset($data['remark']) ? $data['remark'] : null,
            "modrId" => $data['id'],
            "modrNm" => substr($data['item_name'], 0, 60)
        ];



        if( $branch->solution_type == 'vsdc' )
            $response = self::sendGuzzleRequest($branch, '/imports/updateImportItems', $data);
        else
            $response = self::sendGuzzleRequest($branch, '/updateImportItem', $data);

        // Log::info("Updated import item " . json_encode($response));
        return $response;
    }

    public static function getPurchaseItemList(Branch $branch, $subMonths = 36)
    {

        $date = Carbon::today()->subMonths($subMonths)->format('Ymd');

        $data = [
            "tpin" => $branch->kra_pin,
            "bhfId" => $branch->branch_code,
            "lastReqDt" => $date . "000000"
        ];



        if( $branch->solution_type == 'vsdc' )
            $response = self::sendGuzzleRequest($branch, '/trnsPurchase/selectTrnsPurchaseSales', $data);
        else
            $response = self::sendGuzzleRequest($branch, '/selectTrnsPurchaseSalesList', $data);
        self::createAnEtimsPurchase($branch, $response);

        return $response;
    }

    private static function createAnEtimsPurchase(Branch $branch, $response)
    {
        if (is_null($response))  return;
        if (! count((array)$response))  return;
        if ($response->resultCd !== '000') return;
        if (empty($response->data)) return;
        $saleList = $response->data['saleList'] ? $response->data['saleList'] : [];

        foreach ($saleList as $list) :
            $list = (object) $list;
            $etimsPurchase = self::saveEtimsPurchase($branch, $list);
            if (empty($etimsPurchase)) continue;
            self::saveEtimsPurchaseItems($etimsPurchase, $list);
            // AcknowledgeEtimsPurchase::dispatch($etimsPurchase->fresh());
        endforeach;
    }

    private static function saveEtimsPurchase(Branch $branch, $data)
    {
        if (empty($data)) return;
        $user = Auth::user();

        $purchaseExists = Purchase::where('supplier_invoice_number', $data->spplrInvcNo)->where('supplier_kra_pin', $data->spplrTin)->where('validated_date', $data->cfmDt)->first();
        if ($purchaseExists) return;
        return $branch->purchases()->create([
            'invoice_number' => time(),
            'supplier_kra_pin' => $data->spplrTin,
            'purchase_number' => time(),
            'supplier_name' => $data->spplrNm,
            'supplier_branch_code' => $data->spplrBhfId,
            'supplier_invoice_number' => $data->spplrInvcNo,
            'receipt_type_code' => $data->rcptTyCd,
            'payment_type_code' => $data->pmtTyCd,
            'validated_date' => $data->cfmDt,
            'sale_date' => $data->salesDt,
            'stock_release_date' => $data->stockRlsDt,
            'total_item_count' => $data->totItemCnt,

            'taxable_amount_A' => $data->taxblAmtA,
            'tax_rate_A' => $data->taxRtA,
            'tax_amount_A' => $data->taxAmtA,

            'taxable_amount_B' => $data->taxblAmtB,
            'tax_rate_B' => $data->taxRtB,
            'tax_amount_B' => $data->taxAmtB,

            'taxable_amount_C' => $data->taxblAmtC,
            'tax_rate_C' => $data->taxRtC,
            'tax_amount_C' => $data->taxAmtC,

            'taxable_amount_E' => isset($data->taxblAmtE) ? $data->taxblAmtE : 0,
            'tax_rate_E' => isset($data->taxRtE) ? $data->taxRtE : 0,
            'tax_amount_E' => isset($data->taxAmtE) ? $data->taxAmtE : 0,



            'taxable_amount_D' => $data->taxblAmtD,
            'tax_rate_D' => 0,
            'tax_amount_D' => $data->taxAmtD,

            'total_taxable_amount' => $data->totTaxblAmt,
            'total_tax_amount' => $data->totTaxAmt,
            'total_amount' => $data->totAmt,
            'description' => $data->remark,
            'recieved_by' => $user->id

        ]);
    }

    private static function saveEtimsPurchaseItems(Purchase $etimsPurchase, $data)
    {
        if (empty($data)) return;

        foreach ($data->itemList as $item) :
            $item = (object) $item;
            $etimsPurchase->items()->create([
                'item_name' => $item->itemNm,
                'item_sequence_number' => $item->itemSeq,
                'item_code' => $item->itemCd,
                'bar_code' => $item->bcd,
                'item_classification_code' => $item->itemClsCd,
                'supplier_item_classification_code' => isset($item->spplritemClsCd) ? $item->spplritemClsCd : null,
                'packaging_unit_code' => $item->pkgUnitCd,
                'unit_of_measure_code' => $item->qtyUnitCd,
                'packaging_unit' => $item->pkg,
                'quantity' => $item->qty,
                'quantity_unit_code' => $item->qtyUnitCd,
                'unit_price' => $item->prc,
                'item_total' => $item->splyAmt,
                'discount_rate' => $item->dcRt,
                'discount_amount' => $item->dcAmt,
                'tax_type_code' => $item->taxTyCd,
                'taxable_amount' => $item->taxblAmt,
                'tax_amount' => $item->taxAmt,
                'vat_amount' => $item->taxAmt,
                'total_amount' => $item->totAmt,
                "supply_price" => $item->splyAmt, //create column
                "insurance_code" => $item->isrccCd ?? null, //create column
                "insurance_name" => $item->isrccNm ?? null, //create column
                "insurance_rate" => $item->isrcRt ?? null, //create column
                "insurance_amount" => $item->isrcAmt ?? null, //create column
                "expiry_date" => $item->itemExprDt ?? null, //create column
                'status' => null,//self::checkIfItemIsService($item->itemCd) ? 'processed' : null, //if is a service set 'processed'
                'processed_at' => null//self::checkIfItemIsService($item->itemCd) ? now() : null //i//if is a service set now()
            ]);
        endforeach;
    }

    private static function checkIfItemIsService(String  $itemCode)
    {

        $itemTypeCode = substr($itemCode, 2, 1);
        //    Log::info("$itemCode:  $itemTypeCode");

        return $itemTypeCode == '3';
    }

    public static function saveStockMaster(StockMaster $stockMaster, Branch $branch, User $staff)
    {
            $value = round($stockMaster->remaining_quantity, 2);
            $max = 999999999.99;
            $data = [
                "tpin"    => $branch->kra_pin,
                "bhfId"  => $branch->branch_code,
                "regrId" => $staff->id,
                "regrNm" => ucwords($staff->first_name),
                "modrId" => $staff->id,
                "modrNm" => ucwords($staff->first_name),
                "stockItemList" => [
                    [
                        "itemCd" => $stockMaster->item_code,
                        "rsdQty" =>  min($value, $max)
                    ]
                ]
            ];

            // Log::info("Transmitting stock master: $stockMaster->id ...");

            try {
                //code...


            if( $branch->solution_type == 'vsdc' )
                $response = self::sendGuzzleRequest($branch, '/stockMaster/saveStockMaster', $data);
            else
                $response = self::sendGuzzleRequest($branch, '/saveStockMaster', $data);
            // Log::info(json_encode($response));
            if ( isset($response->resultCd) && $response->resultCd == "000"):
                $stockMaster->update(['synced_at'=>now()]);
                TransmissionQueueManager::markJobAsCompleted($stockMaster);
              else :
                 TransmissionQueueManager::markJobAsFailed($stockMaster, $response);
              endif;
            } catch (\Throwable $th) {
                //throw $th;
                TransmissionQueueManager::markJobAsFailed($stockMaster, $th);
            }


    }

    private static function updateSuccessfulStockInOutSync(StockInOut $stockInOut)
    {
            $stockInOut->update(['synced_at' => now(), "synced_to_etims" =>1, ]);
    }
    private static function updateSuccessfulInvoiceSync(Invoice $invoice)
    {
        // $invoice->update(['synced_at'=>now()]);

        // $taxPin = 'kra tax pine here';
        // InvoiceTransmitedWebHookJob::dispatch($invoice,$taxPin );
    }
    private static function updateSuccessfulStockMasterSync(StockMaster $stockMaster)
    {
        $stockMaster->update(['synced_at' => now()]);
    }

    // private static function updateSuccessfulStockInOutItemSync(StockInOutItem $stockInOutItem)
    // {
    //     $stockInOutItem->update(['synced_at'=>now()]);
    // }

    public static function stockMovement(Branch $branch, $subMonths = 72)
    {
        $date = Carbon::today()->sub('month', $subMonths)->format('Ymd');

        $data = [
            "tpin" => $branch->kra_pin,
            "bhfId" => $branch->branch_code,
            "lastReqDt" => $date . "000000"
        ];



        if( $branch->solution_type == 'vsdc' )
            $response = Self::sendGuzzleRequest($branch, '/stock/selectStockItems', $data);
        else
            $response = Self::sendGuzzleRequest($branch, '/selectStockMoveList', $data);
        return $response;
    }

    public static function selectSalesList(Branch $branch, $subMonths = 3)
    {
        $date = Carbon::today()->sub('month', $subMonths)->format('Ymd');

        $data = [
            "tpin" => $branch->kra_pin,
            "bhfId" => $branch->branch_code,
            "lastReqDt" => $date . "000000"
        ];
        $response = Self::sendGuzzleRequest($branch, '/selectSalesList', $data);
        return $response;
    }

    public static function getInvoice(Branch $branch, Invoice $invoice)
    {
       $data = [
            "tpin" => $branch->kra_pin,
            "bhfId" => $branch->branch_code,
            "invcNo" => $invoice->invoice_number
        ];

        try {
            $response = Self::sendGuzzleRequest($branch, '/selectInvoiceDetails', $data);
            // Log::info(json_encode($response));
            if(isset($response->resultCd) && $response->resultCd == "000")
            {
                if (empty($response->data)) return;
                if( empty($response->data['salesList'])) return;

                foreach( $response->data['salesList'] as $resData):
                    $resObj = (object) $resData['receipt'];
                    $data = [
                        'etims_current_reciept_number' => $resObj->curRcptNo,
                        'etims_total_reciept_number' => $resObj->totRcptNo,
                        'etims_internal_data' => $resObj->intrlData,
                        'etims_reciept_signiture' => $resObj->rcptSign,
                        'etims_control_unit_date_time' => $resObj->sdcDateTime,
                        'etims_control_unit_serial_number' => $resObj->totRcptNo,
                        'etims_control_unit_invoice_number' => $resObj->curRcptNo,
                        'etims_receipt_reference_number' => $resObj->rcptSign,
                        "synced_at" =>  now(),
                        "synced_to_etims" =>1,
                    ];
                    // Log::alert(json_encode($data));
                    self::updateInvoiceData($invoice,$data);
                    TransmissionQueueManager::markJobAsCompleted($invoice);
                    InvoiceTransmitedWebHookJob::dispatch($invoice, $branch->kra_pin);
                endforeach;
            }
        } catch (\Throwable $th) {
            throw $th;
        }

        // self::updateInvoiceData($invoice, $response);

        // Log::alert( json_encode($response));
    }

    public static function getInvoiceList(Branch $branch, $subDays = 1)
    {
        // $date = Carbon::today()->sub('day', $subDays)->format('Ymd');
        // $data = [
        //     "tpin" => $branch->kra_pin,
        //     "bhfId" => $branch->branch_code,
        //     "lastReqDt" => $date . "000000"
        // ];

        // $response = self::sendGuzzleRequest($branch, '/selectTrnsSalesList', $data);
        // echo json_encode($response);
    }


    public static function recordNewSale(Invoice $invoice, Branch $branch, User $staff)
    {

        $customer = $invoice->customer;
        $itemsList = self::generateInvoiceItemList($invoice);

        $originalInvoice = Invoice::where('invoice_number',$invoice->original_invoice_number)->first();

        // if($originalInvoice){
        //     Log::info('Original invoice found for invoice number: ' . $invoice->invoice_number);
        //     Log::info($originalInvoice->id);
        //     Log::info($originalInvoice->sales_control_unit_id);

        // }

        $originalcustomerKraPIN = $originalInvoice ? $originalInvoice->customer_kra_pin : null;
        $originalcustomerName = $originalInvoice ? $originalInvoice->customer_name : null;

        $customerKraPIN = $customer ? $customer->kra_pin : null;
        $customerName = $customer ? $customer->name : null;

        // $env = env('ETIMS_ENV');

        $data = [
            "tpin" => $branch->kra_pin,
            "bhfId" => $branch->branch_code,
            "trdInvcNo" => $invoice->purchase_invoice_number,
            "cisInvcNo" =>  $invoice->invoice_number,
            "destnCountryCd" => $invoice->taxable_amount_C1 > 0 ? "ZA" : NULL,
            "orgInvcNo" => $originalInvoice->control_unit_invoice_number??0,

            "custTpin" => $originalcustomerKraPIN ? $originalcustomerKraPIN : $customerKraPIN,
            "custNm" => $originalcustomerName ? $originalcustomerName : $customerName,
            "orgSdcId" => $originalInvoice ? $originalInvoice->sales_control_unit_id  : null,
            "saleCtyCd" => $invoice->sales_type_code,
            "salesTyCd" => $invoice->sales_type_code,
            "rcptTyCd" => $invoice->receipt_type_code,
            "pmtTyCd" => $invoice->payment_type_code,
            "lpoNumber" => NULL,
            "currencyTyCd" => "ZMW",
            "exchangeRt" => 1,
            "salesSttsCd" => $invoice->sale_status_code,
            "cfmDt" => $invoice->validated_date,
            "salesDt" => $invoice->sale_date,
            "stockRlsDt" => $invoice->stock_released_date,
            "cnclReqDt" => $invoice->cancel_requested_date,
            "cnclDt" => $invoice->canceled_date,
            "rfdDt" => $invoice->credit_note_date,
            "rfdRsnCd" => $invoice->credit_note_reason_code,
            "totItemCnt" => $invoice->items()->count(),
            "taxblAmtA" => sprintf("%0.2f", $invoice->taxable_amount_A),
            "taxblAmtB" => sprintf("%0.2f", $invoice->taxable_amount_B),
            "taxblAmtC1" => sprintf("%0.2f", $invoice->taxable_amount_C1),
            "taxblAmtC2" => sprintf("%0.2f", $invoice->taxable_amount_C2),
            "taxblAmtC3" => sprintf("%0.2f", $invoice->taxable_amount_C3),
            "taxblAmtD" => sprintf("%0.2f", $invoice->taxable_amount_D),
            "taxblAmtE" => sprintf("%0.2f", $invoice->taxable_amount_E),
            "taxblAmtRvat" => sprintf("%0.2f", $invoice->taxable_amount_Rvat),
            "taxblAmtTot" => sprintf("%0.2f", $invoice->taxable_amount_Tot),
            "taxRtA" => $invoice->tax_rate_A,
            "taxRtB" => $invoice->tax_rate_B,
            "taxRtC1" => $invoice->tax_rate_C1,
            "taxRtC2" => $invoice->tax_rate_C2,
            "taxRtC3" => $invoice->tax_rate_C3,
            "taxRtD" => $invoice->tax_rate_D,
            "taxRtE" => $invoice->tax_rate_E,
            "taxRtRvat" => $invoice->tax_rate_Rvat,
            "taxRtTot" => $invoice->tax_rate_Tot,
            "taxAmtA" => sprintf("%0.2f", $invoice->tax_amount_A),
            "taxAmtB" => sprintf("%0.2f", $invoice->tax_amount_B),
            "taxAmtC1" => sprintf("%0.2f", $invoice->tax_amount_C1),
            "taxAmtC2" => sprintf("%0.2f", $invoice->tax_amount_C2),
            "taxAmtC3" => sprintf("%0.2f", $invoice->tax_amount_C3),
            "taxAmtD" => sprintf("%0.2f", $invoice->tax_amount_D),
            "taxAmtRvat" => sprintf("%0.2f", $invoice->tax_amount_Rvat),
            "taxAmtE" => sprintf("%0.2f", $invoice->tax_amount_E),
            "taxAmtTot" => sprintf("%0.2f", $invoice->tax_amount_Tot),
            "totTaxblAmt" => sprintf("%0.2f", $invoice->total_taxable_amount),
            "totTaxAmt" => sprintf("%0.2f", $invoice->total_tax_amount),
            "totAmt" => sprintf("%0.2f", $invoice->total_amount),
            "prchrAcptcYn" => $invoice->purchase_acceptance_status, // purchase accept yes(Y)/ no(N)
            "remark" => $invoice->remark,
            "regrId" => $staff->id,
            "regrNm" => $staff->first_name,
            "modrId" => $staff->id,
            "modrNm" => $staff->first_name,
            "receipt" => [
                "custTpin" => !is_null($customer) ? $customer->kra_pin : '',
                "custMblNo" => !is_null($customer) ? $customer->phone : '',
                "rcptPbctDt" => now()->format('YmdHis'),
                "trdeNm" => $invoice->trade_name,
                "adrs" => $invoice->address,
                "topMsg" => $invoice->top_message,
                "btmMsg" => $invoice->bottom_message,
                "prchrAcptcYn" => $invoice->purchase_acceptance_status
            ],
            "itemList" =>  $itemsList,
        ];

        try {
            //code...


            if( $branch->solution_type == 'vsdc' )
                $response = self::sendGuzzleRequest($branch, '/trnsSales/saveSales', $data);
            else
                $response = self::sendGuzzleRequest($branch, '/saveTrnsSalesOsdc', $data);
            if (isset($response->resultCd) && $response->resultCd == "000"){
                self::updateInvoice($invoice, $response);
                TransmissionQueueManager::markJobAsCompleted($invoice);

                InvoiceTransmitedWebHookJob::dispatch($invoice, $branch->kra_pin);
            }else {
                TransmissionQueueManager::markJobAsFailed($invoice, $response);
            }
            return $response;
        } catch (\Throwable $th) {
            //throw $th;
            TransmissionQueueManager::markJobAsFailed($invoice, $th);
        }






    }


    public static function saveItem(Item $item, Branch $branch, User $staff)
    {
        $role = $staff->roles()->first();

        $data = [
            "tpin" => $branch->kra_pin,
            "regbhfId" => $branch->branch_code,
            "bhfId" => $branch->branch_code,
            "itemCd" => $item->item_code,
            "itemClsCd" => $item->item_classification_code,
            "itemTyCd" => $item->item_type_code,
            "itemNm" => $item->item_name,
            "itemStdNm" => $item->item_standard_name,
            "orgnNatCd" => $item->country_of_origin_code,
            "pkgUnitCd" => $item->packaging_unit_code,
            "qtyUnitCd" => $item->quantity_unit_code,
            "taxTyCd" => $item->tax_type_code,
            "btchNo" => $item->batch_number,
            "bcd" => $item->barcode,
            "dftPrc" => $item->default_unit_price,
            "grpPrcL1" => $item->group_1_price,
            "grpPrcL2" => $item->group_2_price,
            "grpPrcL3" => $item->group_3_price,
            "grpPrcL4" => $item->group_4_price,
            "grpPrcL5" => $item->group_4_price,
            "addInfo" => $item->additional_information,
            "sftyQty" => $item->safety_quantity,
            "isrcAplcbYn" => $item->insurance_applicable ? $item->insurance_applicable : 'N',
            "useYn" => $item->used_unused  ? $item->used_unused : 'Y',
            "regrId" => $staff->id,
            "regrNm" => ucwords($staff->first_name),
            "modrId" => $staff->id,
            "modrNm" => $role ? ucwords(str_replace('-', ' ', $role->name)) : ucwords($staff->first_name)
        ];

        // Log::info("Save Item call:");


        if( $branch->solution_type == 'vsdc' )
            $response = self::sendGuzzleRequest($branch, '/items/saveItem', $data);
        else
            $response = self::sendGuzzleRequest($branch, '/saveItem', $data);
        // Log::info(json_encode($response));
        if ($response->resultCd == "000") {
            $item->update([ 'synced_at'=> now()  ]);
            // TransmissionQueueManager::processQueue();
            // StockItemTransmittedWebHookJob::dispatch($item, $branch->kra_pin);
        }
        return $response;
    }

    public static function itemComposition(ItemCompositionItem $item, Branch $branch, User $staff)
    {
        $itemComposition = $item->item_composition;
        if (is_null($itemComposition)) return ;

        $data = [
            "tpin" => $branch->kra_pin,
            "bhfId" => $branch->branch_code,
            "itemCd" => $itemComposition->item_code,
            "cpstItemCd" => $item->item_code,
            "itemTyCd" => $item->tax_type_code,
            "itemNm" => $item->item_name,
            "cpstQty" => $item->quantity,
            "regrId" => $staff->id,
            "regrNm" => ucwords($staff->first_name),
        ];



        if( $branch->solution_type == 'vsdc' )
            $response = self::sendGuzzleRequest($branch, '/items/saveItemComposition', $data);
        else
            $response = self::sendGuzzleRequest($branch, '/saveItemComposition', $data);

        // Log::info("Item composition " . json_encode($response));

        return $response;
    }


    public static function recordNewPurchase(Purchase $purchase, $mappedProduct)
    {
        $branch = $purchase->branch ? $purchase->branch : null;
        $company = $branch->company ? $branch->company : null;
        if (empty($company)) return;
        $itemsList = self::generatePurchaseItemList($purchase, $mappedProduct);

        $data =   [
            "tpin" => $branch->kra_pin,
            "bhfId" => $branch->branch_code,
            "invcNo" => $purchase->supplier_invoice_number,
            "orgInvcNo" => 0,//$purchase->supplier_invoice_number,
            "spplrTin" => $purchase->supplier_kra_pin,
            "spplrBhfId" => $purchase->supplier_branch_code,
            "spplrNm" => $purchase->supplier_name,
            "spplrInvcNo" => $purchase->supplier_invoice_number, ///
            "regTyCd" => "M", //registration type M(manual) A(automatic)
            "pchsTyCd" => "N", //transaction type N(normal)
            "rcptTyCd" => "P", // receipt type sale
            "pmtTyCd" => $purchase->payment_type_code,
            "pchsSttsCd" => "02", //approved
            "cfmDt" => $purchase->validated_date ? $purchase->validated_date->format('YmdHis') : '',
            "pchsDt" => $purchase->sale_date,
            "wrhsDt" => "",
            "cnclReqDt" => "",
            "cnclDt" => "",
            "rfdDt" => "",
            "totItemCnt" => count($itemsList),
            "taxblAmtA" => $purchase->taxable_amount_A ?? 0,
            "taxblAmtB" => $purchase->taxable_amount_B ?? 0,
            "taxblAmtC" => $purchase->taxable_amount_C ?? 0,
            "taxblAmtD" => $purchase->taxable_amount_D ?? 0,
            "taxblAmtE" => $purchase->taxable_amount_E ?? 0,

            "taxRtA" => $purchase->tax_rate_A ?? 0,
            "taxRtB" => $purchase->tax_rate_B ?? 0,
            "taxRtC" => $purchase->tax_rate_C ?? 0,
            "taxRtD" => $purchase->tax_rate_D ?? 0,
            "taxRtE" => $purchase->tax_rate_E ?? 0,

            "taxAmtA" => $purchase->tax_amount_A ?? 0,
            "taxAmtB" => $purchase->tax_amount_B ?? 0,
            "taxAmtC" => $purchase->tax_amount_C ?? 0,
            "taxAmtD" => $purchase->tax_amount_D ?? 0,
            "taxAmtE" => $purchase->tax_amount_E ?? 0,

            "totTaxblAmt" => $purchase->total_taxable_amount ?? 0,
            "totTaxAmt" => $purchase->total_tax_amount ?? 0,

            "totAmt" => $purchase->total_amount ?? 0,
            "remark" => $purchase->description,
            "regrId" => $purchase->id,
            "regrNm" => $purchase->id,
            "modrId" => $purchase->id,
            "modrNm" => $purchase->id,
            "itemList" => $itemsList,
        ];




        if( $branch->solution_type == 'vsdc' )
            self::sendGuzzleRequest($branch, '/trnsPurchase/savePurchases', $data);
        else
            self::sendGuzzleRequest($branch, '/insertTrnsPurchase', $data);
        return $purchase;
    }


    private static function sendGuzzleRequest(Branch $branch, $endPoint, $data, $method = 'POST')
    {
        $client = new Client();
        $baseUrl = env('SMART_INVOICE_VSDC_URL', 'http://46.224.127.208:8080/VSDC');

            $cmcKey = $branch->cmc_key ? $branch->cmc_key : null;
            $data["tpin"] =  $branch->kra_pin;
            $data["bhfId"] = $branch->branch_code;


        try {
            Log::info("Calling vsdc endpoint:  $baseUrl$endPoint");
            Log::info($data);
            $response = $client->request(
                $method,
                $baseUrl . $endPoint,
                [
                    'json' => $data,
                    'headers' => [
                        "tpin" => $branch->kra_pin,
                        "bhfId" => $branch->branch_code,
                        "cmckey" => $cmcKey,
                        "Content-Type" => "application/json"
                    ],
                ]
            );

            $response = json_decode($response->getBody(), true);

            Log::info("Response:  ".json_encode($response));


            return (object) $response;
        } catch (\Throwable $th) {
                    //     return $e;
                    Log::alert($th);
            return $th;

        }
    }


    public static  function updateItemClassCodeList(array $classList = [])
    {
        $data = [];
        foreach ($classList as $item) :
            $data = [
                'code' => $item['itemClsCd'],
                'name' => $item['itemClsNm'],
                'tax_type_code' => $item['taxTyCd'],
                'tax_class_level' => $item['itemClsLvl'],
                'is_major_target' => $item['mjrTgYn']
            ];
            ItemClassification::updateOrCreate(['code' => $item['itemClsCd']], $data);
        endforeach;
    }

    private static function updateCodesList($codesList)
    {
        foreach ($codesList as $list) :
            if ($list['cdCls'] == '04') //taxation type
                self::updateTaxationTypes($list['dtlList']);
            if ($list['cdCls'] == '05') //countries
                self::updateCountries($list['dtlList']);
            if ($list['cdCls'] == '32') //refund reasons
                self::updateRefundReasons($list['dtlList']);
            if ($list['cdCls'] == '33') //currencies
                self::updateCurrencies($list['dtlList']);
            if ($list['cdCls'] == '36') //banks
                self::updateBanks($list['dtlList']);
            if ($list['cdCls'] == '48') //locales
                self::updateLocales($list['dtlList']);
        endforeach;
    }
    private static function updateCountries($taxCodeList)
    {
        foreach ($taxCodeList as $item) :
            $data = [
                'code' => $item['cd'],
                'name' => $item['cdNm'],
            ];
            Country::updateOrCreate(['code' => $item['cd']], $data);
        endforeach;
    }
    private static function updateRefundReasons($taxCodeList)
    {
        foreach ($taxCodeList as $item) :
            $data = [
                'code' => $item['cd'],
                'name' => $item['cdNm'],
            ];
            RefundReason::updateOrCreate(['code' => $item['cd']], $data);
        endforeach;
    }
    private static function updateCurrencies($taxCodeList)
    {
        foreach ($taxCodeList as $item) :
            $data = [
                'code' => $item['cd'],
                'name' => $item['cdNm'],
            ];
            Currency::updateOrCreate(['code' => $item['cd']], $data);
        endforeach;
    }
    private static function updateBanks($taxCodeList)
    {
        foreach ($taxCodeList as $item) :
            $data = [
                'code' => $item['cd'],
                'name' => $item['cdNm'],
            ];
            Bank::updateOrCreate(['code' => $item['cd']], $data);
        endforeach;
    }
    private static function updateLocales($taxCodeList)
    {
        foreach ($taxCodeList as $item) :
            $data = [
                'code' => $item['cd'],
                'name' => $item['cdNm'],
            ];
            Locale::updateOrCreate(['code' => $item['cd']], $data);
        endforeach;
    }
    private static function updateTaxationTypes($taxCodeList)
    {
        foreach ($taxCodeList as $item) {
            $data = [
                'code' => $item['cd'],
                'name' => $item['cdNm'],
                'rate' => $item['userDfnCd1'],
            ];
            TaxCode::updateOrCreate(['code' => $item['cd']], $data);
        }
    }

    private static function generateStockInputOutputItemsList(Object $items)
    {
        $data = [];
        foreach ($items as $item) :
            $arr = [
                "itemSeq" => $item->item_sequence_number,
                "itemCd" => $item->item_code,
                "itemClsCd" => $item->item_classification_code,
                "itemNm" => $item->item_name,
                "bcd" => $item->barcode,
                "pkgUnitCd" =>  $item->packaging_unit_code,
                "pkg" => $item->packaging_unit,
                "qtyUnitCd" => $item->quantity_unit_code,
                "qty" => $item->quantity,
                "prc" => $item->unit_price,
                "splyAmt" => $item->supply_price,
                "dcRt" => $item->discount_rate ? sprintf("%0.2f", $item->discount_rate) :  0,
                "dcAmt" => $item->discount_amount ? sprintf("%0.2f", $item->discount_amount) :  0,
                "totDcAmt" => $item->total_discount_amount ? sprintf("%0.2f", $item->total_discount_amount) : 0,
                "taxTyCd" => $item->tax_type_code,
                "taxblAmt" => sprintf("%0.2f", $item->taxable_amount),
                "taxAmt" => sprintf("%0.2f", $item->tax_amount),
                "totAmt" => sprintf("%0.2f", $item->total_amount),
                "vatCatCd" => $item->tax_type_code,
            ];

            array_push($data, $arr);
        endforeach;

        return $data;
    }
    private static function generatePurchaseItemList($etimsPurchase, $mappedProduct) ####update to send correct supplier name and code###
    {
        $data = [];

        foreach ($etimsPurchase->items as $item) :

            if($item->item_code != $mappedProduct->item_code) continue;

            $arr = [
                "itemSeq" => $item->item_sequence_number,
                "itemCd" => $mappedProduct->item_code,
                "itemClsCd" => $mappedProduct->item_classification_code,
                "itemNm" => $mappedProduct->item_name,
                "bcd" => $item->bar_code,
                "pkgUnitCd" => $item->packaging_unit_code,
                "pkg" => $item->packaging_unit,
                "qtyUnitCd" => $item->quantity_unit_code,
                "qty" => $item->quantity,
                "prc" => $item->unit_price,
                "dcRt" => $item->discount_percentage ?? 0,
                "dcAmt" => $item->discount_amount ?? 0,
                "totDcAmt" => $item->discount_amount ?? 0,

                "splyAmt" => $item->supply_price,
                "isrccCd" => $item->insurance_code,
                "isrccNm" => $item->insurance_name,
                "isrcRt" => $item->insurance_rate,
                "isrcAmt" => $item->insurance_amount,
                "itemExprDt" => $item->expiry_date,

                "taxTyCd" => $item->tax_type_code,
                "taxblAmt" => $item->taxable_amount,
                "taxAmt" => $item->tax_amount,
                "totAmt" => $item->total_amount,

                "spplrItemClsCd" => $item->item_classification_code,
                "spplrItemCd" => $item->item_code,
                "spplrItemNm" => $item->item_name,

            ];

            array_push($data, $arr);
        endforeach;
        return $data;
    }

    private static function generateInvoiceItemList(Invoice $invoice)
    {
        $itemList = [];
        $count = 1;
        foreach ($invoice->items as $item):

            $itemList[] = [
                'itemSeq' => $count,
                'itemCd' => $item->item_code,
                'itemClsCd' => $item->item_classification_code,
                'itemNm' => $item->item_name,
                'bcd' => $item->barcode,
                'pkgUnitCd' => $item->packaging_unit_code,
                'pkg' => $item->packaging_unit,
                'qtyUnitCd' => $item->quantity_unit_code,
                'qty' => $item->quantity,
                'prc' =>  $item->quantity > 0 ? sprintf("%0.2f", ($item->supply_amount + $item->discount_amount) / $item->quantity) : 0,
                'splyAmt' => sprintf("%0.2f", $item->supply_amount + $item->discount_amount ),
                'dcRt' => $item->discount_rate ? sprintf("%0.2f", $item->discount_rate) : $item->discount_rate,
                'dcAmt' => $item->discount_amount ? sprintf("%0.2f", $item->discount_amount) : $item->discount_amount,
                'isrccCd' => $item->insurance_company_code,
                'isrccNm' => $item->insurance_company_name,
                'isrcRt' => $item->insurance_rate ? sprintf("%0.2f", $item->insurance_rate) : $item->insurance_rate,
                'isrcAmt' => $item->insurance_amount ? sprintf("%0.2f", $item->insurance_amount) : $item->insurance_amount,
                'vatCatCd' => $item->tax_type_code,
                'taxblAmt' => $item->taxable_amount ? sprintf("%0.2f", $item->taxable_amount) : $item->taxable_amount,
                'vatTaxblAmt' => $item->taxable_amount ? sprintf("%0.2f", $item->taxable_amount) : $item->taxable_amount,
                'taxAmt' => $item->tax_amount ? sprintf("%0.2f", $item->tax_amount) : $item->tax_amount,
                'vatAmt' => $item->tax_amount ? sprintf("%0.2f", $item->tax_amount) : $item->tax_amount,
                'totAmt' => $item->total_amount ? sprintf("%0.2f", $item->total_amount) : $item->total_amount,
                'rrp' => $item->supply_amount && $item->tax_type_code == 'B'  && $item->quantity > 0 ? sprintf("%0.2f", $item->supply_amount / $item->quantity) : NULL,
            ];

            $count++;

        endforeach;

        return $itemList;
    }


    public static function getItemTaxTypeCode(Item $product)
    {
        if ($product->vat_exempt) return "A";
        else if ($product->has_vat) return "B";
        else if ($product->vat_zero_rated) return "C";
        else if ($product->non_vat) return "D";
        else if ($product->vat_reduced) return "E";
        else return "D";
    }

    private static function updateInvoice(Invoice $invoice, $response)
    {
        if (empty($response)) return;
        if ($response->resultCd !== '000') return;
        if (empty($response->data)) return;

        $branch = $invoice->branch;

        if( $branch->solution_type == 'vsdc' ):
            $data = [
                'current_reciept_number' => isset($response->data['rcptNo']) ? $response->data['rcptNo'] : null,
                'total_reciept_number' => isset($response->data['totRcptNo']) ? $response->data['totRcptNo'] : null,
                'qr_code' => isset($response->data['qrCodeUrl']) ? $response->data['qrCodeUrl'] : null,
                'internal_data' => isset($response->data['intrlData']) ? $response->data['intrlData'] : null,
                'reciept_signiture' => isset($response->data['rcptSign']) ? $response->data['rcptSign'] : null,
                'control_unit_date_time' => isset($response->data['vsdcRcptPbctDate']) ? $response->data['vsdcRcptPbctDate'] : null,
                'control_unit_serial_number' => isset($response->data['totRcptNo']) ? $response->data['totRcptNo'] : null,
                'control_unit_invoice_number' => isset($response->data['rcptNo']) ? $response->data['rcptNo'] : null,
                'receipt_reference_number' => isset($response->data['rcptSign']) ? $response->data['rcptSign'] : null,
                'sales_control_unit_id' => isset($response->data['sdcId']) ? $response->data['sdcId'] : null,
                'manufacturer_registration_code' => isset($response->data['mrcNo']) ? $response->data['mrcNo'] : null,
                // "synced_at" =>  now(),
                "synced_to_etims" =>1,
            ];
            Log::alert($data);
        else:
            $resObj = (object) $response->data;
            $data = [
                'current_reciept_number' => $resObj->curRcptNo,
                'total_reciept_number' => $resObj->totRcptNo,
                'qr_code' => isset($response->data['qrCodeUrl']) ? $response->data['qrCodeUrl'] : null,
                'internal_data' => $resObj->intrlData,
                'reciept_signiture' => $resObj->rcptSign,
                'control_unit_date_time' => $resObj->sdcDateTime,
                'control_unit_serial_number' => $resObj->totRcptNo,
                'control_unit_invoice_number' => $resObj->curRcptNo,
                'receipt_reference_number' => $resObj->rcptSign,
                'sales_control_unit_id' => isset($resObj->sdcId) ? $resObj->sdcId : null,
                'organisation_sales_device_id' => isset($resObj->sdcId) ? $resObj->sdcId : null,
                'manufacturer_registration_code' => isset($resObj->mrcNo) ? $resObj->mrcNo : null,
                "synced_at" =>  now(),
                "synced_to_etims" =>1,
            ];
        endif;

        Log::alert("Updated invoice with receipt data: ".json_encode($invoice));
        self::updateInvoiceData($invoice,$data);
    }

    private static function updateInvoiceData(Invoice $invoice, Array $data)
    {
        return $invoice->update($data);
    }
}
