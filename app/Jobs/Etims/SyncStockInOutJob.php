<?php

namespace App\Jobs\Etims;

use App\Helpers\ETIMSHelper;
use App\Models\Branch;
use App\Models\StockInOut;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncStockInOutJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $stockInOut, $branch, $staff;

    /**
     * Create a new job instance.
     */
    public function __construct(StockInOut $stockInOut,Branch $branch, User $staff)
    {
        $this->stockInOut = $stockInOut;
        $this->branch = $branch;
        $this->staff = $staff;
    }

//     public function uniqueId()
// {
//     return  $this->stockInOut ->id;
// }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("Inside JOB");
        ETIMSHelper::insertStockInputOut($this->stockInOut,$this->branch, $this->staff);
    }
}
