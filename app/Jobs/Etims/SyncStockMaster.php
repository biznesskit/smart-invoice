<?php

namespace App\Jobs\Etims;

use App\Helpers\ETIMSHelper;
use App\Models\Branch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\StockInOut;
use App\Models\StockMaster;
use App\Models\User;

class SyncStockMaster implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $stockMaster, $branch, $staff;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( StockMaster $stockMaster, Branch $branch, User $staff)
    {
        $this->stockMaster = $stockMaster;
        $this->branch = $branch;
        $this->staff = $staff;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ETIMSHelper::saveStockMaster($this->stockMaster,$this->branch,$this->staff);        
    }
}
