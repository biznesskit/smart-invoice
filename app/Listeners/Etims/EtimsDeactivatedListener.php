<?php

namespace App\Listeners\Etims;

use App\Events\Etims\EtimsDeactivateddEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class EtimsDeactivatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\Etims\EtimsDeactivateddEvent  $event
     * @return void
     */
    public function handle(EtimsDeactivateddEvent $event)
    {
        //
    }
}
