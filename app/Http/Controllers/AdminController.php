<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Stock;
use App\Models\ForRent;
use App\Models\ForReserve;
use App\Models\Package;
use App\Models\User;
use App\Models\UserRent;
use App\Models\UserReserve;
use App\Models\Validate;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }
    public function index(Request $request)
    {
        $selectedMonth = $request->has('month') ? $request->input('month') : Carbon::today();
        $date = empty($selectedMonth) ? Carbon::today() : Carbon::createFromDate($selectedMonth);
        
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
        $confirmedRent = count(UserRent::whereHas('rent_approve')->get());
        $pendingRent = count(UserRent::where('status', 'pending')->get());
        $declinedRent = count(UserRent::whereHas('rent_decline')->get());
        $totalRequest = count(UserRent::get());

        // extend requests
        $confirmedExtend = count(UserRent::whereHas('extend_approve')->get());
        $pendingExtend = count(UserRent::where('status', 'extending')->get());
        $declinedExtend = count(UserRent::whereHas('extend_decline')->get());
        $totalRequestExtend = count(UserRent::whereHas('extend_approve')->orWhereHas('extend_decline')->get());

        $reserves = UserReserve::with(['reserve' => function ($q) {
            $q->where('status', 'approved');
        }])->whereMonth('date', $date->format('m'))->get();

        $products = Stock::paginate(20);

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
            'totalRequestExtend',
            'products',
            'selectedMonth'
        ]));
    }
    public function ScheduleEvents(Request $request)
    {
        $selectMonth = $request->has('month_of') ? $request->input('month_of') : Carbon::now()->format('M');
        $date = empty($selectMonth) ? today()->month : Carbon::createFromDate($selectMonth);
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::createFromDate(today()->month, $i)->format('M');
        }
        $formatWeek = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"];
        $startOfCalendar = $date->copy()->firstOfMonth()->startOfWeek(Carbon::SUNDAY)->startOfDay();
        $endOfCalendar = $date->copy()->lastOfMonth()->endOfWeek(Carbon::SATURDAY)->endOfDay();

        $previousEvents = UserReserve::whereHas('reserve', function ($q) {
            $q->where('status', 'approved');
        })->whereDate('date', '<', today()->format('Y-m-d'))->get();


        $upcomingEvents = UserReserve::whereHas('reserve', function ($q) {
            $q->where('status', 'approved');
        })->whereDate('date', '>=', today()->format('Y-m-d'))->get();

        $events = UserReserve::with('reserve')->whereHas('reserve', function ($q){
            $q->where('status', 'approved');
        })->get();

        $filterEvent = UserReserve::with('reserve')->whereHas('reserve', function ($q){
            $q->where('status', 'approved');
        })->whereDate('date', $request->input('filter'))->get();
        
        $events = UserReserve::with('reserve')->whereHas('reserve', function ($q){
            $q->where('status', 'approved');
        })->get();
        

        return view('admin.schedule_events')->with(compact([
            'date',
            'months',
            'selectMonth',
            'formatWeek',
            'startOfCalendar',
            'endOfCalendar',
            'events',
            'previousEvents',
            'upcomingEvents',
            'filterEvent'
        ]));
    }

    public function ScheduleReservation()
    {
        $reservations = UserReserve::get();
        $approves = ForReserve::with(['user_reserve'])->where('status', 'approved')->get();
        $declines = ForReserve::with(['user_reserve'])->where('status', 'declined')->get();
        
        return view('admin.schedule_reservation')->with(compact([
            'reservations',
            'approves',
            'declines'
        ]));
    }
    public function ScheduleReports()
    {
        $reports = UserReserve::with(['package', 'reserve'])->whereHas('reserve', function ($q) {
            $q->where('status', 'approved');
        })->get();
        return view('admin.schedule_reports')->with(compact([
            'reports'
        ]));
    }
    // stocks
    protected function InventoryStocks(Request $request)
    {
        $search = $request->has('search') ? $request->input('search') : null;
        $packages = Package::get();
        $supplies = Stock::where('item', 'LIKE', '%' . $search . '%')->get();
        return view('admin.inventory.stocks')->with(compact(['packages', 'supplies']));
    }
    // for rents
    public function ForRents(Request $request)
    {
        $search = $request->has('search') ? $request->input('search') : null;
        $supplies = ForRent::whereNot('quantity' , 0)->whereHas('stock', function ($q) use($search) {
            $q->where('item', 'LIKE', '%' . $search . '%');
        })->get();
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
        $rents = UserRent::whereNot('status', 'extending')->whereHas('rent_approve')->get();
        return view('admin.inventory.approves')->with(compact(['rents']));
    }
    // extended
    public function Extends()
    {
        $rents = UserRent::whereNot('status', 'pending')->whereHas('extend_approve')->get();
        return view('admin.inventory.extends')->with(compact(['rents']));
    }
    // retured
    public function Returned()
    {
        $rents = UserRent::whereHas('return')->get();
        return view('admin.inventory.return')->with(compact(['rents']));
    }
    
    public function Reports()
    {
        $rents = UserRent::get();
        return view('admin.inventory.reports')->with(compact(['rents']));
    }
    public function User() {
        $accounts = User::with(['info'])->whereHas('validate', function($q) {
            $q->where('status', false);
        })->get();
        return view('admin.account.request')->with(compact([
            'accounts'
        ]));
    }
    public function verified() {
        $accounts = User::with(['info', 'validate'])->whereHas('validate', function ($q) {
            $q->where('status', true);
        })->get();
        return view('admin.account.verified')->with(compact([
            'accounts'
        ]));
    }
    public function confirmVerification($id){
        Validate::where('id', $id)->update([
            'status' => true
        ]);
        return back()->with([
            'message' => 'Verification confirmed'
        ]);
    }
    public function rejectVerification($id){
        Validate::where('id', $id)->delete();
        return back()->with([
            'reject' => 'Verification failed'
        ]);
    }
}
