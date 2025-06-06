<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd(Auth::user()->role_names->contains("SUPER-ADMIN"));
        if (Auth::check() && Auth::user()->role_names->contains("SUPER-ADMIN")) {
            return $next($request);
        }
        return redirect()->route('admin.login');
    }
}
