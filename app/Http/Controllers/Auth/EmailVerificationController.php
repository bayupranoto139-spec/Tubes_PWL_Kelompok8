<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class EmailVerificationController extends Controller
{
    /**
     * Handle email verification link.
     *
     * Tidak memerlukan middleware 'auth' — user boleh membuka link
     * dari device/browser manapun tanpa harus login dulu.
     *
     * Flow:
     * 1. Validasi signature URL (anti-tamper)
     * 2. Cari user berdasarkan id di URL
     * 3. Validasi hash email cocok
     * 4. Tandai email sebagai verified
     * 5. Auto-login user lalu redirect ke dashboard
     */
    public function verify(Request $request, int $id, string $hash)
    {
        // 1. Validasi URL signature — tolak jika sudah dimanipulasi atau expired
        if (! URL::hasValidSignature($request)) {
            abort(403, 'Link verifikasi tidak valid atau sudah kadaluarsa.');
        }

        // 2. Cari user
        $user = User::findOrFail($id);

        // 3. Validasi hash (hash dari email user harus cocok dengan yang di URL)
        if (! hash_equals(sha1($user->email), $hash)) {
            abort(403, 'Link verifikasi tidak valid.');
        }

        // 4. Tandai verified (idempotent — aman dipanggil berkali-kali)
        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        // 5. Auto-login jika belum login, atau user yang buka adalah orang lain
        if (! Auth::check() || Auth::id() !== $user->id) {
            Auth::login($user);
        }

        return redirect()
            ->route('patient.dashboard')
            ->with('success', 'Email berhasil diverifikasi! Selamat datang.');
    }
}