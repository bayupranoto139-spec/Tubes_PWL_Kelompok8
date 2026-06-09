<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // Cek apakah user aktif
        if (! $user->is_active) {
            Auth::logout();
            return back()->withErrors(['email' => 'Akun Anda tidak aktif. Hubungi administrator.']);
        }

        // Pasien wajib sudah verifikasi email minimal sekali
        // Setelah verified, login selanjutnya bebas masuk
        if ($user->role === 'pasien' && ! $user->hasVerifiedEmail()) {
            Auth::logout();
            return back()
                ->withErrors(['email' => 'Email Anda belum diverifikasi. Silakan cek inbox dan klik link verifikasi yang telah dikirim.'])
                ->with('unverified_email', $user->email)
                ->onlyInput('email');
        }

        return $this->redirectByRole($user);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    private function redirectByRole($user)
    {
        return match ($user->role) {
            'super_admin', 'admin_rs', 'staff' => redirect('/admin'),
            'dokter' => redirect('/doctor/dashboard'),
            'patient', 'pasien' => redirect('/user/patient/dashboard'),

            default => redirect('/login'),
        };
    }
}