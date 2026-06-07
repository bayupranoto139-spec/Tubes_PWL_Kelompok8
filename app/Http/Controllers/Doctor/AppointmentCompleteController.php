<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Services\BillGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentCompleteController extends Controller
{
    public function complete(Request $request, $appointmentId)
    {
        $doctorId = auth()->user()?->doctor?->id;
        if (! $doctorId) {
            abort(403);
        }

        $appointmentId = (int) $appointmentId;

        /** @var Appointment $appointment */
        $appointment = Appointment::with([
            'medicalRecord.prescriptions.medication',
            'doctor.user',
            'patientEnrollment',
            'bill',
        ])
            ->where('id', $appointmentId)
            ->where('doctor_id', $doctorId)
            ->firstOrFail();

        // Sudah selesai → skip
        if ($appointment->isCompleted()) {
            return redirect()->route('doctor.today')
                ->with('info', 'Appointment ini sudah selesai.');
        }

        // Wajib ada medical record sebelum bisa complete
        if (! $appointment->medicalRecord) {
            return redirect()->route('doctor.today')
                ->with('error', 'Rekam medis harus diisi terlebih dahulu sebelum menyelesaikan appointment.');
        }

        DB::transaction(function () use ($appointment) {
            // 1. Update status appointment → completed
            $appointment->update([
                'status'     => 'completed',
                'updated_at' => now(),
            ]);

            // 2. Generate bill otomatis (idempotent)
            app(BillGeneratorService::class)->generate($appointment->fresh());
        });

        return redirect()->route('doctor.today')
            ->with('success', 'Appointment selesai. Tagihan pasien telah dibuat otomatis.');
    }
}