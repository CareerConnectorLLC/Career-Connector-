<?php

use App\Http\Middleware\Guest;
use Sentry\Laravel\Integration;
use App\Http\Middleware\SuperAdmin;
use Inertia\EncryptHistoryMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Request;
use App\Http\Middleware\CustomerMiddleware;
use App\Http\Middleware\ProviderMiddleware;
use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            EncryptHistoryMiddleware::class
        ]);
        $middleware->alias([
            'is-super-admin' => SuperAdmin::class,
            'guest' => Guest::class,
            'is-customer' => CustomerMiddleware::class,
            'is-provider' => ProviderMiddleware::class,
            'onboarding' => \App\Http\Middleware\OnboardMiddleware::class,
        ]);
        $middleware->redirectGuestsTo(function () {
            if (request()->is('admin/*')) {
                return route('admin.login');
            } else {
                return route('frontend.login');
            }
        });
    })->withExceptions(function (Exceptions $exceptions) {
        Integration::handles($exceptions);
    })->create();
