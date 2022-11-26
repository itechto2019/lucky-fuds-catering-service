<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Models\Verify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use TypeError;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }
    public function register()
    {
        return view('auth.register');
    }
    public function signin(LoginRequest $request)
    {
        $credential = $request->validated();
        $remember = $request['remember'] ? true : false;
        $terms = $request['agree-terms'] ? true : false;
        $policy = $request['agree-policy'] ? true : false;

        if (Auth::attempt($credential, $remember)) {
            if ($terms && $policy) {
                sleep(3);
                return redirect('/')->with([
                    'message' => 'Login successfully'
                ]);
            } else {
                return redirect()->back()->withErrors([
                    'message' => [
                        'terms' => 'You must agree to the Terms and Conditions',
                        'policy' => 'You must agree to the Data Policy Policy',
                    ],
                ]);
            }
        } else {
            return redirect()->back()->withErrors([
                'message' => [
                    'credentials' => 'Incorrect Credentials',
                ],
            ]);
        }

    }
    public function signup(Request $request)
    {
        try {
            $validator = Validator::make($request->only('email', 'password','password_confirmation'), [
                'email' => 'required|email',
                'password' => 'required|confirmed|min:8',
            ]);
            $form = $validator->validated();
            $user = User::create([
                'email' => $form['email'],
                'password' => password_hash($form['password'], PASSWORD_BCRYPT),
            ]);
            $token = Str::random(100);
            Verify::create([
                'user_id' => $user->id,
                'token' => $token
            ]);

            Mail::send('user.mail', ['verifyToken' => $token, 'user' => $user], function ($m) use($user) {
                $m->from(env('MAIL_USERNAME'), 'Lucky Fuds Service Catering System');
                $m->to($user->email)->subject('Lucky Fuds Service Catering System | Verification');
            });
            return redirect('/login')->with([
                'message' => 'Registration complete'
            ]);
        } catch (TypeError $e) {
            return back()->withErrors([
                'message' => 'Registration failed'
            ]);;
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
