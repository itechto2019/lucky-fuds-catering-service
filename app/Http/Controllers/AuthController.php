<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use TypeError;

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
    public function signin(Request $request)
    {
        // $credential = $request->validate();
        $remember = $request['remember'] ? true : false;
        $terms = $request['agree-terms'] ? true : false;
        $policy = $request['agree-policy'] ? true : false;

        if (Auth::attempt($request->only('email', 'password'), $remember)) {
            if ($terms && $policy) {
                return redirect('')->with([
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
            User::create([
                'email' => $form['email'],
                'password' => password_hash($form['password'], PASSWORD_BCRYPT),
            ]);
            return redirect('/login')->withErrors([
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
