<?php

namespace App\Console\Commands;

use App\Helpers\HouseKeep;
use App\Models\Landlord\Tenant;
use Illuminate\Console\Command;

class HouseKeeping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'house:keep {tenant?}';

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
            
            HouseKeep::run($tenant);

        } 
        else {
            Tenant::chunk(100, function($tenants) {
                foreach ($tenants as $tenant):
                    $this->info("Running house keep for Tenant #{$tenant->id} ({$tenant->name}) ...");
                     HouseKeep::run($tenant);
                     $this->info("DONE!");
                     echo "".PHP_EOL;

                endforeach;
            });
        }
        //make sure landlord tables have all columns

        return Command::SUCCESS;
    }

    
}
