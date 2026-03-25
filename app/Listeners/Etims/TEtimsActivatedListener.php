<?php

namespace App\Listeners\Etims;

use App\Events\Etims\EtimsActivatedEvent;
use App\Notifications\Etims\EtimsActivatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TEtimsActivatedListener
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
     * @param  \App\Events\Etims\EtimsActivatedEvent  $event
     * @return void
     */
    public function handle(EtimsActivatedEvent $event)
    {
        $user = $event->user;
        $company = $user->branch->company;
        $user->notify(new EtimsActivatedNotification($company));
    }
}
