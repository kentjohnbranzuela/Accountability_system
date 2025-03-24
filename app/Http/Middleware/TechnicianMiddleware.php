<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Ensure this is included

class TechnicianMiddleware
{
    public function handle(Request $request, Closure $next)
{
    Log::info('✅ TechnicianMiddleware is running.');

    $user = Auth::user();
    if (!$user) {
        Log::warning('❌ No user found. Redirecting...');
        return redirect('/login');
    }

    if ($user->role !== 'technician') {
        Log::warning('⛔ Access denied for user ID: ' . $user->id);
        return redirect('/unauthorized');
    }

    return $next($request);
}
}

