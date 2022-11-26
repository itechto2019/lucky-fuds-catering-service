<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Verify;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    public function VerifyNow($token) {
        try {
            $verifiedUser = Verify::where('token', $token)->first();
            if($verifiedUser) {
                User::where('id', $verifiedUser->user_id)->update([
                    'email_verified_at' => now()
                ]);
                Verify::where('token', $token)->delete();
                return redirect()->route('login')->with([
                    'message' => 'Email verified'
                ]);
            }
        }catch(Exception $e) {
            abort(404);
        }
    }
    public function ResendVerification() {
        try {
            $user = Auth::user();
            $token = $user->verify->token;
            $verifiedUser = Verify::where('token', $token)->first();
            if($verifiedUser) {
                $verifyToken = Str::random(100);
                Verify::where('token', $token)->update([
                    'user_id' => $user->id,
                    'token' => $verifyToken
                ]);
                Mail::send('user.mail', ['verifyToken' => $verifyToken, 'user' => $user], function ($m) use($user) {
                    $m->from(env('MAIL_USERNAME'), 'Lucky Fuds Service Catering System');
                    $m->to($user->email)->subject('Lucky Fuds Service Catering System | Verification');
                });
                return view('user.resend_verification')->with(compact(['user','verifyToken']));
            }
            
        }catch(Exception $e) {
            if(Auth::check() && $user->email_verified_at) {
                return redirect()->route('user_dashboard');
            }
            sleep(3);
            return view('auth.login');
        }
        
    }
}
