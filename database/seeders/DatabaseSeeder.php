<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    private array $billIds = [];

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $this->truncateAll();

        $this->seedHospitals();
        $this->seedSpecializations();
        $this->seedMedications();
        $this->seedUsers();
        $this->seedPatientMedicalInfos();
        $this->seedPatientEnrollments();
        $this->seedDoctors();
        $this->seedStaff();
        $this->seedSchedules();
        $this->seedAppointments();
        $this->seedQueues();
        $this->seedMedicalRecords();
        $this->seedPrescriptions();
        $this->seedBills();
        $this->seedBillItems();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('✅ Seeding selesai.');
    }

   
    private function truncateAll(): void
    {
        $tables = [
            'bill_items', 'bills', 'prescriptions', 'medical_records',
            'queues', 'appointments', 'schedules', 'staff', 'doctors',
            'patient_enrollments', 'patient_medical_infos', 'users',
            'medications', 'specializations', 'hospitals',
        ];

        foreach ($tables as $table) {
            DB::statement("TRUNCATE TABLE `{$table}`");
        }
    }

   
    private function seedHospitals(): void
    {
        DB::table('hospitals')->insert([
            [
                'id'         => 1,
                'name'       => 'RS Umum Sehat Sejahtera',
                'code'       => 'RSSS-001',
                'city'       => 'Medan',
                'address'    => 'Jl. Diponegoro No. 12, Medan Baru, Kota Medan',
                'logo'       => null,
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 2,
                'name'       => 'RS Harapan Bunda',
                'code'       => 'RSHB-002',
                'city'       => 'Medan',
                'address'    => 'Jl. Gatot Subroto No. 45, Medan Petisah, Kota Medan',
                'logo'       => null,
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

   
    private function seedSpecializations(): void
    {
        $specs = [
            ['id' => 1, 'name' => 'Dokter Umum',              'description' => 'Pelayanan kesehatan umum dan pemeriksaan dasar'],
            ['id' => 2, 'name' => 'Spesialis Jantung',        'description' => 'Diagnosis dan pengobatan penyakit jantung dan pembuluh darah'],
            ['id' => 3, 'name' => 'Spesialis Anak',           'description' => 'Pelayanan kesehatan bayi, anak, dan remaja'],
            ['id' => 4, 'name' => 'Spesialis Penyakit Dalam', 'description' => 'Penanganan penyakit organ dalam'],
            ['id' => 5, 'name' => 'Spesialis Kulit',          'description' => 'Diagnosis dan pengobatan penyakit kulit dan kelamin'],
            ['id' => 6, 'name' => 'Spesialis Mata',           'description' => 'Diagnosis dan pengobatan penyakit mata'],
        ];

        foreach ($specs as $s) {
            DB::table('specializations')->insert(array_merge($s, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

   
    private function seedMedications(): void
    {
        $meds = [
            ['id' => 1,  'name' => 'Paracetamol 500mg',   'generic_name' => 'Paracetamol',     'category' => 'Analgesik',      'unit' => 'tablet', 'price' => 500],
            ['id' => 2,  'name' => 'Amoxicillin 500mg',   'generic_name' => 'Amoxicillin',     'category' => 'Antibiotik',     'unit' => 'kapsul', 'price' => 2500],
            ['id' => 3,  'name' => 'Omeprazole 20mg',     'generic_name' => 'Omeprazole',      'category' => 'Antasida',       'unit' => 'kapsul', 'price' => 3000],
            ['id' => 4,  'name' => 'Amlodipine 5mg',      'generic_name' => 'Amlodipine',      'category' => 'Antihipertensi', 'unit' => 'tablet', 'price' => 1500],
            ['id' => 5,  'name' => 'Metformin 500mg',     'generic_name' => 'Metformin',       'category' => 'Antidiabetik',   'unit' => 'tablet', 'price' => 1000],
            ['id' => 6,  'name' => 'Cetirizine 10mg',     'generic_name' => 'Cetirizine',      'category' => 'Antihistamin',   'unit' => 'tablet', 'price' => 2000],
            ['id' => 7,  'name' => 'Vitamin C 500mg',     'generic_name' => 'Ascorbic Acid',   'category' => 'Vitamin',        'unit' => 'tablet', 'price' => 500],
            ['id' => 8,  'name' => 'Antasida Doen',       'generic_name' => 'Al/Mg Hydroxide', 'category' => 'Antasida',       'unit' => 'tablet', 'price' => 800],
            ['id' => 9,  'name' => 'Salbutamol 4mg',      'generic_name' => 'Salbutamol',      'category' => 'Bronkodilator',  'unit' => 'tablet', 'price' => 1200],
            ['id' => 10, 'name' => 'Dexamethasone 0.5mg', 'generic_name' => 'Dexamethasone',   'category' => 'Kortikosteroid', 'unit' => 'tablet', 'price' => 600],
            ['id' => 11, 'name' => 'Lain-lain',           'generic_name' => null,              'category' => null,             'unit' => 'unit',   'price' => 0],
        ];

        foreach ($meds as $m) {
            DB::table('medications')->insert(array_merge($m, [
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

   
    private function seedUsers(): void
    {
        $users = [
            ['id' => 1,  'hospital_id' => null, 'name' => 'Super Administrator', 'email' => 'superadmin@healthmesh.id',  'role' => 'super_admin', 'gender' => 'L', 'date_of_birth' => '1985-01-01', 'phone' => '081234567890'],
            ['id' => 2,  'hospital_id' => 1,    'name' => 'Rina Sari',           'email' => 'admin@rsss.id',             'role' => 'admin_rs',    'gender' => 'P', 'date_of_birth' => '1990-03-15', 'phone' => '082345678901'],
            ['id' => 3,  'hospital_id' => 2,    'name' => 'Dedi Kurniawan',      'email' => 'admin@rshb.id',             'role' => 'admin_rs',    'gender' => 'L', 'date_of_birth' => '1988-07-22', 'phone' => '083456789012'],
            ['id' => 4,  'hospital_id' => 1,    'name' => 'dr. Budi Santoso',    'email' => 'budi.santoso@rsss.id',      'role' => 'dokter',      'gender' => 'L', 'date_of_birth' => '1978-05-10', 'phone' => '084567890123'],
            ['id' => 5,  'hospital_id' => 1,    'name' => 'dr. Sari Indah',      'email' => 'sari.indah@rsss.id',        'role' => 'dokter',      'gender' => 'P', 'date_of_birth' => '1982-11-20', 'phone' => '085678901234'],
            ['id' => 6,  'hospital_id' => 2,    'name' => 'dr. Ahmad Fauzi',     'email' => 'ahmad.fauzi@rshb.id',       'role' => 'dokter',      'gender' => 'L', 'date_of_birth' => '1975-08-30', 'phone' => '086789012345'],
            ['id' => 7,  'hospital_id' => 1,    'name' => 'Maya Putri',          'email' => 'maya@rsss.id',              'role' => 'staff',       'gender' => 'P', 'date_of_birth' => '1995-04-12', 'phone' => '087890123456'],
            ['id' => 8,  'hospital_id' => 1,    'name' => 'Hendra Wijaya',       'email' => 'hendra@rsss.id',            'role' => 'staff',       'gender' => 'L', 'date_of_birth' => '1993-09-05', 'phone' => '088901234567'],
            ['id' => 9,  'hospital_id' => 2,    'name' => 'Lena Susanti',        'email' => 'lena@rshb.id',              'role' => 'staff',       'gender' => 'P', 'date_of_birth' => '1996-02-18', 'phone' => '089012345678'],
            ['id' => 10, 'hospital_id' => null, 'name' => 'Agus Setiawan',       'email' => 'agus.setiawan@gmail.com',   'role' => 'pasien',      'gender' => 'L', 'date_of_birth' => '1988-06-25', 'phone' => '081122334455'],
            ['id' => 11, 'hospital_id' => null, 'name' => 'Dewi Rahayu',         'email' => 'dewi.rahayu@gmail.com',     'role' => 'pasien',      'gender' => 'P', 'date_of_birth' => '1992-12-03', 'phone' => '082233445566'],
            ['id' => 12, 'hospital_id' => null, 'name' => 'Farhan Ramadhan',     'email' => 'farhan.ramadhan@gmail.com', 'role' => 'pasien',      'gender' => 'L', 'date_of_birth' => '2000-07-14', 'phone' => '083344556677'],
            ['id' => 13, 'hospital_id' => null, 'name' => 'Siti Nuraini',        'email' => 'siti.nuraini@gmail.com',    'role' => 'pasien',      'gender' => 'P', 'date_of_birth' => '1975-03-28', 'phone' => '084455667788'],
        ];

        foreach ($users as $u) {
            DB::table('users')->insert(array_merge($u, [
                'password'   => Hash::make('password'),
                'address'    => 'Jl. Contoh No. ' . $u['id'] . ', Medan',
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

   
    private function seedPatientMedicalInfos(): void
    {
        $infos = [
            ['user_id' => 10, 'blood_type' => 'A',  'allergies' => 'Penisilin',      'emergency_contact_name' => 'Siti Setiawan',  'emergency_contact_phone' => '081298765432', 'insurance_provider' => 'BPJS Kesehatan',   'insurance_policy_number' => 'BPJS-00123456'],
            ['user_id' => 11, 'blood_type' => 'O',  'allergies' => null,             'emergency_contact_name' => 'Budi Rahayu',    'emergency_contact_phone' => '082198765432', 'insurance_provider' => 'BPJS Kesehatan',   'insurance_policy_number' => 'BPJS-00234567'],
            ['user_id' => 12, 'blood_type' => 'B',  'allergies' => 'Sulfa, Aspirin', 'emergency_contact_name' => 'Rina Ramadhan',  'emergency_contact_phone' => '083198765432', 'insurance_provider' => null,               'insurance_policy_number' => null],
            ['user_id' => 13, 'blood_type' => 'AB', 'allergies' => null,             'emergency_contact_name' => 'Hasan Nuraini',  'emergency_contact_phone' => '084198765432', 'insurance_provider' => 'Asuransi Mandiri', 'insurance_policy_number' => 'AM-9988776655'],
        ];

        foreach ($infos as $info) {
            DB::table('patient_medical_infos')->insert(array_merge($info, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

   
    private function seedPatientEnrollments(): void
    {
        $enrollments = [
            ['id' => 1, 'user_id' => 10, 'hospital_id' => 1, 'medical_record_number' => 'RSSS-2024-0001'],
            ['id' => 2, 'user_id' => 11, 'hospital_id' => 1, 'medical_record_number' => 'RSSS-2024-0002'],
            ['id' => 3, 'user_id' => 12, 'hospital_id' => 1, 'medical_record_number' => 'RSSS-2024-0003'],
            ['id' => 4, 'user_id' => 13, 'hospital_id' => 1, 'medical_record_number' => 'RSSS-2024-0004'],
            ['id' => 5, 'user_id' => 10, 'hospital_id' => 2, 'medical_record_number' => 'RSHB-2024-0001'], // pasien 10 di RS 2
            ['id' => 6, 'user_id' => 11, 'hospital_id' => 2, 'medical_record_number' => 'RSHB-2024-0002'],
        ];

        foreach ($enrollments as $e) {
            DB::table('patient_enrollments')->insert(array_merge($e, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

   
    private function seedDoctors(): void
    {
        $doctors = [
            ['id' => 1, 'user_id' => 4, 'specialization_id' => 1, 'licence_number' => 'SIP-001/2020/IDI', 'consultation_fee' => 150000, 'years_of_experience' => 10],
            ['id' => 2, 'user_id' => 5, 'specialization_id' => 2, 'licence_number' => 'SIP-002/2018/IDI', 'consultation_fee' => 350000, 'years_of_experience' => 12],
            ['id' => 3, 'user_id' => 6, 'specialization_id' => 3, 'licence_number' => 'SIP-003/2015/IDI', 'consultation_fee' => 300000, 'years_of_experience' => 15],
        ];

        foreach ($doctors as $d) {
            DB::table('doctors')->insert(array_merge($d, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

   
    private function seedStaff(): void
    {
        $staffs = [
            ['user_id' => 2, 'position' => 'Kepala Administrasi', 'department' => 'Administrasi'],
            ['user_id' => 3, 'position' => 'Kepala Administrasi', 'department' => 'Administrasi'],
            ['user_id' => 7, 'position' => 'Resepsionis',         'department' => 'Front Office'],
            ['user_id' => 8, 'position' => 'Kasir',               'department' => 'Keuangan'],
            ['user_id' => 9, 'position' => 'Resepsionis',         'department' => 'Front Office'],
        ];

        foreach ($staffs as $s) {
            DB::table('staff')->insert(array_merge($s, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

   
    private function seedSchedules(): void
    {
        $schedules = [
            // dr. Budi (dokter umum, RS 1) — Senin, Rabu, Jumat pagi
            ['id' => 1, 'doctor_id' => 1, 'day_of_week' => 1, 'start_time' => '08:00', 'end_time' => '12:00', 'max_patients' => 20],
            ['id' => 2, 'doctor_id' => 1, 'day_of_week' => 3, 'start_time' => '08:00', 'end_time' => '12:00', 'max_patients' => 20],
            ['id' => 3, 'doctor_id' => 1, 'day_of_week' => 5, 'start_time' => '08:00', 'end_time' => '11:00', 'max_patients' => 15],
            // dr. Sari (jantung, RS 1) — Selasa dan Kamis
            ['id' => 4, 'doctor_id' => 2, 'day_of_week' => 2, 'start_time' => '09:00', 'end_time' => '13:00', 'max_patients' => 10],
            ['id' => 5, 'doctor_id' => 2, 'day_of_week' => 4, 'start_time' => '14:00', 'end_time' => '17:00', 'max_patients' => 8],
            // dr. Ahmad (anak, RS 2)
            ['id' => 6, 'doctor_id' => 3, 'day_of_week' => 1, 'start_time' => '13:00', 'end_time' => '17:00', 'max_patients' => 15],
            ['id' => 7, 'doctor_id' => 3, 'day_of_week' => 3, 'start_time' => '08:00', 'end_time' => '12:00', 'max_patients' => 15],
        ];

        foreach ($schedules as $s) {
            DB::table('schedules')->insert(array_merge($s, [
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

   
    private function seedAppointments(): void
    {
        $appointments = [
            // Kemarin — completed
            ['id' => 1,  'patient_enrollment_id' => 1, 'doctor_id' => 1, 'schedule_id' => 1,    'scheduled_at' => Carbon::yesterday()->setTime(9, 0),   'status' => 'completed', 'complaint' => 'Demam dan sakit kepala sejak 2 hari yang lalu'],
            ['id' => 2,  'patient_enrollment_id' => 2, 'doctor_id' => 1, 'schedule_id' => 1,    'scheduled_at' => Carbon::yesterday()->setTime(9, 30),  'status' => 'completed', 'complaint' => 'Batuk berdahak sudah seminggu'],
            ['id' => 3,  'patient_enrollment_id' => 3, 'doctor_id' => 2, 'schedule_id' => 4,    'scheduled_at' => Carbon::yesterday()->setTime(10, 0),  'status' => 'completed', 'complaint' => 'Nyeri dada dan sesak napas'],
            // Hari ini — scheduled
            ['id' => 4,  'patient_enrollment_id' => 1, 'doctor_id' => 1, 'schedule_id' => 2,    'scheduled_at' => Carbon::today()->setTime(8, 0),       'status' => 'scheduled', 'complaint' => 'Kontrol rutin tekanan darah'],
            ['id' => 5,  'patient_enrollment_id' => 4, 'doctor_id' => 1, 'schedule_id' => 2,    'scheduled_at' => Carbon::today()->setTime(8, 30),      'status' => 'scheduled', 'complaint' => 'Gatal-gatal di kulit lengan'],
            ['id' => 6,  'patient_enrollment_id' => 2, 'doctor_id' => 2, 'schedule_id' => 4,    'scheduled_at' => Carbon::today()->setTime(9, 0),       'status' => 'scheduled', 'complaint' => 'Detak jantung tidak teratur'],
            // Walk-in hari ini (schedule_id = null)
            ['id' => 7,  'patient_enrollment_id' => 3, 'doctor_id' => 1, 'schedule_id' => null, 'scheduled_at' => Carbon::today()->setTime(10, 15),     'status' => 'completed', 'complaint' => 'Walk-in: pusing mendadak'],
            // Cancelled & no_show
            ['id' => 8,  'patient_enrollment_id' => 4, 'doctor_id' => 2, 'schedule_id' => 5,    'scheduled_at' => Carbon::yesterday()->setTime(14, 0),  'status' => 'cancelled', 'complaint' => 'Kontrol jantung'],
            ['id' => 9,  'patient_enrollment_id' => 1, 'doctor_id' => 1, 'schedule_id' => 3,    'scheduled_at' => Carbon::yesterday()->setTime(8, 30),  'status' => 'no_show',   'complaint' => 'Sakit perut'],
            // RS 2
            ['id' => 10, 'patient_enrollment_id' => 5, 'doctor_id' => 3, 'schedule_id' => 6,    'scheduled_at' => Carbon::yesterday()->setTime(13, 0),  'status' => 'completed', 'complaint' => 'Anak demam tinggi 39 derajat'],
        ];

        foreach ($appointments as $a) {
            DB::table('appointments')->insert(array_merge($a, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

   
    private function seedQueues(): void
    {
        $yesterday = Carbon::yesterday()->toDateString();
        $today     = Carbon::today()->toDateString();

        $queues = [
            // Kemarin
            ['appointment_id' => 1,  'queue_date' => $yesterday, 'queue_number' => 1, 'type' => 'appointment', 'priority' => 1, 'status' => 'completed', 'called_at' => Carbon::yesterday()->setTime(9, 5),   'started_at' => Carbon::yesterday()->setTime(9, 8),   'completed_at' => Carbon::yesterday()->setTime(9, 25)],
            ['appointment_id' => 2,  'queue_date' => $yesterday, 'queue_number' => 2, 'type' => 'appointment', 'priority' => 1, 'status' => 'completed', 'called_at' => Carbon::yesterday()->setTime(9, 30),  'started_at' => Carbon::yesterday()->setTime(9, 33),  'completed_at' => Carbon::yesterday()->setTime(9, 50)],
            ['appointment_id' => 3,  'queue_date' => $yesterday, 'queue_number' => 1, 'type' => 'appointment', 'priority' => 1, 'status' => 'completed', 'called_at' => Carbon::yesterday()->setTime(10, 5),  'started_at' => Carbon::yesterday()->setTime(10, 8),  'completed_at' => Carbon::yesterday()->setTime(10, 35)],
            ['appointment_id' => 9,  'queue_date' => $yesterday, 'queue_number' => 3, 'type' => 'appointment', 'priority' => 1, 'status' => 'skipped',   'called_at' => null,                                 'started_at' => null,                                 'completed_at' => null],
            // Hari ini
            ['appointment_id' => 4,  'queue_date' => $today,     'queue_number' => 1, 'type' => 'appointment', 'priority' => 1, 'status' => 'waiting',   'called_at' => null,                                 'started_at' => null,                                 'completed_at' => null],
            ['appointment_id' => 5,  'queue_date' => $today,     'queue_number' => 2, 'type' => 'appointment', 'priority' => 1, 'status' => 'waiting',   'called_at' => null,                                 'started_at' => null,                                 'completed_at' => null],
            ['appointment_id' => 6,  'queue_date' => $today,     'queue_number' => 1, 'type' => 'appointment', 'priority' => 1, 'status' => 'waiting',   'called_at' => null,                                 'started_at' => null,                                 'completed_at' => null],
            ['appointment_id' => 7,  'queue_date' => $today,     'queue_number' => 3, 'type' => 'walk_in',     'priority' => 2, 'status' => 'completed', 'called_at' => Carbon::today()->setTime(10, 20),     'started_at' => Carbon::today()->setTime(10, 23),     'completed_at' => Carbon::today()->setTime(10, 45)],
            // RS 2
            ['appointment_id' => 10, 'queue_date' => $yesterday, 'queue_number' => 1, 'type' => 'appointment', 'priority' => 1, 'status' => 'completed', 'called_at' => Carbon::yesterday()->setTime(13, 10), 'started_at' => Carbon::yesterday()->setTime(13, 12), 'completed_at' => Carbon::yesterday()->setTime(13, 30)],
        ];

        foreach ($queues as $q) {
            DB::table('queues')->insert(array_merge($q, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }


    private function seedMedicalRecords(): void
    {
        $records = [
            ['id' => 1, 'appointment_id' => 1,  'visit_date' => Carbon::yesterday()->setTime(9, 8),   'diagnosis' => 'Demam tifoid ringan',              'treatment_plan' => 'Pemberian antibiotik, istirahat cukup, minum banyak air putih',                  'notes' => 'Pasien disarankan kontrol kembali dalam 5 hari', 'case_status' => 'active'],
            ['id' => 2, 'appointment_id' => 2,  'visit_date' => Carbon::yesterday()->setTime(9, 33),  'diagnosis' => 'Bronkitis akut',                   'treatment_plan' => 'Pemberian mukolitik dan ekspektoran, hindari rokok dan polusi',                  'notes' => 'Jika tidak membaik dalam 3 hari, rontgen thorax', 'case_status' => 'active'],
            ['id' => 3, 'appointment_id' => 3,  'visit_date' => Carbon::yesterday()->setTime(10, 8),  'diagnosis' => 'Angina pektoris stabil',           'treatment_plan' => 'Pemberian nitrat sublingual, beta blocker, dan modifikasi gaya hidup',          'notes' => 'Jadwalkan stress test dan echocardiography',     'case_status' => 'active'],
            ['id' => 4, 'appointment_id' => 7,  'visit_date' => Carbon::today()->setTime(10, 23),     'diagnosis' => 'Vertigo perifer (BPPV)',            'treatment_plan' => 'Manuver Epley, betahistin jika perlu',                                          'notes' => 'Hindari perubahan posisi mendadak',              'case_status' => 'healed'],
            ['id' => 5, 'appointment_id' => 10, 'visit_date' => Carbon::yesterday()->setTime(13, 12), 'diagnosis' => 'Febris e.c. infeksi virus (common cold)', 'treatment_plan' => 'Antipiretik, perbanyak minum dan istirahat',                            'notes' => 'Kompres hangat jika demam di atas 38.5°C',       'case_status' => 'healed'],
        ];

        foreach ($records as $r) {
            DB::table('medical_records')->insert(array_merge($r, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }


    private function seedPrescriptions(): void
    {
        $prescriptions = [
            // Medical record 1 — demam tifoid
            ['medical_record_id' => 1, 'medication_id' => 2, 'dosage' => '3x1',   'duration' => '7 hari',  'quantity' => 21, 'notes' => 'Habiskan antibiotik'],
            ['medical_record_id' => 1, 'medication_id' => 1, 'dosage' => '3x1',   'duration' => '5 hari',  'quantity' => 15, 'notes' => 'Minum jika demam di atas 38°C'],
            ['medical_record_id' => 1, 'medication_id' => 7, 'dosage' => '1x1',   'duration' => '10 hari', 'quantity' => 10, 'notes' => null],
            // Medical record 2 — bronkitis
            ['medical_record_id' => 2, 'medication_id' => 9, 'dosage' => '3x1',   'duration' => '5 hari',  'quantity' => 15, 'notes' => 'Minum setelah makan'],
            ['medical_record_id' => 2, 'medication_id' => 1, 'dosage' => '3x1',   'duration' => '3 hari',  'quantity' => 9,  'notes' => 'Jika perlu saja'],
            // Medical record 3 — angina
            ['medical_record_id' => 3, 'medication_id' => 4, 'dosage' => '1x1',   'duration' => '30 hari', 'quantity' => 30, 'notes' => 'Minum pagi hari'],
            ['medical_record_id' => 3, 'medication_id' => 3, 'dosage' => '1x1',   'duration' => '14 hari', 'quantity' => 14, 'notes' => 'Minum 30 menit sebelum makan'],
            // Medical record 4 — vertigo
            ['medical_record_id' => 4, 'medication_id' => 6, 'dosage' => '1x1',   'duration' => '3 hari',  'quantity' => 3,  'notes' => 'Minum malam sebelum tidur'],
            // Medical record 5 — demam anak
            ['medical_record_id' => 5, 'medication_id' => 1, 'dosage' => '4x0.5', 'duration' => '3 hari',  'quantity' => 6,  'notes' => 'Dosis anak disesuaikan berat badan 15kg'],
            ['medical_record_id' => 5, 'medication_id' => 7, 'dosage' => '1x1',   'duration' => '5 hari',  'quantity' => 5,  'notes' => null],
        ];

        foreach ($prescriptions as $p) {
            DB::table('prescriptions')->insert(array_merge($p, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }


    private function seedBills(): void
    {
        $bills = [
            // [0] — demam tifoid, RS 1, BPJS (insurance)
            [
                'patient_enrollment_id' => 1,
                'appointment_id'        => 1,
                'total_amount'          => 0,
                'status'                => 'paid',
                'payment_due_date'      => Carbon::yesterday()->toDateString(),
                'payment_method'        => 'insurance',
                'payment_date'          => Carbon::yesterday()->setTime(10, 0),
                'reference_number'      => null,
            ],
            // [1] — bronkitis, RS 1, cash
            [
                'patient_enrollment_id' => 2,
                'appointment_id'        => 2,
                'total_amount'          => 0,
                'status'                => 'paid',
                'payment_due_date'      => Carbon::yesterday()->toDateString(),
                'payment_method'        => 'cash',
                'payment_date'          => Carbon::yesterday()->setTime(10, 30),
                'reference_number'      => null,
            ],
            // [2] — angina, RS 1, bank_transfer
            [
                'patient_enrollment_id' => 3,
                'appointment_id'        => 3,
                'total_amount'          => 0,
                'status'                => 'paid',
                'payment_due_date'      => Carbon::yesterday()->toDateString(),
                'payment_method'        => 'bank_transfer',
                'payment_date'          => Carbon::yesterday()->setTime(11, 15),
                'reference_number'      => 'TRF-' . Carbon::yesterday()->format('Ymd') . '-001',
            ],
            // [3] — walk-in vertigo, RS 1, qris
            [
                'patient_enrollment_id' => 3,
                'appointment_id'        => 7,
                'total_amount'          => 0,
                'status'                => 'paid',
                'payment_due_date'      => Carbon::today()->toDateString(),
                'payment_method'        => 'qris',
                'payment_date'          => Carbon::today()->setTime(11, 0),
                'reference_number'      => 'QRIS-' . Carbon::today()->format('Ymd') . '-001',
            ],
            // [4] — kontrol tekanan darah, RS 1, belum lunas
            [
                'patient_enrollment_id' => 1,
                'appointment_id'        => 4,
                'total_amount'          => 0,
                'status'                => 'unpaid',
                'payment_due_date'      => Carbon::today()->addDays(3)->toDateString(),
                'payment_method'        => null,
                'payment_date'          => null,
                'reference_number'      => null,
            ],
            // [5] — demam anak, RS 2, asuransi swasta
            [
                'patient_enrollment_id' => 5,
                'appointment_id'        => 10,
                'total_amount'          => 0,
                'status'                => 'paid',
                'payment_due_date'      => Carbon::yesterday()->toDateString(),
                'payment_method'        => 'insurance',
                'payment_date'          => Carbon::yesterday()->setTime(14, 0),
                'reference_number'      => 'INS-AM-' . Carbon::yesterday()->format('Ymd') . '-001',
            ],
        ];

        foreach ($bills as $b) {
            $this->billIds[] = DB::table('bills')->insertGetId(array_merge($b, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }


    private function seedBillItems(): void
    {
        [$b0, $b1, $b2, $b3, $b4, $b5] = $this->billIds;

        $items = [
            // Bill 0 — demam tifoid
            [$b0, 'consultation',  'Biaya Konsultasi Dokter Umum',      1,  150000],
            [$b0, 'medication',    'Amoxicillin 500mg (21 kapsul)',     21,    2500],
            [$b0, 'medication',    'Paracetamol 500mg (15 tablet)',     15,     500],
            [$b0, 'medication',    'Vitamin C 500mg (10 tablet)',       10,     500],
            [$b0, 'administration','Biaya Administrasi',                 1,   15000],

            // Bill 1 — bronkitis
            [$b1, 'consultation',  'Biaya Konsultasi Dokter Umum',      1,  150000],
            [$b1, 'medication',    'Salbutamol 4mg (15 tablet)',        15,    1200],
            [$b1, 'medication',    'Paracetamol 500mg (9 tablet)',       9,     500],
            [$b1, 'administration','Biaya Administrasi',                 1,   15000],

            // Bill 2 — angina pektoris
            [$b2, 'consultation',  'Biaya Konsultasi Spesialis Jantung', 1, 350000],
            [$b2, 'medication',    'Amlodipine 5mg (30 tablet)',        30,    1500],
            [$b2, 'medication',    'Omeprazole 20mg (14 kapsul)',       14,    3000],
            [$b2, 'procedure',     'Biaya EKG',                          1,  150000],
            [$b2, 'administration','Biaya Administrasi',                  1,  25000],

            // Bill 3 — walk-in vertigo
            [$b3, 'consultation',  'Biaya Konsultasi Dokter Umum',      1,  150000],
            [$b3, 'medication',    'Cetirizine 10mg (3 tablet)',         3,    2000],
            [$b3, 'administration','Biaya Administrasi',                  1,  15000],

            // Bill 4 — kontrol tekanan darah (belum lunas)
            [$b4, 'consultation',  'Biaya Konsultasi Dokter Umum',      1,  150000],
            [$b4, 'administration','Biaya Administrasi',                  1,  15000],

            // Bill 5 — demam anak RS 2
            [$b5, 'consultation',  'Biaya Konsultasi Spesialis Anak',   1,  300000],
            [$b5, 'medication',    'Paracetamol 500mg (6 tablet)',       6,     500],
            [$b5, 'medication',    'Vitamin C 500mg (5 tablet)',         5,     500],
            [$b5, 'administration','Biaya Administrasi',                  1,  25000],
        ];

        foreach ($items as [$billId, $type, $desc, $qty, $price]) {
            DB::table('bill_items')->insert([
                'bill_id'     => $billId,
                'item_type'   => $type,
                'description' => $desc,
                'quantity'    => $qty,
                'unit_price'  => $price,
                'subtotal'    => $qty * $price,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        // Sync bills.total_amount dari SUM(bill_items.subtotal)
        DB::statement("
            UPDATE bills b
            SET b.total_amount = (
                SELECT COALESCE(SUM(bi.subtotal), 0)
                FROM bill_items bi
                WHERE bi.bill_id = b.id
            )
        ");
    }
}