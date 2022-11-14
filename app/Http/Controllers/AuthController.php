<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    public function signin(LoginRequest $request)
    {
        $credential = $request->validated();
        $remember = $request['remember'] ? true : false;
        $terms = $request['agree-terms'] ? true : false;
        $policy = $request['agree-policy'] ? true : false;

        if (Auth::attempt($credential, $remember)) {
            if ($terms && $policy) {
                return redirect('/');
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


        // if(Auth::attempt($credential, $remember)) {
        //     if(!$terms && !$policy) {
        //         return redirect()->back()->withErrors([
        //             'message' => [
        //                 'terms' => 'You must agree to the Terms and Conditions',
        //                 'policy' => 'You must agree to the Data Policy Policy',
        //             ],
        //         ]);
        //     }
        // }else {
        //     return redirect()->back()->withErrors([
        //         'message' => [
        //             'credentials' => 'Incorrect Credentials',
        //             'terms' => 'You must agree to the Terms and Conditions',
        //             'policy' => 'You must agree to the Data Policy Policy',
        //         ],
        //     ]);
        // }


    }
    public function signup(Request $request)
    {
        try {
            $form = $request->validate([
                'email' => 'required',
                'name' => 'required|min:8',
                'password' => 'required|confirmed|min:8'
            ]);
            $userExist = User::where('is_admin', '1')->get();
            if ($userExist->isEmpty()) {
                User::create([
                    'email' => $form['email'],
                    'name' => $form['name'],
                    'password' => password_hash($form['password'], PASSWORD_BCRYPT),
                    'is_admin' => '1'
                ]);
            } else {

                User::create([
                    'email' => $form['email'],
                    'name' => $form['name'],
                    'password' => password_hash($form['password'], PASSWORD_BCRYPT),
                ]);
            }
            return redirect('/login');
        } catch (TypeError $e) {
            dd($e);
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
