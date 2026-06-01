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
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // Cek apakah user aktif
        if (!$user->is_active) {
            Auth::logout();
            return back()->withErrors(['email' => 'Akun Anda tidak aktif.']);
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
        return match($user->role) {
            'super_admin', 'admin_rs' => redirect('/admin'),
            'staff'                   => redirect('/staff'),
            'dokter'                  => redirect('/user/doctor/dashboard'),
            'pasien'                  => redirect('/user/patient/dashboard'),
            default                   => redirect('/login'),
        };
    }
}
