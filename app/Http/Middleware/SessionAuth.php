<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SessionAuth
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('Session ID:', [session()->getId()]);
        Log::info('Session Data:', session()->all());

        if (!session()->has('user_id')) {
            Log::warning('User is NOT logged in, redirecting to login.');
            return redirect('/login')->with('error', 'Please login first.');
        }

        // 🔹 Restrict access to admin routes only if user is NOT an admin
        if ($request->is('admin/*') && session('role') !== 'admin') {
            Log::warning('Unauthorized admin access attempt by user ID: ' . session('user_id'));
            return redirect('/')->with('error', 'Unauthorized access!');
        }

        Log::info('User is logged in and authorized, proceeding to next request.');

        return $next($request);
    }
}
