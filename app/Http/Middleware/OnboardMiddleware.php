<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnboardMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!$request->session()->has('user_onboard')) {
            abort(403);
        } elseif (!$user = \App\Models\User::find($request->session()->get('user_onboard')['id'])) {
            abort(403);
        }

        return $next($request);
    }
}
