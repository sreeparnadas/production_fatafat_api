<?php

namespace App\Console;

use App\Console\Commands\GenerateResult;
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
        '\App\Console\Commands\GenerateResult',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        //$schedule->command('generate:result')->everyThirtyMinutes()->timezone('Asia/Kolkata');

//        $schedule->command('generate:result')->cron('50 10-13,15-19 * * * ')->timezone('Asia/Kolkata');
//        $schedule->command('generate:result')->dailyAt('21:00')->timezone('Asia/Kolkata');


        $schedule->command('generateFatafat:result')->cron('30 11,14,17,20 * * * ')->timezone('Asia/Kolkata');
        $schedule->command('generateFatafat:result')->cron('00 10,13,16,19 * * * ')->timezone('Asia/Kolkata');
        $schedule->command('generateShirdi:result')->cron('30 10,13,16,18,19 * * * ')->timezone('Asia/Kolkata');
        $schedule->command('generateShirdi:result')->cron('00 12,15,21 * * * ')->timezone('Asia/Kolkata');
        $schedule->command('drawOver:update')->dailyAt('00:00')->timezone('Asia/Kolkata');
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
