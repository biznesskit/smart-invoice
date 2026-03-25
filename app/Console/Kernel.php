<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('get:code-list')->monthly()->withoutOverlapping()->onOneServer();
        $schedule->command('get:item-class-list')->monthly()->withoutOverlapping()->onOneServer();
        $schedule->command('get:notices')->twiceDaily()->withoutOverlapping()->onOneServer();
        $schedule->command('process:transmission-queue')->everyTenMinutes()->withoutOverlapping()->onOneServer();
        // $schedule->command('house:keep')->everyTwoMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
