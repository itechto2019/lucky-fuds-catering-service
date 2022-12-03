<?php

namespace App\Console\Commands;

use App\Models\UserRent;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class PaymentWaitingTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'online_payment:hourly';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'For Online payment send a mail';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Mail::send('', [], function ($m) {
            $user = Auth::user();
            $m->from(env('MAIL_USERNAME'), 'Lucky Fuds Service Catering System');
            $m->to($user->email)->subject('Lucky Fuds Service Catering System | Online Payment Reminders');
        });
    }
}
