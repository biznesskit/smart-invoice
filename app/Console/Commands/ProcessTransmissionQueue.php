<?php

namespace App\Console\Commands;

use App\Events\Invoice\InvoiceCreatedEvent;
use App\Helpers\ETIMSHelper;
use App\Helpers\TransmissionQueueManager;
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

class ProcessTransmissionQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:transmission-queue{tenant?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process etims transmission queue';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->argument('tenant')) {
            $tenant = Tenant::find($this->argument('tenant'));
            if( empty ($tenant)) return;
            
           $this->processQueue($tenant);

        } 
        else {
            Tenant::chunk(100, function($tenants) {
                foreach ($tenants as $tenant):
                   $this->processQueue($tenant);
                     $this->info("DONE!");
                     echo "".PHP_EOL;

                endforeach;
            });
        }
        //make sure landlord tables have all columns

        return Command::SUCCESS;
    }

    public function processQueue(Tenant $tenant)
    {
        $tenant->configure()->use();
        $this->info("Processing transmission queue #{$tenant->id} ({$tenant->name}) ...");
        TransmissionQueueManager::processQueue();

    }
   
}
