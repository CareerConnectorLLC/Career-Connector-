<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'appName'=>config('app.name'),
            'appUrl'=>config('app.url'),
            'auth'=>[
                'user'=> [
                    'full_name'=>auth()->user()->full_name ?? null,
                    'profile_photo_url'=>  isset(auth()->user()->profile_photo_url) ? auth()->user()->profile_photo_url : null,
                    'email'=>  isset(auth()->user()->email) ? auth()->user()->email : null,
                    'phone'=>  isset(auth()->user()->phone) ? auth()->user()->phone : null,
                    'location'=>  isset(auth()->user()->location) ? auth()->user()->location : null,
                  ]
                ],
                'flash' => [
                    'success' => fn() => $request->session()->get('success'),
                    'error' => fn() => $request->session()->get('error'),
                    'info' => fn() => $request->session()->get('info'),
                    'warning' => fn() => $request->session()->get('warning'),
                ],

        ]);
    }

    public function rootView(Request $request): string
    {
        if ($request->is('admin/*')) {
            return 'admin';
        }
        else{
            return 'frontend';
        }

        return $this->rootView;
    }
}
