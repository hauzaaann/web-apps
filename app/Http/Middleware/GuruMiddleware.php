<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GuruMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in
        if (!Session::has('user')) {
            return redirect('/login')->with('error', 'Please login first');
        }

        // Check if user has guru role
        $user = Session::get('user');
        if ($user->role !== 'guru') {
            return redirect('/login')->with('error', 'Unauthorized access. Guru only.');
        }

        return $next($request);
    }
}