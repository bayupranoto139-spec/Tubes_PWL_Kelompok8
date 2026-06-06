<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Simulasi ID Dokter yang login adalah Dr. Budi Santoso (ID User: 3, ID Dokter: 1)
        $doctorId = 1;

        // 1. Hitung total janji temu/antrean hari ini (2026-06-01 sesuai dump sql)
        // Pakai tanggal hari ini (bukan hardcoded dump date)
        $todayDate = now()->toDateString();

        // 1. Total antrean hari ini (termasuk yang sudah selesai)
        $todayQueue = DB::table('appointments')
            ->where('doctor_id', $doctorId)
            ->whereDate('scheduled_at', $todayDate)
            ->count();

        // 2. Total kunjungan yang berstatus 'completed' pada hari ini
        $completedVisits = DB::table('appointments')
            ->where('doctor_id', $doctorId)
            ->whereDate('scheduled_at', $todayDate)
            ->where('status', 'completed')
            ->count();

        // 3. Ambil data tarif konsultasi dokter dari tabel doctors
        $doctorInfo = DB::table('doctors')->where('id', $doctorId)->first();
        $consultationFee = $doctorInfo ? $doctorInfo->consultation_fee : 0;

        // Recent appointments (data nyata, bukan dummy)
        $recentAppointments = Appointment::query()
            ->where('doctor_id', $doctorId)
            ->whereDate('scheduled_at', now()->toDateString())
            ->with(['patientEnrollment.user'])
            ->orderBy('scheduled_at', 'desc')
            ->limit(5)
            ->get();

        return view('doctor.dashboard', compact('todayQueue', 'completedVisits', 'consultationFee', 'recentAppointments'));
    }

    public function today()
    {
        $user = auth()->user();

        // Ambil doctor dari relasi user->doctor
        // Catatan: pada model User relasinya bernama `doctor()` (return hasOne(Doctor::class)).
        $doctor = $user?->doctor;
        $doctorId = $doctor?->id;

        if (! $doctorId) {
            abort(403, 'Dokter tidak ditemukan untuk user yang sedang login.');
        }

        // Ambil antrean periksa hari ini untuk doctor login.
        // Kita prioritaskan appointment yang statusnya scheduled/confirmed.
        $todayAppointments = Appointment::query()
            ->where('doctor_id', $doctorId)
            ->whereDate('scheduled_at', now()->toDateString())
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->with(['patientEnrollment.hospital', 'patientEnrollment.user', 'doctor.specialization'])
            ->orderBy('scheduled_at', 'asc')
            ->get();

        return view('doctor.today', compact('doctorId', 'todayAppointments'));
    }
}
