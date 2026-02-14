<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // If already logged in, redirect to appropriate dashboard
        if (Session::has('user')) {
            $user = Session::get('user');
            return $this->redirectToDashboard($user->role);
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Get user from database
        $user = DB::table('users')->where('email', $request->email)->first();

        // Check credentials
        if ($user && Hash::check($request->password, $user->password)) {
            // Store user in session (excluding password)
            unset($user->password);
            Session::put('user', $user);
            Session::put('login_time', now());
            
            // Regenerate session ID for security
            Session::regenerate();
            
            // Redirect based on role
            return $this->redirectToDashboard($user->role);
        }

        return back()->with('error', 'Invalid email or password')->withInput();
    }

    public function logout(Request $request)
    {
        // Clear all session data
        Session::forget('user');
        Session::forget('login_time');
        Session::flush();
        
        // Regenerate session
        Session::regenerate();
        
        return redirect('/login')->with('success', 'Logged out successfully');
    }

    private function redirectToDashboard($role)
    {
        switch ($role) {
            case 'admin':
                return redirect('/admin/dashboard');
            case 'guru':
                return redirect('/guru/dashboard');
            case 'siswa':
                return redirect('/siswa/dashboard');
            default:
                return redirect('/login')->with('error', 'Invalid role');
        }
    }
}