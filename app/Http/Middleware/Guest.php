<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class Guest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // if (Auth::check()) {
        //     $roleNames = Auth::user()->role_names->toArray();
        //     if (in_array('SUPER-ADMIN', $roleNames) && Auth::user()->activeRole->name == 'SUPER-ADMIN') {
        //         return Inertia::location(route('admin.dashboard'));
        //     } else if (in_array('USER', $roleNames) && Auth::user()->activeRole->name == 'USER') {
        //         // return Inertia::location(route('frontend.index'));
        //     } else {
        //         Auth::logout();
        //         return abort(403);
        //     }
        // }
        if (Auth::check()) {
            if (Auth::user()->hasRole("SUPER-ADMIN")) {
                return redirect()->route('admin.dashboard');
            } elseif (Auth::user()->hasRole("USER")) {
                return redirect()->route('frontend.client.dashboard');
            } elseif (Auth::user()->hasRole("SERVICE-PROVIDER")) {
                return redirect()->route('frontend.provider.dashboard');
            } else {
                Auth::logout();
                return abort(403);
            }
        }

        return $next($request);
    }
}
