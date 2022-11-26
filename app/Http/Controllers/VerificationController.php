<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Verify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    public function VerifyNow($token) {
        $verifiedUser = Verify::where('token', $token)->first();
        $result = User::where('id', $verifiedUser->user_id)->update([
            'email_verified_at' => now()
        ]);
        if($result) {
            Verify::where('token', $token)->delete();
            return redirect()->route('login')->with([
                'message' => 'Email verified'
            ]);
        }
        abort(404);
    }
}
