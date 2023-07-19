<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('currency:update-history')->everyFifteenMinutes();
        $schedule->command('email:daily-crypto')->timezone('Europe/London')->dailyAt('13:00');
        $schedule->command('telegram:daily-crypto')->timezone('Europe/London')->dailyAt('13:00');
        $schedule->command('config:clear')->daily();
        $schedule->command('config:cache')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
