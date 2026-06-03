<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileUpdateController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        // Ambil user yang sedang login
        $userId = auth()->id();

        DB::transaction(function () use ($request, $userId) {
            // Pada struktur skema saat ini, phone & address berada di tabel `users`.
            DB::table('users')
                ->where('id', $userId)
                ->update([
                    'name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'address' => $request->input('address'),
                    'updated_at' => now(),
                ]);

            // Jika di skema Anda `phone` dan `address` berada di tabel doctors,
            // pindahkan update di bawah sesuai kebutuhan.
            // Saat ini mengikuti view yang membaca dari relasi $doctorUser?->doctor?->phone dan $doctorUser?->doctor?->address.
            // Pada migration saat ini, tabel `doctors` tidak memiliki kolom `phone` maupun `address`.
            // Jadi update khusus dokter tidak dilakukan untuk kolom tersebut.
            // Data phone/address disimpan di tabel `users`.
            // (Biarkan kosong)
        });

        return redirect()->route('doctor.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}

