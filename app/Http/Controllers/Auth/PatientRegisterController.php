<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Hospital;
use App\Models\PatientEnrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PatientRegisterController extends Controller
{
    public function create()
    {
        $hospitals = Hospital::where('is_active', true)->get();

        return view(
            'auth.register',
            compact('hospitals')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:8'],
            'phone' => ['required'],
            'address' => ['required'],
            'gender' => ['required'],
            'date_of_birth' => ['required'],
            'hospital_id' => ['required', 'exists:hospitals,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'pasien',
            'phone' => $request->phone,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'hospital_id' => null,
        ]);

        PatientEnrollment::create([
            'user_id' => $user->id,
            'hospital_id' => $request->hospital_id,
            'medical_record_number' => 'MRN-'.now()->timestamp.$user->id,
        ]);

        $user->sendEmailVerificationNotification();

        // Login otomatis lalu arahkan ke halaman verifikasi email
        Auth::login($user);

        return redirect()->route('verification.notice');
    }
}