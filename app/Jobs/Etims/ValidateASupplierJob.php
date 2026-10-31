<?php

namespace App\Jobs\Etims;

use App\Helpers\ETIMSHelper;
use App\Models\Branch;
use App\Models\Supplier;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ValidateASupplierJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $supplier, $branch;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Supplier $supplier, Branch $branch)
    {
        $this->supplier = $supplier;
        $this->branch = $branch;
        // $this->staff = $staff;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            
          if($this->supplier->kra_token) ETIMSHelper::generateSupplierToken($this->branch, $this->supplier);
        } catch (\Throwable $th) {
           Log::error($th);
        }

        try {
          if($this->supplier->etims_token)  ETIMSHelper::validateSupplierToken($this->branch, $this->supplier);
        } catch (\Throwable $th) {
                     Log::error($th);
        }
        try {
           if($this->supplier->etims_etims_branch_code)    ETIMSHelper::initializeSupplierDevice($this->branch, $this->supplier);    
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
