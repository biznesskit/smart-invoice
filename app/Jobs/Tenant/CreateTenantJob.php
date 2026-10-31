<?php

namespace App\Jobs\Tenant;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Helpers\NewTenantAccount;
use Illuminate\Support\Facades\Log;

class CreateTenantJob implements ShouldQueue 
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $tenant, $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tenant,$data)
    {
        $this->tenant = $tenant;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        NewTenantAccount::migrateTenantDB($this->tenant);
        $company = NewTenantAccount::createCompany($this->data, $this->tenant);
        NewTenantAccount::createBranch($this->data, $company);

    }
}
