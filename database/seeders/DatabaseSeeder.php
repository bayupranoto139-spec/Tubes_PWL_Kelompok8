<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Super Admin',  'superadmin@healthmesh.id', 'password'],
                ['Admin RS 1',   'admin@rsss.id',            'password'],
                ['Admin RS 2',   'admin@rshb.id',            'password'],
                ['Admin RS 3',   'admin@rsmk.id',            'password'],
                ['Dokter (RS1)', 'budi.santoso@rsss.id',     'password'],
                ['Pasien',       'agus.setiawan@gmail.com',  'password'],
            ]
        );
    }

    // -------------------------------------------------------------------------
    // TRUNCATE
    // -------------------------------------------------------------------------

    private function truncateAll(): void
    {
        foreach ([
            'bill_items', 'bills', 'prescriptions', 'medical_records',
            'queues', 'appointments', 'schedules', 'staff', 'doctors',
            'patient_enrollments', 'patient_medical_infos', 'users',
            'medications', 'specializations', 'hospitals',
        ] as $table) {
            DB::statement("TRUNCATE TABLE `{$table}`");
        }
    }

    // -------------------------------------------------------------------------
    // HOSPITALS  (3 RS)
    // -------------------------------------------------------------------------

    private function seedHospitals(): void
    {
        DB::table('hospitals')->insert([
            ['id' => 1, 'name' => 'RS Umum Sehat Sejahtera', 'code' => 'RSSS-001', 'city' => 'Medan',    'address' => 'Jl. Diponegoro No. 12, Medan Baru',         'logo' => null, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'RS Harapan Bunda',        'code' => 'RSHB-002', 'city' => 'Medan',    'address' => 'Jl. Gatot Subroto No. 45, Medan Petisah',   'logo' => null, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'RS Mitra Keluarga',       'code' => 'RSMK-003', 'city' => 'Binjai',   'address' => 'Jl. Soekarno Hatta No. 88, Kota Binjai',    'logo' => null, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    // -------------------------------------------------------------------------
    // SPECIALIZATIONS  (8 spesialisasi)
    // -------------------------------------------------------------------------

    private function seedSpecializations(): void
    {
        $rows = [
            [1, 'Dokter Umum',              'Pelayanan kesehatan umum dan pemeriksaan dasar'],
            [2, 'Spesialis Jantung',        'Diagnosis dan pengobatan penyakit jantung dan pembuluh darah'],
            [3, 'Spesialis Anak',           'Pelayanan kesehatan bayi, anak, dan remaja'],
            [4, 'Spesialis Penyakit Dalam', 'Penanganan penyakit organ dalam'],
            [5, 'Spesialis Kulit',          'Diagnosis dan pengobatan penyakit kulit dan kelamin'],
            [6, 'Spesialis Mata',           'Diagnosis dan pengobatan penyakit mata'],
            [7, 'Spesialis Orthopedi',      'Penanganan penyakit tulang, sendi, dan otot'],
            [8, 'Spesialis Saraf',          'Diagnosis dan pengobatan penyakit sistem saraf'],
        ];
        foreach ($rows as [$id, $name, $desc]) {
            DB::table('specializations')->insert(['id' => $id, 'name' => $name, 'description' => $desc, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    // -------------------------------------------------------------------------
    // MEDICATIONS  (15 obat + 1 fallback)
    // -------------------------------------------------------------------------

    private function seedMedications(): void
    {
        $meds = [
            [1,  'Paracetamol 500mg',      'Paracetamol',       'Analgesik',       'tablet', 500],
            [2,  'Amoxicillin 500mg',      'Amoxicillin',       'Antibiotik',      'kapsul', 2500],
            [3,  'Omeprazole 20mg',        'Omeprazole',        'Antasida',        'kapsul', 3000],
            [4,  'Amlodipine 5mg',         'Amlodipine',        'Antihipertensi',  'tablet', 1500],
            [5,  'Metformin 500mg',        'Metformin',         'Antidiabetik',    'tablet', 1000],
            [6,  'Cetirizine 10mg',        'Cetirizine',        'Antihistamin',    'tablet', 2000],
            [7,  'Vitamin C 500mg',        'Ascorbic Acid',     'Vitamin',         'tablet', 500],
            [8,  'Antasida Doen',          'Al/Mg Hydroxide',   'Antasida',        'tablet', 800],
            [9,  'Salbutamol 4mg',         'Salbutamol',        'Bronkodilator',   'tablet', 1200],
            [10, 'Dexamethasone 0.5mg',    'Dexamethasone',     'Kortikosteroid',  'tablet', 600],
            [11, 'Lain-lain',              null,                 null,             'unit',   0],
            [12, 'Losartan 50mg',          'Losartan',          'Antihipertensi',  'tablet', 2000],
            [13, 'Simvastatin 20mg',       'Simvastatin',       'Antilipid',       'tablet', 1800],
            [14, 'Ciprofloxacin 500mg',    'Ciprofloxacin',     'Antibiotik',      'tablet', 3500],
            [15, 'Ibuprofen 400mg',        'Ibuprofen',         'NSAID',           'tablet', 1000],
            [16, 'Methylprednisolone 4mg', 'Methylprednisolone','Kortikosteroid',  'tablet', 1500],
        ];
        foreach ($meds as [$id, $name, $generic, $category, $unit, $price]) {
            DB::table('medications')->insert(['id' => $id, 'name' => $name, 'generic_name' => $generic, 'category' => $category, 'unit' => $unit, 'price' => $price, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    // -------------------------------------------------------------------------
    // USERS
    // Catatan: hospital_id di users hanya untuk dokter/staff/admin_rs.
    //          Pasien → hospital_id = null (relasi ke RS via patient_enrollments).
    // -------------------------------------------------------------------------

    private function seedUsers(): void
    {
        $users = [
            // Super admin
            [1,  null, 'Super Administrator',    'superadmin@healthmesh.id',   'super_admin', 'L', '1985-01-01', '081234567890'],

            // Admin RS
            [2,  1,    'Rina Sari',              'admin@rsss.id',              'admin_rs',    'P', '1990-03-15', '082345678901'],
            [3,  2,    'Dedi Kurniawan',         'admin@rshb.id',              'admin_rs',    'L', '1988-07-22', '083456789012'],
            [4,  3,    'Fitri Handayani',        'admin@rsmk.id',              'admin_rs',    'P', '1991-11-05', '084567890130'],

            // Dokter RS 1
            [5,  1,    'dr. Budi Santoso',       'budi.santoso@rsss.id',       'dokter',      'L', '1978-05-10', '084567890123'],
            [6,  1,    'dr. Sari Indah',         'sari.indah@rsss.id',         'dokter',      'P', '1982-11-20', '085678901234'],
            [7,  1,    'dr. Teguh Prabowo',      'teguh.prabowo@rsss.id',      'dokter',      'L', '1976-04-08', '086789012340'],

            // Dokter RS 2
            [8,  2,    'dr. Ahmad Fauzi',        'ahmad.fauzi@rshb.id',        'dokter',      'L', '1975-08-30', '086789012345'],
            [9,  2,    'dr. Nila Kusuma',        'nila.kusuma@rshb.id',        'dokter',      'P', '1983-02-14', '087890123450'],

            // Dokter RS 3
            [10, 3,    'dr. Eko Prasetyo',       'eko.prasetyo@rsmk.id',       'dokter',      'L', '1980-09-17', '088901234560'],

            // Staff
            [11, 1,    'Maya Putri',             'maya@rsss.id',               'staff',       'P', '1995-04-12', '087890123456'],
            [12, 1,    'Hendra Wijaya',          'hendra@rsss.id',             'staff',       'L', '1993-09-05', '088901234567'],
            [13, 2,    'Lena Susanti',           'lena@rshb.id',               'staff',       'P', '1996-02-18', '089012345678'],
            [14, 3,    'Roni Saputra',           'roni@rsmk.id',               'staff',       'L', '1994-06-30', '089123456780'],

            // Pasien (hospital_id = null — relasi via patient_enrollments)
            [15, null, 'Agus Setiawan',          'agus.setiawan@gmail.com',    'pasien',      'L', '1988-06-25', '081122334455'],
            [16, null, 'Dewi Rahayu',            'dewi.rahayu@gmail.com',      'pasien',      'P', '1992-12-03', '082233445566'],
            [17, null, 'Farhan Ramadhan',        'farhan.ramadhan@gmail.com',  'pasien',      'L', '2000-07-14', '083344556677'],
            [18, null, 'Siti Nuraini',           'siti.nuraini@gmail.com',     'pasien',      'P', '1975-03-28', '084455667788'],
            [19, null, 'Rudi Hartono',           'rudi.hartono@gmail.com',     'pasien',      'L', '1968-11-11', '085566778899'],
            [20, null, 'Indah Permatasari',      'indah.permata@gmail.com',    'pasien',      'P', '1995-08-20', '086677889900'],
            [21, null, 'Bagas Prasetyo',         'bagas.prasetyo@gmail.com',   'pasien',      'L', '2003-01-05', '087788990011'],
            [22, null, 'Yuni Astuti',            'yuni.astuti@gmail.com',      'pasien',      'P', '1980-05-15', '088899001122'],
            [23, null, 'Andi Firmansyah',        'andi.firmansyah@gmail.com',  'pasien',      'L', '1972-09-23', '089900112233'],
            [24, null, 'Nina Kusumawati',        'nina.kusuma@gmail.com',      'pasien',      'P', '1998-04-02', '081011223344'],
        ];

        foreach ($users as [$id, $hospitalId, $name, $email, $role, $gender, $dob, $phone]) {
            DB::table('users')->insert([
                'id'            => $id,
                'hospital_id'   => $hospitalId,
                'name'          => $name,
                'email'         => $email,
                'password'      => Hash::make('password'),
                'role'          => $role,
                'gender'        => $gender,
                'date_of_birth' => $dob,
                'phone'         => $phone,
                'address'       => 'Jl. Contoh No. ' . $id . ', Kota Medan',
                'is_active'     => 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }

    // -------------------------------------------------------------------------
    // PATIENT MEDICAL INFOS
    // -------------------------------------------------------------------------

    private function seedPatientMedicalInfos(): void
    {
        // user_id 15-24 adalah pasien
        $infos = [
            [15, 'A',  'Penisilin',            'Siti Setiawan',      '081298765432', 'BPJS Kesehatan',    'BPJS-00123456'],
            [16, 'O',  null,                   'Budi Rahayu',        '082198765432', 'BPJS Kesehatan',    'BPJS-00234567'],
            [17, 'B',  'Sulfa, Aspirin',        'Rina Ramadhan',      '083198765432', null,                null],
            [18, 'AB', null,                   'Hasan Nuraini',      '084198765432', 'Asuransi Mandiri',  'AM-9988776655'],
            [19, 'O',  'Debu, Udang',           'Tini Hartono',       '085198765432', 'BPJS Kesehatan',    'BPJS-00345678'],
            [20, 'A',  null,                   'Heri Permata',       '086198765432', null,                null],
            [21, 'B',  null,                   'Eko Prasetyo',       '087198765432', 'BPJS Kesehatan',    'BPJS-00456789'],
            [22, 'O',  'Amoxicillin',           'Dodo Astuti',        '088198765432', 'Allianz',           'ALZ-12345678'],
            [23, 'AB', 'Aspirin',               'Lia Firmansyah',     '089198765432', 'Asuransi Mandiri',  'AM-1122334455'],
            [24, 'A',  null,                   'Agus Kusuma',        '081298765430', 'BPJS Kesehatan',    'BPJS-00567890'],
        ];

        foreach ($infos as [$userId, $blood, $allergies, $ecName, $ecPhone, $insProvider, $insPolicy]) {
            DB::table('patient_medical_infos')->insert([
                'user_id'                   => $userId,
                'blood_type'                => $blood,
                'allergies'                 => $allergies,
                'emergency_contact_name'    => $ecName,
                'emergency_contact_phone'   => $ecPhone,
                'insurance_provider'        => $insProvider,
                'insurance_policy_number'   => $insPolicy,
                'created_at'                => now(),
                'updated_at'                => now(),
            ]);
        }
    }

    // -------------------------------------------------------------------------
    // PATIENT ENROLLMENTS
    // Beberapa pasien terdaftar di >1 RS untuk testing multi-hospital scenario.
    // -------------------------------------------------------------------------

    private function seedPatientEnrollments(): void
    {
        $enrollments = [
            // RS 1 (RSSS)
            [1,  15, 1, 'RSSS-2024-0001'],
            [2,  16, 1, 'RSSS-2024-0002'],
            [3,  17, 1, 'RSSS-2024-0003'],
            [4,  18, 1, 'RSSS-2024-0004'],
            [5,  19, 1, 'RSSS-2024-0005'],
            [6,  20, 1, 'RSSS-2024-0006'],
            [7,  21, 1, 'RSSS-2024-0007'],

            // RS 2 (RSHB)
            [8,  15, 2, 'RSHB-2024-0001'], // Agus terdaftar di RS 1 & RS 2
            [9,  16, 2, 'RSHB-2024-0002'], // Dewi terdaftar di RS 1 & RS 2
            [10, 18, 2, 'RSHB-2024-0003'], // Siti terdaftar di RS 1 & RS 2
            [11, 22, 2, 'RSHB-2024-0004'],
            [12, 23, 2, 'RSHB-2024-0005'],

            // RS 3 (RSMK)
            [13, 15, 3, 'RSMK-2024-0001'], // Agus terdaftar di RS 1, 2 & 3
            [14, 19, 3, 'RSMK-2024-0002'], // Rudi terdaftar di RS 1 & RS 3
            [15, 24, 3, 'RSMK-2024-0003'],
            [16, 17, 3, 'RSMK-2024-0004'], // Farhan terdaftar di RS 1 & RS 3
        ];

        foreach ($enrollments as [$id, $userId, $hospitalId, $mrn]) {
            DB::table('patient_enrollments')->insert([
                'id'                    => $id,
                'user_id'               => $userId,
                'hospital_id'           => $hospitalId,
                'medical_record_number' => $mrn,
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);
        }
    }

    // -------------------------------------------------------------------------
    // DOCTORS
    // -------------------------------------------------------------------------

    private function seedDoctors(): void
    {
        // [id, user_id, specialization_id, licence, fee, experience]
        $doctors = [
            [1, 5,  1, 'SIP-001/2020/IDI', 150000, 10], // dr. Budi  — Dokter Umum,  RS1
            [2, 6,  2, 'SIP-002/2018/IDI', 350000, 12], // dr. Sari  — Jantung,      RS1
            [3, 7,  4, 'SIP-003/2019/IDI', 275000, 11], // dr. Teguh — Penyakit Dlm, RS1
            [4, 8,  3, 'SIP-004/2015/IDI', 300000, 15], // dr. Ahmad — Anak,         RS2
            [5, 9,  5, 'SIP-005/2017/IDI', 325000, 13], // dr. Nila  — Kulit,        RS2
            [6, 10, 7, 'SIP-006/2016/IDI', 400000, 14], // dr. Eko   — Orthopedi,    RS3
        ];

        foreach ($doctors as [$id, $userId, $specId, $lic, $fee, $exp]) {
            DB::table('doctors')->insert([
                'id' => $id, 'user_id' => $userId, 'specialization_id' => $specId,
                'licence_number' => $lic, 'consultation_fee' => $fee, 'years_of_experience' => $exp,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }

    // -------------------------------------------------------------------------
    // STAFF
    // -------------------------------------------------------------------------

    private function seedStaff(): void
    {
        $staffs = [
            [2,  'Kepala Administrasi', 'Administrasi'],
            [3,  'Kepala Administrasi', 'Administrasi'],
            [4,  'Kepala Administrasi', 'Administrasi'],
            [11, 'Resepsionis',         'Front Office'],
            [12, 'Kasir',               'Keuangan'],
            [13, 'Resepsionis',         'Front Office'],
            [14, 'Resepsionis',         'Front Office'],
        ];
        foreach ($staffs as [$userId, $position, $dept]) {
            DB::table('staff')->insert(['user_id' => $userId, 'position' => $position, 'department' => $dept, 'created_at' => now(), 'updated_at' => now()]);
        }
    }

    // -------------------------------------------------------------------------
    // SCHEDULES
    // -------------------------------------------------------------------------

    private function seedSchedules(): void
    {
        // [id, doctor_id, day_of_week(1=Senin), start, end, max]
        $schedules = [
            // dr. Budi (umum, RS1) — Senin, Rabu, Jumat
            [1,  1, 1, '08:00', '12:00', 20],
            [2,  1, 3, '08:00', '12:00', 20],
            [3,  1, 5, '08:00', '11:00', 15],
            // dr. Sari (jantung, RS1) — Selasa, Kamis
            [4,  2, 2, '09:00', '13:00', 10],
            [5,  2, 4, '14:00', '17:00', 8],
            // dr. Teguh (penyakit dalam, RS1) — Senin, Kamis, Sabtu
            [6,  3, 1, '13:00', '17:00', 12],
            [7,  3, 4, '08:00', '12:00', 12],
            [8,  3, 6, '09:00', '12:00', 8],
            // dr. Ahmad (anak, RS2) — Senin, Rabu
            [9,  4, 1, '13:00', '17:00', 15],
            [10, 4, 3, '08:00', '12:00', 15],
            // dr. Nila (kulit, RS2) — Selasa, Jumat
            [11, 5, 2, '10:00', '14:00', 10],
            [12, 5, 5, '13:00', '17:00', 10],
            // dr. Eko (orthopedi, RS3) — Rabu, Sabtu
            [13, 6, 3, '09:00', '13:00', 12],
            [14, 6, 6, '08:00', '12:00', 10],
        ];

        foreach ($schedules as [$id, $docId, $day, $start, $end, $max]) {
            DB::table('schedules')->insert([
                'id' => $id, 'doctor_id' => $docId, 'day_of_week' => $day,
                'start_time' => $start, 'end_time' => $end, 'max_patients' => $max,
                'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }

    // -------------------------------------------------------------------------
    // APPOINTMENTS
    // Skenario: completed (dengan & tanpa bill), scheduled (hari ini), cancelled
    // -------------------------------------------------------------------------

    private function seedAppointments(): void
    {
        $today     = Carbon::today();
        $yesterday = Carbon::yesterday();
        $twoDays   = Carbon::today()->subDays(2);
        $threeDays = Carbon::today()->subDays(3);
        $lastWeek  = Carbon::today()->subDays(7);

        // [id, enrollment_id, doctor_id, schedule_id, scheduled_at, status, complaint]
        $appointments = [

            // ── MINGGU LALU — semua completed, ada bill ──────────────────────
            [1,  1,  1, 1,    $lastWeek->copy()->setTime(8,  0), 'completed', 'Demam dan sakit kepala 3 hari'],
            [2,  2,  1, 1,    $lastWeek->copy()->setTime(8, 30), 'completed', 'Batuk berdahak sudah seminggu'],
            [3,  4,  2, 4,    $lastWeek->copy()->setTime(9,  0), 'completed', 'Nyeri dada dan sesak napas'],
            [4,  8,  4, 9,    $lastWeek->copy()->setTime(13, 0), 'completed', 'Anak sering batuk dan pilek'],
            [5,  11, 5, 11,   $lastWeek->copy()->setTime(10, 0), 'completed', 'Ruam merah di tangan dan punggung'],
            [6,  13, 6, 13,   $lastWeek->copy()->setTime(9,  0), 'completed', 'Nyeri lutut kiri setelah jatuh'],

            // ── 3 HARI LALU — completed ───────────────────────────────────────
            [7,  3,  3, 6,    $threeDays->copy()->setTime(13, 0), 'completed', 'Gula darah tidak terkontrol'],
            [8,  5,  1, 1,    $threeDays->copy()->setTime(9,  0), 'completed', 'Pusing dan mual saat berdiri'],
            [9,  10, 4, 9,    $threeDays->copy()->setTime(13, 0), 'completed', 'Anak demam 3 hari tidak turun'],
            [10, 12, 5, 11,   $threeDays->copy()->setTime(10, 0), 'completed', 'Gatal-gatal setelah makan seafood'],

            // ── 2 HARI LALU — completed ───────────────────────────────────────
            [11, 6,  1, 1,    $twoDays->copy()->setTime(8,  0),  'completed', 'Kontrol rutin tekanan darah'],
            [12, 1,  2, 4,    $twoDays->copy()->setTime(9,  0),  'completed', 'Palpitasi jantung saat olahraga'],
            [13, 14, 6, 13,   $twoDays->copy()->setTime(9,  0),  'completed', 'Sakit punggung bawah kronik'],

            // ── KEMARIN — completed (ada yang sudah dibayar, ada yang belum) ──
            [14, 1,  1, 1,    $yesterday->copy()->setTime(8,  0), 'completed', 'Lanjutan kontrol demam tifoid'],
            [15, 2,  3, 6,    $yesterday->copy()->setTime(13, 0), 'completed', 'Kolesterol dan gula darah tinggi'],
            [16, 4,  2, 4,    $yesterday->copy()->setTime(9,  0), 'completed', 'EKG lanjutan'],
            [17, 9,  4, 10,   $yesterday->copy()->setTime(8,  0), 'completed', 'Diare berdarah, anak 8 thn'],
            [18, 15, 6, 14,   $yesterday->copy()->setTime(8,  0), 'completed', 'Cedera pergelangan kaki'],

            // ── KEMARIN — walk-in (schedule_id null) ─────────────────────────
            [19, 7,  1, null, $yesterday->copy()->setTime(10, 0), 'completed', 'Walk-in: sakit tenggorokan mendadak'],
            [20, 5,  3, null, $yesterday->copy()->setTime(14, 0), 'completed', 'Walk-in: mual dan muntah sejak pagi'],

            // ── HARI INI — scheduled (menunggu) ──────────────────────────────
            [21, 1,  1, 2,    $today->copy()->setTime(8,  0),  'scheduled',  'Kontrol rutin, cek tensi'],
            [22, 2,  1, 2,    $today->copy()->setTime(8, 30),  'scheduled',  'Batuk tidak kunjung sembuh'],
            [23, 3,  1, 2,    $today->copy()->setTime(9,  0),  'scheduled',  'Minta surat keterangan sehat'],
            [24, 6,  3, 7,    $today->copy()->setTime(8,  0),  'scheduled',  'Cek diabetes mellitus'],
            [25, 4,  2, 5,    $today->copy()->setTime(14, 0),  'scheduled',  'Kontrol jantung lanjutan'],
            [26, 8,  4, 10,   $today->copy()->setTime(8,  0),  'scheduled',  'Imunisasi anak'],
            [27, 11, 5, 12,   $today->copy()->setTime(13, 0),  'scheduled',  'Jerawat parah di wajah'],
            [28, 16, 6, 14,   $today->copy()->setTime(8,  0),  'scheduled',  'Fisioterapi lutut'],

            // ── HARI INI — walk-in yang sudah selesai (ada rekam medis, belum complete) ──
            [29, 7,  1, null, $today->copy()->setTime(10, 0),  'scheduled',  'Walk-in: sakit kepala berat'],

            // ── CANCELLED / NO_SHOW ──────────────────────────────────────────
            [30, 5,  2, 5,    $yesterday->copy()->setTime(14, 0), 'cancelled', 'Kontrol jantung'],
            [31, 7,  1, 1,    $twoDays->copy()->setTime(9,  0),   'no_show',   'Sakit perut'],
        ];

        foreach ($appointments as [$id, $enrollId, $docId, $schedId, $scheduledAt, $status, $complaint]) {
            DB::table('appointments')->insert([
                'id'                    => $id,
                'patient_enrollment_id' => $enrollId,
                'doctor_id'             => $docId,
                'schedule_id'           => $schedId,
                'scheduled_at'          => $scheduledAt,
                'status'                => $status,
                'complaint'             => $complaint,
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);
        }
    }

    // -------------------------------------------------------------------------
    // QUEUES
    // -------------------------------------------------------------------------

    private function seedQueues(): void
    {
        $today     = Carbon::today()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();
        $twoDays   = Carbon::today()->subDays(2)->toDateString();
        $threeDays = Carbon::today()->subDays(3)->toDateString();
        $lastWeek  = Carbon::today()->subDays(7)->toDateString();

        // [appointment_id, queue_date, queue_number, type, priority, status, called_at, started_at, completed_at]
        $queues = [
            // Minggu lalu
            [1,  $lastWeek,  1, 'appointment', 1, 'completed', Carbon::parse($lastWeek)->setTime(8,5),   Carbon::parse($lastWeek)->setTime(8,8),   Carbon::parse($lastWeek)->setTime(8,25)],
            [2,  $lastWeek,  2, 'appointment', 1, 'completed', Carbon::parse($lastWeek)->setTime(8,35),  Carbon::parse($lastWeek)->setTime(8,38),  Carbon::parse($lastWeek)->setTime(8,55)],
            [3,  $lastWeek,  1, 'appointment', 1, 'completed', Carbon::parse($lastWeek)->setTime(9,5),   Carbon::parse($lastWeek)->setTime(9,8),   Carbon::parse($lastWeek)->setTime(9,40)],
            [4,  $lastWeek,  1, 'appointment', 1, 'completed', Carbon::parse($lastWeek)->setTime(13,5),  Carbon::parse($lastWeek)->setTime(13,8),  Carbon::parse($lastWeek)->setTime(13,30)],
            [5,  $lastWeek,  1, 'appointment', 1, 'completed', Carbon::parse($lastWeek)->setTime(10,5),  Carbon::parse($lastWeek)->setTime(10,8),  Carbon::parse($lastWeek)->setTime(10,35)],
            [6,  $lastWeek,  1, 'appointment', 1, 'completed', Carbon::parse($lastWeek)->setTime(9,5),   Carbon::parse($lastWeek)->setTime(9,8),   Carbon::parse($lastWeek)->setTime(9,45)],

            // 3 hari lalu
            [7,  $threeDays, 1, 'appointment', 1, 'completed', Carbon::parse($threeDays)->setTime(13,5),  Carbon::parse($threeDays)->setTime(13,8),  Carbon::parse($threeDays)->setTime(13,40)],
            [8,  $threeDays, 2, 'appointment', 1, 'completed', Carbon::parse($threeDays)->setTime(9,5),   Carbon::parse($threeDays)->setTime(9,8),   Carbon::parse($threeDays)->setTime(9,25)],
            [9,  $threeDays, 1, 'appointment', 1, 'completed', Carbon::parse($threeDays)->setTime(13,5),  Carbon::parse($threeDays)->setTime(13,8),  Carbon::parse($threeDays)->setTime(13,35)],
            [10, $threeDays, 2, 'appointment', 1, 'completed', Carbon::parse($threeDays)->setTime(10,5),  Carbon::parse($threeDays)->setTime(10,8),  Carbon::parse($threeDays)->setTime(10,40)],

            // 2 hari lalu
            [11, $twoDays,   1, 'appointment', 1, 'completed', Carbon::parse($twoDays)->setTime(8,5),    Carbon::parse($twoDays)->setTime(8,8),    Carbon::parse($twoDays)->setTime(8,25)],
            [12, $twoDays,   2, 'appointment', 1, 'completed', Carbon::parse($twoDays)->setTime(9,5),    Carbon::parse($twoDays)->setTime(9,8),    Carbon::parse($twoDays)->setTime(9,40)],
            [13, $twoDays,   1, 'appointment', 1, 'completed', Carbon::parse($twoDays)->setTime(9,5),    Carbon::parse($twoDays)->setTime(9,8),    Carbon::parse($twoDays)->setTime(9,45)],

            // Kemarin
            [14, $yesterday, 1, 'appointment', 1, 'completed', Carbon::parse($yesterday)->setTime(8,5),  Carbon::parse($yesterday)->setTime(8,8),  Carbon::parse($yesterday)->setTime(8,30)],
            [15, $yesterday, 1, 'appointment', 1, 'completed', Carbon::parse($yesterday)->setTime(13,5), Carbon::parse($yesterday)->setTime(13,8), Carbon::parse($yesterday)->setTime(13,40)],
            [16, $yesterday, 2, 'appointment', 1, 'completed', Carbon::parse($yesterday)->setTime(9,5),  Carbon::parse($yesterday)->setTime(9,8),  Carbon::parse($yesterday)->setTime(9,45)],
            [17, $yesterday, 1, 'appointment', 1, 'completed', Carbon::parse($yesterday)->setTime(8,5),  Carbon::parse($yesterday)->setTime(8,8),  Carbon::parse($yesterday)->setTime(8,35)],
            [18, $yesterday, 1, 'appointment', 1, 'completed', Carbon::parse($yesterday)->setTime(8,5),  Carbon::parse($yesterday)->setTime(8,8),  Carbon::parse($yesterday)->setTime(8,50)],
            [19, $yesterday, 3, 'walk_in',     2, 'completed', Carbon::parse($yesterday)->setTime(10,5), Carbon::parse($yesterday)->setTime(10,8), Carbon::parse($yesterday)->setTime(10,30)],
            [20, $yesterday, 2, 'walk_in',     2, 'completed', Carbon::parse($yesterday)->setTime(14,5), Carbon::parse($yesterday)->setTime(14,8), Carbon::parse($yesterday)->setTime(14,30)],
            [30, $yesterday, 4, 'appointment', 1, 'skipped',   null,                                     null,                                     null],

            // Hari ini — menunggu
            [21, $today,     1, 'appointment', 1, 'waiting',   null, null, null],
            [22, $today,     2, 'appointment', 1, 'waiting',   null, null, null],
            [23, $today,     3, 'appointment', 1, 'waiting',   null, null, null],
            [24, $today,     1, 'appointment', 1, 'waiting',   null, null, null],
            [25, $today,     1, 'appointment', 1, 'waiting',   null, null, null],
            [26, $today,     1, 'appointment', 1, 'waiting',   null, null, null],
            [27, $today,     1, 'appointment', 1, 'waiting',   null, null, null],
            [28, $today,     1, 'appointment', 1, 'waiting',   null, null, null],
            [29, $today,     4, 'walk_in',     2, 'waiting',   null, null, null],
        ];

        foreach ($queues as [$aptId, $date, $num, $type, $priority, $status, $calledAt, $startedAt, $completedAt]) {
            DB::table('queues')->insert([
                'appointment_id' => $aptId,
                'queue_date'     => $date,
                'queue_number'   => $num,
                'type'           => $type,
                'priority'       => $priority,
                'status'         => $status,
                'called_at'      => $calledAt,
                'started_at'     => $startedAt,
                'completed_at'   => $completedAt,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }

    // -------------------------------------------------------------------------
    // MEDICAL RECORDS  (hanya untuk appointment completed)
    // -------------------------------------------------------------------------

    private function seedMedicalRecords(): void
    {
        $lastWeek  = Carbon::today()->subDays(7);
        $threeDays = Carbon::today()->subDays(3);
        $twoDays   = Carbon::today()->subDays(2);
        $yesterday = Carbon::yesterday();
        $today     = Carbon::today();

        // [id, appointment_id, visit_date, diagnosis, treatment_plan, notes, case_status]
        $records = [
            // Minggu lalu
            [1,  1,  $lastWeek->copy()->setTime(8,8),    'Demam tifoid ringan',                   'Pemberian antibiotik, istirahat, banyak minum',                 'Kontrol 5 hari lagi',                         'healed'],
            [2,  2,  $lastWeek->copy()->setTime(8,38),   'Bronkitis akut',                        'Mukolitik dan ekspektoran, hindari rokok',                      'Rontgen thorax jika tidak membaik',           'healed'],
            [3,  3,  $lastWeek->copy()->setTime(9,8),    'Angina pektoris stabil',                'Nitrat sublingual, beta blocker, modifikasi gaya hidup',        'Jadwalkan stress test',                       'active'],
            [4,  4,  $lastWeek->copy()->setTime(13,8),   'Infeksi saluran napas atas (ISPA)',     'Antipiretik, minum cukup, istirahat',                           'Bila 3 hari tidak membaik, kembali ke RS',    'healed'],
            [5,  5,  $lastWeek->copy()->setTime(10,8),   'Dermatitis kontak alergi',              'Kortikosteroid topikal, hindari iritan',                        'Patch test jika kambuh',                      'active'],
            [6,  6,  $lastWeek->copy()->setTime(9,8),    'Ruptur ligamen kolateral medial grade I','RICE (Rest, Ice, Compression, Elevation), analgesik',           'Fisioterapi 2 minggu',                        'active'],

            // 3 hari lalu
            [7,  7,  $threeDays->copy()->setTime(13,8),  'Diabetes mellitus tipe 2 tidak terkontrol', 'Penyesuaian dosis metformin, edukasi diet',                'Cek HbA1c 3 bulan lagi',                      'active'],
            [8,  8,  $threeDays->copy()->setTime(9,8),   'Vertigo perifer (BPPV)',                 'Manuver Epley, betahistin',                                    'Hindari perubahan posisi mendadak',            'healed'],
            [9,  9,  $threeDays->copy()->setTime(13,8),  'Febris e.c. infeksi virus',              'Antipiretik, kompres hangat, cairan cukup',                    'Kembali jika demam > 38.5°C',                 'healed'],
            [10, 10, $threeDays->copy()->setTime(10,8),  'Urtikaria akut',                        'Antihistamin oral, hindari seafood',                            'Epipen jika reaksi berat',                    'healed'],

            // 2 hari lalu
            [11, 11, $twoDays->copy()->setTime(8,8),     'Hipertensi grade I terkontrol',          'Lanjutkan Amlodipine, diit rendah garam',                      'Pantau tensi tiap bulan',                     'active'],
            [12, 12, $twoDays->copy()->setTime(9,8),     'Aritmia supraventrikular',               'EKG 24 jam (Holter monitoring), beta blocker',                 'Rujuk jika frekuensi meningkat',              'active'],
            [13, 13, $twoDays->copy()->setTime(9,8),     'Low back pain kronik',                   'Analgesik, muscle relaxant, fisioterapi',                      'MRI lumbal jika tidak membaik',               'active'],

            // Kemarin
            [14, 14, $yesterday->copy()->setTime(8,8),   'Resolusi demam tifoid',                 'Lanjutkan antibiotik 2 hari, probiotik',                       'Pasien membaik, tidak perlu kontrol jika baik','healed'],
            [15, 15, $yesterday->copy()->setTime(13,8),  'Dislipidemia + pre-diabetes',           'Simvastatin, metformin dosis rendah, diet',                    'Cek lipid profil 1 bulan lagi',               'active'],
            [16, 16, $yesterday->copy()->setTime(9,8),   'Angina stabil, EKG normal sinus',       'Lanjutkan terapi, tambah Losartan',                            'Jadwalkan echo 1 bulan',                      'active'],
            [17, 17, $yesterday->copy()->setTime(8,8),   'Disentri basiler',                      'Ciprofloxacin, oral rehidrasi, diet lunak',                    'Isolasi dari teman bermain sementara',         'active'],
            [18, 18, $yesterday->copy()->setTime(8,8),   'Sprain ankle grade II',                  'Imobilisasi brace, analgesik, fisioterapi',                    'Hindari olahraga 3 minggu',                   'active'],
            [19, 19, $yesterday->copy()->setTime(10,8),  'Faringitis akut',                       'Antibiotik, kumur air garam, analgesik',                       null,                                          'active'],
            [20, 20, $yesterday->copy()->setTime(14,8),  'Gastroenteritis akut',                  'Rehidrasi oral, antasida, diet BRAT',                          'Hindari susu dan makanan berlemak',            'active'],

            // Hari ini — appointment 29 (walk-in, sudah ada rekam medis tapi belum complete)
            [21, 29, $today->copy()->setTime(10,15),     'Tension headache',                      'Ibuprofen 400mg, relaksasi, kompres dingin',                   'Kurangi stres dan kafein',                    'active'],
        ];

        foreach ($records as [$id, $aptId, $visitDate, $diagnosis, $treatment, $notes, $caseStatus]) {
            DB::table('medical_records')->insert([
                'id'             => $id,
                'appointment_id' => $aptId,
                'visit_date'     => $visitDate,
                'diagnosis'      => $diagnosis,
                'treatment_plan' => $treatment,
                'notes'          => $notes,
                'case_status'    => $caseStatus,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }

    // -------------------------------------------------------------------------
    // PRESCRIPTIONS
    // -------------------------------------------------------------------------

    private function seedPrescriptions(): void
    {
        // [medical_record_id, medication_id, dosage, duration, quantity, notes]
        $prescriptions = [
            // MR 1 — demam tifoid
            [1, 2,  '3x1',    '7 hari',   21, 'Habiskan antibiotik'],
            [1, 1,  '3x1',    '5 hari',   15, 'Minum jika demam > 38°C'],
            [1, 7,  '1x1',    '10 hari',  10, null],

            // MR 2 — bronkitis
            [2, 9,  '3x1',    '5 hari',   15, 'Minum setelah makan'],
            [2, 1,  '3x1',    '3 hari',    9, 'Jika perlu saja'],

            // MR 3 — angina
            [3, 4,  '1x1',    '30 hari',  30, 'Minum pagi hari'],
            [3, 3,  '1x1',    '14 hari',  14, '30 menit sebelum makan'],

            // MR 4 — ISPA anak (tanpa resep — cocok untuk testing skenario tanpa prescription)

            // MR 5 — dermatitis
            [5, 10, '2x1',    '7 hari',   14, 'Oleskan tipis pada area merah'],
            [5, 16, '1x1',    '5 hari',    5, 'Minum malam hari'],

            // MR 6 — ligamen — tanpa obat (hanya fisioterapi)

            // MR 7 — DM
            [7, 5,  '3x1',    '30 hari',  90, 'Minum bersama makan'],

            // MR 8 — vertigo
            [8, 6,  '1x1',    '3 hari',    3, 'Minum malam sebelum tidur'],

            // MR 9 — febris anak — tanpa resep

            // MR 10 — urtikaria
            [10, 6, '1x1',   '5 hari',    5, 'Minum malam hari'],

            // MR 11 — hipertensi
            [11, 4,  '1x1',   '30 hari',  30, 'Minum pagi hari, pantau tekanan darah'],

            // MR 12 — aritmia
            [12, 4,  '1x1',   '14 hari',  14, 'Pantau denyut nadi'],
            [12, 3,  '1x1',   '14 hari',  14, '30 menit sebelum makan'],

            // MR 13 — LBP
            [13, 15, '3x1',   '5 hari',   15, 'Setelah makan, jangan perut kosong'],
            [13, 16, '1x1',   '5 hari',    5, 'Malam hari'],

            // MR 14 — resolusi tifoid
            [14, 2,  '3x1',   '2 hari',    6, 'Habiskan'],
            [14, 3,  '1x1',   '7 hari',    7, 'Jaga lambung'],

            // MR 15 — dislipidemia + pre-DM
            [15, 13, '1x1',   '30 hari',  30, 'Minum malam setelah makan'],
            [15, 5,  '3x1',   '30 hari',  90, 'Bersama makan'],

            // MR 16 — angina + losartan
            [16, 4,  '1x1',   '30 hari',  30, 'Pagi hari'],
            [16, 12, '1x1',   '30 hari',  30, 'Malam hari'],

            // MR 17 — disentri
            [17, 14, '2x1',   '5 hari',   10, 'Habiskan, jangan setengah-setengah'],

            // MR 18 — sprain ankle
            [18, 15, '3x1',   '5 hari',   15, 'Setelah makan'],

            // MR 19 — faringitis
            [19, 2,  '3x1',   '5 hari',   15, 'Habiskan antibiotik'],
            [19, 1,  '3x1',   '3 hari',    9, 'Jika perlu'],
            [19, 8,  '3x1',   '3 hari',    9, 'Setelah makan'],

            // MR 20 — gastroenteritis
            [20, 8,  '3x1',   '3 hari',    9, 'Setelah makan'],
            [20, 1,  '3x1',   '3 hari',    9, 'Jika kram atau nyeri'],

            // MR 21 — tension headache (hari ini, appointment belum complete)
            [21, 15, '3x1',   '3 hari',    9, 'Setelah makan'],
        ];

        foreach ($prescriptions as [$mrId, $medId, $dosage, $duration, $qty, $notes]) {
            DB::table('prescriptions')->insert([
                'medical_record_id' => $mrId,
                'medication_id'     => $medId,
                'dosage'            => $dosage,
                'duration'          => $duration,
                'quantity'          => $qty,
                'notes'             => $notes,
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        }
    }

    // -------------------------------------------------------------------------
    // BILLS
    // -------------------------------------------------------------------------

    private function seedBills(): void
    {
        $yesterday = Carbon::yesterday();
        $twoDays   = Carbon::today()->subDays(2);
        $threeDays = Carbon::today()->subDays(3);
        $lastWeek  = Carbon::today()->subDays(7);

        // [patient_enrollment_id, appointment_id, status, payment_due_date, payment_method, payment_date, reference_number]
        $bills = [
            // Minggu lalu — semua paid
            [1,  1,  'paid',   $lastWeek->copy()->addDays(7)->toDateString(), 'insurance',     $lastWeek->copy()->setTime(10,0),  null],
            [2,  2,  'paid',   $lastWeek->copy()->addDays(7)->toDateString(), 'cash',          $lastWeek->copy()->setTime(10,30), null],
            [4,  3,  'paid',   $lastWeek->copy()->addDays(7)->toDateString(), 'bank_transfer', $lastWeek->copy()->setTime(11,0),  'TRF-' . $lastWeek->format('Ymd') . '-001'],
            [8,  4,  'paid',   $lastWeek->copy()->addDays(7)->toDateString(), 'insurance',     $lastWeek->copy()->setTime(14,0),  null],
            [11, 5,  'paid',   $lastWeek->copy()->addDays(7)->toDateString(), 'qris',          $lastWeek->copy()->setTime(11,0),  'QRIS-' . $lastWeek->format('Ymd') . '-001'],
            [13, 6,  'paid',   $lastWeek->copy()->addDays(7)->toDateString(), 'cash',          $lastWeek->copy()->setTime(10,0),  null],

            // 3 hari lalu
            [3,  7,  'paid',   $threeDays->copy()->addDays(7)->toDateString(), 'insurance',    $threeDays->copy()->setTime(14,30), null],
            [5,  8,  'paid',   $threeDays->copy()->addDays(7)->toDateString(), 'cash',         $threeDays->copy()->setTime(10,0),  null],
            [9,  9,  'paid',   $threeDays->copy()->addDays(7)->toDateString(), 'insurance',    $threeDays->copy()->setTime(14,0),  null],
            [10, 10, 'paid',   $threeDays->copy()->addDays(7)->toDateString(), 'qris',         $threeDays->copy()->setTime(11,0),  'QRIS-' . $threeDays->format('Ymd') . '-001'],

            // 2 hari lalu
            [6,  11, 'paid',   $twoDays->copy()->addDays(7)->toDateString(),  'cash',          $twoDays->copy()->setTime(9,30),  null],
            [1,  12, 'paid',   $twoDays->copy()->addDays(7)->toDateString(),  'bank_transfer', $twoDays->copy()->setTime(10,30), 'TRF-' . $twoDays->format('Ymd') . '-001'],
            [14, 13, 'paid',   $twoDays->copy()->addDays(7)->toDateString(),  'cash',          $twoDays->copy()->setTime(10,0),  null],

            // Kemarin — mix paid & unpaid
            [1,  14, 'paid',   $yesterday->copy()->addDays(7)->toDateString(), 'insurance',    $yesterday->copy()->setTime(9,30),  null],
            [2,  15, 'unpaid', $yesterday->copy()->addDays(7)->toDateString(), null,            null,                               null],
            [4,  16, 'paid',   $yesterday->copy()->addDays(7)->toDateString(), 'qris',         $yesterday->copy()->setTime(10,30), 'QRIS-' . $yesterday->format('Ymd') . '-001'],
            [9,  17, 'unpaid', $yesterday->copy()->addDays(7)->toDateString(), null,            null,                               null],
            [15, 18, 'unpaid', $yesterday->copy()->addDays(7)->toDateString(), null,            null,                               null],
            [7,  19, 'paid',   $yesterday->copy()->addDays(7)->toDateString(), 'cash',         $yesterday->copy()->setTime(11,30), null],
            [5,  20, 'unpaid', $yesterday->copy()->addDays(7)->toDateString(), null,            null,                               null],
        ];

        foreach ($bills as [$enrollId, $aptId, $status, $dueDate, $method, $paidAt, $ref]) {
            $this->billIds[] = DB::table('bills')->insertGetId([
                'patient_enrollment_id' => $enrollId,
                'appointment_id'        => $aptId,
                'total_amount'          => 0, // sync setelah bill_items
                'status'                => $status,
                'payment_due_date'      => $dueDate,
                'payment_method'        => $method,
                'payment_date'          => $paidAt,
                'reference_number'      => $ref,
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);
        }
    }

    // -------------------------------------------------------------------------
    // BILL ITEMS
    // -------------------------------------------------------------------------

    private function seedBillItems(): void
    {
        // consultation_fee: dr. Budi=150k, dr.Sari=350k, dr.Teguh=275k, dr.Ahmad=300k, dr.Nila=325k, dr.Eko=400k
        $adm = 15000;
        $admSpec = 25000;

        // Map [bill_index => [[type, desc, qty, price], ...]]
        // bill index sesuai urutan $this->billIds
        $billItems = [
            // 0: apt1 — demam tifoid, dr.Budi, RS1
            [['consultation','Biaya Konsultasi Dokter Umum',1,150000],['medication','Amoxicillin 500mg (21 kapsul)',21,2500],['medication','Paracetamol 500mg (15 tablet)',15,500],['medication','Vitamin C 500mg (10 tablet)',10,500],['administration','Biaya Administrasi',1,$adm]],
            // 1: apt2 — bronkitis, dr.Budi
            [['consultation','Biaya Konsultasi Dokter Umum',1,150000],['medication','Salbutamol 4mg (15 tablet)',15,1200],['medication','Paracetamol 500mg (9 tablet)',9,500],['administration','Biaya Administrasi',1,$adm]],
            // 2: apt3 — angina, dr.Sari
            [['consultation','Biaya Konsultasi Spesialis Jantung',1,350000],['medication','Amlodipine 5mg (30 tablet)',30,1500],['medication','Omeprazole 20mg (14 kapsul)',14,3000],['procedure','Biaya EKG',1,150000],['administration','Biaya Administrasi',1,$admSpec]],
            // 3: apt4 — ISPA anak, dr.Ahmad (tanpa resep)
            [['consultation','Biaya Konsultasi Spesialis Anak',1,300000],['administration','Biaya Administrasi',1,$admSpec]],
            // 4: apt5 — dermatitis, dr.Nila
            [['consultation','Biaya Konsultasi Spesialis Kulit',1,325000],['medication','Dexamethasone 0.5mg (14 tablet)',14,600],['medication','Methylprednisolone 4mg (5 tablet)',5,1500],['administration','Biaya Administrasi',1,$admSpec]],
            // 5: apt6 — ligamen, dr.Eko (tanpa obat)
            [['consultation','Biaya Konsultasi Spesialis Orthopedi',1,400000],['procedure','Biaya Rontgen Lutut',1,200000],['administration','Biaya Administrasi',1,$admSpec]],
            // 6: apt7 — DM, dr.Teguh
            [['consultation','Biaya Konsultasi Spesialis Penyakit Dalam',1,275000],['medication','Metformin 500mg (90 tablet)',90,1000],['procedure','Biaya Cek Gula Darah',1,50000],['administration','Biaya Administrasi',1,$admSpec]],
            // 7: apt8 — vertigo, dr.Budi
            [['consultation','Biaya Konsultasi Dokter Umum',1,150000],['medication','Cetirizine 10mg (3 tablet)',3,2000],['administration','Biaya Administrasi',1,$adm]],
            // 8: apt9 — febris anak, dr.Ahmad (tanpa resep)
            [['consultation','Biaya Konsultasi Spesialis Anak',1,300000],['administration','Biaya Administrasi',1,$admSpec]],
            // 9: apt10 — urtikaria, dr.Nila
            [['consultation','Biaya Konsultasi Spesialis Kulit',1,325000],['medication','Cetirizine 10mg (5 tablet)',5,2000],['administration','Biaya Administrasi',1,$admSpec]],
            // 10: apt11 — hipertensi, dr.Budi
            [['consultation','Biaya Konsultasi Dokter Umum',1,150000],['medication','Amlodipine 5mg (30 tablet)',30,1500],['administration','Biaya Administrasi',1,$adm]],
            // 11: apt12 — aritmia, dr.Sari
            [['consultation','Biaya Konsultasi Spesialis Jantung',1,350000],['medication','Amlodipine 5mg (14 tablet)',14,1500],['medication','Omeprazole 20mg (14 kapsul)',14,3000],['procedure','Biaya EKG',1,150000],['administration','Biaya Administrasi',1,$admSpec]],
            // 12: apt13 — LBP, dr.Eko
            [['consultation','Biaya Konsultasi Spesialis Orthopedi',1,400000],['medication','Ibuprofen 400mg (15 tablet)',15,1000],['medication','Methylprednisolone 4mg (5 tablet)',5,1500],['procedure','Biaya Fisioterapi',1,175000],['administration','Biaya Administrasi',1,$admSpec]],
            // 13: apt14 — resolusi tifoid, dr.Budi
            [['consultation','Biaya Konsultasi Dokter Umum',1,150000],['medication','Amoxicillin 500mg (6 kapsul)',6,2500],['medication','Omeprazole 20mg (7 kapsul)',7,3000],['administration','Biaya Administrasi',1,$adm]],
            // 14: apt15 — dislipidemia, dr.Teguh (unpaid)
            [['consultation','Biaya Konsultasi Spesialis Penyakit Dalam',1,275000],['medication','Simvastatin 20mg (30 tablet)',30,1800],['medication','Metformin 500mg (90 tablet)',90,1000],['procedure','Biaya Cek Lipid Profil',1,120000],['administration','Biaya Administrasi',1,$admSpec]],
            // 15: apt16 — angina + losartan, dr.Sari
            [['consultation','Biaya Konsultasi Spesialis Jantung',1,350000],['medication','Amlodipine 5mg (30 tablet)',30,1500],['medication','Losartan 50mg (30 tablet)',30,2000],['procedure','Biaya EKG',1,150000],['administration','Biaya Administrasi',1,$admSpec]],
            // 16: apt17 — disentri, dr.Ahmad (unpaid)
            [['consultation','Biaya Konsultasi Spesialis Anak',1,300000],['medication','Ciprofloxacin 500mg (10 tablet)',10,3500],['administration','Biaya Administrasi',1,$admSpec]],
            // 17: apt18 — sprain ankle, dr.Eko (unpaid)
            [['consultation','Biaya Konsultasi Spesialis Orthopedi',1,400000],['medication','Ibuprofen 400mg (15 tablet)',15,1000],['procedure','Biaya Rontgen Kaki',1,150000],['administration','Biaya Administrasi',1,$admSpec]],
            // 18: apt19 — faringitis walk-in, dr.Budi
            [['consultation','Biaya Konsultasi Dokter Umum',1,150000],['medication','Amoxicillin 500mg (15 kapsul)',15,2500],['medication','Paracetamol 500mg (9 tablet)',9,500],['medication','Antasida Doen (9 tablet)',9,800],['administration','Biaya Administrasi',1,$adm]],
            // 19: apt20 — gastroenteritis walk-in, dr.Teguh (unpaid)
            [['consultation','Biaya Konsultasi Spesialis Penyakit Dalam',1,275000],['medication','Antasida Doen (9 tablet)',9,800],['medication','Paracetamol 500mg (9 tablet)',9,500],['administration','Biaya Administrasi',1,$admSpec]],
        ];

        foreach ($billItems as $idx => $items) {
            $billId = $this->billIds[$idx] ?? null;
            if (! $billId) {
                continue;
            }
            foreach ($items as [$type, $desc, $qty, $price]) {
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
        }

        // Sync bills.total_amount dari SUM(bill_items.subtotal)
        DB::statement('
            UPDATE bills b
            SET b.total_amount = (
                SELECT COALESCE(SUM(bi.subtotal), 0)
                FROM bill_items bi
                WHERE bi.bill_id = b.id
            )
        ');
    }
}