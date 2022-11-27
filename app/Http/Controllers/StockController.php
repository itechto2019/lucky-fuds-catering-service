<?php

namespace App\Http\Controllers;

use App\Models\Approve;
use App\Models\Decline;
use App\Models\Deliver;
use App\Models\Extend;
use App\Models\ExtendApprove;
use App\Models\ExtendDecline;
use App\Models\ForRent;
use App\Models\Pickup;
use App\Models\RentApprove;
use App\Models\RentDecline;
use App\Models\Returns;
use App\Models\Stock;
use App\Models\UserRent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Firebase;
use Illuminate\Support\Facades\Mail;

class StockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    protected function createSupply(Request $request)
    {
        $form = $request->validate([
            'item' => "required|min:8",
            'image' => "nullable|min:1|max:4096|mimes:jpg,png,jpeg|max:5000",
            'price' => "required|min:0",
            'quantity' => "required|min:0",
        ]);
        if ($request->image) {
            $filename = $form['item'] . '_' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('stocks'), $filename);

            $file = fopen(public_path('stocks/') . $filename, 'r');

            $storage = Firebase::storage();
            $storage->getBucket()->upload($file, ['name' => 'stocks/'  . $filename]);

            $imageReference = app('firebase.storage')->getBucket()->object("stocks/" . $filename);
            $image = $imageReference->signedUrl(Carbon::now()->addCenturies(1));

            unlink(public_path('stocks/') . $filename);

            Stock::create([
                'item' => $form['item'],
                'image' => $image,
                'temp_name' => $filename,
                'quantity' => $form['quantity'],
                'price' => $form['price'],
            ]);

        } else {
            Stock::create([
                'item' => $form['item'],
                'quantity' => $form['quantity'],
                'price' => $form['price'],
            ]);
        }
        return redirect()->back()->with([
            'message' => 'New item added'
        ]);
    }
    protected function updateSupply(Request $request, $id)
    {
        $stock = Stock::where('id', $id)->first();
        $form = $request->validate([
            'item' => "required|min:8",
            'image' => "nullable|min:1|max:4096|mimes:jpg,png,jpeg",
            'price' => "required|min:0",
            'quantity' => "required|min:0",
        ]);
        if ($request->image) {
            $filename = $form['item'] . '_' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('stocks'), $filename);

            $file = fopen(public_path('stocks/') . $filename, 'r');

            $storage = Firebase::storage();
            $storage->getBucket()->upload($file, ['name' => 'stocks/'  . $filename]);

            $imageReference = app('firebase.storage')->getBucket()->object("stocks/" . $filename);
            $image = $imageReference->signedUrl(Carbon::now()->addCenturies(1));

            app('firebase.storage')->getBucket()->object("stocks/" . $stock->temp_name)->delete();
            unlink(public_path('stocks/') . $filename);


            $quantity = $request->quantity;
            if (!$quantity > 0) {
                Stock::where('id', $id)->update([
                    'item' => $form['item'],
                    'image' => $image,
                    'temp_name' => $filename,
                    'quantity' => 0,
                    'price' => $form['price'],
                ]);
            } else {
                Stock::where('id', $id)->update([
                    'item' => $form['item'],
                    'image' => $image,
                    'temp_name' => $filename,
                    'quantity' => $form['quantity'],
                    'price' => $form['price'],
                ]);
            }
        } else {
            $stock = Stock::where('id', $id)->get()->first();
            if (file_exists(public_path("stocks/" . $stock->image))) {
                Stock::where('id', $id)->update([
                    'item' => $form['item'],
                    'quantity' => $form['quantity'],
                    'price' => $form['price'],
                ]);
            } else {
                Stock::where('id', $id)->update([
                    'item' => $form['item'],
                    'quantity' => $form['quantity'],
                    'price' => $form['price'],
                ]);
            }
        }

        return redirect()->back()->with([
            'message' => "Item is updated"
        ]);
    }
    protected function deleteSupply($id)
    {
        Stock::where('id', $id)->delete();
        return redirect()->back()->with([
            'message' => "Item has been removed on your inventory"
        ]);
    }
    protected function toRent(Request $request, $id)
    {
        $stock = Stock::where('id', $id)->get()->first();
        $form = $request->only('quantity');
        if ($form['quantity'] < $stock->quantity) {
            ForRent::create([
                'stock_id' => $id,
                'quantity' => $form['quantity'],
                'is_rented' => 1
            ]);
            Stock::where('id', $id)->update([
                'quantity' => $stock->quantity - $form['quantity']
            ]);
        } else {
            ForRent::create([
                'stock_id' => $id,
                'quantity' => $stock->quantity,
                'is_rented' => 1
            ]);
            Stock::where('id', $id)->update([
                'quantity' => 0
            ]);
        }
        
        return redirect()->back()->with([
            'message' => "Item is now rentable"
        ]);
    }
    protected function userRent(Request $request, $rentId, $stockId)
    {
        // user_id, stock_id, for_rent_id
        $user = Auth::user();

        $stock = Stock::where('id', $stockId)->get()->first();
        $forrent = ForRent::where('id', $rentId)->get()->first();
        $validator = Validator::make($request->only(
            'quantity',
            'date',
            'address',
            'return'
        ), [
            'quantity' => 'required|min:1',
            'date' => 'required',
            'address' => 'required',
            'return' => 'required'
        ]);
        $form = $validator->validated();
        $address = $request->input('venue') == "current" ? $user->info->address : ($request->input('venue') == "manual" ?  $form['address'] : $user->info->address);
        $result = UserRent::create([
            'user_info_id' => $user->info->id,
            'stock_id' => $stockId,
            'for_rent_id' => $rentId,
            'amount' => $form['quantity'] * $stock->price,
            'quantity' => $form['quantity'],
            'address' => $address,
            'date' => $form['date'],
            'return' => $form['return'],
        ]);
        if ($result) {
            $method = $request->method;
            ForRent::where('id', $rentId)->update([
                'quantity' => $forrent->quantity - $form['quantity']
            ]);
            
            if ($method == "deliver") {
                Deliver::create([
                    'user_rent_id' => $result->id
                ]);
            }
            if ($method == "pickup") {
                Pickup::create([
                    'user_rent_id' => $result->id
                ]);
            }
            return redirect()->back()->with([
                'message' => 'Successfully rent, wait for approval.'
            ]);
        }
        return redirect()->back()->withErrors([
            'message' => "There's something wrong with your inputs"
        ]);
    }
    protected function toCheckOut($id)
    {
        $rent = UserRent::where('id', $id)->get()->first();
        $extendRate = 100;
        if ($rent->status == "pending") {
            UserRent::where('id', $id)->update([
                'status' => 'approved'
            ]);
            $userRent = UserRent::where('id', $id)->first();
            $userInfo = $userRent->info;
            $user = $userInfo->user;
            Mail::send('admin.rent-report-email', ['rent' => $userRent, 'status' => 'APPROVED'], function ($m) use($user) {
                $m->from(env('MAIL_USERNAME'), 'Lucky Fuds Service Catering System');
                $m->to($user->email)->subject('Lucky Fuds Service Catering System | Reservation Status');
            });
            RentApprove::create([
                'user_rent_id' => $rent->id
            ]);
            
            return redirect()->back()->with([
                'message' => "Rent Approved"
            ]);
        }
        if ($rent->status == "extending") {
            $rentPerDay = Carbon::now()->diffInDays($rent->extends->return, false);
            UserRent::where('id', $id)->update([
                'status' => 'extended',
                'amount' => $extendRate * $rentPerDay
            ]);
            $userRent = UserRent::where('id', $id)->first();
            $userInfo = $userRent->info;
            $user = $userInfo->user;
            Mail::send('admin.rent-report-email', ['rent' => $userRent, 'status' => 'APPROVED EXTENSION'], function ($m) use($user) {
                $m->from(env('MAIL_USERNAME'), 'Lucky Fuds Service Catering System');
                $m->to($user->email)->subject('Lucky Fuds Service Catering System | Reservation Status');
            });
            ExtendApprove::create([
                'user_rent_id' => $rent->id
            ]);

            return redirect()->back()->with([
                'message' => "Extend Approved"
            ]);
        }
    }
    protected function toReject($id)
    {
        $rent = UserRent::where('id', $id)->get()->first();

        if ($rent->status == "pending") {
            UserRent::where('id', $id)->update([
                'status' => 'declined',
            ]);
            $userRent = UserRent::where('id', $id)->first();
            $userInfo = $userRent->info;
            $user = $userInfo->user;
            Mail::send('admin.rent-report-email', ['rent' => $userRent, 'status' => 'DECLINED'], function ($m) use($user) {
                $m->from(env('MAIL_USERNAME'), 'Lucky Fuds Service Catering System');
                $m->to($user->email)->subject('Lucky Fuds Service Catering System | Reservation Status');
            });
            RentDecline::create([
                'user_rent_id' => $rent->id
            ]);
            ForRent::where('id', $rent->for_rent_id)->update([
                'quantity' => $rent->for_rent->quantity + $rent->quantity
            ]);
            return redirect()->back()->with([
                'decline' => "Rent Declined",
            ]);
        }
        if ($rent->status == "extending") {
            UserRent::where('id', $id)->update([
                'status' => 'extend declined',
            ]);
            $userRent = UserRent::where('id', $id)->first();
            $userInfo = $userRent->info;
            $user = $userInfo->user;
            Mail::send('admin.rent-report-email', ['rent' => $userRent, 'status' => 'DECLINE EXTENSION'], function ($m) use($user) {
                $m->from(env('MAIL_USERNAME'), 'Lucky Fuds Service Catering System');
                $m->to($user->email)->subject('Lucky Fuds Service Catering System | Reservation Status');
            });
            ExtendDecline::create([
                'user_rent_id' => $rent->id
            ]);
            Extend::where('id', $rent->extends->id)->delete();
            return redirect()->back()->with([
                'decline' => "Extend Declined",
            ]);
        }
        return redirect()->back();
    }
    protected function userExtends(Request $request, $id)
    {
        $form = $request->validate([
            'date' => 'required',
            'return' => 'required'
        ]);
        UserRent::where('id', $id)->update([
            'status' => 'extending',
        ]);
        Extend::create([
            'user_rent_id' => $id,
            'date' => $form['date'],
            'return' => $form['return'],
        ]);
        return redirect()->back()->with([
            'message' => "Rent extends, wait for approval"
        ]);
    }
    protected function toReturn($id)
    {
        $rent = UserRent::where('id', $id)->get()->first();

        if ($rent->rent_approve) {
            $result = UserRent::where('id', $id)->update([
                'status' => 'returned'
            ]);
            if ($result) {
                Returns::create([
                    'user_rent_id' => $rent->id,
                    'date' => $rent->date,
                    'return' => $rent->return,
                ]);
            }
        }
        if ($rent->extend_approve) {
            $result = UserRent::where('id', $id)->update([
                'status' => 'returned'
            ]);
            if ($result) {
                Returns::create([
                    'user_rent_id' => $rent->id,
                    'date' => $rent->extend && $rent->extend->date ? $rent->extend->date : $rent->date,
                    'return' => $rent->extend && $rent->extend->return ? $rent->extend->return : $rent->return,
                    'quantity' => $rent->quantity,
                ]);
            }
        }
        if ($rent->extend_decline) {
            $result = UserRent::where('id', $id)->update([
                'status' => 'returned'
            ]);
            if ($result) {
                Returns::create([
                    'user_rent_id' => $rent->id,
                    'date' => $rent->extend && $rent->extend->date ? $rent->extend->date : $rent->date,
                    'return' => $rent->extend && $rent->extend->return ? $rent->extend->return : $rent->return,
                    'quantity' => $rent->quantity,
                ]);
            }
        }
        return redirect()->back()->with([
            'message' => "Rent returned, check to your returns page"
        ]);
    }

    protected function addToItems(Request $request, $id)
    {
        $rent = UserRent::where('id', $id)->get()->first();

        Stock::where('id', $rent->stock->id)->update([
            'quantity' => $rent->stock->quantity + $rent->quantity,
        ]);
        UserRent::where('id', $id)->update([
            'is_returned' => 1
        ]);
        return redirect()->back()->with([
            'message' => "Item has been added in the inventory"
        ]);
    }
    protected function addToRents(Request $request, $id)
    {
        $rent = UserRent::where('id', $id)->get()->first();
        ForRent::where('id', $rent->for_rent_id)->update([
            'quantity' => $rent->for_rent->quantity + $rent->quantity,
        ]);
        UserRent::where('id', $id)->update([
            'is_returned' => 1
        ]);
        return redirect()->back()->with([
            'message' => "Item has been applied to a rentable items."
        ]);
    }
}
