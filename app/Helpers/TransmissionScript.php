<?php

namespace App\Helpers;

use App\Models\JobQueueSequence;
use Illuminate\Support\Facades\Cache;

class TransmissionScript
{

    public static function jobsDispatcherScript()
    {
        $status = self::getStatus();   // Return if script is running

        $totalJobs =  JobQueueSequence::count();

        if(!$totalJobs) return  self::getStatus('stopped');


        if ($totalJobs > 0):
            $counter = 0;
            do {
                TransmissionQueueManager::processQueue();
                sleep(0.2);
                $counter++;
            } while ($counter <= 10);
        endif;

        sleep(1);

          self::jobsDispatcherScript();  // Repeat as long as there are pending jobs
    }

    /**
    * Script status can be either running | stopped | undefined
     */
    public static function setStatus($status='running')
    {
        $keyName = "tenant_tenant->business_code_transmission_script_status";

        $value = Cache::get($keyName);

        if (! $value)  Cache::put($keyName, $status, now()->addMinutes(10));
        return $value == 'running';
    }


    public static function getStatus()
    {
        $keyName = "tenant_tenant->business_code_transmission_script_status";
        $status = Cache::get($keyName);
        return $status == 'running';
    }

    public static function forgetStatus()
    {
        $keyName = "tenant_tenant->business_code_transmission_script_status";
    Cache::forget($keyName);
    }


}
