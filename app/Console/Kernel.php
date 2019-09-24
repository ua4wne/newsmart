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
        'App\Console\Commands\Topics',
        'App\Console\Commands\CleanLog',
        'App\Console\Commands\CheckFail',
        'App\Console\Commands\SysState',
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
        $schedule->command('eventlog:clean')->daily(); //каждый день запускаем задачу очистки журнала событий
        $schedule->command('check:fail')->dailyAt('07:00'); //каждый день в 7:00 запускаем задачу проверки параметров, данные которых не обновляются более суток
        $schedule->command('system:state')->dailyAt('07:30'); //каждый день в 7:30 шлем сообщение о загруженности системы
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
