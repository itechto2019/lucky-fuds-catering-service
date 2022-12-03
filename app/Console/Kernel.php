<?php

namespace App\Console;

use App\Console\Commands\PaymentWaitingTime;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected $commands = [
        PaymentWaitingTime::class
    ];
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function() {
            Mail::send('', [], function ($m) {
                $user = Auth::user();
                $m->from(env('MAIL_USERNAME'), 'Lucky Fuds Service Catering System');
                $m->to($user->email)->subject('Lucky Fuds Service Catering System | Online Payment Reminders');
            });
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
