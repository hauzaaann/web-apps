<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Session;

class SessionHelper
{
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn()
    {
        return Session::has('user');
    }

    /**
     * Get current user
     */
    public static function getUser()
    {
        return Session::get('user');
    }

    /**
     * Get user role
     */
    public static function getRole()
    {
        $user = Session::get('user');
        return $user ? $user->role : null;
    }

    /**
     * Check if user has specific role
     */
    public static function hasRole($role)
    {
        $user = Session::get('user');
        return $user && $user->role === $role;
    }

    /**
     * Clear session
     */
    public static function logout()
    {
        Session::forget('user');
        Session::flush();
    }
}