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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo(function () {
            $user = auth()->user();
            if ($user?->hasRole('Admin'))         return '/admin';
            if ($user?->hasRole('ProblemSetter')) return '/judge';
            return '/dashboard';
        });

        // Register the custom role-checking middleware under the alias 'role'
        // Usage: ->middleware('role:Admin') or ->middleware('role:Admin,ProblemSetter')
        $middleware->alias([
            'role'     => \App\Http\Middleware\RoleMiddleware::class,
            'approved' => \App\Http\Middleware\CheckApproved::class,
        ]);
    })
    ->withSchedule(function (\Illuminate\Console\Scheduling\Schedule $schedule) {
        $schedule->command('contests:refresh-external')->everyTenMinutes();
        $schedule->command('contest:update-status')->everyMinute();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
