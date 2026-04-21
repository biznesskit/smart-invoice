<?php

namespace App\Helpers;

use App\Models\Branch;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\User;
use Carbon\Carbon;
use Dflydev\DotAccessData\Util;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebHooksHelper
{
    public static function sendInvoiceTransmitedSuccess(Invoice $invoice, string $taxPIN)
    {
        if(!$endpoint = self::getWebhookURL( $invoice,'invoices_client_webhook_url'))  return Log::alert("Returned line 19");
        $scuDateTimestamp = Carbon::createFromFormat('YmdHis', $invoice->etims_control_unit_date_time)->timestamp;
        $invoice = $invoice->fresh();
        $tenant = $invoice->branch->company->tenant;

        $data = [
            'type' => Utilities::sanitizeString($invoice->type),
            'message' => 'Transaction successfully transmitted ',
            'tax_pin' => $taxPIN,
            'tenant_tracking_number' => $tenant->tracking_number,
            // 'id'=>$invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'original_invoice_number' => $invoice->original_invoice_number,
            'tracking_number' => $invoice->tracking_number,
            'date_received' => $invoice->created_at,
            'date_transmitted' => $invoice->updated_at,

            'success' => true,
            'etims_total_reciept_number' => $invoice->total_reciept_number,
            'etims_current_reciept_number' => $invoice->ecurrent_reciept_number,

            'etims_internal_data' => $invoice->internal_data,
            'etims_reciept_signiture' => $invoice->reciept_signiture,
            'control_unit_date_time' => $invoice->control_unit_date_time,
            'etims_control_unit_date_time' => $scuDateTimestamp,

            'etims_control_unit_serial_number' => $invoice->control_unit_serial_number,
            'etims_control_unit_invoice_number' => $invoice->control_unit_invoice_number,

            'etims_sales_control_unit_id' => $invoice->sales_control_unit_id,
            'etims_manufacturer_registration_code' => $invoice->manufacturer_registration_code,
            'invoice_number' => $invoice->invoice_number,
            'qr_code' => $invoice->qr_code,
        ];
        // Log::info('Sending invoice webhook...');
       $response =  self::sendWebHook($endpoint, ['data' => $data]);

       self::processClientWebHookResponse($response, $invoice);
    }

    public static function sendProductSavedSuccess(Item $item, $taxPIN)
    {
        if(!$endpoint = self::getWebhookURL( $item,'products_client_webhook_url'))  return;

        $data = [
            'type' => 'Stock item',
            'message' => 'Transaction successfully transmitted ',
            'tax_pin' => $taxPIN,
            'id' => $item->id,
            'tracking_number' => $item->tracking_number,
            'item name' => $item->name,
            'success' => true,
            'date_received' => $item->created_at,
            'date_transmitted' => $item->updated_at,
        ];

        // Log::info('Sending item  transmitted webhook...');
       $response =  self::sendWebHook($endpoint, ['data' => $data]);
       self::processClientWebHookResponse($response, $item);

    }


    public static function sendStockMassterTransmittedSuccess($item, $taxPIN)
    {
        if(!$endpoint = self::getWebhookURL( $item,'stock_master_client_webhook_url'))  return;

        $data = [
            'type' => 'Stock master',
            'message' => 'Transaction successfully transmitted ',
            'tax_pin' => $taxPIN,
            'id' => $item->id,
            'tracking_number' => $item->tracking_number, //$item->tracking_id,
            'item name' => $item->name,
            'success' => true,
            'date_received' => $item->created_at,
            'date_transmitted' => $item->updated_at,
        ];
        // Log::info('Sending Stock master webhook...');

        // $response =  self::sendWebHook($endpoint, ['data' => $data]);
        // self::processClientWebHookResponse($response,$item);
    }

    public static function sendStockIOTransmittedSuccess($item, $taxPIN)
    {
        if(!$endpoint = self::getWebhookURL( $item,'stock_io_client_webhook_url'))  return;

        $data = [
            'type' => 'Stock I/O',
            'message' => 'Transaction successfully transmitted ',
            'tax_pin' => $taxPIN,
            'id' => $item->id,
            'tracking_number' => $item->tracking_number, //$item->tracking_id,
            'item name' => $item->name,
            'success' => true,
            'date_received' => $item->created_at,
            'date_transmitted' => $item->updated_at,
        ];
        // Log::info('Sending Stock I/O webhook...');

        $response =  self::sendWebHook($endpoint, ['data' => $data]);
        self::processClientWebHookResponse($response,$item);
    }

    public static function sendUserTransmittedSuccess(User $user, $taxPIN)
    {
        if(!$endpoint = self::getWebhookURL( $user,'users_client_webhook_url'))  return;

        $data = [
            'type' => 'Branch user',
            'message' => 'Transaction successfully transmitted ',
            'tax_pin' => $taxPIN,
            'id' => $user->id,
            'tracking_number' => $user->tracking_number, //$item->tracking_id,
            'item name' => $user->first_name,
            'success' => true,
            'date_received' => $user->created_at,
            'date_transmitted' => $user->synced_at,
        ];
        // Log::info('Sending user webhook...');

        $response =  self::sendWebHook($endpoint, ['data' => $data]);
        self::processClientWebHookResponse($response,$user);
    }
    public static function sendCustomerTransmittedSuccess(Customer $customer, $taxPIN)
    {
        if(!$endpoint = self::getWebhookURL( $customer,'customers_client_webhook_url'))  return;

        $data = [
            'type' => 'Branch customer',
            'message' => 'Transaction successfully transmitted ',
            'tax_pin' => $taxPIN,
            'id' => $customer->id,
            'tracking_number' => $customer->tracking_number, //$item->tracking_id,
            'item name' => $customer->name,
            'success' => true,
            'date_received' => $customer->created_at,
            'date_transmitted' => $customer->synced_at,
        ];
        // Log::info('Sending customer webhook...');

        $response =  self::sendWebHook($endpoint, ['data' => $data]);
        self::processClientWebHookResponse($response,$customer);
    }


    protected static function sendWebHook(string $clientWebhookEndpoint, array $data)
    {
        try {
        $response=   Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])
        ->timeout(10)
        ->retry(2, 5000) // Retry 2 times with 5-second delay
        ->post($clientWebhookEndpoint, $data);

          return $response;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public static function processClientWebHookResponse($response,  $model )
    {
      return   $model->update(['client_webhook_sent' => 1]);
        if ($response->failed()) return Log::alert("Returned line 183");
        $data = $response->json();
        if (!empty($data['message']) && $data['message'] == 'RECEIVED')  $model->update(['client_webhook_sent' => 1]);
        Log::info('Web hook delivered');
    }

    public static function getWebhookURL($model, $name)
    {
        if(!$model || !$name) return false;
        $url = MetaDataHelper::getMetaDataValue(Branch::first(), $name);

        if(!$url) return Log::error('194 Client webhook url not found');
        return $url;
    }
}
