<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAuth
{
   public function handle(Request $request, Closure $next)
{
    if (!session()->has('user_id') || !session('user_id')) {
        session()->flush();
        return redirect()->route('login')
               ->with('error', 'Please log in to continue.');
    }
    return $next($request);
    
}
}