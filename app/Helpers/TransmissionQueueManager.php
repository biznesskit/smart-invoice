<?php

namespace App\Helpers;

use App\Jobs\Etims\SyncNewSale;
use App\Jobs\Etims\SyncStockInOutJob;
use App\Jobs\Etims\SyncStockMaster;
use App\Jobs\Etims\TransmitCustomerJob;
use App\Jobs\Etims\TransmitItemJob;
use App\Jobs\Etims\TransmitUserJob;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\JobQueueSequence;
use App\Models\StockInOut;
use App\Models\StockMaster;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TransmissionQueueManager
{

    public static function transmitInvoicesOneByOne()
    {
        $firstUnTransmitedInvoice = Invoice::where('etims_reciept_signiture', null)->first();
        if (empty($firstUnTransmitedInvoice)) return;
        $staff = $firstUnTransmitedInvoice->staff;
        SyncNewSale::dispatch($firstUnTransmitedInvoice, $firstUnTransmitedInvoice->branch,   $staff);
    }

    public static function transmitItemsOneByOne()
    {
        $firstUnTransmitedItem = Item::where('synced_at', null)->first();
        if (empty($firstUnTransmitedItem)) return;
        $staff = $firstUnTransmitedItem->staff;
        // Log::info($staff);
        TransmitItemJob::dispatch($firstUnTransmitedItem, $firstUnTransmitedItem->branch,   $staff);
    }

    public static function transmitStockInOutOneByOne()
    {
        $firstUnTransmitedIO = StockInOut::where('synced_at', null)->first();
        if (empty($firstUnTransmitedIO)) return;
        $staff = $firstUnTransmitedIO->staff;
        SyncStockInOutJob::dispatch($firstUnTransmitedIO, $firstUnTransmitedIO->branch,   $staff);
    }

    public static function transmitStockMastersOneByOne()
    {
        $firstUnTransmited = StockMaster::where('synced_at', null)->first();
        if (empty($firstUnTransmited)) return;
        $staff = $firstUnTransmited->staff;
        SyncStockMaster::dispatch($firstUnTransmited, $firstUnTransmited->branch,   $staff);
    }

    public static function transmitUsersOneByOne()
    {
        $firstUnTransmitedUser = User::where('synced_to_etims', null)->first();
        if (empty($firstUnTransmitedUser)) return;
        TransmitUserJob::dispatch($firstUnTransmitedUser,  $firstUnTransmitedUser->branch);
    }

    public static function transmitCustomersOneByOne()
    {
        $firstUnTransmited = Customer::where('synced_at', null)->first();
        if (empty($firstUnTransmited)) return;
        $staff = $firstUnTransmited->staff;
        TransmitCustomerJob::dispatch($firstUnTransmited, $firstUnTransmited->branch,   $staff);
    }


    // =============================================================================


    public  static function processQueue()
    {
        // $record = JobQueueSequence::first();

          $record = JobQueueSequence::whereNull('dispatched_at')
            ->lockForUpdate()
            ->orderByRaw("queueable_type = 'App\\\\Models\\\\Order' DESC")
            ->orderBy('id')
            ->first();

        if (empty($record)) return;
        if ($record->dispatched_at) return;

        $failure_exception = json_decode($record->failure_exception);
        $resultCode = isset($failure_exception->resultCd) ? $failure_exception->resultCd : null;
        $resultMessage = isset($failure_exception->resultMsg) ? $failure_exception->resultMsg : null;
        $model = $record->queueable_type;
        $modelId = $record->queueable_id;

        // if the job failure exception code was "resultCd":"924" and "resultMsg":"Invoice number already exists."
        if( $resultCode == '924' && $resultMessage == 'Invoice number already exists.'):
            if( $model == 'App\Models\Invoice' )
            {
                $invoice = Invoice::find($modelId);
                if( $invoice )
                    return ETIMSHelper::getInvoice($invoice->branch,$invoice);
            }
            return;
        endif;

        $queuedModel =  $record->queueable;

        if (empty($queuedModel) ||  $queuedModel->synced_to_etims || $queuedModel->synced_at)
                 return  $record->forceDelete();  // Remove job from queue if transmitted

        $jobType = $record->queueable_type;


        if ($queuedModel instanceof Invoice):
            self::transmitInvoice($queuedModel);
        elseif ($queuedModel instanceof StockInOut):
            self::transmitStockIO($queuedModel);
        elseif ($queuedModel instanceof StockMaster):
            self::transmitStockMaster($queuedModel);
        else:
            Log::error('Cannot process an Unknown job from transmission queue. Job Type: ' . $jobType);
            return;
        endif;

        $record->update(['dispatched_at' => now()]);
    }

    public static function transmitInvoice(Invoice $invoice)
    {
        if ($invoice->synced_at)  return;
        SyncNewSale::dispatch($invoice, $invoice->branch,   $invoice->staff);
    }

    public static function transmitStockIO(StockInOut $stockInOut)
    {
        Log::info('Transmitting stock I/O ' . $stockInOut->stored_and_released_number);
        if ($stockInOut->synced_at)  return Log::alert("returned here");
        SyncStockInOutJob::dispatch($stockInOut, $stockInOut->branch,   $stockInOut->staff);
    }

    public static function transmitStockMaster(StockMaster $stockMaster)
    {
        if ($stockMaster->synced_at)  return;
        SyncStockMaster::dispatch($stockMaster, $stockMaster->branch,   $stockMaster->staff);
    }

    public static function markJobAsFailed($model, $exception)
    {
        if (!$model) return;
        $job = $model->jobQueueSequence;

        if( ! $job ) return;

        $job->update([
            'failed_at' => now(),
            'dispatched_at' => null,
            'failure_exception' => json_encode($exception)
        ]);


    }
    public static function markJobAsCompleted($model)
    {
        if (empty($model)) return;
        $job = $model->jobQueueSequence;
        if( empty($job) ) return;
        $job->forceDelete();
        self::processQueue();
    }


}
