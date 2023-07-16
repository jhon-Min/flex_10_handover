<?php

namespace App\Console;



use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('sync:partsdb')->timezone(env('CRON_TIME_ZONE'))->dailyAt(env('CRON_TIME'));
        $schedule->command('stock:cron')->everyTwoHours();
        $schedule->command('productprice:cron')->everyTwoHours();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
