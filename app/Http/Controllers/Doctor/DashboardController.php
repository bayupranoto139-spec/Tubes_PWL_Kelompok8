<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $doctor   = $user?->doctor;
        $doctorId = $doctor?->id ?? 1; // fallback untuk dev

        $todayDate = now()->toDateString();

        $todayQueue = DB::table('appointments')
            ->where('doctor_id', $doctorId)
            ->whereDate('scheduled_at', $todayDate)
            ->count();

        $completedVisits = DB::table('appointments')
            ->where('doctor_id', $doctorId)
            ->whereDate('scheduled_at', $todayDate)
            ->where('status', 'completed')
            ->count();

        $doctorInfo      = DB::table('doctors')->where('id', $doctorId)->first();
        $consultationFee = $doctorInfo ? $doctorInfo->consultation_fee : 0;

        $recentAppointments = Appointment::query()
            ->where('doctor_id', $doctorId)
            ->whereDate('scheduled_at', now()->toDateString())
            ->with(['patientEnrollment.user'])
            ->orderBy('scheduled_at', 'desc')
            ->limit(5)
            ->get();

        return view('doctor.dashboard', compact(
            'todayQueue',
            'completedVisits',
            'consultationFee',
            'recentAppointments'
        ));
    }

    public function today()
    {
        $user   = auth()->user();
        $doctor = $user?->doctor;
        $doctorId = $doctor?->id;

        if (! $doctorId) {
            abort(403, 'Dokter tidak ditemukan untuk user yang sedang login.');
        }

        // Load appointment hari ini (scheduled + confirmed + completed)
        // completed juga diikutkan agar dokter bisa melihat riwayat hari ini
        $todayAppointments = Appointment::query()
            ->where('doctor_id', $doctorId)
            ->whereDate('scheduled_at', now()->toDateString())
            ->with([
                'patientEnrollment.hospital',
                'patientEnrollment.user',
                'doctor.specialization',
                'medicalRecord.prescriptions',  // untuk cek apakah sudah ada rekam medis & prescription
                'bill',                          // untuk cek apakah sudah ada tagihan
            ])
            ->orderBy('scheduled_at', 'asc')
            ->get();

        return view('doctor.today', compact('doctorId', 'todayAppointments'));
    }
}