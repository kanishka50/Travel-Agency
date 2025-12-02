<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Alias middleware
        $middleware->alias([
            'tourist' => \App\Http\Middleware\EnsureTourist::class,
            'guide' => \App\Http\Middleware\EnsureGuide::class,
            'admin' => \App\Http\Middleware\EnsureAdmin::class,
        ]);

        // Exclude API routes and Stripe webhook from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'api/*',
            'webhook/stripe',
        ]);

        // Security: Rate limiting for authentication routes
        $middleware->throttleApi();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
