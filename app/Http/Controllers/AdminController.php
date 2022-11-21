<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Package;
use App\Models\Stock;
use App\Models\Rent;
use App\Models\Returns;
use App\Models\Extend;
use App\Models\ForRent;
use App\Models\Report;
use App\Models\Reserve;
use App\Models\Reservation;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\UserRent;

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
        // for($i = 1; $i <= $noOfWeeks; $i++) {
        //     $weeks[] = Carbon::now()->days($i)->format('D');
        // }
        $approved = count(Reserve::where('status', 'approved')->get());
        $declined = count(Reserve::where('status', 'declined')->get());
        $pending = count(Reserve::where('status', 'pending')->get());
        $request = count(Reserve::get());


        $confirmedRent = count(Rent::where('status', 'approved')->get());
        $pendingRent = count(Rent::where('status', 'pending')->get());
        $declinedRent = count(Rent::where('status', 'declined')->get());
        $totalRequest = count(Rent::where('status', 'approved')->orWhere('status', 'pending')->orWhere('status', 'declined')->get());

        $confirmedExtend = count(Rent::where('status', 'extended')->get());
        $pendingExtend = count(Rent::where('status', 'extending')->get());
        $declinedExtend = count(Rent::where('status', 'declined')->whereHas('extends')->get());
        $totalRequestExtend = count(Rent::where('status', 'extended')->orWhere('status', 'extending')->orWhere('status', 'declined')->whereHas('extends')->get());

        $reserves = Reserve::where('status', 'approved')->get();

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
        $previousEvents = Reserve::whereDate('date', '<', today()->format('Y-m-d'))->where('status', 'approved')->get();
        $upcomingEvents = Reserve::whereDate('date', '>=', today()->format('Y-m-d'))->where('status', 'approved')->get();
        $events = Reserve::where('status', 'approved')->get();
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
        $reservations = Reserve::get();
        $approves = Reserve::where('status', 'approved')->get();
        $declines = Reserve::where('status', 'declined')->get();
        return view('admin.schedule_reservation')->with(compact([
            'reservations',
            'approves',
            'declines'
        ]));
    }
    public function ScheduleReports()
    {
        $reports = Reserve::with('package')->get();
        return view('admin.schedule_reports')->with(compact([
            'reports'
        ]));
    }

    // stocks
    public function InventoryStocks()
    {
        // $packages = Package::get();
        $supplies = Stock::get();
        // return view('admin.inventory.stocks')->with(compact(['packages', 'supplies']));
        return view('admin.inventory.stocks')->with(compact([ 'supplies']));
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
        // $rents = UserRent::where('status', 'pending')->get();
        // $rents = UserRent::with('info')->where('status', 'pending')->get();
        $rents = UserRent::with('user_info')->where('status', 'pending')->get();
        dd($rents);
        return view('admin.inventory.rents')->with(compact(['rents']));
    }
    // for extend request
    public function ExtendRequest()
    {
        $rents = Rent::where('status', 'extending')->with('extends')->get();
        return view('admin.inventory.extend_request')->with(compact(['rents']));
    }
    // approval
    public function Approves()
    {
        $rents = Rent::where('status', 'approved')->orWhere('status', 'extend')->get();
        return view('admin.inventory.approves')->with(compact(['rents']));
    }
    // extended
    public function Extends()
    {
        $rents = Rent::where('status', 'extended')->with(['extends'])->get();
        // $rents = Rent::where('status', 'extend')->with('extends')->get();
        return view('admin.inventory.extends')->with(compact(['rents']));
    }
    // retured
    public function Returned()
    {
        $rents = Rent::where('status', 'returned')->orWhere('status', 'extended')->orWhere('is_returned', true)->with('returns')->get();
        return view('admin.inventory.return')->with(compact(['rents']));
    }
    
    public function Reports()
    {
        $reports = Report::get();
        return view('admin.inventory.reports')->with(compact(['reports']));
    }
}
