<?php

namespace App\Http\Controllers;

use App\Models\ForReserve;
use App\Models\Reserve;
use App\Models\Reservation;
use App\Models\UserReserve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $reserve = UserReserve::create([
                'user_info_id' => Auth::user()->info->id,
                'package_id' => $request->package_id,
                'contact' => Auth::user()->info->contact ,
                'email' => Auth::user()->info->email ,
                'date' =>$request->date,
                'time' => $request->time,
                'address' => $request->address,
                'guest' => $request->guest,
                'event' => $request->event
            ]);
            ForReserve::create([
                'user_info_id' => Auth::user()->info->id,
                'user_reserve_id' => $reserve->id,
                'status' => 'pending'
            ]);
            return back();
        }
        

    }
    protected function ApproveReserve($id) {
        
        ForReserve::where('id', $id)->update([
            'status' => 'approved'
        ]);
        return redirect()->back()->withErrors([
            'message' => "Reservation approved"
        ]);
    }
    protected function RejectReserve($id) {
        ForReserve::where('id', $id)->update([
            'status' => 'declined'
        ]);
        return redirect()->back()->withErrors([
            'message' => "Reservation declined"
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
