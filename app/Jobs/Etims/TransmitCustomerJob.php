<?php

namespace App\Jobs\Etims;

use App\Helpers\ETIMSHelper;
use App\Models\Branch;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TransmitCustomerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $customer, $branch, $staff;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Customer $customer, Branch $branch)
    {
        $this->customer = $customer;
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
        ETIMSHelper::saveNewCustomer($this->customer,$this->branch);        
    }
}
