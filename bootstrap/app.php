<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'owner' => \App\Http\Middleware\CheckOwnerRole::class,
            'auth:api' => \App\Http\Middleware\Authenticate::class,
        ]);

        $middleware->pushMiddleware(CORS::class); // Register Cors middleware

        // $middleware->append(GetUserFromToken::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
