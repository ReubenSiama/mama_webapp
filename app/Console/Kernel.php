<?php

namespace App\Console;
use MatviiB\Scheduler\Console\Kernel as SchedulerKernel;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use DB;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
      'App\Console\Commands\Resetward',
        'App\Console\Commands\cronEmail',

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
	$schedule->command('command:resetward')->everyMinute();
        $schedule->command('log:resetward')->everyMinute();
        $schedule->command('command:SendEmail')->daily();
      
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
