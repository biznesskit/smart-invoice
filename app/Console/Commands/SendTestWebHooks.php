<?php

namespace App\Console\Commands;

use App\Events\Invoice\InvoiceCreatedEvent;
use App\Helpers\ETIMSHelper;
use App\Helpers\WebHooksHelper;
use App\Jobs\WebHooks\InvoiceTransmitedWebHookJob;
use App\Jobs\WebHooks\StockItemTransmittedWebHookJob;
use App\Jobs\WebHooks\StockMasterTransmittedWebHookJob;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Landlord\Tenant;
use App\Models\StockMaster;
use Illuminate\Console\Command;

class SendTestWebHooks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:test-webhooks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tenant = Tenant::first();
        $tenant->configure()->use();

        $invoice = Invoice::first();
        $product = Item::first();
        $stockMaster = StockMaster::first();
event(new InvoiceCreatedEvent($invoice));
    //   if( $invoice)  InvoiceTransmitedWebHookJob::dispatch($invoice, $tenant->kra_pin);
    //    if($product) StockItemTransmittedWebHookJob::dispatch($product, $tenant->kra_pin);
    //    if($stockMaster) StockMasterTransmittedWebHookJob::dispatch($stockMaster, $tenant->kra_pin);
    }
   
}
