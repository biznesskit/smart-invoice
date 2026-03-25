<?php
namespace App\Listeners\Invoice;

use App\Events\Invoice\InvoiceCreatedEvent;
use App\Helpers\TransmissionQueueManager;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvoiceCreatedListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(InvoiceCreatedEvent $event): void
    {
        // $unTransmitedInvoice = Invoice::where('etims_reciept_signiture', null)->first();
        // Log::info($unTransmitedInvoice);
        // $staff = $unTransmitedInvoice ? ($unTransmitedInvoice->staff ? $unTransmitedInvoice->staff   : User::first()):User::first();
        // SyncNewSale::dispatch( $unTransmitedInvoice,$unTransmitedInvoice->branch,   $staff);
        // TransmissionQueueManager::transmitInvoicesOneByOne();
    }

   

}
