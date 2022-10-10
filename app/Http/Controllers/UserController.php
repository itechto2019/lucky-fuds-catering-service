<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Package;
use App\Models\Stock;
use App\Models\Rent;
use App\Models\Returns;
use App\Models\Reserve;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('user');
    }
    public function index()
    {
        // $noOfDays = today()->daysInMonth;
        $noOfMonths = Carbon::now()->months()->format('m');
        $noOfDays = Carbon::now()->days()->format('j');
        $noOfWeeks = 7;
        $months = [];
        $days = [];
        // $weeks = [];
        for ($i = 1; $i <= $noOfMonths; $i++) {
            $months[] = Carbon::now()->months($i)->format('M');
        }
        for ($i = 1; $i <= $noOfDays; $i++) {
            $days[] = Carbon::now()->days($i)->format('j');
        }
        $id = Auth::id();

        $approved = count(Reserve::where('user_id', $id)->where('status', 'approved')->get());
        $declined = count(Reserve::where('user_id', $id)->where('status', 'declined')->get());
        $pending = count(Reserve::where('user_id', $id)->where('status', 'pending')->get());
        $request = count(Reserve::where('user_id', $id)->get());
        $reserves = Reserve::where('user_id', $id)->where('status', 'approved')->get();
        $user = Auth::user();
        return view('user.dashboard')->with(compact([
            'noOfDays',
            'months',
            'days',
            'noOfWeeks',
            'approved',
            'declined',
            'pending',
            'request',
            'reserves',
            'user'
        ]));
    }
    public function ScheduleEvents()
    {
        $date = empty($date) ? Carbon::today() : Carbon::createFromDate();
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::createFromDate(today()->month, $i)->format('M');
        }
        $formatWeek = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun",];
        $startOfCalendar = $date->copy()->firstOfMonth()->startOfWeek(Carbon::SUNDAY);
        $endOfCalendar = $date->copy()->lastOfMonth()->endOfWeek(Carbon::SATURDAY);

        $id = Auth::id();
        $previousEvents = Reserve::where('user_id', $id)->whereDate('date', '<', today()->format('Y-m-d'))->where('status', 'approved')->get();
        $upcomingEvents = Reserve::where('user_id', $id)->whereDate('date', '>=', today()->format('Y-m-d'))->where('status', 'approved')->get();
        $events = Reserve::where('user_id', $id)->where('status', 'approved')->get();
        return view('user.schedule_events')->with(compact([
            'date',
            'months',
            'formatWeek',
            'startOfCalendar',
            'endOfCalendar',
            'events',
            'previousEvents',
            'upcomingEvents'
        ]));
    }
    public function ScheduleReservation()
    {
        $packages = Package::get();
        return view('user.schedule_reservation')->with(compact(['packages']));
    }

    public function ForRents()
    {
        $supplies = Stock::with(['for_rents' => function ($q) {
            return $q->where('is_rented', true);
        }])->get();
        return view('user.inventory.for_rents')->with(compact(['supplies']));
    }
    public function Rented()
    {
        $id = Auth::user()->id;
        $rents = Rent::where('user_id', $id)->with('extends')->get();
        return view('user.inventory.rents')->with(compact(['rents']));
    }
    public function Summary()
    {
        $id = Auth::id();
        $rents = Rent::where('user_id', $id)->get();
        $returns = Returns::where('user_id', $id)->get();
        $reserves = Reserve::where('user_id', $id)->get();
        return view('user.inventory.summary')->with(compact(['rents', 'returns', 'reserves']));
    }
}
