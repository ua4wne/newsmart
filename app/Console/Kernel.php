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
        'App\Console\Commands\GetWeather',
        'App\Console\Commands\Logger',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // artisan command method
        $schedule->command('weather:file')->hourly(); //каждый час запрашиваем данные погоды
        $schedule->command('logger:save')->everyMinute(); //каждую минуту находим переменные, значения которых необходимо писать в лог
        $schedule->command('topic:save')->everyMinute(); //каждую минуту обработчик данных, поступающих из топиков mqtt
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
