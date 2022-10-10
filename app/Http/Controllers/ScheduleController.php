<?php

namespace App\Http\Controllers;

use App\Models\Reserve;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    protected function CreateEvent(Request $request) {

        $id = Auth::user()->id;
        Reserve::create([
            'user_id' => $id,
            'client' => Auth::user()->name,
            'contact' => request('contact'),
            'email' => request('email'),
            'method' => request('method'),
            'date' => request('date'),
            'time' => request('time'),
            'address' => request('address'),
            'guest' => request('guest'),
            'event' => request('event'),
            'package_id' => request('package_id'),
        ]);
        
        return back();

    }
    protected function ApproveReserve($id) {
        Reserve::where('id', $id)->update([
            'status' => 'approved'
        ]);
        return redirect()->back();
    }
    protected function RejectReserve($id) {
        Reserve::where('id', $id)->update([
            'status' => 'declined'
        ]);
        return redirect()->back();
    }
    protected function getEvent($id) {
        $event = Reserve::where('id', $id)->get()->first();
        $event->date = date('l', strtotime($event->date)) . ' ' . date('F', strtotime($event->date)) . ' ' . date('jS', strtotime($event->date));
        $today = date('Y-m-d', strtotime($event->date)) == date('Y-m-d') ? 'Today' : "Upcoming event";
        return response([
            'event' => $event,
            'today' =>$today
        ]);
    }
}
