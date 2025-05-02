<?php

use App\Http\Middleware\Guest;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\SuperAdmin;
use Sentry\Laravel\Integration;
use Illuminate\Support\Facades\Request;
use Inertia\EncryptHistoryMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            HandleInertiaRequests::class,
            EncryptHistoryMiddleware::class
        ]);
        $middleware->alias([
            'is-super-admin' => SuperAdmin::class,
            'guest' => Guest::class

        ]);

        // $middleware->redirectGuestsTo(fn (Request $request) => {
        //     dd($request->all())
        // });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        Integration::handles($exceptions);
    })->create();
