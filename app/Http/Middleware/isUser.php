<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class isUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check()) {
            if(!Auth::user()->is_admin) {
                return $next($request);
            }else if (!Auth::user()->is_admin && !Auth::user()->email_verified_at) {
                return route('verify_first');
            }else {
                return route('dashboard');
            }
        }else {
            return redirect('/login');
        }
    }
}
