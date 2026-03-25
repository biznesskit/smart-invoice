<?php

namespace App\Console\Commands;

use App\Helpers\HouseKeeper\HouseKeeperReuseables;
use App\Helpers\ReuseableMethods;
use App\Models\Landlord\Tenant;
use Illuminate\Console\Command;

class ScheduleMigrationsComand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:tenants-migrations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedule to run migrations for each tenant for next time there is a request';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
         ReuseableMethods::scheduleMigrationsToRunNextImmediateRequestForAllTenant();
         return Command::SUCCESS;
    }
    

  
}
