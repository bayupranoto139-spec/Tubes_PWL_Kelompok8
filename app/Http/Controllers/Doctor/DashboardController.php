<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Simulasi ID Dokter yang login adalah Dr. Budi Santoso (ID User: 3, ID Dokter: 1)
        $doctorId = 1;

        // 1. Hitung total janji temu/antrean hari ini (2026-06-01 sesuai dump sql)
        $todayQueue = DB::table('appointments')
            ->where('doctor_id', $doctorId)
            ->whereDate('scheduled_at', '2026-06-01')
            ->count();

        // 2. Hitung total kunjungan yang berstatus 'completed'
        $completedVisits = DB::table('appointments')
            ->where('doctor_id', $doctorId)
            ->where('status', 'completed')
            ->count();

        // 3. Ambil data tarif konsultasi dokter dari tabel doctors
        $doctorInfo = DB::table('doctors')->where('id', $doctorId)->first();
        $consultationFee = $doctorInfo ? $doctorInfo->consultation_fee : 0;

        return view('doctor.dashboard', compact('todayQueue', 'completedVisits', 'consultationFee'));
    }

    public function today()
    {
        // Gunakan placeholder dulu untuk dokter yang login.
        // Nanti bisa diganti ke: auth()->user()->doctor_id
        $doctorId = 1;

        return view('doctor.today', compact('doctorId'));
    }
}
