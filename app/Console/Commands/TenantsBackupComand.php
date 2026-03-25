<?php

namespace App\Console\Commands;

use App\Models\Landlord\Tenant;
use Illuminate\Console\Command;

class TenantsBackupComand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:backup {tenant?}';

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
            $this->backup(
                Tenant::find($this->argument('tenant'))
            );

        } 
        else {
            Tenant::chunk(100, function($tenants) {
                foreach ($tenants as $tenant) $this->backup($tenant);
            });
        }

        return Command::SUCCESS;
    }
    

    public function backup($tenant)
    {
       if( empty($tenant->subscription_active) ) return ;
        $tenant->configure()->use();

        $this->line('');
        $this->line("-----------------------------------------");
        $this->info("Backing up Tenant #{$tenant->id} ({$tenant->name})");
        $this->line("-----------------------------------------");


        $options = ['--only-db'  => true, '--disable-notifications' => true ];

        $this->call(
            'backup:run',
            $options
        );

    }
}
