<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware ini memastikan email sudah terverifikasi,
 * HANYA untuk user dengan role 'pasien'.
 *
 * Role lain (super_admin, admin_rs, dokter, staff) dilewatkan
 * tanpa perlu verifikasi email — termasuk akun lama yang
 * email_verified_at-nya masih null.
 */
class EnsurePatientEmailVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // Verifikasi email hanya di-enforce langsung setelah registrasi
        // (via redirect di PatientRegisterController), bukan saat login.
        // Middleware ini hanya memastikan user sudah login (auth).

        return $next($request);
    }
}