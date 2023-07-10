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
        $schedule->command('sync:category')->everyTwoHours();
        $schedule->command('sync:partsdb')->daily('1:00')->withoutOverlapping();
        $schedule->command('sync:product-image')->daily('7:00');
        $schedule->command('stock:cron')->daily()->between('6:00', '16:00');
        $schedule->command('productprice:cron')->daily()->between('6:00', '16:00');
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
