<?php

namespace App\Jobs\Etims;

use App\Helpers\ETIMSHelper;
use App\Models\Branch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StoreClassificationCode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $codeList,$branch;

    /**
     * Create a new job instance.
     */
    public function __construct(Array $codeList, Branch $branch)
    {
        $this->codeList = $codeList;
        $this->branch = $branch;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        ETIMSHelper::updateItemClassCodeList($this->codeList);
    }
}
