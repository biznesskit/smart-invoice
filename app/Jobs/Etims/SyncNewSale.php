<?php

namespace App\Jobs\Etims;

use App\Helpers\ETIMSHelper;
use App\Models\Branch;
use App\Models\Invoice;
use App\Models\StockInOut;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncNewSale implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $invoice, $branch, $staff;
    public $timeout = 120; // 2 minutes
    /**
     * Create a new job instance.
     */
    public function __construct(Invoice $invoice,Branch $branch,User $staff)
    {
        $this->invoice = $invoice;
        $this->branch = $branch;
        $this->staff = $staff;

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

    if($this->invoice->type == 'reverse_invoice')   ETIMSHelper::recordNewReverseInvoice($this->invoice,$this->branch,$this->staff);
      else  ETIMSHelper::recordNewSale($this->invoice,$this->branch,$this->staff);

    }
}
