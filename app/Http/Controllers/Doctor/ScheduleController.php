<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $doctor = $user->doctor;

        if (! $doctor) {
            abort(403, 'Akun ini tidak terdaftar sebagai dokter.');
        }

        $schedules = $doctor->schedules()
            ->orderBy('day_of_week')
            ->get();

        // Summary stats (semua jadwal, bukan hanya aktif)
        $activeSchedules = $schedules->where('is_active', true);

        $totalMinutes = $activeSchedules->sum(function ($s) {
            [$sh, $sm] = array_map('intval', explode(':', substr($s->start_time, 0, 5)));
            [$eh, $em] = array_map('intval', explode(':', substr($s->end_time,   0, 5)));
            return max(0, ($eh * 60 + $em) - ($sh * 60 + $sm));
        });

        $stats = [
            'active_days'    => $activeSchedules->count(),
            'total_hours'    => round($totalMinutes / 60, 1),
            'weekly_quota'   => $activeSchedules->sum('max_patients'),
        ];

        $daysMapping = [
            1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
            4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu',
        ];

        $sessionMapping = [
            'Pagi'   => ['start' => '00:00', 'end' => '12:00'],
            'Siang'  => ['start' => '12:00', 'end' => '15:00'],
            'Sore'   => ['start' => '15:00', 'end' => '18:00'],
            'Malam'  => ['start' => '18:00', 'end' => '23:59'],
        ];

        return view('doctor.schedule', compact(
            'schedules',
            'stats',
            'daysMapping',
            'sessionMapping',
        ));
    }
}