<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PrescriptionController extends Controller
{
    public function index()
    {
        $doctorId = 1; // Dr. Budi Santoso

        // Ambil resep obat dari rekam medis pasien yang ditangani oleh dokter ini
        $prescriptions = DB::table('prescriptions')
            ->join('medications', 'prescriptions.medication_id', '=', 'medications.id')
            ->join('medical_records', 'prescriptions.medical_record_id', '=', 'medical_records.id')
            ->join('appointments', 'medical_records.appointment_id', '=', 'appointments.id')
            ->join('patient_enrollments', 'appointments.patient_enrollment_id', '=', 'patient_enrollments.id')
            ->join('users', 'patient_enrollments.user_id', '=', 'users.id')
            ->where('appointments.doctor_id', $doctorId)
            ->select(
                'users.name as patient_name',
                'medical_records.diagnosis',
                'medical_records.visit_date',
                'medications.name as medication_name',
                'prescriptions.dosage',
                'prescriptions.duration',
                'prescriptions.quantity',
                'prescriptions.notes'
            )
            ->orderBy('medical_records.visit_date', 'desc')
            ->get();

        return view('doctor.prescription', compact('prescriptions'));
    }
}