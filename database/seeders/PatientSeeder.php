<?php

namespace Database\Seeders;

use App\Models\Patient; 
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder 
{
    public function run(): void
    {
        Patient::create([
            'user_id' => 1, 
            'hospital_id' => 1,
            'medical_record_number' => 'MRN-2026-0001',
            'blood_type' => 'O',
            'allergies' => 'Seafood',
            'emergency_contact_name' => 'Budi',
            'emergency_contact_phone' => '08123456789',
            'insurance_provider' => 'BPJS',
            'insurance_policy_number' => '12345678',
        ]);
    }
}