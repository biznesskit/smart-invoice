<?php

namespace App\Jobs\Etims;

use App\Helpers\ETIMSHelper;
use App\Models\Branch;
use App\Models\Item;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class TransmitItemJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $item, $branch, $staff;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( Item $item, Branch $branch, User $staff)
    {
        $this->item = $item;
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
        ETIMSHelper::saveItem($this->item,$this->branch,$this->staff);        
    }
}
