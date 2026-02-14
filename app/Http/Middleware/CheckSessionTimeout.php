<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CheckSessionTimeout
{
    public function handle(Request $request, Closure $next)
    {
        if (Session::has('user') && Session::has('login_time')) {
            $loginTime = Session::get('login_time');
            $timeout = 3600; // 1 hour in seconds
            
            if (now()->diffInSeconds($loginTime) > $timeout) {
                Session::forget('user');
                Session::forget('login_time');
                return redirect('/login')->with('error', 'Session expired. Please login again.');
            }
        }
        
        return $next($request);
    }
}