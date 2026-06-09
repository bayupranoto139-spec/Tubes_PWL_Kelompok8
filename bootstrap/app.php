<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // menggunakan scheme/host yang benar (https, bukan http)
        $middleware->trustProxies(at: '*');

        // Exclude Midtrans notification from CSRF verification
        $middleware->validateCsrfTokens(except: [
            'payment/notification',
            'api/midtrans/callback',
        ]);

        $middleware->redirectGuestsTo('/login');

        $middleware->redirectUsersTo(function (Request $request) {
            $user = $request->user();
            if (! $user) {
                return '/login';
            }

            return match ($user->role) {
                'super_admin', 'admin_rs', 'staff' => '/admin',
                'dokter' => '/doctor/dashboard',
                'pasien', 'patient' => '/user/patient/dashboard',
                default => '/login',
            };
        });
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();