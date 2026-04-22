<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckSession;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Providers\ViewComposerServiceProvider;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        ViewComposerServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'role' => CheckRole::class,
        'check.session' => CheckSession::class,
        'cookie.consent' => \App\Http\Middleware\CookieConsent::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();