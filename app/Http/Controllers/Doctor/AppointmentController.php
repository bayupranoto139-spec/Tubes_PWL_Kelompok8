<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function today()
    {
        $doctorId = 1; // Dr. Budi Santoso

        // Ambil janji medis hari ini dengan join ke tabel users (pasien) melalui patient_enrollments
        $appointments = DB::table('appointments')
            ->join('patient_enrollments', 'appointments.patient_enrollment_id', '=', 'patient_enrollments.id')
            ->join('users', 'patient_enrollments.user_id', '=', 'users.id')
            ->leftJoin('queues', 'appointments.id', '=', 'queues.appointment_id')
            ->where('appointments.doctor_id', $doctorId)
            ->whereDate('appointments.scheduled_at', '2026-06-01') // Menyesuaikan tanggal di SQL dump
            ->select(
                'appointments.id',
                'appointments.scheduled_at',
                'appointments.status',
                'appointments.complaint',
                'users.name as patient_name',
                'patient_enrollments.medical_record_number',
                'queues.queue_number'
            )
            ->orderBy('queues.queue_number', 'asc')
            ->get();

        return view('doctor.today', compact('appointments'));
    }
}