<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectFilamentLogoutToLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Endpoint logout admin panel Filament: POST /admin/logout
        if ($request->is('admin/logout')) {
            $response = $next($request);

            // Jika Filament mengembalikan redirect ke /admin/login, ganti ke /login
            if ($response instanceof \Illuminate\Http\RedirectResponse) {
                $target = $response->getTargetUrl();

                if (is_string($target) && str_contains($target, '/admin/login')) {
                    return redirect('/login');
                }
            }

            return $response;
        }

        return $next($request);
    }
}

