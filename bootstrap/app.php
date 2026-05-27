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
        // 🔓 Untuk pengguna YANG BELUM login (guest), diarahkan ke halaman login
        $middleware->redirectGuestsTo('/login');

        // 🔐 Untuk pengguna YANG SUDAH login (menuju halaman login/register), diarahkan berdasarkan role
        $middleware->redirectUsersTo(function (Request $request) {
            $user = $request->user();
            if (! $user) {
                return '/login';
            }

            return match ($user->role) {
                'super_admin', 'admin_rs', 'staff' => '/admin',
                'pasien', 'dokter' => '/user/home',
                default => '/login',
            };
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
