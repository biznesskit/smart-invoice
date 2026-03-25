<?php

namespace App\Console\Commands;

use App\Helpers\TransmissionQueueManager;
use App\Models\Landlord\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class KeepETIMSjobsAlive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'keep:etims-jobs-alive{tenant?}';

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
        $this->info("Running eTIMS jobs queue   #{$tenant->id} ({$tenant->name}) ...");
        TransmissionQueueManager::processQueue();
    }
   
}
