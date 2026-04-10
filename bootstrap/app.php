<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'checkAdmin' => \App\Http\Middleware\checkAdmin::class,
            'checkStatus' => \App\Http\Middleware\checkStatus::class,
            'accueillant' => \App\Http\Middleware\AccueillantMiddleware::class,
            'photographe' => \App\Http\Middleware\PhotographeMiddleware::class,
            'mensuration' => \App\Http\Middleware\MensurationMiddleware::class,
            'jury' => \App\Http\Middleware\JuryMiddleware::class,
            'juryOrAdmin' => \App\Http\Middleware\JuryOrAdmin::class,
            'photographeOrAdmin' => \App\Http\Middleware\PhotographeOrAdmin::class,
            'mensurationOrAdmin' => \App\Http\Middleware\MensurationOrAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
