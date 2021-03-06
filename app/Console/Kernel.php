<?php

namespace App\Console;

use App\Console\Commands\AuthPermissionCommand;
use App\Console\Commands\flushredisqueue;
use App\Jobs\ProcessEmptyAbstracts;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {



}

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        Commands\AddJournal::class;
        Commands\ReindexCommand::class;
        flushredisqueue::class;
        AuthPermissionCommand::class;
        require base_path('routes/console.php');
    }

}
