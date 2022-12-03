<?php

namespace App\Http\Controllers;

use App\Models\ForRent;
use Carbon\Carbon;
use App\Models\Package;
use App\Models\Stock;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\UserRent;
use App\Models\UserReserve;
use App\Models\Validate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Firebase;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['user', 'verified']);
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

        $approved = count(UserReserve::with(['reserve' => function ($q) {
            $q->where('status', 'approved');
        }])->where('user_info_id', $id)->get());

        $declined = count(UserReserve::with(['reserve' => function ($q) {
            $q->where('status', 'declined');
        }])->where('user_info_id', $id)->get());

        $pending = count(UserReserve::with(['reserve' => function ($q) {
            $q->where('status', 'pending');
        }])->where('user_info_id', $id)->get());

        $request = count(UserReserve::with(['reserve' => function ($q) {
            $q->where('status', 'approved');
        }])->where('user_info_id', $id)->get());



        $reserves = UserReserve::with('reserve')->whereHas('reserve', function ($q) {
            $q->where('status', 'approved');
        })->where('user_info_id', $id)->whereMonth('date', $date->format('m'))->get();


        $user = Auth::user();

        $reservationCount = count(UserReserve::where('user_info_id', $id)->get());
        $rentCount = count(UserRent::whereHas('rent_approve')->where('user_info_id', $id)->get());
        $rentConfirmCount = count(UserReserve::whereHas('reserve', function ($q) {
            $q->where('status', 'pending');
        })->where('user_info_id', $id)->get());
        $rentRequestCount = count(UserRent::where('user_info_id', $id)->get());
        $extendRequestCount = count(UserRent::whereHas('extends')->where('user_info_id', $id)->where('status', 'pending')->get());


        $products = Stock::paginate(20);

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
            'date',
            'products'
        ]));
    }
    public function ConfirmationRequest()
    {
        $id = Auth::user()->info ? Auth::user()->info->id : null;
        $reservations = UserReserve::with(['info', 'package', 'reserve'])->where('user_info_id', $id)->get();
        return view('user.schedule_confirmation')->with(compact([
            'reservations',
        ]));
    }
    public function ScheduleEvents(Request $request)
    {
        $selectMonth = $request->has('month_of') ? $request->input('month_of') : Carbon::now()->format('M');
        $date = empty($selectMonth) ? Carbon::today() : Carbon::createFromDate($selectMonth);
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $months[] = Carbon::createFromDate(today()->month, $i)->format('M');
        }
        $formatWeek = ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun",];
        $startOfCalendar = $date->copy()->firstOfMonth()->startOfWeek(Carbon::SUNDAY);
        $endOfCalendar = $date->copy()->lastOfMonth()->endOfWeek(Carbon::SATURDAY);

        $id = Auth::user()->info ? Auth::user()->info->id : null;

        $previousEvents = UserReserve::whereHas('reserve', function ($q) {
            $q->where('status', 'approved');
        })->where('user_info_id', $id)->whereDate('date', '<', today()->format('Y-m-d'))->get();

        $upcomingEvents = UserReserve::whereHas('reserve', function ($q) {
            $q->where('status', 'approved');
        })->where('user_info_id', $id)->whereDate('date', '>=', today()->format('Y-m-d'))->get();

        $filterEvent = UserReserve::with('reserve')->whereHas('reserve', function ($q){
            $q->where('status', 'approved');
        })->where('user_info_id', $id)->whereDate('date', $request->input('filter'))->get();
        $events = UserReserve::with('reserve')->whereHas('reserve', function ($q) {
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
            'upcomingEvents',
            'filterEvent'
        ]));
    }
    public function ScheduleReservation()
    {
        $packages = Package::get();
        return view('user.schedule_reservation')->with(compact(['packages']));
    }
    public function ForRents(Request $request)
    {
        $search = $request->has('search') ? $request->input('search') : null;
        $supplies = ForRent::whereNot('quantity', 0)->whereHas('stock', function ($q) use($search) {
            $q->where('item', 'LIKE', '%' . $search . '%');
        })->get();
        return view('user.inventory.for_rents')->with(compact(['supplies']));
    }
    public function Rented()
    {
        $id = Auth::user()->info ? Auth::user()->info->id : null;

        $rents = UserRent::where('user_info_id', $id)->get();
        return view('user.inventory.rents')->with(compact(['rents']));
    }
    public function Extends()
    {
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
    public function ReservationSummary()
    {
        $id = Auth::user()->info ? Auth::user()->info->id : null;
        $reserves = UserReserve::with(['info'])->where('user_info_id', $id)->get();

        return view('user.schedule_summary')->with(compact(['reserves']));
    }
    public function AccountProfile()
    {

        return view('user.account.Profile');
    }

    public function UpdateProfile(Request $request)
    {
        $validator = Validator::make($request->only(
            'user_id',
            'profile',
            'name',
            'contact',
            'address',
            'method',
            'birthday'
        ), [
            'profile' => 'mimes:png,jpg,jpeg|nullable|max:5000',
            'name' => 'nullable',
            'contact' => 'nullable',
            'address' => 'nullable',
            'method' => 'nullable',
            'birthday' => 'nullable:date'
        ]);
        if ($validator->fails()) {
            return back();
        } else {
            $form = $validator->validated();
            if ($request->hasFile('profile')) {
                $filename = time() . '_profile.' . $form['profile']->extension();
                $form['profile']->move(public_path('profile'), $filename);

                $file = fopen(public_path('profile/') . $filename, 'r');


                $storage = Firebase::storage();
                $storage->getBucket()->upload($file, ['name' => 'profile/'  . $filename]);

                $imageReference = app('firebase.storage')->getBucket()->object("profile/" . $filename);
                $image = $imageReference->signedUrl(Carbon::now()->addCenturies(1));

                unlink(public_path('profile/') . $filename);

                if (Auth::user()->info) {
                    app('firebase.storage')->getBucket()->object("profile/" . Auth::user()->info->temp_name)->delete();
                    UserInfo::where('user_id', Auth::id())->update([
                        'user_id' => Auth::id(),
                        'profile' => $image,
                        'temp_name' => $filename,
                        'name' => Auth::user()->info->name,
                        'contact' => Auth::user()->info->contact,
                        'address' => $form['address'],
                        'method' => $form['method'],
                        'birthday' => $form['birthday'],
                    ]);
                } else {
                    UserInfo::create([
                        'user_id' => Auth::id(),
                        'profile' => $image,
                        'temp_name' => $filename,
                        'name' => $form['name'],
                        'contact' => $form['contact'],
                        'address' => $form['address'],
                        'method' => $form['method'],
                        'birthday' => $form['birthday'],
                    ]);
                }
                return back()->with([
                    'message' => 'Profile updated'
                ]);
            } else {
                if (Auth::user()->info) {
                    UserInfo::where('user_id', Auth::id())->update([
                        'user_id' => Auth::id(),
                        'name' => Auth::user()->info->name,
                        'contact' => Auth::user()->info->contact,
                        'address' => $form['address'],
                        'method' => $form['method'],
                        'birthday' => $form['birthday'],

                    ]);
                } else {
                    UserInfo::create([
                        'user_id' => Auth::id(),
                        'name' => $form['name'],
                        'contact' => $form['contact'],
                        'address' => $form['address'],
                        'method' => $form['method'],
                        'birthday' => $form['birthday'],
                    ]);
                }
            }
        }
    }

    public function validateId(Request $request)
    {
        $validator = Validator::make($request->only(
            'image'
        ), [
            'image' => 'mimes:png,jpg,jpeg|nullable|max:5000',
        ]);

        $form = $validator->validated();
        if ($validator->fails()) {
            return back()->withErrors([
                'message' => 'The maximum file upload is 5 MegaBytes (5MB)'
            ]);
        } else {
            $filename = time() . '_indentity.' . $form['image']->extension();
            $form['image']->move(public_path('validate'), $filename);

            $file = fopen(public_path('validate/') . $filename, 'r');


            $storage = Firebase::storage();
            $storage->getBucket()->upload($file, ['name' => 'validate/'  . $filename]);

            $imageReference = app('firebase.storage')->getBucket()->object("validate/" . $filename);
            $image = $imageReference->signedUrl(Carbon::now()->addCenturies(1));

            unlink(public_path('validate/') . $filename);

            if (!Auth::user()->validate) {
                Validate::create([
                    'user_id' => Auth::id(),
                    'image' => $image,
                    'temp_name' => $filename,
                    'status' => false
                ]);
            } else {
                app('firebase.storage')->getBucket()->object("validate/" . Auth::user()->validate->temp_name)->delete();    
                Validate::where('user_id', Auth::id())->update([
                    'image' => $image,
                    'temp_name' => $filename,
                    'status' => false
                ]);
            }
            
            return back()->with([
                'message' => 'Your ID has been uploaded, wait for admin approval.'
            ]);
        }
    }

}
