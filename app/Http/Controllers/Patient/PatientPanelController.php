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
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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
        if (! $user) {
            $totalAppointments = 0;
            $medicalRecordsCount = 0;
            $unpaidBillsCount = 0;
            $totalUnpaidAmount = 0;
            $activePrescriptionsCount = 0;
            $upcomingAppointments = collect();

            return view('user.patient.dashboard', compact(
                'totalAppointments',
                'medicalRecordsCount',
                'unpaidBillsCount',
                'totalUnpaidAmount',
                'activePrescriptionsCount',
                'upcomingAppointments'
            ));
        }

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

    /**
     * Display patient's appointments and handles scheduling modal resources.
     */
    public function appointments(Request $request)
    {
        $user = Auth::user();
        $status = $request->input('status');

        if (! $user) {
            $appointments = new LengthAwarePaginator([], 0, 10, 1, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);
            $hospitals = Hospital::where('is_active', true)->get();
            $doctors = Doctor::with(['user', 'specialization', 'schedules' => function ($query) {
                $query->where('is_active', true);
            }])->get();

            return view('user.patient.appointments', compact('appointments', 'hospitals', 'doctors', 'status'));
        }

        $enrollmentIds = $user->patientEnrollments->pluck('id');

        $status = $request->input('status');

        $query = Appointment::whereIn('patient_enrollment_id', $enrollmentIds);

        if ($status && in_array($status, ['scheduled', 'confirmed', 'completed', 'cancelled'])) {
            $query->where('status', $status);
        }

        $appointments = $query->with(['doctor.user', 'doctor.specialization', 'schedule', 'patientEnrollment.hospital'])
            ->orderBy('scheduled_at', 'desc')
            ->paginate(10);

        // Fetch active hospitals and doctors for scheduling a new appointment
        $hospitals = Hospital::where('is_active', true)->get();
        $doctors = Doctor::with(['user', 'specialization', 'schedules' => function ($query) {
            $query->where('is_active', true);
        }])->get();

        return view('user.patient.appointments', compact('appointments', 'hospitals', 'doctors', 'status'));
    }

    /**
     * Book a new appointment and dynamically enroll the patient if not already enrolled.
     */
    public function bookAppointment(Request $request)
    {
        $request->validate([
            'hospital_id'  => 'required|exists:hospitals,id',
            'doctor_id'    => 'required|exists:doctors,id',
            'scheduled_at' => 'required|date|after_or_equal:today',
            'complaint'    => 'required|string|max:1000',
        ]);

        $user = $this->resolvePatientUser();

        // 1. Check or create PatientEnrollment for this hospital
        $enrollment = PatientEnrollment::where('user_id', $user->id)
            ->where('hospital_id', $request->hospital_id)
            ->first();

        if (! $enrollment) {
            $hospital = Hospital::findOrFail($request->hospital_id);
            $year = date('Y');
            
            // Format MRN: RSCODE-YEAR-SEQUENCE
            $seqNumber = PatientEnrollment::where('hospital_id', $hospital->id)->count() + 1;
            $sequence = sprintf('%04d', $seqNumber);
            $cleanName = preg_replace('/[^a-zA-Z]/', '', $hospital->name);
            $codePrefix = strtoupper(substr($cleanName, 0, 4));
            if (empty($codePrefix)) {
                $codePrefix = 'MEDV';
            }
            $mrn = "{$codePrefix}-{$year}-{$sequence}";

            $enrollment = PatientEnrollment::create([
                'user_id'               => $user->id,
                'hospital_id'           => $request->hospital_id,
                'medical_record_number' => $mrn,
            ]);
        }

        // 2. Identify doctor's schedule on that day of week (Carbon dayOfWeekIso: 1 for Monday to 7 for Sunday)
        $scheduledDate = Carbon::parse($request->scheduled_at);
        $dayOfWeek = $scheduledDate->dayOfWeekIso;

        $schedule = Schedule::where('doctor_id', $request->doctor_id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        // 3. Create the appointment
        Appointment::create([
            'patient_enrollment_id' => $enrollment->id,
            'doctor_id'             => $request->doctor_id,
            'schedule_id'           => $schedule ? $schedule->id : null,
            'scheduled_at'          => $scheduledDate,
            'complaint'             => $request->complaint,
            'status'                => 'scheduled',
        ]);

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
        if (! $user) {
            $records = collect();
            return view('user.patient.medical-records', compact('records'));
        }

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
        if (! $user) {
            $unpaidBills = collect();
            $paidBills = collect();
            $totalUnpaidAmount = 0;
            $unpaidCount = 0;
            $paidCount = 0;

            return view('user.patient.bills', compact('unpaidBills', 'paidBills', 'totalUnpaidAmount', 'unpaidCount', 'paidCount'));
        }

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
        if (! $user) {
            $prescriptions = collect();
            $activeCount = 0;
            $completedCount = 0;
            $cancelledCount = 0;

            return view('user.patient.prescriptions', compact('prescriptions', 'activeCount', 'completedCount', 'cancelledCount'));
        }

        $enrollmentIds = $user->patientEnrollments->pluck('id');

        $prescriptions = Prescription::whereHas('medicalRecord.appointment', function ($query) use ($enrollmentIds) {
            $query->whereIn('patient_enrollment_id', $enrollmentIds);
        })
        ->with(['medicalRecord.appointment.doctor.user', 'medicalRecord.appointment.patientEnrollment.hospital', 'medication'])
        ->orderBy('created_at', 'desc')
        ->get();

        // Calculate custom counts
        $activeCount = $prescriptions->filter(fn($p) => $p->medicalRecord->case_status === 'active')->count();
        $completedCount = $prescriptions->filter(fn($p) => $p->medicalRecord->case_status === 'healed')->count();
        $cancelledCount = 0; // The seeders don't support soft deleted or cancelled prescriptions directly, but we can compute from appointment cancelled if needed.

        return view('user.patient.prescriptions', compact('prescriptions', 'activeCount', 'completedCount', 'cancelledCount'));
    }

    /**
     * Display patient's profile details.
     */
    public function profile()
    {
        $user = Auth::user() ?? $this->resolvePatientUser();
        $medicalInfo = $user->patientMedicalInfo ?? new PatientMedicalInfo();

        return view('user.patient.profile', compact('user', 'medicalInfo'));
    }

    /**
     * Update patient's profile details and account password.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user() ?? $this->resolvePatientUser();

        $rules = [
            'name'                     => 'required|string|max:255',
            'email'                    => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone'                    => 'required|string|max:20',
            'address'                  => 'required|string|max:500',
            'gender'                   => 'required|in:L,P',
            'date_of_birth'            => 'required|date|before:today',
            'blood_type'               => 'nullable|in:A,B,AB,O',
            'allergies'                => 'nullable|string|max:1000',
            'emergency_contact_name'   => 'required|string|max:255',
            'emergency_contact_phone'  => 'required|string|max:20',
            'insurance_provider'       => 'nullable|string|max:255',
            'insurance_policy_number'  => 'nullable|string|max:255',
        ];

        if ($request->input('action') === 'change_password') {
            $rules = array_merge($rules, [
                'current_password' => 'required|string',
                'new_password'     => 'required|string|min:8|confirmed',
            ]);
        } else {
            $rules['current_password'] = 'nullable|required_with:new_password|string';
            $rules['new_password'] = 'nullable|min:8|confirmed';
        }

        $request->validate($rules);

        if ($request->input('action') === 'change_password') {
            if (! Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            $user->update(['password' => Hash::make($request->new_password)]);

            return redirect()->route('patient.profile')->with('success', 'Password changed successfully!');
        }

        // 1. Update primary User details
        $user->update([
            'name'          => $request->name,
            'email'         => $request->email,
            'phone'         => $request->phone,
            'address'       => $request->address,
            'gender'        => $request->gender,
            'date_of_birth' => $request->date_of_birth,
        ]);

        // 2. Update or create PatientMedicalInfo
        PatientMedicalInfo::updateOrCreate(
            ['user_id' => $user->id],
            [
                'blood_type'              => $request->blood_type,
                'allergies'               => $request->allergies,
                'emergency_contact_name'  => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
                'insurance_provider'      => $request->insurance_provider,
                'insurance_policy_number' => $request->insurance_policy_number,
            ]
        );

        if ($request->filled('new_password')) {
            if (! Hash::check($request->current_password, $user->password)) {
                return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            $user->update(['password' => Hash::make($request->new_password)]);
        }

        return redirect()->route('patient.profile')->with('success', 'Profile updated successfully!');
    }

    public function manageSessions()
    {
        $user = Auth::user() ?? $this->resolvePatientUser();

        return view('user.patient.manage-sessions', compact('user'));
    }

    public function logoutOtherSessions(Request $request)
    {
        $user = Auth::user() ?? $this->resolvePatientUser();

        $request->validate([
            'current_password' => 'required|string',
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        Auth::login($user);
        Auth::logoutOtherDevices($request->current_password);

        return redirect()->route('patient.profile.sessions')->with('success', 'Other sessions have been logged out successfully.');
    }

    protected function resolvePatientUser()
    {
        if ($user = Auth::user()) {
            return $user;
        }

        return User::firstOrCreate(
            ['email' => 'guest.patient@healthmesh.test'],
            [
                'name'          => 'Guest Patient',
                'password'      => Hash::make('password'),
                'role'          => 'pasien',
                'gender'        => 'L',
                'date_of_birth' => '2000-01-01',
                'phone'         => '081234567890',
                'address'       => 'Jl. Guest No. 1, Medan',
                'is_active'     => 1,
            ]
        );
    }
}
