<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\PatientEnrollment;
use App\Models\Schedule;
use App\Services\QueueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WalkInController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'patient_enrollment_id' => ['required', 'integer', 'exists:patient_enrollments,id'],
            'doctor_id'             => ['required', 'integer', 'exists:doctors,id'],
            'complaint'             => ['required', 'string', 'max:1000'],
        ]);

        $hospitalId = auth()->user()->hospital_id;

        $enrollment = PatientEnrollment::where('id', $request->patient_enrollment_id)
            ->where('hospital_id', $hospitalId)
            ->firstOrFail();

        $doctor = Doctor::with('user')->findOrFail($request->doctor_id);

        $schedule = Schedule::where('doctor_id', $doctor->id)
            ->where('day_of_week', now()->dayOfWeekIso)
            ->where('is_active', true)
            ->first();

        DB::transaction(function () use ($enrollment, $doctor, $schedule, $request) {
            $appointment = Appointment::create([
                'patient_enrollment_id' => $enrollment->id,
                'doctor_id'             => $doctor->id,
                'schedule_id'           => $schedule?->id,
                'scheduled_at'          => now(),
                'complaint'             => $request->complaint,
                'status'                => 'scheduled',
            ]);

            // Priority 2 = walk-in (eksplisit, tidak bergantung schedule_id)
            QueueService::createForAppointment($appointment, true);
        });

        return redirect()
            ->to('/admin/queues')
            ->with('success', 'Pasien ' . $enrollment->user->name . ' berhasil didaftarkan ke antrian walk-in.');
    }
}