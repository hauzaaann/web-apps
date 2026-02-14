<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is logged in
        if (!Session::has('user')) {
            return redirect('/login')->with('error', 'Please login first');
        }

        // Check if user has admin role
        $user = Session::get('user');
        if ($user->role !== 'admin') {
            return redirect('/login')->with('error', 'Unauthorized access. Admin only.');
        }

        return $next($request);
    }
}