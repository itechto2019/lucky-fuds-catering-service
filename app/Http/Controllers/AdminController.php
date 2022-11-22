<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Stock;
use App\Models\ForRent;
use App\Models\ForReserve;
use App\Models\Package;
use App\Models\Reserve;
use App\Models\UserRent;
use App\Models\UserReserve;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
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

        // reservation requests
        $approved = count(ForReserve::where('status', 'approved')->get());
        $declined = count(ForReserve::where('status', 'declined')->get());
        $pending = count(ForReserve::where('status', 'pending')->get());
        $request = count(ForReserve::get());

        // rent requests
        $confirmedRent = count(UserRent::where('status', 'approved')->get());
        $pendingRent = count(UserRent::where('status', 'pending')->get());
        $declinedRent = count(UserRent::where('status', 'declined')->get());
        $totalRequest = count(UserRent::get());

        // extend requests
        $confirmedExtend = count(UserRent::whereHas('extends')->where('status', 'extended')->get());
        $pendingExtend = count(UserRent::whereHas('extends')->where('status', 'extending')->get());
        $declinedExtend = count(UserRent::whereHas('extends')->where('status', 'declined')->whereHas('extends')->get());
        $totalRequestExtend = count(UserRent::whereHas('extends')->get());

        $reserves = UserReserve::with(['reserve' => function ($q) {
            $q->where('status', 'approved');
        }])->get();

        return view('admin.dashboard')->with(compact([
            'noOfDays',
            'months',
            'days',
            'noOfWeeks',
            'approved',
            'declined',
            'pending',
            'request',
            'reserves',
            'confirmedRent',
            'pendingRent',
            'declinedRent',
            'totalRequest',
            'confirmedExtend',
            'pendingExtend',
            'declinedExtend',
            'totalRequestExtend'
        ]));
    }
    public function ScheduleEvents()
    {
        $date = empty($date) ? Carbon::now() : Carbon::createFromDate();
        $months = [];
        for ($i = 1; $i < 12; $i++) {
            $months[] = Carbon::createFromDate(today()->month, $i)->format('M');
        }
        $formatWeek = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
        $startOfCalendar = $date->copy()->firstOfMonth()->startOfWeek(Carbon::SUNDAY);
        $endOfCalendar = $date->copy()->lastOfMonth()->endOfWeek(Carbon::SATURDAY);

        
        $previousEvents = UserReserve::with(['reserve' => function($q) {
            $q->where('status', 'approved');
        }])->whereDate('date', '<', today()->format('Y-m-d'))->get();


        $upcomingEvents = UserReserve::with(['reserve' => function($q) {
            $q->where('status', 'approved');
        }])->whereDate('date', '>=', today()->format('Y-m-d'))->get();


        $events = UserReserve::with(['reserve' => function($q) {
            $q->where('status', 'approved');
        }])->get();


        return view('admin.schedule_events')->with(compact([
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
        $reservations = UserReserve::get();
        // $approves = UserReserve::with(['reserve' => function ($q) {
        //     $q->where('status', 'approved');
        // }])->get();

        $approves = ForReserve::with(['user_reserve'])->where('status', 'approved')->get();

        // $declines = UserReserve::with(['reserve' => function ($q) {
        //     $q->where('status', 'declined');
        // }])->get();
        $declines = ForReserve::with(['user_reserve'])->where('status', 'declined')->get();
        


        return view('admin.schedule_reservation')->with(compact([
            'reservations',
            'approves',
            'declines'
        ]));
    }
    public function ScheduleReports()
    {
        $reports = UserReserve::with('package')->get();
        return view('admin.schedule_reports')->with(compact([
            'reports'
        ]));
    }

    // stocks
    public function InventoryStocks()
    {
        $packages = Package::get();
        $supplies = Stock::get();
        return view('admin.inventory.stocks')->with(compact(['packages', 'supplies']));
    }
    // for rents
    public function ForRents()
    {
        $supplies = ForRent::with('stock')->get();
        return view('admin.inventory.for_rents')->with(compact(['supplies']));
    }
    // for rented
    public function ForRented()
    {
        $rents = UserRent::with('info')->with('stock')->where('status', 'pending')->get();
        return view('admin.inventory.rents')->with(compact(['rents']));
    }
    // for extend request
    public function ExtendRequest()
    {
        $rents = UserRent::with(['info', 'stock', 'extends'])->where('status', 'extending')->get();
        return view('admin.inventory.extend_request')->with(compact(['rents']));
    }
    // approval
    public function Approves()
    {
        $rents = UserRent::with(['info', 'stock'])->where('status', 'approved')->whereHas('extends')->orWhereHas('return')->get();
        return view('admin.inventory.approves')->with(compact(['rents']));
    }
    // extended
    public function Extends()
    {
        $rents = UserRent::with(['info', 'stock'])->where('status', 'extend')->orWhere('status', 'returned')->get();
        return view('admin.inventory.extends')->with(compact(['rents']));
    }
    // retured
    public function Returned()
    {
        // $rents = Rent::where('status', 'returned')->orWhere('status', 'extended')->orWhere('is_returned', true)->with('returns')->get();
        $rents = UserRent::with(['info', 'extends'])->where('status', 'returned')->get();
        return view('admin.inventory.return')->with(compact(['rents']));
    }
    
    public function Reports()
    {
        $rents = UserRent::get();
        return view('admin.inventory.reports')->with(compact(['rents']));
    }
}
