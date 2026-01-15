<?php

require_once __DIR__ . '/helpers.php';


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
            'hierarchy.access' => \App\Http\Middleware\CheckHierarchyAccess::class,
        ]);
    })
    ->withSchedule(function ($schedule) {
        $schedule->command('hf:cleanup-bin')->daily();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
