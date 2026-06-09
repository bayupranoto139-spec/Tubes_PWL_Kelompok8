<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Bill;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\MedicalRecord;
use App\Models\PatientEnrollment;
use App\Models\PatientMedicalInfo;
use App\Models\Prescription;
use App\Models\Schedule;
use App\Services\QueueService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PatientPanelController extends Controller
{
    /**
     * Display patient dashboard overview.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $enrollmentIds = $user->patientEnrollments->pluck('id');

        // Total appointments (all time)
        $totalAppointments = Appointment::whereIn('patient_enrollment_id', $enrollmentIds)->count();

        // Medical records count
        $medicalRecordsCount = MedicalRecord::whereHas('appointment', function ($query) use ($enrollmentIds) {
            $query->whereIn('patient_enrollment_id', $enrollmentIds);
        })->count();

        // Unpaid bills count and total outstanding amount
        $unpaidBillsQuery = Bill::whereIn('patient_enrollment_id', $enrollmentIds)->where('status', 'unpaid');
        $unpaidBillsCount = $unpaidBillsQuery->count();
        $totalUnpaidAmount = $unpaidBillsQuery->sum('total_amount');

        // Active prescriptions count (prescriptions linked to active medical record cases)
        $activePrescriptionsCount = Prescription::whereHas('medicalRecord', function ($query) use ($enrollmentIds) {
            $query->where('case_status', 'active')
                ->whereHas('appointment', function ($subQuery) use ($enrollmentIds) {
                    $subQuery->whereIn('patient_enrollment_id', $enrollmentIds);
                });
        })->count();

        // Upcoming 5 appointments
        $upcomingAppointments = Appointment::whereIn('patient_enrollment_id', $enrollmentIds)
            ->where('scheduled_at', '>=', Carbon::today())
            ->whereIn('status', ['scheduled', 'confirmed'])
            ->with(['doctor.user', 'patientEnrollment.hospital'])
            ->orderBy('scheduled_at', 'asc')
            ->take(5)
            ->get();

        return view('user.patient.dashboard', compact(
            'totalAppointments',
            'medicalRecordsCount',
            'unpaidBillsCount',
            'totalUnpaidAmount',
            'activePrescriptionsCount',
            'upcomingAppointments'
        ));
    }

    public function hospitals()
    {
        $user = auth()->user();

        $joinedHospitals = Hospital::whereHas(
            'patientEnrollments',
            fn ($q) => $q->where('user_id', $user->id)
        )->get();

        $availableHospitals = Hospital::where('is_active', true)
            ->whereNotIn('id', $joinedHospitals->pluck('id'))
            ->get();

        return view(
            'user.patient.hospitals',
            compact(
                'joinedHospitals',
                'availableHospitals'
            )
        );
    }

    public function enrollHospital(Request $request)
    {
        $request->validate([
            'hospital_id' => 'required|exists:hospitals,id',
        ]);

        $user = auth()->user();

        $exists = PatientEnrollment::where(
            'user_id',
            $user->id
        )
            ->where(
                'hospital_id',
                $request->hospital_id
            )
            ->exists();

        if ($exists) {
            return back()->with(
                'error',
                'Anda sudah terdaftar.'
            );
        }

        PatientEnrollment::create([
            'user_id' => $user->id,
            'hospital_id' => $request->hospital_id,
            'medical_record_number' => $this->generateMrn($request->hospital_id),
        ]);

        return back()->with(
            'success',
            'Berhasil mendaftar.'
        );
    }

    private function generateMrn($hospitalId)
    {
        $hospital = Hospital::findOrFail($hospitalId);

        $count = PatientEnrollment::where(
            'hospital_id',
            $hospitalId
        )->count() + 1;

        return strtoupper($hospital->code)
            .'-'
            .str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Display patient's appointments and handles scheduling modal resources.
     */
    public function appointments(Request $request)
    {
        $user = Auth::user();
        $enrollmentIds = $user->patientEnrollments->pluck('id');

        $status = $request->input('status');

        $query = Appointment::whereIn('patient_enrollment_id', $enrollmentIds);

        if ($status && in_array($status, ['scheduled', 'confirmed', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }

        $appointments = $query->with(['doctor.user', 'doctor.specialization', 'schedule', 'patientEnrollment.hospital'])
            ->orderBy('scheduled_at', 'desc')
            ->paginate(10);

        // Fetch ONLY hospitals where the patient is already enrolled
        $hospitals = Hospital::where('is_active', true)
            ->whereHas('patientEnrollments', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->get();

        // Fetch doctors that belong to the patient's enrolled hospitals
        $doctorHospitalIds = $hospitals->pluck('id')->all();

        $doctors = Doctor::with(['user', 'specialization', 'schedules' => function ($query) {
            $query->where('is_active', true);
        }])
            ->whereHas('user', function ($q) use ($doctorHospitalIds) {
                $q->whereIn('hospital_id', $doctorHospitalIds);
            })
            ->whereHas('schedules', function ($q) {
                $q->where('is_active', true);
            })
            ->get();

        // Helpers for frontend schedule+slot filtering
        $scheduleByDoctorAndDate = [];
        $start = Carbon::tomorrow()->startOfDay();
        $end = (clone $start)->addDays(14)->endOfDay();

        // Preload schedules for doctors listed above
        $doctorSchedules = Schedule::query()
            ->whereIn('doctor_id', $doctors->pluck('id')->all())
            ->where('is_active', true)
            ->get();

        foreach ($doctors as $doc) {
            /** @var Collection $docSchedules */
            $docSchedules = $doctorSchedules->where('doctor_id', $doc->id);

            for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                $dow = $d->dayOfWeekIso;
                $schedule = $docSchedules->where('day_of_week', $dow)->first();
                if (! $schedule) {
                    continue;
                }

                // Build 30-minute slot labels within [start_time, end_time)
                $cursor = Carbon::parse($schedule->start_time);
                $slotEnd = Carbon::parse($schedule->end_time);
                $slots = [];

                while ($cursor->lt($slotEnd)) {
                    $slots[] = $cursor->format('H:i');
                    $cursor->addMinutes(30);
                }

                if (! empty($slots)) {
                    $scheduleByDoctorAndDate[$doc->id][$d->format('Y-m-d')] = $slots;
                }
            }
        }

        return view('user.patient.appointments', compact('appointments', 'hospitals', 'doctors', 'status', 'scheduleByDoctorAndDate'));
    }

    /**
     * Book a new appointment and dynamically enroll the patient if not already enrolled.
     */
    public function bookAppointment(Request $request)
    {
        $request->validate([
            'hospital_id' => 'required|exists:hospitals,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_slot' => 'required|date_format:H:i',
            'complaint' => 'required|string|max:1000',
        ]);

        $user = Auth::user();

        // 1) Enforce: hospital must be where the patient is enrolled
        $enrollment = PatientEnrollment::where('user_id', $user->id)
            ->where('hospital_id', $request->hospital_id)
            ->first();

        if (! $enrollment) {
            return redirect()->back()->with('error', 'Pasien tidak terdaftar di rumah sakit yang dipilih.');
        }

        // 2) Enforce: doctor must belong to the selected hospital

        $doctor = Doctor::with('user')->findOrFail($request->doctor_id);
        if ((int) $doctor->user->hospital_id !== (int) $request->hospital_id) {
            return redirect()->back()->with('error', 'Dokter tidak terdaftar di rumah sakit yang dipilih.');
        }

        // 3) Enforce schedule window for selected day and slot
        $appointmentDate = Carbon::parse($request->appointment_date)->startOfDay();
        $dayOfWeek = $appointmentDate->dayOfWeekIso;

        $schedule = Schedule::where('doctor_id', $request->doctor_id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        if (! $schedule) {
            return redirect()->back()->with('error', 'Dokter tidak memiliki jadwal pada tanggal yang dipilih.');
        }

        $slotTime = Carbon::createFromFormat('H:i', $request->appointment_slot);
        $startTime = Carbon::parse($schedule->start_time);
        $endTime = Carbon::parse($schedule->end_time);

        // slot must fit within [start_time, end_time)
        if ($slotTime->lt($startTime) || ! $slotTime->lt($endTime)) {
            return redirect()->back()->with('error', 'Slot waktu tidak sesuai dengan jam jadwal dokter.');
        }

        // scheduled_at final
        $scheduledAt = Carbon::parse($appointmentDate->format('Y-m-d').' '.$request->appointment_slot);

        // Additional safeguard: must be tomorrow+
        if ($scheduledAt->lt(Carbon::tomorrow()->startOfDay())) {
            return redirect()->back()->with('error', 'Jadwal tidak boleh kurang dari besok.');
        }

        // (Optional) quota check
        if ($schedule->remainingQuota($appointmentDate->format('Y-m-d')) <= 0) {
            return redirect()->back()->with('error', 'Kuota jadwal untuk tanggal tersebut sudah penuh.');
        }

        // 4) Create the appointment
        $appointment = Appointment::create([
            'patient_enrollment_id' => $enrollment->id,
            'doctor_id' => $request->doctor_id,
            'schedule_id' => $schedule->id,
            'scheduled_at' => $scheduledAt,
            'complaint' => $request->complaint,
            'status' => 'scheduled',
        ]);

        // 5) Auto-create queue entry (priority 1 = appointment, bukan walk-in)
        QueueService::createForAppointment($appointment);

        return redirect()->route('patient.appointments')->with('success', 'Appointment successfully scheduled!');
    }

    /**
     * Cancel a pending/scheduled appointment.
     */
    public function cancelAppointment(Appointment $appointment)
    {
        $user = Auth::user();
        $enrollmentIds = $user->patientEnrollments->pluck('id')->toArray();

        // Verify ownership
        if (! in_array($appointment->patient_enrollment_id, $enrollmentIds)) {
            abort(403, 'Unauthorized action.');
        }

        // Verify cancel condition
        if ($appointment->status !== 'scheduled') {
            return redirect()->back()->with('error', 'Only scheduled appointments can be cancelled.');
        }

        $appointment->update(['status' => 'cancelled']);

        return redirect()->route('patient.appointments')->with('success', 'Appointment has been cancelled successfully.');
    }

    /**
     * Display medical record history.
     */
    public function medicalRecords()
    {
        $user = Auth::user();
        $enrollmentIds = $user->patientEnrollments->pluck('id');

        $records = MedicalRecord::whereHas('appointment', function ($query) use ($enrollmentIds) {
            $query->whereIn('patient_enrollment_id', $enrollmentIds);
        })
            ->with(['appointment.doctor.user', 'appointment.doctor.specialization', 'appointment.patientEnrollment.hospital', 'prescriptions.medication'])
            ->orderBy('visit_date', 'desc')
            ->get();

        return view('user.patient.medical-records', compact('records'));
    }

    /**
     * Display billing details and invoice payments.
     */
    public function bills()
    {
        $user = Auth::user();
        $enrollmentIds = $user->patientEnrollments->pluck('id');

        // Unpaid Invoices
        $unpaidBills = Bill::whereIn('patient_enrollment_id', $enrollmentIds)
            ->where('status', 'unpaid')
            ->with(['appointment.doctor.user', 'patientEnrollment.hospital', 'billItems'])
            ->orderBy('payment_due_date', 'asc')
            ->get();

        // Paid Invoices
        $paidBills = Bill::whereIn('patient_enrollment_id', $enrollmentIds)
            ->where('status', 'paid')
            ->with(['appointment.doctor.user', 'patientEnrollment.hospital', 'billItems'])
            ->orderBy('payment_date', 'desc')
            ->get();

        // Summary stats
        $totalUnpaidAmount = $unpaidBills->sum('total_amount');
        $unpaidCount = $unpaidBills->count();
        $paidCount = $paidBills->count();

        return view('user.patient.bills', compact('unpaidBills', 'paidBills', 'totalUnpaidAmount', 'unpaidCount', 'paidCount'));
    }

    /**
     * Display prescribed medication list.
     */
    public function prescriptions()
    {
        $user = Auth::user();
        $enrollmentIds = $user->patientEnrollments->pluck('id');

        $prescriptions = Prescription::whereHas('medicalRecord.appointment', function ($query) use ($enrollmentIds) {
            $query->whereIn('patient_enrollment_id', $enrollmentIds);
        })
            ->with(['medicalRecord.appointment.doctor.user', 'medicalRecord.appointment.patientEnrollment.hospital', 'medication'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate custom counts
        $activeCount = $prescriptions->filter(fn ($p) => $p->medicalRecord->case_status === 'active')->count();
        $completedCount = $prescriptions->filter(fn ($p) => $p->medicalRecord->case_status === 'healed')->count();
        $cancelledCount = 0; // The seeders don't support soft deleted or cancelled prescriptions directly, but we can compute from appointment cancelled if needed.

        return view('user.patient.prescriptions', compact('prescriptions', 'activeCount', 'completedCount', 'cancelledCount'));
    }

    /**
     * Display patient's profile details.
     */
    public function profile()
    {
        $user = Auth::user();
        $medicalInfo = $user->patientMedicalInfo ?? new PatientMedicalInfo;

        return view('user.patient.profile', compact('user', 'medicalInfo'));
    }

    /**
     * Update patient's profile details and account password.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'gender' => 'required|in:L,P',
            'date_of_birth' => 'required|date|before:today',
            'blood_type' => 'nullable|in:A,B,AB,O',
            'allergies' => 'nullable|string|max:1000',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'insurance_provider' => 'nullable|string|max:255',
            'insurance_policy_number' => 'nullable|string|max:255',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8',
            'new_password_confirmation' => 'nullable|same:new_password',
        ]);

        // 1. Update primary User details
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
        ]);

        // 2. Update or create PatientMedicalInfo
        PatientMedicalInfo::updateOrCreate(
            ['user_id' => $user->id],
            [
                'blood_type' => $request->blood_type,
                'allergies' => $request->allergies,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'insurance_provider' => $request->insurance_provider,
                'insurance_policy_number' => $request->insurance_policy_number,
            ]
        );

        // 3. Update password if requested
        if ($request->filled('new_password')) {
            if (! Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Current password matches incorrectly.']);
            }

            $user->update([
                'password' => Hash::make($request->new_password),
            ]);
        }

        return redirect()->route('patient.profile')->with('success', 'Profile updated successfully!');
    }
}
