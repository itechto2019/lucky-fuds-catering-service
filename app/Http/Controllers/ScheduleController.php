<?php

namespace App\Http\Controllers;

use App\Models\ForReserve;
use App\Models\OnlineReserve;
use App\Models\Reserve;
use App\Models\Reservation;
use App\Models\ReservationPayment;
use App\Models\UserReserve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ScheduleController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    protected function CreateEvent(Request $request) {

        $id = Auth::user()->id;
        if(!$request->package_id) {
            return back()->withErrors([
                "message" => "Please choose a package"
            ]);
        }else {
            $payment_method = $request->payment === "online" ? true : false;
            $reserve = UserReserve::create([
                'user_info_id' => Auth::user()->info->id,
                'package_id' => $request->package_id,
                'contact' => Auth::user()->info->contact ,
                'email' => Auth::user()->email ,
                'date' =>$request->date,
                'time' => $request->time,
                'address' => $request->address,
                'guest' => $request->guest,
                'event' => $request->event
            ]);
            $payment = ReservationPayment::create([
                'user_reserve' => $reserve->id,
                'payment_method' => $payment_method
            ]);
            if($payment){
                OnlineReserve::create([
                    'reservation_payment' => $payment->id,
                    'user_reserve' => $reserve->id,
                ]);
            }
            ForReserve::create([
                'user_info_id' => Auth::user()->info->id,
                'user_reserve_id' => $reserve->id,
                'status' => 'pending'
            ]);
            return back()->with([
                'message' => 'Reservation successful, please wait for admin approval.'
            ]);
        }
        

    }
    protected function ApproveReserve($id) {
        
        ForReserve::where('id', $id)->update([
            'status' => 'approved'
        ]);

        $reserve = ForReserve::with(['info'])->where('id', $id)->first();
        $user = $reserve->user_reserve;
        Mail::send('admin.reservation-report-email', ['client' => $user, 'status' => 'APPROVE'], function ($m) use($user) {
            $m->from(env('MAIL_USERNAME'), 'Lucky Fuds Service Catering System');
            $m->to($user->email)->subject('Lucky Fuds Service Catering System | Reservation Status');
        });
        return redirect()->back()->with([
            'message' => "Reservation approved"
        ]);
    }
    protected function RejectReserve($id) {
        ForReserve::where('id', $id)->update([
            'status' => 'declined'
        ]);
        $reserve = ForReserve::with(['info'])->where('id', $id)->first();
        $user = $reserve->user_reserve;
        Mail::send('admin.reservation-report-email', ['client' => $user, 'status' => 'DECLINED'], function ($m) use($user) {
            $m->from(env('MAIL_USERNAME'), 'Lucky Fuds Service Catering System');
            $m->to($user->email)->subject('Lucky Fuds Service Catering System | Reservation Status');
        });
        return redirect()->back()->with([
            'reject' => "Reservation declined"
        ]);
    }
    protected function getEvent($id) {
        $event = UserReserve::where('id', $id)->get()->first();
        $event->date = date('l', strtotime($event->date)) . ' ' . date('F', strtotime($event->date)) . ' ' . date('jS', strtotime($event->date));
        $today = date('Y-m-d', strtotime($event->date)) == date('Y-m-d') ? 'Today' : "Upcoming event";
        return response([
            'event' => $event,
            'today' =>$today
        ]);
    }
}
