<?php

namespace App\Http\Controllers\Etims;


use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Helpers\ETIMSHelper;
use App\Helpers\TransmissionQueueManager;
use App\Http\Requests\Etims\ValidateRevereseInvoiceRequest;
use App\Models\Branch;
use App\Models\Customer;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;

class ReverseInvoicingController extends Controller
{

    public function generateSupplierToken(Request $request)
    {
        $data = $request->validate([
            'supplier_pin' => 'required|string',
        ]);

        $supplier = Supplier::where('kra_pin', $data['supplier_pin'])->first();

        if (is_null($supplier)) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found. Please register the entity first.',
                'data' => [],

            ], 404);
        }

        $branch = $supplier->branch;

        $response =  ETIMSHelper::generateSupplierToken($branch, $supplier);
        // // $response =  ETIMSHelper::validateSupplierToken($branch, $supplier);
        // // $response =  ETIMSHelper::initializeSupplierDevice($branch, $supplier);

        return $response;
    }

    public function validateSupplierToken(Request $request)
    {
        $data = $request->validate([
            'supplier_pin' => 'required|string',
        ]);
        $supplier = Supplier::where('kra_pin', $data['supplier_pin'])->first();

        if (is_null($supplier)) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found. Please register the entity first.',
                'data' => [],

            ], 404);
        }
        $branch = $supplier->branch;
        $response =  ETIMSHelper::validateSupplierToken($branch, $supplier);

        if ($response->RESULT['info']['cuSerialNo'] ?? null)
                $supplier->update([
                        'etims_device_serial_number' => $response->RESULT['info']['cuSerialNo'] ?? '',
                        'etims_cmc_key' => $response->RESULT['info']['cmcKey'] ?? '',
                        'etims_branch_code' => $response->RESULT['info']['bhfId'] ?? ''
                ]);

        return $response;
    }
    public function initializeSupplierDevice(Request $request)
    {
        $data = $request->validate([
            'supplier_pin' => 'required|string',
        ]);
        $supplier = Supplier::where('kra_pin', $data['supplier_pin'])->first();

        if (is_null($supplier)) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found. Please register the entity first.',
                'data' => [],

            ], 404);
        }
        $branch = $supplier->branch;
        $response =  ETIMSHelper::initializeSupplierDevice($branch, $supplier);

         if ($response->RESULT['info']['cuSerialNo'] ?? null)
                $supplier->update([
                        'etims_device_serial_number' => $response->RESULT['info']['cuSerialNo'] ?? '',
                        'etims_cmc_key' => $response->RESULT['info']['cmcKey'] ?? '',
                        'etims_branch_code' => $response->RESULT['info']['bhfId'] ?? ''
                ]);
        return $response;
    }
    public function selectSupplierList(Request $request, $tracking_number)
    {
       $branch = Branch::where('tracking_number', $tracking_number)->first();

        if (is_null($branch))

            return response()->json([
                'success' => false,
                'message' => 'Branch not found.',
                'data' => [],

            ], 404);

            $data = $branch->suppliers()->paginate(10);

        return 
         response()->json([
             'success' => true,
             'message' => 'A list of registerd entities .',
             'data' => $data,
         ],200);
    }


    public static function createReverseInvoice(ValidateRevereseInvoiceRequest $request, $tracking_number)
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

        $supplier = Supplier::where('kra_pin', $request->supplier_kra_pin)->first();
        if (is_null($supplier))
            return response()->json([
                'success' => false,
                'message' => 'Supplier not found. please create the entity first.',
                'data' => [],
            ]);
        $invoiceData['supplier_id'] = $supplier ? $supplier->id : null;

        $invoiceData['customer_kra_pin'] =  $branch->kra_pin; // $customer ? $customer->kra_pin : null;

        $invoiceData['created_by'] = $staff->id;
        $invoiceData['type'] = 'reverse_invoice';

        if($request->original_invoice_number>0){
          $invoice =  Invoice::where('invoice_number', $request->original_invoice_number)->first();
            if(is_null($invoice)) return response()->json([
                'success' => false,
                'message' => 'Original invoice not found.',
                'data' => [],
            ]);
        }

        $invoice = $branch->invoices()->create($invoiceData);

        foreach ($invoiceItemsData as $invoiceItemData):
            $invoice->items()->create($invoiceItemData);
        endforeach;



        $invoice->jobQueueSequence()->create(['created_by' => $staff->id]);

        return response()->json([
            'success' => true,
            'message' => 'Invoice  accepted',
             'data' =>  collect($invoice)->only([
                    'tracking_number',
                    'invoice_number',
                    'original_invoice_number',
                    'customer_pin',
                    'customer_name',
                ]),
        ], 202, ['Content-Type' => 'application/json']);
    }




}
