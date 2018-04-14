<?php

namespace App\Console;

use App\Console\Commands\Spark\StoreAdditionalPerformanceIndicatorsCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Laravel\Spark\Console\Commands\StorePerformanceIndicatorsCommand;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        StoreAdditionalPerformanceIndicatorsCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule
            ->command(StorePerformanceIndicatorsCommand::class)
            ->dailyAt('03:00')
            ->then(function () {
                $this->app->call(StoreAdditionalPerformanceIndicatorsCommand::class.'@handle');
            });
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
