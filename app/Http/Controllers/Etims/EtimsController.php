<?php

namespace App\Http\Controllers\Etims;

use App\Helpers\ETIMSHelper;
use App\Helpers\MetaDataHelper;
use App\Helpers\StandardTaxationCodes;
use App\Helpers\TransmissionQueueManager;
use App\Helpers\Utilities;
use App\Helpers\WebHooksHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Etims\BranchInitializationRequest;
use App\Http\Requests\Etims\SaveBranchCustomerRequest;
use App\Http\Requests\Etims\SaveBranchInsurance;
use App\Http\Requests\Etims\SaveBranchUserRequest;
use App\Http\Requests\Etims\SaveCreditNoteRequest;
use App\Http\Requests\Etims\SaveItemCompositionRequest;
use App\Http\Requests\Etims\SaveItemRequest;
use App\Http\Requests\Etims\SaveTransactionSaleRequest;
use App\Http\Requests\Etims\StockInOutRequest;
use App\Http\Requests\Etims\UpdateImportItemRequest;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Supplier;
use App\Models\ImportItem;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Landlord\ItemClassification;
use App\Models\Landlord\Notice;
use App\Models\Landlord\TaxCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EtimsController extends Controller
{
    public function initialize_branch(BranchInitializationRequest $request, $tracking_number)
    {
        $branch = Branch::where('tracking_number', $tracking_number)->first();

        if (is_null($branch))

            return response()->json([
                'success' => false,
                'message' => 'Branch not found.',
                'data' => [],

            ], 404);

        $data = $request->validated();
        if( isset($data['company_kra_pin'])){
            $data['kra_pin'] = strtoupper($request->company_kra_pin);
            unset($data['company_kra_pin']);
        }
        if(empty($data['kra_pin'])) return response()->json([
            'success' => false,
            'message' => 'Company KRA PIN is required.',
            'data' => [],
        ],422);
        $branch->update($data);
        $response = ETIMSHelper::initialize_branch($branch);

        if (is_null($response) || ! count((array)$response))  return response()->json([
            'success' => false,
            'message' => 'Unable to get response from Etims server',
            'data' => [],

        ], 503);

        return $response;
    }
    public function select_code_list()
    {
        ETIMSHelper::getCodeList(Branch::first()); //temporary for testing
        return TaxCode::paginate(env('API_PAGINATION', 10));
    }
    public function select_item_classification_list()
    {
        ETIMSHelper::ItemClassList(Branch::first()); //temporary for testing
        return ItemClassification::paginate(env('API_PAGINATION', 10));
    }
    public function select_notice_list()
    {
        ETIMSHelper::noticesSearch(Branch::first(), 7200); //temporary for testing
        return Notice::paginate(env('API_PAGINATION', 10));
    }
    public function select_customer_list($tracking_number)
    {
        $branch = Branch::where('tracking_number', $tracking_number)->first();

        if (is_null($branch))

            return response()->json([
                'success' => false,
                'message' => 'Branch not found.',
                'data' => [],

            ], 404);

        return EtimsHelper::getCustomerList();
    }
    public function select_item_list($tracking_number)
    {
        $branch = Branch::where('tracking_number', $tracking_number)->first();

        if (is_null($branch))

            return response()->json([
                'success' => false,
                'message' => 'Branch not found.',
                'data' => [],

            ], 404);


        return ETIMSHelper::itemSearch($branch,72);

        // return $branch->items()->paginate(env('API_PAGINATION', 10));
    }
    public function select_stock__movement_list($tracking_number)
    {
        $branch = Branch::where('tracking_number', $tracking_number)->first();

        if (is_null($branch))

            return response()->json([
                'success' => false,
                'message' => 'Branch not found.',
                'data' => [],

            ], 404);
        return ETIMSHelper::stockMovement($branch);
    }
    public function select_sales_list($tracking_number) ###############
    {
        $branch = Branch::where('tracking_number', $tracking_number)->first();

        if (is_null($branch))

            return response()->json([
                'success' => false,
                'message' => 'Branch not found.',
                'data' => [],

            ], 404);

            $invoices = $branch->invoices()->paginate(env('API_PAGINATION', 20));
            return response()->json([
                'success' => false,
                'message' => 'Branch not found.',
                'data' => $invoices,

            ], 200);
    }
    public function select_branch_list()
    {
        ETIMSHelper::getBranchList();
        return Branch::paginate(env('API_PAGINATION', 10));
    }
    public function select_import_list($tracking_number)
    {
        $branch = Branch::where('tracking_number', $tracking_number)->first();

        if (is_null($branch))

            return response()->json([
                'success' => false,
                'message' => 'Branch not found.',
                'data' => [],

            ], 404);

        ETIMSHelper::getImportItemList($branch);
        return $branch->imports()->with(['items'])->paginate(env('API_PAGINATION', 10));
    }
    public function select_purchase_list($tracking_number)
    {
        $branch = Branch::where('tracking_number', $tracking_number)->first();

        if (is_null($branch))

            return response()->json([
                'success' => false,
                'message' => 'Branch not found.',
                'data' => [],

            ], 404);

        ETIMSHelper::getPurchaseItemList($branch);
        return $branch->purchases()->with(['items'])->paginate(env('API_PAGINATION', 10));
    }

    public function update_import_item(UpdateImportItemRequest $request, $tracking_number, ImportItem $item)
    {
        $branch = Branch::where('tracking_number', $tracking_number)->first();

        if (is_null($branch))

            return response()->json([
                'success' => false,
                'message' => 'Branch not found.',
                'data' => [],

            ], 404);


        $data = $request->validated();
        $data['id'] = $item->id;
        $response = ETIMSHelper::updateImportItem($data, $branch);
        if (is_null($response) || ! count((array)$response)) return response()->json([
            'success' => false,
            'message' => 'Unable to get response from Etims server',
            'data' => [],

        ], 503);
        if ($response->resultCd != "000") return $response;
        $item->update($data);
        return $item;
    }

    public function save_branch_customer(SaveBranchCustomerRequest $request, $tracking_number)
    {
        $data = $request->validated();
        $branch = Branch::where('tracking_number', $tracking_number)->first();

        if (is_null($branch))

            return response()->json([
                'success' => false,
                'message' => 'Branch not found.',
                'data' => [],

            ], 404);

        $data['kra_pin'] = strtoupper($data['kra_pin']);
        $customer = $branch->customers()->create($data);

        $response = ETIMSHelper::saveNewCustomer($customer, $branch);
        if( is_null($response) || ! count((array)$response)) {$customer->forceDelete(); return response()->json([
            'success' => false,
            'message' => 'Unable to get response from Etims server',
            'data' => [],

        ], 503);}

        if( $response->resultCd == "000" ) $customer->update(['synced_at'=>now()]);
        else $customer->forceDelete();
        return $response;
    }

    public function save_branch_user(SaveBranchUserRequest $request, $tracking_number)
    {
        $data = $request->validated();
        $branch = Branch::where('tracking_number', $tracking_number)->first();

        if (is_null($branch))
            return response()->json([
                'success' => false,
                'message' => 'Branch not found.',
                'data' => [],

            ], 404);

            $branchUser = $branch->users()->create($data);
        // $response = ETIMSHelper::saveNewUser($branchUser, $branch);

        $response = ETIMSHelper::saveNewUser($branchUser, $branch);
        if(  is_null($response) || ! count((array)$response)) {$branchUser->forceDelete(); return response()->json([
            'success' => false,
            'message' => 'Unable to get response from Etims server',
            'data' => [],

        ], 503);}
        if( $response->resultCd == "000" ) $branchUser->update(['synced_at'=>now()]);
        else $branchUser->forceDelete();
        return $response;
    }

    public function save_branch_insurance(SaveBranchInsurance $request, $tracking_number)
    {
        $data = $request->validated();
        $branch = Branch::where('tracking_number', $tracking_number)->first();

        if (is_null($branch))

            return response()->json([
                'success' => false,
                'message' => 'Branch not found.',
                'data' => [],

            ], 404);

        $insurance = $branch->insurances()->create($data);
        $response = ETIMSHelper::saveBranchInsuarance($insurance, $branch);
        if (is_null($response) || ! count((array)$response)) {
            $insurance->forceDelete();
            return response()->json([
                'success' => false,
                'message' => 'Unable to get response from Etims server',
                'data' => [],

            ], 503);
        }

        if ($response->resultCd == "000") $insurance->update(['synced_at' => now()]);
        else $insurance->forceDelete();
        return $response;
    }
    public function save_item(SaveItemRequest $request, $tracking_number)
    {
        $data = $request->validated();

        $branch = Branch::where('tracking_number',$tracking_number)->first();

        if( is_null($branch) )

        return response()->json([
            'success' => false,
            'message' => 'Branch not found.',
            'data' => [],

        ], 404);

        $data['packaging_unit_code'] = $data['type'] == 'service' ? 'BE' : $data['packaging_unit_code'];
        $data['quantity_unit_code'] = $data['type'] == 'service' ? 'NO' : $data['quantity_unit_code'];
        $item = $branch->items()->create($data);

        $response = ETIMSHelper::saveItem($item, $branch,$request->user());

        if( is_null($response) || ! count((array)$response)) {$item->forceDelete(); return response()->json([
            'success' => false,
            'message' => 'Unable to get response from Etims server',
            'data' => [],

        ], 503);}


        if($item->type == 'service') return $response;

        if( $response->resultCd == "000" || true):

            $item->update(['synced_at'=>now()]);
            if( $request->opening_balance ):
                $stock_type_code = StandardTaxationCodes::getStockOutType($request->stock_balance_reason);

                $released_type_code = $stock_type_code ? $stock_type_code : "06";//incoming_adjustment
                $original_stored_and_released_number = 0;
                $customer = null;
                $staff = $request->user();
                $item_list = [[
                    "item_id" => $item->id,
                    "tracking_number" => $item->tracking_number,
                    'quantity' => $request->opening_balance,
                    'remaining_quantity' => $request->opening_balance,
                    'unit_price' => $request->default_unit_price,
                    'supply_price' => $request->default_unit_price,
                    'discount_rate' => $request->discount_rate,
                    'discount_amount' => $request->discount_amount,
                    'total_discount_amount' => $request->total_discount_amount,
                    'taxable_amount' => $request->taxable_amount,
                    'tax_amount' => $request->tax_amount,
                    'total_amount' => $request->total_amount,
                ]];
                Utilities::recordStockIO($branch,$released_type_code,$original_stored_and_released_number,$request->taxable_amount,$request->tax_amount,$request->total_amount,$item_list,$staff,$request->stock_tracking_number,$customer);
            endif;

        else:
             $item->forceDelete();
        endif;
        return $response;
    }

    public function save_stock_io(StockInOutRequest $request, $tracking_number)
    {
        $staff = $request->user();
       $branch = Branch::where('tracking_number',$tracking_number)->first();

        if( is_null($branch) )

        return response()->json([
            'success' => false,
            'message' => 'Branch not found.',
            'data' => [],

        ], 404);

       $customer = Customer::find($request->customer_id);
       $original_stored_and_released_number = 0;
       $stockIO = Utilities::recordStockIO($branch,$request->stored_and_released_type_code,$original_stored_and_released_number,$request->total_taxable_amount,$request->total_tax_amount,$request->total_amount,$request->items,$staff,$request->tracking_number,$customer,null,$request);
       return $stockIO;
    }

    // public function decrease_item_inventory(StockInOutRequest $request,Branch $branch)
    // {
    //    $staff = $request->user();
    //    $customer = Customer::find($request->customer_id);
    //    $original_stored_and_released_number = 0;
    //    $stockIO = Utilities::recordStockIO($branch,$request->stored_and_released_type_code,$original_stored_and_released_number,$request->total_taxable_amount,$request->total_tax_amount,$request->total_amount,$request->items,$staff,$customer);
    //    return $stockIO;
    // }

    public function save_item_composition(SaveItemCompositionRequest $request, $tracking_number)
    {
        //ensure item stock quantities exist
        // $unavailableItems = Utilities::validateItemsAvailableInStock($request->item_list);
        // if( count($unavailableItems) )
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Unavailable items',
        //         'data' => $unavailableItems
        //     ], 404);
        $branch = Branch::where('tracking_number', $tracking_number)->first();

        if (is_null($branch))

            return response()->json([
                'success' => false,
                'message' => 'Branch not found.',
                'data' => [],

            ], 404);


        $staff = $request->user();

        //save item composition (reduce stock for each item used)
        $itemCompositon = $branch->composite_items()->create(['tracking_number'=>$request->tracking_number,'item_code' => $request->composition_item_code, 'item_name' => $request->composition_item_name]);
        foreach ($request->item_list as $item):
            $savedItem = Item::where('item_code', $item['item_code'])->first();
            $itemCompositonItem = $itemCompositon->composition_items()->create([
                'item_id' => $savedItem->id,
                'item_code' => $item['item_code'],
                'tracking_number' => $item['tracking_number'],
                'item_name' => $savedItem->item_name,
                'quantity' => $item['quantity'],
                'remaining_quantity' => $item['remaining_quantity'],
                'unit_price' => $savedItem->default_unit_price,
                'discount_rate' => $item['discount_rate'],
                'discount_amount' => $item['discount_amount'],
                'total_discount_amount' => $item['discount_amount'],
                'tax_type_code' => $savedItem->tax_type_code,
                'taxable_amount' => $item['taxable_amount'],
                'tax_amount' => $item['tax_amount'],
                'total_amount' => $item['total_amount'],
            ]);

            //dispatch composition job
            ETIMSHelper::itemComposition($itemCompositonItem, $branch, $staff);
        endforeach;

        $released_type_code = "14"; //outgoing_adjustment
        $original_stored_and_released_number = 0;
        $customer = null;

        Utilities::recordStockIO($branch, $released_type_code, $original_stored_and_released_number, $request->total_taxable_amount, $request->total_tax_amount, $request->total_amount, $itemCompositon->composition_items->toArray(), $staff, $request->tracking_number, $customer);

        return $itemCompositon;
    }

    public static function save_transaction_sale(SaveTransactionSaleRequest $request, $tracking_number)
    {
        $branch = Branch::where('tracking_number', $tracking_number)->first();
        if (is_null($branch))

            return response()->json([
                'success' => false,
                'message' => 'Branch not found.',
                'data' => [],

            ], 404);

        $staff = $request->user();
        $invoiceData =  $request->except(['item_list']);
        $invoiceItemsData =  $request->item_list;

        if($request->customer_kra_pin){
        $customer = Customer::where('kra_pin', $request->customer_kra_pin)->first();
         $invoiceData['customer_id'] = $customer ? $customer->id : null;
        }
        if($request->supplier_kra_pin){
        $supplier= Supplier::where('kra_pin', $request->supplier_kra_pin)->first();
         $invoiceData['supplier_id'] = $supplier ? $supplier->id : null;
        }

        $invoiceData['created_by'] = $staff->id;
        $invoiceData['type'] = $request->original_invoice_number==0? 'normal_invoice' : 'credit_note';
        $tenant = app()->bound('tenant') ? app('tenant') : null;

        // $invoice = DB::transaction(function () use (&$invoiceData, $branch, &$invoiceItemsData, $request, $staff,$tenant) {


            $nextInvNumber = $branch->current_fiscal_invoice_number ? $branch->current_fiscal_invoice_number + 1 : 1;

            ######################################
            //temporary fix
            $nextInvNumber = $nextInvNumber + 300;
            ######################################

            $invoiceData['invoice_number'] = $nextInvNumber;
            $invoiceData['purchase_invoice_number'] = $nextInvNumber;

            $branch->update(['current_fiscal_invoice_number' => $nextInvNumber]);


        $invoice = $branch->invoices()->create($invoiceData);

        foreach ($invoiceItemsData as $invoiceItemData):
            $invoice->items()->create($invoiceItemData);
        endforeach;

        $released_type_code = $request->original_invoice_number == '0' ? "11" : "03"; //outgoing_adjustment
        $original_stored_and_released_number = 0;
        if( $invoice->original_invoice_number != '0' )
        {
            $originalInvoice = $branch->invoices()->where('invoice_number',$invoice->original_invoice_number)->first();
            if( $originalInvoice )
            {
                $originalStockInOut = $originalInvoice->stock_in_out;
                $original_stored_and_released_number = $originalStockInOut  ? $originalStockInOut->stored_and_released_number : $original_stored_and_released_number;
            }
        }
        $customer = $invoice->customer;

        $invoice->jobQueueSequence()->create(['created_by' => $staff->id]);
        Utilities::recordStockIO($branch, $released_type_code, $original_stored_and_released_number, $request->total_taxable_amount, $request->total_tax_amount, $request->total_amount, $invoice->items->toArray(), $staff, $request->stock_tracking_number, $customer, $invoice);

        // });

        return response()->json([
            'success' => true,
            'message' => 'Request accepted',
               'data' =>  collect($invoice)->only([
                    'tracking_number',
                    'invoice_number',
                    'original_invoice_number',
                    'customer_pin',
                    'customer_name',
                ]),
        ], 202, ['Content-Type' => 'application/json']);
    }
    public static function create_credit_note(SaveCreditNoteRequest $request, $tracking_number)
    {
        $branch = Branch::where('tracking_number', $tracking_number)->first();
        if (is_null($branch))

            return response()->json([
                'success' => false,
                'message' => 'Branch not found.',
                'data' => [],

            ], 404);

        $staff = $request->user();
        $invoiceData =  $request->except(['item_list']);
        $invoiceItemsData =  $request->item_list;

        // if($request->customer_kra_pin){
        // $customer = Customer::where('kra_pin', $request->customer_kra_pin)->first();
        //  $invoiceData['customer_id'] = $customer ? $customer->id : null;
        // }
        // if($request->supplier_kra_pin){
        // $supplier= Supplier::where('kra_pin', $request->supplier_kra_pin)->first();
        //  $invoiceData['supplier_id'] = $supplier ? $supplier->id : null;
        // }

        // Log::info($invoiceData);

        $originalInvoice = $branch->invoices()->where('invoice_number',$request->original_invoice_number)->first();
        // Log::info(json_encode($originalInvoice));
         $invoiceData['customer_id'] = $originalInvoice ->customer_id;
         $invoiceData['customer_name'] = $originalInvoice ->customer_name;
         $invoiceData['customer_kra_pin'] = $originalInvoice ->customer_kra_pin;
         $invoiceData['credit_note_reason_code'] = StandardTaxationCodes::sanitizeCreditNoteCode($request->credit_note_reason_code);

         $invoiceData['supplier_id'] = $originalInvoice->supplier_id;
         $invoiceData['supplier_name'] = $originalInvoice->supplier_name;
         $invoiceData['supplier_kra_pin'] = $originalInvoice->supplier_kra_pin;

        $invoiceData['created_by'] = $staff->id;
        $invoiceData['type'] = $request->original_invoice_number==0? 'normal_invoice' : 'credit_note';

        // $invoice = DB::transaction(function () use (&$invoiceData, $branch, &$invoiceItemsData, $request, $staff) {
            $nextInvNumber = $branch->current_fiscal_invoice_number ? $branch->current_fiscal_invoice_number + 1 : 1;

            $invoiceData['invoice_number'] = $nextInvNumber;
            $invoiceData['purchase_invoice_number'] = $nextInvNumber;

            $branch->update(['current_fiscal_invoice_number' => $nextInvNumber]);

        $invoice = $branch->invoices()->create($invoiceData);

        foreach ($invoiceItemsData as $invoiceItemData):
            $invoice->items()->create($invoiceItemData);
        endforeach;

        $released_type_code = $request->original_invoice_number == '0' ? "11" : "03"; //outgoing_adjustment
        $original_stored_and_released_number = 0;
        if( $invoice->original_invoice_number != '0' )
        {
            $originalInvoice = $branch->invoices()->where('invoice_number',$invoice->original_invoice_number)->first();
            if( $originalInvoice )
            {
                $originalStockInOut = $originalInvoice->stock_in_out;
                $original_stored_and_released_number = $originalStockInOut  ? $originalStockInOut->stored_and_released_number : $original_stored_and_released_number;
            }
        }
        $customer = $invoice->customer;

        $invoice->jobQueueSequence()->create(['created_by' => $staff->id]);
        Utilities::recordStockIO($branch, $released_type_code, $original_stored_and_released_number, $request->total_taxable_amount, $request->total_tax_amount, $request->total_amount, $invoice->items->toArray(), $staff, $request->stock_tracking_number, $customer, $invoice);

        // });

        return response()->json([
            'success' => true,
            'message' => 'Request accepted',
               'data' =>  collect($invoice)->only([
                    'tracking_number',
                    'invoice_number',
                    'original_invoice_number',
                    'customer_pin',
                    'customer_name',
                ]),
        ], 202, ['Content-Type' => 'application/json']);
    }


    public static function registerClientWebHookURLs(Request $request)
    {

       $data =  $request->validate([
            'invoices_client_webhook_url' => 'string|nullable',
            'products_client_webhook_url' => 'string|nullable',
            'customers_client_webhook_url' => 'string|nullable',
            'stock_io_client_webhook_url' => 'string|nullable',
            'stock_master_client_webhook_url' => 'string|nullable',
        ]);

        $branch = Branch::first();


        foreach ($data as $key=>$value) {
            MetaDataHelper::createOrUpdate($branch, 'branches', [ $key => $value], 'client_webhook_urls');
        }
        return response()->json([
            'success' => true,
            'message' => 'Webhook URLs registered',
            'data' => []
        ], 200);
    }


public function getInvoice(Request $request, $tracking_number)
{

    $branch = $request->user()->branch;
    $invoice = Invoice::where('tracking_number', $tracking_number)->first();
    if( is_null($invoice) )
        return response()->json([
            'success' => true,
            'message' => 'Invoice not found',
            'data' => []
        ], 404);

    // WebHooksHelper::sendInvoiceTransmitedSuccess($invoice,$branch->kra_pin);
    // TransmissionQueueManager::processQueue();

    return response()->json([
        'success' => true,
        'message' => 'Single Invoice fetched',
        'data' => $invoice
    ], 200);
}

}
