<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Bill;
use App\Models\Prescription;
use App\Models\MedicalRecord;
use App\Models\Queue;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $doctor   = $user?->doctor;
        $doctorId = $doctor?->id;

        if (! $doctorId) {
            // Kirim data kosong agar view tidak error
            return view('doctor.dashboard', [
                'todayQueue'         => 0,
                'waitingCount'       => 0,
                'completedVisits'    => 0,
                'consultationFee'    => 0,
                'totalAllTime'       => 0,
                'prescriptionsToday' => 0,
                'revenueToday'       => 0,
                'monthlyVisits'      => array_fill(0, 12, 0),
                'recentAppointments' => collect(),
                'nextQueue'          => null,
            ]);
        }

        $today = now()->toDateString();

        // Appointment hari ini (semua status kecuali cancelled)
        $todayQueue = Appointment::where('doctor_id', $doctorId)
            ->whereDate('scheduled_at', $today)
            ->whereNotIn('status', ['cancelled'])
            ->count();

        // Menunggu diperiksa hari ini (scheduled)
        $waitingCount = Appointment::where('doctor_id', $doctorId)
            ->whereDate('scheduled_at', $today)
            ->where('status', 'scheduled')
            ->count();

        // Selesai diperiksa hari ini
        $completedVisits = Appointment::where('doctor_id', $doctorId)
            ->whereDate('scheduled_at', $today)
            ->where('status', 'completed')
            ->count();

        // Tarif konsultasi dokter
        $consultationFee = $doctor->consultation_fee ?? 0;

        // Total semua kunjungan (all-time, status completed)
        $totalAllTime = Appointment::where('doctor_id', $doctorId)
            ->where('status', 'completed')
            ->count();

        // Resep yang dikeluarkan hari ini
        // Prescription → MedicalRecord → Appointment (doctor_id + hari ini)
        $prescriptionsToday = Prescription::whereHas('medicalRecord.appointment', function ($q) use ($doctorId, $today) {
            $q->where('doctor_id', $doctorId)
              ->whereDate('scheduled_at', $today);
        })->count();

        // Pendapatan hari ini dari bills yang sudah paid,
        // melalui appointment dokter ini hari ini
        $revenueToday = Bill::whereHas('appointment', function ($q) use ($doctorId, $today) {
            $q->where('doctor_id', $doctorId)
              ->whereDate('scheduled_at', $today);
        })->where('status', 'paid')->sum('total_amount');

        // Kunjungan per bulan tahun ini (untuk chart)
        $monthlyRaw = Appointment::where('doctor_id', $doctorId)
            ->whereYear('scheduled_at', now()->year)
            ->whereNotIn('status', ['cancelled'])
            ->selectRaw('MONTH(scheduled_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthlyVisits = collect(range(1, 12))
            ->map(fn($m) => $monthlyRaw->get($m, 0))
            ->values()
            ->toArray();

        // 5 appointment terbaru hari ini
        $recentAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('scheduled_at', $today)
            ->with(['patientEnrollment.user', 'patientEnrollment.hospital'])
            ->orderBy('scheduled_at', 'asc')
            ->limit(5)
            ->get();

        // Pasien berikutnya di antrian
        $nextQueue = Queue::getNextQueue($doctorId);

        return view('doctor.dashboard', compact(
            'todayQueue',
            'waitingCount',
            'completedVisits',
            'consultationFee',
            'totalAllTime',
            'prescriptionsToday',
            'revenueToday',
            'monthlyVisits',
            'recentAppointments',
            'nextQueue',
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

        $todayAppointments = Appointment::where('doctor_id', $doctorId)
            ->whereDate('scheduled_at', now()->toDateString())
            ->with([
                'patientEnrollment.hospital',
                'patientEnrollment.user',
                'doctor.specialization',
                'medicalRecord.prescriptions',
                'bill',
                'queue',
            ])
            ->orderBy('scheduled_at', 'asc')
            ->get();

        $nextQueue = Queue::getNextQueue($doctorId);

        return view('doctor.today', compact('doctorId', 'todayAppointments', 'nextQueue'));
    }
}