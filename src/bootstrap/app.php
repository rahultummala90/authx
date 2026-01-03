<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Passport\Http\Middleware\CheckToken;              // for all scopes
use Laravel\Passport\Http\Middleware\CheckTokenForAnyScope;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'passport.scopes' => CheckToken::class,
            'passport.scope'  => CheckTokenForAnyScope::class,
        ]);
    })
    ->withProviders([
        App\Providers\AuthServiceProvider::class,
        App\Providers\AppServiceProvider::class,
        // App\Providers\RouteServiceProvider::class, // if you have it, etc.
    ])
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
