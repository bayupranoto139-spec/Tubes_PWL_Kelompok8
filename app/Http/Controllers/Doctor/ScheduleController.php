<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index()
    {
        $doctorId = 1; // Dr. Budi Santoso

        // Ambil semua data jadwal praktik milik dokter ini
        $schedules = DB::table('schedules')
            ->where('doctor_id', $doctorId)
            ->where('is_active', 1)
            ->orderBy('day_of_week', 'asc')
            ->get();

        // Mapping angka hari ke teks Bahasa Indonesia
        $daysMapping = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];

        return view('doctor.schedule', compact('schedules', 'daysMapping'));
    }
}