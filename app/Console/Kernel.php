<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /*
                to enable running this task from cron jobs edit your cron tasks with the command: crontab -e
                and add the following line to your jobs:
                
                * * * * * cd /var/www/btc-ticker/ && php artisan schedule:run >> /dev/null 2>&1

        */
        $schedule->command('daemon:ticker')->everyMinute()->withoutOverlapping(525600)->runInBackground();  // un mes corriendo
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
