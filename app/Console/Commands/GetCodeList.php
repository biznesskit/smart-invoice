<?php

namespace App\Console\Commands;

use App\Helpers\ETIMSHelper;
use App\Models\Branch;
use App\Models\Company;
use App\Models\Landlord\Tenant;
use Illuminate\Console\Command;

class GetCodeList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:code-list {subMonths?}';

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
        Tenant::chunk(100, function($tenants) {
            foreach ($tenants as $tenant):
                    $tenant->configure()->use();
                    $this->line("-----------------------------------------");
                    $this->info("using Tenant #{$tenant->id} ({$tenant->name})");
                    ETIMSHelper::getCodeList(Branch::first(),$this->argument('subMonths'));
                    $this->line("-----------------------------------------");
                    $this->info("All done!");
                    break;
            endforeach;
        });
    }
   
}
