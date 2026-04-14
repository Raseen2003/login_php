<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
{
    // ✅ Check login first
    if (!session()->has('user_id') || !session('user_id')) {
        session()->flush();
        return redirect()->route('login')
               ->with('error', 'Please log in to continue.');
    }

    // ✅ Then check admin role
    if (session('user_role') !== 'admin') {
        return redirect()->route('home')
               ->with('error', 'Access Denied: Admins Only.');
    }

    return $next($request);
}
}