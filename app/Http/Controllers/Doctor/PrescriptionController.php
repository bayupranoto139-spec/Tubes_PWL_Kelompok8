<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class PrescriptionController extends Controller
{
    public function index()
    {
        $doctorId = auth()->user()?->doctor?->id;
        if (! $doctorId) {
            abort(403);
        }

        $today = now()->toDateString();

        $appointments = DB::table('appointments')
            ->join('patient_enrollments', 'appointments.patient_enrollment_id', '=', 'patient_enrollments.id')
            ->join('users', 'patient_enrollments.user_id', '=', 'users.id')
            ->leftJoin('medical_records', 'medical_records.appointment_id', '=', 'appointments.id')
            ->where('appointments.doctor_id', $doctorId)
            ->whereDate('appointments.scheduled_at', $today)
            ->where('appointments.status', 'scheduled')
            ->whereNull('medical_records.id')
            ->select([
                'appointments.id as appointment_id',
                'users.name as patient_name',
            ])
            ->orderBy('appointments.scheduled_at', 'asc')
            ->get();

        $medications = DB::table('medications')
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        $q = DB::table('prescriptions')
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
            );

        $search = request()->query('search');
        if ($search) {
            $q->where(function ($sub) use ($search) {
                $sub->where('users.name', 'like', "%{$search}%")
                    ->orWhere('medications.name', 'like', "%{$search}%")
                    ->orWhere('medical_records.diagnosis', 'like', "%{$search}%");
            });
        }

        $filterMedicationId = request()->query('medication_id');
        if ($filterMedicationId) {
            $q->where('prescriptions.medication_id', $filterMedicationId);
        }

        $filterStatus = request()->query('status');
        if ($filterStatus) {

        }

        $perPage = 5;
        $prescriptions = $q->orderBy('medical_records.visit_date', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        return view('doctor.prescription', compact('prescriptions', 'appointments', 'medications', 'search', 'filterMedicationId'));
    }
}
