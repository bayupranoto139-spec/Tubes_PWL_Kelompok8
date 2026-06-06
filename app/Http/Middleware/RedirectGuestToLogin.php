<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware ini dipakai pada route-route fitur yang hanya bisa
 * diakses oleh user yang sudah login.
 *
 * Jika guest (belum login) mencoba akses, dia akan diarahkan
 * ke halaman login dengan pesan informatif.
 */
class RedirectGuestToLogin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            // Simpan URL yang ingin dikunjungi agar setelah login bisa redirect balik
            session(['url.intended' => $request->fullUrl()]);

            return redirect()->route('filament.admin.auth.login')
                ->with('info', 'Silakan login terlebih dahulu untuk mengakses fitur ini.');
        }

        return $next($request);
    }
}
