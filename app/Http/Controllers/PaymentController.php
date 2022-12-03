<?php

namespace App\Http\Controllers;

use App\Models\ExtendOnlineTransaction;
use App\Models\OnlinePayment;
use App\Models\OnlineTransaction;
use App\Models\UserRent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kreait\Laravel\Firebase\Facades\Firebase;
use GuzzleHttp\Client;

class PaymentController extends Controller
{
    public function rentOnline(Request $request, $rent_id)
    {
        $rent = UserRent::where('id', $rent_id)->first();
        $form = $request->validate([
            'ref' => 'required|numeric|min:13',
            'image' => 'required|mimes:png,jpg,jpeg'
        ]);
        $filename = time() . '_payment.' . $form['image']->extension();
        $form['image']->move(public_path('payments'), $filename);

        $file = fopen(public_path('payments/') . $filename, 'r');

        $storage = Firebase::storage();
        $storage->getBucket()->upload($file, ['name' => 'payments/'  . $filename]);

        $imageReference = app('firebase.storage')->getBucket()->object("payments/" . $filename);
        $image = $imageReference->signedUrl(Carbon::now()->addCenturies(1));

        unlink(public_path('payments/') . $filename);

        if($rent->extend_online_transaction) {
            ExtendOnlineTransaction::where('user_rent', $rent->id)->update([
                'reference' => $form['ref'],
                'image' => $image,
                'temp_name' => $filename,
            ]);
            return back()->with([
                'message' => 'Payment uploaded'
            ]);
        }else {
            OnlineTransaction::where('user_rent', $rent->id)->update([
                'reference' => $form['ref'],
                'image' => $image,
                'temp_name' => $filename,
            ]);
            return back()->with([
                'message' => 'Payment uploaded'
            ]);
        }
        return back()->withErrors([
            'message' => 'Payment failed to upload'
        ]);
    }
    public function acceptPayment($rent_id) {
        $rent = UserRent::where('id', $rent_id)->first();
        if($rent->extend_online_transaction) {
            ExtendOnlineTransaction::where('user_rent', $rent->id)->update([
                'payment_status' => true
            ]);
            return back()->with([
                'message' => 'Payment uploaded'
            ]);
        }else {
            OnlineTransaction::where('user_rent', $rent->id)->update([
                'payment_status' => true
            ]);
            return back()->with([
                'message' => 'Payment uploaded'
            ]);
        }
        return back()->with([
            'message' => 'Received payment'
        ]);
    }

}
