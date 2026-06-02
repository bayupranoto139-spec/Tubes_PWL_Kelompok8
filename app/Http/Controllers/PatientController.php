<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('user')->get();
        $totalPatients = $patients->count();

        return view('patients.index', compact('patients', 'totalPatients'));
    }

    public function create()
    {
        return view('patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:15',
            'gender' => 'required|in:L,P',
            'blood_type' => 'nullable|string|max:3',
            'allergies' => 'nullable|string',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'insurance_provider' => 'nullable|string|max:255',
            'insurance_policy_number' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'gender' => $validated['gender'],
                'password' => Hash::make('password123'),
                'role' => 'pasien',
                'is_active' => true,
                'hospital_id' => Auth::user()->hospital_id ?? 1,
            ]);

            Patient::create([
                'user_id' => $user->id,
                'hospital_id' => $user->hospital_id,
                'medical_record_number' => 'MRN-' . time(),
                'blood_type' => $validated['blood_type'],
                'allergies' => $validated['allergies'],
                'emergency_contact_name' => $validated['emergency_contact_name'],
                'emergency_contact_phone' => $validated['emergency_contact_phone'],
                'insurance_provider' => $validated['insurance_provider'],
                'insurance_policy_number' => $validated['insurance_policy_number'],
            ]);
        });

        return redirect()->route('patients.index')->with('success', 'Pasien berhasil ditambahkan.');
    }

    public function show($id)
    {
        $patient = Patient::with('user', 'hospital')->findOrFail($id);

        return view('patients.show', compact('patient'));
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        
        DB::transaction(function () use ($patient) {
            $userId = $patient->user_id;
            $patient->delete();
            User::destroy($userId);
        });

        return redirect()->route('patients.index')->with('success', 'Pasien berhasil dihapus.');
    }

    public function edit($id)
{
    $patient = Patient::findOrFail($id);

    return view('patients.edit', compact('patient'));
}

public function update(Request $request, $id)
{
    $patient = Patient::with('user')->findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($patient->user_id)],
        'phone' => 'required|string|max:15',
        'gender' => 'required|in:L,P',
        'blood_type' => 'nullable|string|max:3',
        'allergies' => 'nullable|string',
        'emergency_contact_name' => 'nullable|string|max:255',
        'emergency_contact_phone' => 'nullable|string|max:20',
        'insurance_provider' => 'nullable|string|max:255',
        'insurance_policy_number' => 'nullable|string|max:255',
    ]);

    DB::transaction(function () use ($patient, $validated) {
        $patient->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'gender' => $validated['gender'],
        ]);

        $patient->update([
            'blood_type' => $validated['blood_type'],
            'allergies' => $validated['allergies'],
            'emergency_contact_name' => $validated['emergency_contact_name'],
            'emergency_contact_phone' => $validated['emergency_contact_phone'],
            'insurance_provider' => $validated['insurance_provider'],
            'insurance_policy_number' => $validated['insurance_policy_number'],
        ]);
    });

    return redirect()->route('patients.index')->with('success', 'Data pasien berhasil diperbarui.');
}
}