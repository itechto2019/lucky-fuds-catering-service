<?php

namespace App\Http\Controllers;

use App\Models\Deliver;
use App\Models\Extend;
use App\Models\ForRent;
use App\Models\Pickup;
use App\Models\Rent;
use App\Models\Returns;
use App\Models\Stock;
use App\Models\Report;
use App\Models\UserRent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
            'image' => "nullable|min:1|max:4096|mimes:jpg,png,jpeg",
            'price' => "required|min:0",
            'quantity' => "required|min:0",
        ]);
        if ($request->image) {
            $filename = $form['item'] . '_' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('stocks'), $filename);

            $result = Stock::create([
                'item' => $form['item'],
                'image' => $filename,
                'quantity' => $form['quantity'],
                'price' => $form['price'],
            ]);
        } else {
            $result = Stock::create([
                'item' => $form['item'],
                'image' => "no_image.png",
                'quantity' => $form['quantity'],
                'price' => $form['price'],
            ]);
        }
        return redirect()->back()->withErrors([
            'message' => "New item created"
        ]);
    }
    protected function updateSupply(Request $request, $id)
    {
        $form = $request->validate([
            'item' => "required|min:8",
            'image' => "nullable|min:1|max:4096|mimes:jpg,png,jpeg",
            'price' => "required|min:0",
            'quantity' => "required|min:0",
        ]);
        if ($request->image) {
            $filename = $form['item'] . '_' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('stocks'), $filename);
            $quantity = $request->quantity;
            if(!$quantity > 0) {
                Stock::where('id', $id)->update([
                    'item' => $form['item'],
                    'image' => $filename,
                    'quantity' => 0,
                    'price' => $form['price'],
                ]);
            }else {
                Stock::where('id', $id)->update([
                    'item' => $form['item'],
                    'image' => $filename,
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
                    'image' => "no_image.png",
                    'quantity' => $form['quantity'],
                    'price' => $form['price'],
                ]);
            }
        }

        return redirect()->back()->withErrors([
            'message' => "Item is updated"
        ]);
    }
    protected function deleteSupply($id)
    {
        Stock::where('id', $id)->delete();
        return redirect()->back()->withErrors([
            'message' => "Item sucessfully removed"
        ]);
    }
    protected function toRent(Request $request, $id)
    {
        $stock = Stock::where('id', $id)->get()->first();
        $form = $request->only('quantity');
        if($form['quantity'] < $stock->quantity) {
            ForRent::create([
                'stock_id' => $id,
                'quantity' => $form['quantity'],
                'is_rented' => 1
            ]);
            Stock::where('id', $id)->update([
                'quantity' => $stock->quantity - $form['quantity']
            ]);
        }else {
            ForRent::create([
                'stock_id' => $id,
                'quantity' => $stock->quantity,
                'is_rented' => 1
            ]);
            Stock::where('id', $id)->update([
                'quantity' => 0
            ]);
        }
        
        return redirect()->back()->withErrors([
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
            'return'
        ),[
            'quantity' => 'required|min:1',
            'date' => 'required',
            'return' => 'required'
        ]);
        $form = $validator->validated();
        $result = UserRent::create([
            'user_info_id' => $user->info->id,
            'stock_id' => $stockId,
            'for_rent_id' => $rentId,
            'amount' => $form['quantity'] * $stock->price,
            'date' => $form['date'],
            'return' => $form['return'],
        ]);
        if($result) {
            $method = $request->method;
            ForRent::where('id', $rentId)->update([
                'quantity' => $forrent->quantity - $form['quantity']
            ]);
            if($method == "deliver") {
                Deliver::create([
                    'user_rent_id' => $result->id
                ]);
            }
            if($method == "pickup") {
                Pickup::create([
                    'user_rent_id' => $result->id
                ]);
            }
            return redirect()->back()->withErrors([
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

        if ($rent->status == "pending") {
            UserRent::where('id', $id)->update([
                'status' => 'approved'
            ]);
            return redirect()->back()->withErrors([
                'message' => "Rent Approved"
            ]);
        }
        if($rent->status == "extending"){
            UserRent::where('id', $id)->update([
                'status' => 'extend'
            ]);
            return redirect()->back()->withErrors([
                'message' => "Rent extends"
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
            ForRent::where('id', $rent->for_rent_id)->update([
                'quantity' => $rent->for_rent->quantity + $rent->amount / $rent->stock->price
            ]);
        }
        if($rent->status == "extending") {
            UserRent::where('id', $id)->update([
                'status' => 'approved',
            ]);
            Extend::where('id', $rent->extends->id)->delete();
            ForRent::where('id', $rent->for_rent_id)->update([
                'quantity' => $rent->for_rent->quantity + $rent->amount / $rent->stock->price
            ]);
        }

        return redirect()->back()->withErrors([
            'message' => "Rent Declined"
        ]);
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
        
        return redirect()->back()->withErrors([
            'message' => "Rent extends, wait for approval"
        ]);
    }
    protected function toReturn($id)
    {
        $rent = UserRent::where('id', $id)->get()->first();
        // $item = ForRent::where('id', $rent->for_rent_id)->get()->first();
        // $stock = Stock::where('id', $item->stock_id)->get()->first();

        if($rent->status == "approved") {
            $result = UserRent::where('id', $id)->update([
                'status' => 'returned'
            ]);
            if($result) {
                Returns::create([
                    'user_rent_id' => $rent->id,
                    'date' => $rent->date,
                    'return' => $rent->return,
                ]);
            }
        }
        if($rent->status == "extended") {
            $result = UserRent::where('id', $id)->update([
                'status' => 'returned'
            ]);
            if($result) {
                Returns::create([
                    'user_rent_id' => $rent->id,
                    'date' => $rent->extend && $rent->extend->date ? $rent->extend->date : $rent->date,
                    'return' => $rent->extend && $rent->extend->return ? $rent->extend->return : $rent->return,
                    'quantity' => $rent->amount / $rent->stock->price,
                ]);
            }
        }
        return redirect()->back()->withErrors([
            'message' => "Rent returned, check to your returns page"
        ]);
    }

    protected function addToItems(Request $request, $id)
    {
        $rent = UserRent::where('id', $id)->get()->first();

        Stock::where('id', $rent->stock->id)->update([
            'quantity' => $rent->stock->quantity + $rent->amount / $rent->stock->price
        ]);
        UserRent::where('id', $id)->update([
            'is_returned' => 1
        ]);
        return redirect()->back()->withErrors([
            'message' => "Item successfully added in the inventory"
        ]);
    }
    protected function addToRents(Request $request, $id)
    {
        $rent = UserRent::where('id', $id)->get()->first();
        ForRent::where('id', $rent->for_rent_id)->update([
            'quantity' => $rent->for_rent->quantity + $rent->amount / $rent->stock->price
        ]);
        UserRent::where('id', $id)->update([
            'is_returned' => 1
        ]);
        return redirect()->back()->withErrors([
            'message' => "Item is applied to rentable items."
        ]);
    }
}
