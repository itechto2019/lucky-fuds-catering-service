<?php

namespace App\Http\Controllers;

use App\Models\Extend;
use App\Models\ForRent;
use App\Models\Rent;
use App\Models\Returns;
use App\Models\Stock;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return redirect()->back();
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

        return redirect()->back();
    }
    protected function deleteSupply($id)
    {
        Stock::where('id', $id)->delete();
        return redirect()->back();
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
        
        return redirect()->back();
    }
    protected function userRent(Request $request, $rentId, $stockId)
    {
        $stock = Stock::where('id', $stockId)->get()->first();
        $user = Auth::user();
        $form = $request->validate([
            'client' => 'required|min:3',
            'quantity' => 'required|min:1',
            'date' => 'required',
            'return' => 'required'
        ]);
        Rent::create([
            'user_id' => $user->id,
            'for_rent_id' => $rentId,
            'client' => $form['client'],
            'amount' => $form['quantity'] * $stock->price,
            'date' => $form['date'],
            'return' => $form['return'],
        ]);

        return redirect()->back();
    }
    protected function toCheckOut($id)
    {
        $rent = Rent::where('id', $id)->get()->first();
        $forRent = ForRent::where('id', $rent->for_rent_id)->first();
        // $forRent = ForRent::where('id', $rent->for_rent_id)->get()->first();
        $stock = Stock::where('id', $forRent->stock_id)->get()->first();

        if ($rent->status == "pending") {
            Rent::where('id', $id)->update([
                'status' => 'approved'
            ]);
            ForRent::where('id', $rent->for_rent_id)->update([
                'quantity' => $forRent->quantity - $rent->amount / $stock->price
            ]);
        } else {
            Rent::where('id', $rent->id)->update([
                'status' => 'extended'
            ]);
        }
        return redirect()->back();
    }
    protected function toReject($id)
    {
        $rent = Rent::where('id', $id)->get()->first();
        $forRent = ForRent::where('id', $rent->for_rent_id)->first();
        // $forRent = ForRent::where('id', $rent->for_rent_id)->get()->first();
        $stock = Stock::where('id', $forRent->stock_id)->get()->first();


        if ($rent->status == "pending" || $rent->status == "extending") {
            Rent::where('id', $id)->update([
                'status' => 'declined',
            ]);
            ForRent::where('id', $rent->for_rent_id)->update([
                'quantity' => $forRent->quantity + $rent->amount / $stock->price
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
        Rent::where('id', $id)->update([
            'status' => 'extending',
        ]);
        Extend::create([
            'rent_id' => $id,
            'date' => $form['date'],
            'return' => $form['return'],
        ]);
        return redirect()->back();
    }
    protected function toReturn($id)
    {
        $rent = Rent::where('id', $id)->get()->first();
        $item = ForRent::where('id', $rent->for_rent_id)->get()->first();
        $stock = Stock::where('id', $item->stock_id)->get()->first();

        if ($rent->status == "approved" || $rent->status == "extended" || $rent->status == "declined") {
            $result = Rent::where('id', $id)->update([
                'status' => 'returned'
            ]);
            $userId = Auth::user()->id;
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
            // dd($result);
        }
        return redirect()->back();
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
        return redirect()->back();
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
        return redirect()->back();
    }
}
