<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use App\Models\Invite;
use App\Models\verif;
use App\Models\PasswordReset;
use Carbon\Carbon;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            Invite::truncate();
        })->daily();
        
        $schedule->call(function () {
            verif::where('created_at', '<=', Carbon::now()->addMinutes(1))->delete();
        })->everyMinute();
        
        $schedule->call(function () {
            PasswordReset::where('created_at', '<=', Carbon::now()->addMinutes(10))->delete();
        })->everyMinute();

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
