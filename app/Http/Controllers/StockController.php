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
        $stock = Stock::where('id', $stockId)->get()->first();
        $user = Auth::user();
        $validator = Validator::make($request->only(
            'client',
            'items',
            'quantity',
            'date',
            'return'
        ),[
            'client' => 'required|min:3',
            'items' => 'required',
            'quantity' => 'required|min:1',
            'date' => 'required',
            'return' => 'required'
        ]);
        $form = $validator->validated();
        $result = Rent::create([
            'user_id' => $user->id,
            'for_rent_id' => $rentId,
            'client' => Auth::user()->name,
            'items' => $form['items'],
            'amount' => $form['quantity'] * $stock->price,
            'date' => $form['date'],
            'return' => $form['return'],
        ]);
        if($result) {
            $method = $request->method;
            if($method == "deliver") {
                Deliver::create([
                    'rent_id' => $result->id
                ]);
            }
            if($method == "pickup") {
                Pickup::create([
                    'rent_id' => $result->id
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
        $rent = Rent::where('id', $id)->get()->first();
        $forRent = ForRent::where('id', $rent->for_rent_id)->first();
        $stock = Stock::where('id', $forRent->stock_id)->get()->first();

        if ($rent->status == "pending") {
            Rent::where('id', $id)->update([
                'status' => 'approved'
            ]);
            ForRent::where('id', $rent->for_rent_id)->update([
                'quantity' => $forRent->quantity - $rent->amount / $stock->price
            ]);
            return redirect()->back()->withErrors([
                'message' => "Rent Approved"
            ]);
        }
        if($rent->status == "extending"){
            Rent::where('id', $id)->update([
                'status' => 'extend'
            ]);
            return redirect()->back()->withErrors([
                'message' => "Rent extended"
            ]);
        }
        
    }
    protected function toReject($id)
    {
        $rent = Rent::where('id', $id)->get()->first();
        $forRent = ForRent::where('id', $rent->for_rent_id)->first();
        $stock = Stock::where('id', $forRent->stock_id)->get()->first();


        if ($rent->status == "pending" || $rent->status == "extending") {
            Rent::where('id', $id)->update([
                'status' => 'declined',
            ]);
            ForRent::where('id', $rent->for_rent_id)->update([
                'quantity' => $forRent->quantity + $rent->amount / $stock->price
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
        Rent::where('id', $id)->update([
            'status' => 'extending',
        ]);
        Extend::create([
            'rent_id' => $id,
            'date' => $form['date'],
            'return' => $form['return'],
        ]);
        
        return redirect()->back()->withErrors([
            'message' => "Rent extends, wait for approval"
        ]);
    }
    protected function toReturn($id)
    {
        $rent = Rent::where('id', $id)->get()->first();
        $item = ForRent::where('id', $rent->for_rent_id)->get()->first();
        $stock = Stock::where('id', $item->stock_id)->get()->first();

        if($rent->status == "approved") {
            $result = Rent::where('id', $id)->update([
                'status' => 'returned'
            ]);
            $userId = $rent->user_id;
            if($result) {
                Returns::create([
                    'user_id' => $userId,
                    'rent_id' => $id,
                    'item' => $stock->item,
                    'quantity' => $rent->amount / $stock->price,
                    'amount' => $rent->amount,
                    'date' => $rent->date,
                    'return' => $rent->return,
                ]);
            }
        }
        if($rent->status == "extend") {
            $result = Rent::where('id', $id)->update([
                'status' => 'extended'
            ]);
            $userId = $rent->user_id;
            if($result) {
                Returns::create([
                    'user_id' => $userId,
                    'rent_id' => $id,
                    'item' => $stock->item,
                    'quantity' => $rent->amount / $stock->price,
                    'amount' => $rent->amount,
                    'date' => $rent->date,
                    'return' => $rent->return,
                ]);
            }
        }
        return redirect()->back()->withErrors([
            'message' => "Rent returned, check to your returns page"
        ]);
    }

    protected function addToItems($id)
    {
        $rent = Rent::where('id', $id)->with(['returns', 'extends'])->first();
        $rentAmount = $rent->amount;
        $rentQuantity = $rent->returns->quantity;

        $forRent = ForRent::where('id', $rent->for_rent_id)->first();
        $stock = Stock::where('id', $forRent->stock_id)->first();

        Stock::where('id', $stock->id)->update([
            'quantity' => $stock->quantity + $rentQuantity
        ]);
        Rent::where('id', $id)->update([
            'is_returned' => 1
        ]);
        Report::create([
            'image' => $stock->image,
            'item' => $stock->item,
            'client' => $rent->client,
            'quantity' => $rentQuantity,
            'amount' => $rentAmount,
            'date' => $rent->extends ? $rent->extends->date : $rent->date,
            'return' => $rent->extends ? $rent->extends->return : $rent->return,
        ]);
        return redirect()->back()->withErrors([
            'message' => "Item successfully added in the inventory"
        ]);
    }
    protected function addToRents($id)
    {
        $rent = Rent::where('id', $id)->with(['returns', 'extends'])->first();
        $rentAmount = $rent->amount;
        $rentQuantity = $rent->returns->quantity;

        $forRent = ForRent::where('id', $rent->for_rent_id)->first();
        $forRentQuantity = $forRent->quantity;

        $stock = Stock::where('id', $forRent->stock_id)->first();

        ForRent::where('id', $rent->for_rent_id)->update([
            'quantity' => $forRentQuantity + $rentQuantity
        ]);
        Rent::where('id', $id)->update([
            'is_returned' => 1
        ]);
        Report::create([
            'image' => $stock->image,
            'item' => $stock->item,
            'client' => $rent->client,
            'quantity' => $rentQuantity,
            'amount' => $rentAmount,
            'date' => $rent->extends ? $rent->extends->date : $rent->date,
            'return' => $rent->extends ? $rent->extends->return : $rent->return,
        ]);
        return redirect()->back()->withErrors([
            'message' => "Item is applied to rentable items."
        ]);
    }
}
