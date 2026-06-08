<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    /**
     * Show form create medical record untuk appointment tertentu.
     */
    public function create(Appointment $appointment)
    {
        $doctor = auth()->user()?->doctor;

        if (! $doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403);
        }

        // Sudah ada rekam medis → redirect ke today dengan info
        if ($appointment->medicalRecord) {
            return redirect()->route('doctor.today')
                ->with('info', 'Rekam medis untuk appointment ini sudah ada.');
        }

        $appointment->load(['patientEnrollment.user', 'patientEnrollment.hospital']);

        return view('doctor.medical-record.create', compact('appointment'));
    }

    /**
     * Simpan medical record baru.
     */
    public function store(Request $request, Appointment $appointment)
    {
        $doctor = auth()->user()?->doctor;

        if (! $doctor || $appointment->doctor_id !== $doctor->id) {
            abort(403);
        }

        // Idempotent: jika sudah ada, skip store
        if ($appointment->medicalRecord) {
            return redirect()->route('doctor.today')
                ->with('info', 'Rekam medis sudah ada untuk appointment ini.');
        }

        $validated = $request->validate([
            'diagnosis'      => 'required|string|max:1000',
            'treatment_plan' => 'required|string|max:2000',
            'notes'          => 'nullable|string|max:2000',
            'case_status'    => 'required|in:active,healed',
        ]);

        MedicalRecord::create([
            'appointment_id' => $appointment->id,
            'visit_date'     => now(),
            'diagnosis'      => $validated['diagnosis'],
            'treatment_plan' => $validated['treatment_plan'],
            'notes'          => $validated['notes'] ?? null,
            'case_status'    => $validated['case_status'],
        ]);

        return redirect()->route('doctor.today')
            ->with('success', 'Rekam medis berhasil disimpan. Kamu bisa menambahkan resep atau langsung menyelesaikan appointment.');
    }
}