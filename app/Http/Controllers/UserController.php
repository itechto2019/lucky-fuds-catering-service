<?php

namespace App\Http\Controllers;

use App\Models\ForRent;
use Carbon\Carbon;
use App\Models\Package;
use App\Models\Stock;
use App\Models\Rent;
use App\Models\Returns;
use App\Models\Reserve;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\UserRent;
use App\Models\UserReserve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('user');
    }

    public function index()
    {
        $noOfMonths = Carbon::now()->months()->format('m');
        $noOfDays = Carbon::now()->days()->format('j');
        $noOfWeeks = 7;
        $months = [];
        $days = [];
        for ($i = 1; $i <= $noOfMonths; $i++) {
            $months[] = Carbon::now()->months($i)->format('M');
        }
        for ($i = 1; $i <= $noOfDays; $i++) {
            $days[] = Carbon::now()->days($i)->format('j');
        }
        $id = Auth::user()->info ? Auth::user()->info->id : null;

        $approved = count(UserReserve::with(['reserve' => function($q) {
            $q->where('status', 'approved');
        }])->where('user_info_id', $id)->get());

        $declined = count(UserReserve::with(['reserve' => function($q) {
            $q->where('status', 'declined');
        }])->where('user_info_id', $id)->get());

        $pending = count(UserReserve::with(['reserve' => function($q) {
            $q->where('status', 'pending');
        }])->where('user_info_id', $id)->get());

        $request = count(UserReserve::with(['reserve' => function($q) {
            $q->where('status', 'approved');
        }])->where('user_info_id', $id)->get());

        $reserves = UserReserve::with(['reserve' => function($q) {
            $q->where('status', 'approved');
        }])->where('user_info_id', $id)->get();


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
    public function ConfirmationRequest() {
        $id = Auth::user()->info ? Auth::user()->info->id : null;
        $reservations = UserReserve::with('package')->where('user_info_id', $id)->get();
        return view('user.schedule_confirmation')->with(compact([
            'reservations',
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

        $id = Auth::user()->info ? Auth::user()->info->id : null;

        $previousEvents = UserReserve::with(['reserve' => function ($q) {
            $q->where('status', 'approved');
        }])->where('user_info_id', $id)->whereDate('date', '<', today()->format('Y-m-d'))->get();

        $upcomingEvents = UserReserve::with(['reserve' => function ($q) {
            $q->where('status', 'approved');
        }])->where('user_info_id', $id)->whereDate('date', '>=', today()->format('Y-m-d'))->get();

        $events = UserReserve::with(['reserve' => function ($q) {
            $q->where('status', 'approved');
        }])->where('user_info_id', $id)->get();
        

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
        $supplies = ForRent::with('stock')->get();
        return view('user.inventory.for_rents')->with(compact(['supplies']));
    }
    public function Rented()
    {
        $id = Auth::user()->info ? Auth::user()->info->id : null;

        $rents = UserRent::with(['info', 'stock'])->where('user_info_id', $id)->get();

        return view('user.inventory.rents')->with(compact(['rents']));
    }
    public function Extends() {
        $id = Auth::user()->info ? Auth::user()->info->id : null;

        $rents = UserRent::with(['info','stock', 'extends'])->where('user_info_id', $id)->where('status', 'extended')->get();

        return view('user.inventory.extends')->with(compact(['rents']));
    }
    public function Summary(Request $request)
    {
        $id = Auth::user()->info ? Auth::user()->info->id : null;
        $rents = UserRent::with(['info','stock', 'extends'])->where('user_info_id', $id)->get();
        $returns = UserRent::with(['info'])->whereHas('return')->where('user_info_id', $id)->get();

        return view('user.inventory.summary')->with(compact(['rents', 'returns']));
    }
    public function ReservationSummary() {
        $id = Auth::user()->info ? Auth::user()->info->id : null;
        $reserves = UserReserve::with(['info' => function ($q) use($id){
            $q->where('id',$id);            
        }])->where('user_info_id', $id)->get();
        return view('user.schedule_summary')->with(compact(['reserves']));
    }
    public function AccountProfile() {
        return view('user.account.profile');
    }

    public function UpdateProfile(Request $request) {
        $validator = Validator::make($request->only(
            'user_id',
            'profile',
            'name',
            'contact',
            'email',
            'address',
            'method'
        ),[
            'profile' => 'mimes:png,jpg,jpeg|nullable',
            'name' => 'nullable',
            'contact' => 'nullable',
            'email' => 'email|nullable',
            'address' => 'nullable',
            'method' => 'nullable',
        ]);
        if($validator->fails()) {
            return back()->withErrors([
                'message' => 'Please check your fields'
            ]);
        }else {
            $form = $validator->validated();
            if($request->hasFile('profile')) {
                $filename = time() . '_profile.' . $form['profile']->extension();
                $form['profile']->move(public_path("asset/profile"), $filename);
                UserInfo::updateOrCreate([
                    'user_id' => Auth::id(),
                    'profile' => $filename,
                    'name' => $form['name'],
                    'contact' => $form['contact'],
                    'email' => $form['email'],
                    'address' => $form['address'],
                    'method' => $form['method'],
                ]);
                return back()->withErrors([
                    'message' => 'Profile updated'
                ]);
            }else {
                UserInfo::updateOrCreate([
                    'user_id' => Auth::id(),
                    'name' => $form['name'],
                    'contact' => $form['contact'],
                    'email' => $form['email'],
                    'address' => $form['address'],
                    'method' => $form['method'],
                ]);
            }
            
        }
    }
}
