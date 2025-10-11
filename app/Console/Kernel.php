<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\DeleteOldLoginHistory::class,
    ];

    protected function schedule(Schedule $schedule)
    {
        // Run login history cleanup daily at midnight
        $schedule->command('login-history:cleanup')->daily();

        // For testing, you can uncomment this:
        // $schedule->command('login-history:cleanup')->everyMinute();
    }

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
