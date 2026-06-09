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

        // Blokir pasien yang belum pernah verifikasi email.
        // Setelah verified, email_verified_at tidak pernah null lagi
        // sehingga login selanjutnya bebas masuk tanpa cek ulang.
        if ($user->role === 'pasien' && ! $user->hasVerifiedEmail()) {
            Auth::logout();

            return redirect()->route('login')
                ->withErrors(['email' => 'Email Anda belum diverifikasi. Silakan cek inbox Anda.'])
                ->with('unverified_email', $user->email);
        }

        return $next($request);
    }
}