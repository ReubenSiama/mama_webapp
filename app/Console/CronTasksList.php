<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;

/*
 * Trait CronTasksList
 *
 * To use: uncomment all lines and copy your commands list
 * from app/Console/Kernel.php schedule() to tasks() function.
 *
 * @package App\Console
 */
trait CronTasksList
{
    public function tasks(Schedule $schedule)
    {
        $schedule->command('log:resetward')->everyMinute()->withoutOverlapping();
        $schedule->command('command:SendEmail')->everyMinute()->withoutOverlapping();
    }
}
