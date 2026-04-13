<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
   public function handle(Request $request, Closure $next)
{
    if (session('user_role') !== 'admin') {
        return redirect()->route('home')
               ->with('error', 'Access Denied: Admins Only');
    }
    return $next($request);
}
}
