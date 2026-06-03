<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index()
    {
        // Ambil user yang sedang login
        $userId = auth()->id();

        // Ambil data user yang digabungkan dengan detail spesialisasi kedokterannya
        $doctorProfile = DB::table('users')
            ->join('doctors', 'users.id', '=', 'doctors.user_id')
            ->join('specializations', 'doctors.specialization_id', '=', 'specializations.id')
            ->join('hospitals', 'users.hospital_id', '=', 'hospitals.id')
            ->where('users.id', $userId)
            ->select(
                'users.id',
                'users.name',
                'users.email',
                'users.phone',
                'users.address',
                'doctors.licence_number',
                'doctors.years_of_experience',
                'specializations.name as specialization_name',
                'hospitals.name as hospital_name'
            )
            ->first();

        return view('doctor.profile', compact('doctorProfile'));
    }
}