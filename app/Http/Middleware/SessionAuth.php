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

    Log::info('User is logged in, proceeding to next request.');

    return $next($request);
}
}



