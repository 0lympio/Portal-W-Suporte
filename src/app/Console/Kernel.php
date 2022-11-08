<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\DisabledPost::class,
        // Commands\PublisedCron::class,

    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('disabled:post')->everyMinute()->runInBackground();
        $schedule->command('disabled:questionnaires')->everyMinute()->runInBackground();
        $schedule->command('disabled:slideshow')->everyMinute()->runInBackground();
        $schedule->command('report:billing')->monthlyOn(1, '00:01')->runInBackground();
        // $schedule->command('publised:cron')->everyMinute()->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
