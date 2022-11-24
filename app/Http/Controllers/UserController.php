<?php

namespace App\Http\Controllers;

use App\Models\ForRent;
use Carbon\Carbon;
use App\Models\Package;
use App\Models\UserInfo;
use App\Models\UserRent;
use App\Models\UserReserve;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Firebase;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('user');
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
        }])->where('user_info_id', $id)->whereMonth('date', $date->format('m'))->get();

        
        $user = Auth::user();

        $reservationCount = count(UserReserve::where('user_info_id', $id)->get());
        $rentCount = count(UserRent::whereHas('rent_approve')->where('user_info_id', $id)->get());
        $rentConfirmCount = count(UserReserve::whereHas('reserve', function ($q) {
            $q->where('status', 'pending');
        })->where('user_info_id', $id)->get());
        $rentRequestCount = count(UserRent::where('user_info_id', $id)->get());
        $extendRequestCount = count(UserRent::whereHas('extends')->where('user_info_id', $id)->where('status', 'pending')->get());

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
            'user',
            'reservationCount',
            'rentCount',
            'rentConfirmCount',
            'rentRequestCount',
            'extendRequestCount',
            'selectedMonth',
            'date'
        ]));
    }
    public function ConfirmationRequest() {
        $id = Auth::user()->info ? Auth::user()->info->id : null;
        $reservations = UserReserve::with(['info','package', 'reserve'])->where('user_info_id', $id)->get();
        return view('user.schedule_confirmation')->with(compact([
            'reservations',
        ]));
    }
    public function ScheduleEvents(Request $request)
    {
        $selectMonth = $request->has('month_of') ? $request->input('month_of') : today()->month;
        $date = empty($selectMonth) ? Carbon::today() : Carbon::createFromDate($selectMonth);
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


        $events = UserReserve::with('reserve')->whereHas('reserve', function ($q){
            $q->where('status', 'approved');
        })->where('user_info_id', $id)->get();
        

        return view('user.schedule_events')->with(compact([
            'date',
            'months',
            'selectMonth',
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
        $supplies = ForRent::whereNot('quantity' , 0)->get();
        return view('user.inventory.for_rents')->with(compact(['supplies']));
    }
    public function Rented()
    {
        $id = Auth::user()->info ? Auth::user()->info->id : null;

        $rents = UserRent::where('user_info_id', $id)->get();
        return view('user.inventory.rents')->with(compact(['rents']));
    }
    public function Extends() {
        $id = Auth::user()->info ? Auth::user()->info->id : null;

        $rents = UserRent::whereHas('extend_approve')->orWhereHas('extend_decline')->where('user_info_id', $id)->get();

        return view('user.inventory.extends')->with(compact(['rents']));
    }
    public function Summary(Request $request)
    {
        $id = Auth::user()->info ? Auth::user()->info->id : null;
        $rents = UserRent::where('user_info_id', $id)->get();
        $returns = UserRent::whereHas('return')->where('user_info_id', $id)->get();
        
        return view('user.inventory.summary')->with(compact(['rents', 'returns']));
    }
    public function ReservationSummary() {
        $id = Auth::user()->info ? Auth::user()->info->id : null;
        $reserves = UserReserve::with(['info'])->where('user_info_id', $id)->get();

        return view('user.schedule_summary')->with(compact(['reserves']));
    }
    public function AccountProfile() {

        return view('user.account.Profile');
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
                $form['profile']->move(public_path('profile'), $filename);
                
                $file = fopen(public_path('profile/'). $filename, 'r');
                

                $storage = Firebase::storage();
                $storage->getBucket()->upload($file, ['name' => 'profile/'  . $filename ]);
                
                $imageReference = app('firebase.storage')->getBucket()->object("profile/" . $filename);
                $image = $imageReference->signedUrl(Carbon::now()->addCenturies(1));

                unlink(public_path('profile/'). $filename);

                if(Auth::user()->info) {
                    UserInfo::where('user_id', Auth::id())->update([
                        'user_id' => Auth::id(),
                        'profile' => $image,
                        'name' => $form['name'],
                        'contact' => $form['contact'],
                        'email' => $form['email'],
                        'address' => $form['address'],
                        'method' => $form['method'],
                    ]);
                }else {
                    UserInfo::create([
                        'user_id' => Auth::id(),
                        'profile' => $image,
                        'name' => $form['name'],
                        'contact' => $form['contact'],
                        'email' => $form['email'],
                        'address' => $form['address'],
                        'method' => $form['method'],
                    ]);
                }
                return back()->withErrors([
                    'message' => 'Profile updated'
                ]);
            }else {
                if(Auth::user()->info) {
                    UserInfo::where('user_id', Auth::id())->update([
                        'user_id' => Auth::id(),
                        'name' => $form['name'],
                        'contact' => $form['contact'],
                        'email' => $form['email'],
                        'address' => $form['address'],
                        'method' => $form['method'],
                    ]);
                }else {
                    UserInfo::create([
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
}
