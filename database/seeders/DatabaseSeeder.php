<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID'); // Indonesian locale for realistic names

        // Disable foreign key checks to avoid constraint issues
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables in correct order (optional – uncomment if needed)
        // $this->truncateTables();

        // ------------------------------------------------------------------
        // 1. Hospitals (min 5, all active)
        // ------------------------------------------------------------------
        $hospitals = [];
        $hospitalNames = [
            'RS Umum Sehat Sejahtera', 'RS Harapan Bunda', 'RS Mitra Keluarga',
            'RS Sari Asih', 'RS Permata Medika'
        ];
        $cities = ['Medan', 'Jakarta', 'Surabaya', 'Bandung', 'Semarang'];
        for ($i = 0; $i < 5; $i++) {
            $code = 'HOSP-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT);
            $id = DB::table('hospitals')->insertGetId([
                'name'       => $hospitalNames[$i],
                'code'       => $code,
                'city'       => $cities[$i % count($cities)],
                'address'    => $faker->address,
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $hospitals[] = ['id' => $id, 'name' => $hospitalNames[$i], 'code' => $code];
        }

        // ------------------------------------------------------------------
        // 2. Specializations (min 5)
        // ------------------------------------------------------------------
        $specs = [
            ['name' => 'Dokter Umum', 'description' => 'Pelayanan kesehatan umum'],
            ['name' => 'Spesialis Jantung', 'description' => 'Penanganan penyakit jantung'],
            ['name' => 'Spesialis Anak', 'description' => 'Pelayanan kesehatan anak'],
            ['name' => 'Spesialis Penyakit Dalam', 'description' => 'Penyakit organ dalam'],
            ['name' => 'Spesialis Kulit', 'description' => 'Penyakit kulit dan kelamin'],
            ['name' => 'Spesialis Orthopedi', 'description' => 'Tulang, sendi dan otot'],
        ];
        $specializations = [];
        foreach ($specs as $spec) {
            $id = DB::table('specializations')->insertGetId([
                'name'        => $spec['name'],
                'description' => $spec['description'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
            $specializations[] = ['id' => $id, 'name' => $spec['name']];
        }

        // ------------------------------------------------------------------
        // 3. Users (super_admin, admin_rs, staff, doctors, patients)
        // ------------------------------------------------------------------
        // 3a. Super Admin
        DB::table('users')->insert([
            'name'           => 'Super Administrator',
            'email'          => 'superadmin.healthmesh@gmail.com',
            'password'       => Hash::make('password'),
            'role'           => 'super_admin',
            'phone'          => '081234567890',
            'address'        => 'Jl. Contoh No. 1',
            'gender'         => 'L',
            'date_of_birth'  => '1985-01-01',
            'is_active'      => 1,
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        $adminUsers = [];
        $staffUsers = [];
        $doctorUsers = [];
        $patientUsers = [];

        // 3b. Admin RS (1 per hospital)
        foreach ($hospitals as $hospital) {
            $adminId = DB::table('users')->insertGetId([
                'name'           => $faker->name,
                'email'          => $faker->unique()->userName() . '@gmail.com',
                'password'       => Hash::make('password'),
                'role'           => 'admin_rs',
                'hospital_id'    => $hospital['id'],
                'phone'          => $faker->phoneNumber,
                'address'        => $faker->address,
                'gender'         => $faker->randomElement(['L', 'P']),
                'date_of_birth'  => $faker->date('Y-m-d', '1990-01-01'),
                'is_active'      => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
            $adminUsers[] = $adminId;
            // Insert into staff table (admin_rs also needs staff record? Actually staff table is for non-doctor employees)
            DB::table('staff')->insert([
                'user_id'     => $adminId,
                'position'    => 'Kepala Administrasi',
                'department'  => 'Administrasi',
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        // 3c. Staff (2 per hospital)
        foreach ($hospitals as $hospital) {
            for ($i = 0; $i < 2; $i++) {
                $staffId = DB::table('users')->insertGetId([
                    'name'           => $faker->name,
                    'email'          => $faker->unique()->userName() . '@gmail.com',
                    'password'       => Hash::make('password'),
                    'role'           => 'staff',
                    'hospital_id'    => $hospital['id'],
                    'phone'          => $faker->phoneNumber,
                    'address'        => $faker->address,
                    'gender'         => $faker->randomElement(['L', 'P']),
                    'date_of_birth'  => $faker->date('Y-m-d', '1995-01-01'),
                    'is_active'      => 1,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
                $staffUsers[] = $staffId;
                DB::table('staff')->insert([
                    'user_id'     => $staffId,
                    'position'    => $faker->randomElement(['Resepsionis', 'Kasir', 'Administrasi']),
                    'department'  => $faker->randomElement(['Front Office', 'Keuangan', 'Administrasi']),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }

        // 3d. Doctors (min 15, each belongs to one hospital)
        $doctorCount = 15;
        $doctorSpecializations = [];
        // Ensure there are enough general practitioners to cover full week per hospital
        $generalSpecId = null;
        foreach ($specializations as $spec) {
            if ($spec['name'] === 'Dokter Umum') {
                $generalSpecId = $spec['id'];
                break;
            }
        }
        // At least 3 GPs per hospital? Actually we need full week coverage per hospital.
        // We'll create 5 GPs and distribute across hospitals, then later ensure schedules cover all days.
        $gpIds = [];
        for ($i = 0; $i < 5; $i++) {
            $hospital = $hospitals[$i % count($hospitals)];
            $userId = DB::table('users')->insertGetId([
                'name'           => 'dr. ' . $faker->name,
                'email'          => $faker->unique()->userName() . '@gmail.com',
                'password'       => Hash::make('password'),
                'role'           => 'dokter',
                'hospital_id'    => $hospital['id'],
                'phone'          => $faker->phoneNumber,
                'address'        => $faker->address,
                'gender'         => $faker->randomElement(['L', 'P']),
                'date_of_birth'  => $faker->date('Y-m-d', '1970-01-01'),
                'is_active'      => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
            $doctorId = DB::table('doctors')->insertGetId([
                'user_id'             => $userId,
                'specialization_id'   => $generalSpecId,
                'licence_number'      => 'SIP-GP-' . $faker->unique()->numerify('#####'),
                'consultation_fee'    => 150000,
                'years_of_experience' => $faker->numberBetween(5, 20),
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
            $gpIds[] = ['doctor_id' => $doctorId, 'hospital_id' => $hospital['id'], 'user_id' => $userId];
            $doctorUsers[] = $userId;
            $doctorSpecializations[] = $doctorId;
        }

        // Fill remaining doctors (10 more) with various specializations
        $nonGpSpecs = array_filter($specializations, fn($s) => $s['name'] !== 'Dokter Umum');
        for ($i = 0; $i < 10; $i++) {
            $hospital = $hospitals[$i % count($hospitals)];
            $spec = $nonGpSpecs[array_rand($nonGpSpecs)];
            $userId = DB::table('users')->insertGetId([
                'name'           => 'dr. ' . $faker->name,
                'email'          => $faker->unique()->userName() . '@gmail.com',
                'password'       => Hash::make('password'),
                'role'           => 'dokter',
                'hospital_id'    => $hospital['id'],
                'phone'          => $faker->phoneNumber,
                'address'        => $faker->address,
                'gender'         => $faker->randomElement(['L', 'P']),
                'date_of_birth'  => $faker->date('Y-m-d', '1975-01-01'),
                'is_active'      => 1,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
            $doctorId = DB::table('doctors')->insertGetId([
                'user_id'             => $userId,
                'specialization_id'   => $spec['id'],
                'licence_number'      => 'SIP-' . $faker->unique()->numerify('#####'),
                'consultation_fee'    => $faker->randomElement([250000, 300000, 350000, 400000]),
                'years_of_experience' => $faker->numberBetween(3, 25),
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
            $doctorUsers[] = $userId;
            $doctorSpecializations[] = $doctorId;
        }

        // Collect all doctors with their hospital_id and specialization
        $allDoctors = DB::table('doctors')
            ->join('users', 'doctors.user_id', '=', 'users.id')
            ->select('doctors.id as doctor_id', 'users.hospital_id', 'doctors.specialization_id', 'doctors.consultation_fee')
            ->get();

        // 3e. Patients (min 30, active)
for ($i = 0; $i < 30; $i++) {
    $userId = DB::table('users')->insertGetId([
        'name'               => $faker->name,
        'email'              => $faker->unique()->userName() . '@gmail.com',
        'password'           => Hash::make('password'),
        'role'               => 'pasien',
        'email_verified_at'  => now(), // <-- tambahkan ini
        'phone'              => $faker->phoneNumber,
        'address'            => $faker->address,
        'gender'             => $faker->randomElement(['L', 'P']),
        'date_of_birth'      => $faker->date('Y-m-d', '2005-01-01'),
        'is_active'          => 1,
        'created_at'         => now(),
        'updated_at'         => now(),
    ]);
    $patientUsers[] = $userId;
}
        // ------------------------------------------------------------------
        // 4. Patient Enrollments (some patients enrolled in multiple hospitals)
        // ------------------------------------------------------------------
        $enrollments = [];
        foreach ($patientUsers as $patientId) {
            // Each patient enrolled in 1-3 random hospitals
            $hospitalIds = collect($hospitals)->pluck('id')->shuffle()->take(rand(1, 3))->toArray();
            foreach ($hospitalIds as $hospitalId) {
                $mrn = $faker->unique()->bothify('MRN-####-????');
                DB::table('patient_enrollments')->insert([
                    'user_id'               => $patientId,
                    'hospital_id'           => $hospitalId,
                    'medical_record_number' => $mrn,
                    'created_at'            => now(),
                    'updated_at'            => now(),
                ]);
                $enrollments[] = [
                    'patient_id'    => $patientId,
                    'hospital_id'   => $hospitalId,
                    'mrn'           => $mrn,
                ];
            }
            // Also create patient_medical_infos for each patient (1 per patient)
            DB::table('patient_medical_infos')->insert([
                'user_id'                 => $patientId,
                'blood_type'              => $faker->randomElement(['A', 'B', 'AB', 'O']),
                'allergies'               => $faker->optional(0.3)->sentence(),
                'emergency_contact_name'  => $faker->name,
                'emergency_contact_phone' => $faker->phoneNumber,
                'insurance_provider'      => $faker->optional(0.5)->company,
                'insurance_policy_number' => $faker->optional(0.5)->numerify('POL-########'),
                'created_at'              => now(),
                'updated_at'              => now(),
            ]);
        }

        // ------------------------------------------------------------------
        // 5. Medications (from the existing schema)
        // ------------------------------------------------------------------
        $medicationsData = [
            ['name' => 'Paracetamol 500mg', 'generic_name' => 'Paracetamol', 'category' => 'Analgesik', 'unit' => 'tablet', 'price' => 500],
            ['name' => 'Amoxicillin 500mg', 'generic_name' => 'Amoxicillin', 'category' => 'Antibiotik', 'unit' => 'kapsul', 'price' => 2500],
            ['name' => 'Omeprazole 20mg', 'generic_name' => 'Omeprazole', 'category' => 'Antasida', 'unit' => 'kapsul', 'price' => 3000],
            ['name' => 'Amlodipine 5mg', 'generic_name' => 'Amlodipine', 'category' => 'Antihipertensi', 'unit' => 'tablet', 'price' => 1500],
            ['name' => 'Metformin 500mg', 'generic_name' => 'Metformin', 'category' => 'Antidiabetik', 'unit' => 'tablet', 'price' => 1000],
            ['name' => 'Cetirizine 10mg', 'generic_name' => 'Cetirizine', 'category' => 'Antihistamin', 'unit' => 'tablet', 'price' => 2000],
            ['name' => 'Vitamin C 500mg', 'generic_name' => 'Ascorbic Acid', 'category' => 'Vitamin', 'unit' => 'tablet', 'price' => 500],
            ['name' => 'Antasida Doen', 'generic_name' => 'Al/Mg Hydroxide', 'category' => 'Antasida', 'unit' => 'tablet', 'price' => 800],
            ['name' => 'Salbutamol 4mg', 'generic_name' => 'Salbutamol', 'category' => 'Bronkodilator', 'unit' => 'tablet', 'price' => 1200],
            ['name' => 'Dexamethasone 0.5mg', 'generic_name' => 'Dexamethasone', 'category' => 'Kortikosteroid', 'unit' => 'tablet', 'price' => 600],
            ['name' => 'Losartan 50mg', 'generic_name' => 'Losartan', 'category' => 'Antihipertensi', 'unit' => 'tablet', 'price' => 2000],
            ['name' => 'Simvastatin 20mg', 'generic_name' => 'Simvastatin', 'category' => 'Antilipid', 'unit' => 'tablet', 'price' => 1800],
            ['name' => 'Ciprofloxacin 500mg', 'generic_name' => 'Ciprofloxacin', 'category' => 'Antibiotik', 'unit' => 'tablet', 'price' => 3500],
            ['name' => 'Ibuprofen 400mg', 'generic_name' => 'Ibuprofen', 'category' => 'NSAID', 'unit' => 'tablet', 'price' => 1000],
            ['name' => 'Methylprednisolone 4mg', 'generic_name' => 'Methylprednisolone', 'category' => 'Kortikosteroid', 'unit' => 'tablet', 'price' => 1500],
        ];
        $medicationIds = [];
        foreach ($medicationsData as $med) {
            $id = DB::table('medications')->insertGetId(array_merge($med, [
                'is_active'   => 1,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]));
            $medicationIds[] = $id;
        }

        // ------------------------------------------------------------------
        // 6. Doctor Schedules (with full week coverage for GPs per hospital)
        // ------------------------------------------------------------------
        // First, create schedules for non‑GP doctors (simple random days)
        foreach ($allDoctors as $doctor) {
            $specName = DB::table('specializations')->where('id', $doctor->specialization_id)->value('name');
            if ($specName === 'Dokter Umum') continue; // handle GPs separately

            $days = $faker->randomElements([1,2,3,4,5,6,7], rand(2,4));
            foreach ($days as $day) {
                $startTime = $faker->randomElement(['08:00:00', '09:00:00', '13:00:00']);
                $endTime = $startTime === '13:00:00' ? '17:00:00' : '12:00:00';
                DB::table('schedules')->insert([
                    'doctor_id'    => $doctor->doctor_id,
                    'day_of_week'  => $day,
                    'start_time'   => $startTime,
                    'end_time'     => $endTime,
                    'max_patients' => rand(10, 20),
                    'is_active'    => 1,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }
        }

        // GP full‑week coverage per hospital: group GPs by hospital
        $gpDoctors = DB::table('doctors')
            ->join('users', 'doctors.user_id', '=', 'users.id')
            ->where('doctors.specialization_id', $generalSpecId)
            ->select('doctors.id as doctor_id', 'users.hospital_id')
            ->get()
            ->groupBy('hospital_id');

        foreach ($gpDoctors as $hospitalId => $gps) {
            $daysToCover = [1,2,3,4,5,6,7]; // Monday to Sunday
            // Distribute days among GPs of this hospital
            $numGPs = count($gps);
            if ($numGPs == 0) continue;
            $assignments = [];
            foreach ($daysToCover as $day) {
                $gpIndex = $day % $numGPs;
                $assignments[$day] = $gps[$gpIndex]->doctor_id;
            }
            // Create schedule entries for each assigned day
            foreach ($assignments as $day => $docId) {
                // Morning or afternoon shift? Alternate
                $startTime = ($day % 2 == 0) ? '08:00:00' : '13:00:00';
                $endTime = ($startTime == '08:00:00') ? '12:00:00' : '17:00:00';
                DB::table('schedules')->insert([
                    'doctor_id'    => $docId,
                    'day_of_week'  => $day,
                    'start_time'   => $startTime,
                    'end_time'     => $endTime,
                    'max_patients' => 15,
                    'is_active'    => 1,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }
        }

        // ------------------------------------------------------------------
        // 7. Appointments (min 50, many on June 10)
        // ------------------------------------------------------------------
        $appointments = [];
        $targetDate = '2026-06-10'; // many appointments on this date
        $startDate = strtotime('2026-05-01');
        $endDate = strtotime('2026-06-30');

        // Get all active schedules with doctor info
        $schedules = DB::table('schedules')
            ->join('doctors', 'schedules.doctor_id', '=', 'doctors.id')
            ->join('users', 'doctors.user_id', '=', 'users.id')
            ->select('schedules.*', 'doctors.consultation_fee', 'users.hospital_id')
            ->where('schedules.is_active', 1)
            ->get();

        $appointmentCount = 0;
        $targetCount = 60; // we'll create 60 appointments

        while ($appointmentCount < $targetCount) {
            // Pick a random schedule
            $schedule = $schedules->random();
            $dayOfWeek = $schedule->day_of_week;
            // Generate date between May 1 and June 30 that matches day_of_week
            $date = null;
            if ($appointmentCount < 20) { // force many on June 10
                $date = '2026-06-10';
                if (date('N', strtotime($date)) != $dayOfWeek) {
                    continue; // skip if not matching day of week
                }
            } else {
                $timestamp = rand($startDate, $endDate);
                $date = date('Y-m-d', $timestamp);
                if (date('N', strtotime($date)) != $dayOfWeek) {
                    continue;
                }
            }
            // Generate time within schedule window
            $startHour = intval(substr($schedule->start_time, 0, 2));
            $startMin = intval(substr($schedule->start_time, 3, 2));
            $endHour = intval(substr($schedule->end_time, 0, 2));
            $endMin = intval(substr($schedule->end_time, 3, 2));
            $hour = rand($startHour, $endHour - 1);
            $minute = rand(0, 59);
            if ($hour == $endHour - 1 && $minute >= $endMin) $minute = $endMin - 1;
            $time = sprintf('%02d:%02d:00', $hour, $minute);
            $scheduledAt = "$date $time";

            // Choose patient enrollment that belongs to the same hospital as the doctor
            $possibleEnrollments = DB::table('patient_enrollments')
                ->where('hospital_id', $schedule->hospital_id)
                ->pluck('id')
                ->toArray();
            if (empty($possibleEnrollments)) continue;
            $enrollmentId = $faker->randomElement($possibleEnrollments);

            // Determine status: mostly completed and scheduled, some cancelled/no_show
            $status = 'scheduled';
            $randStatus = rand(1, 10);
            if ($scheduledAt < now()) {
                if ($randStatus <= 6) $status = 'completed';
                elseif ($randStatus <= 8) $status = 'cancelled';
                else $status = 'no_show';
            } else {
                $status = 'scheduled';
            }

            $appointmentId = DB::table('appointments')->insertGetId([
                'patient_enrollment_id' => $enrollmentId,
                'schedule_id'           => $schedule->id,
                'doctor_id'             => $schedule->doctor_id,
                'scheduled_at'          => $scheduledAt,
                'status'                => $status,
                'complaint'             => $faker->sentence,
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);
            $appointments[] = [
                'id'                => $appointmentId,
                'doctor_id'         => $schedule->doctor_id,
                'enrollment_id'     => $enrollmentId,
                'scheduled_at'      => $scheduledAt,
                'status'            => $status,
                'consultation_fee'  => $schedule->consultation_fee,
            ];
            $appointmentCount++;
        }

        // Create some walk‑in appointments (schedule_id = null)
        $walkInCount = 10;
        for ($i = 0; $i < $walkInCount; $i++) {
            $doctor = $allDoctors->random();
            $possibleEnrollments = DB::table('patient_enrollments')
                ->where('hospital_id', $doctor->hospital_id)
                ->pluck('id')
                ->toArray();
            if (empty($possibleEnrollments)) continue;
            $enrollmentId = $faker->randomElement($possibleEnrollments);
            $scheduledAt = $faker->dateTimeBetween('2026-05-01', '2026-06-30')->format('Y-m-d H:i:s');
            $status = $scheduledAt < now() ? 'completed' : 'scheduled';
            $appointmentId = DB::table('appointments')->insertGetId([
                'patient_enrollment_id' => $enrollmentId,
                'schedule_id'           => null,
                'doctor_id'             => $doctor->doctor_id,
                'scheduled_at'          => $scheduledAt,
                'status'                => $status,
                'complaint'             => $faker->sentence,
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);
            $appointments[] = [
                'id'                => $appointmentId,
                'doctor_id'         => $doctor->doctor_id,
                'enrollment_id'     => $enrollmentId,
                'scheduled_at'      => $scheduledAt,
                'status'            => $status,
                'consultation_fee'  => $doctor->consultation_fee,
            ];
        }

        // ------------------------------------------------------------------
        // 8. Bills, Bill Items, Medical Records, Prescriptions, Queues
        // ------------------------------------------------------------------
        $procedureItems = [
            ['description' => 'Biaya EKG', 'price' => 150000],
            ['description' => 'Biaya Rontgen', 'price' => 200000],
            ['description' => 'Biaya Fisioterapi', 'price' => 175000],
            ['description' => 'Biaya Cek Gula Darah', 'price' => 50000],
            ['description' => 'Biaya USG', 'price' => 250000],
        ];

        foreach ($appointments as $app) {
            // Create bill
            $dueDate = date('Y-m-d', strtotime($app['scheduled_at'] . ' +7 days'));
            $billId = DB::table('bills')->insertGetId([
                'patient_enrollment_id' => $app['enrollment_id'],
                'appointment_id'        => $app['id'],
                'total_amount'          => 0, // will update after items
                'status'                => $faker->randomElement(['unpaid', 'paid']),
                'payment_due_date'      => $dueDate,
                'created_at'            => now(),
                'updated_at'            => now(),
            ]);

            // Build bill items
            $items = [];
            $total = 0;

            // Consultation fee
            $consultFee = $app['consultation_fee'];
            $items[] = [
                'bill_id'     => $billId,
                'item_type'   => 'consultation',
                'description' => 'Biaya Konsultasi',
                'quantity'    => 1,
                'unit_price'  => $consultFee,
                'subtotal'    => $consultFee,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
            $total += $consultFee;

            // Add 1-3 random medications
            $medCount = rand(1, 3);
            $selectedMeds = $faker->randomElements($medicationIds, $medCount);
            foreach ($selectedMeds as $medId) {
                $med = DB::table('medications')->find($medId);
                $qty = rand(5, 30);
                $subtotal = $med->price * $qty;
                $items[] = [
                    'bill_id'     => $billId,
                    'item_type'   => 'medication',
                    'description' => $med->name,
                    'quantity'    => $qty,
                    'unit_price'  => $med->price,
                    'subtotal'    => $subtotal,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
                $total += $subtotal;
            }

            // Optionally add a procedure (30% chance)
            if (rand(1, 10) <= 3) {
                $proc = $faker->randomElement($procedureItems);
                $items[] = [
                    'bill_id'     => $billId,
                    'item_type'   => 'procedure',
                    'description' => $proc['description'],
                    'quantity'    => 1,
                    'unit_price'  => $proc['price'],
                    'subtotal'    => $proc['price'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
                $total += $proc['price'];
            }

            // Add administration fee
            $adminFee = 25000;
            $items[] = [
                'bill_id'     => $billId,
                'item_type'   => 'administration',
                'description' => 'Biaya Administrasi',
                'quantity'    => 1,
                'unit_price'  => $adminFee,
                'subtotal'    => $adminFee,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
            $total += $adminFee;

            DB::table('bill_items')->insert($items);
            DB::table('bills')->where('id', $billId)->update(['total_amount' => $total]);

            // For completed appointments: create medical record, prescriptions, and possibly mark bill as paid
            if ($app['status'] === 'completed') {
                $visitDate = $app['scheduled_at'];
                $medicalRecordId = DB::table('medical_records')->insertGetId([
                    'appointment_id'  => $app['id'],
                    'visit_date'      => $visitDate,
                    'diagnosis'       => $faker->sentence,
                    'treatment_plan'  => $faker->paragraph,
                    'notes'           => $faker->optional()->sentence,
                    'case_status'     => $faker->randomElement(['active', 'healed']),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);

                // Add prescriptions based on medications from bill items
                $prescribedMeds = DB::table('bill_items')
                    ->where('bill_id', $billId)
                    ->where('item_type', 'medication')
                    ->get();
                foreach ($prescribedMeds as $item) {
                    // Find medication id by name
                    $med = DB::table('medications')->where('name', $item->description)->first();
                    if ($med) {
                        DB::table('prescriptions')->insert([
                            'medical_record_id' => $medicalRecordId,
                            'medication_id'     => $med->id,
                            'dosage'            => $faker->randomElement(['1x1', '2x1', '3x1']),
                            'duration'          => rand(3, 14) . ' hari',
                            'quantity'          => $item->quantity,
                            'notes'             => $faker->optional()->sentence,
                            'created_at'        => now(),
                            'updated_at'        => now(),
                        ]);
                    }
                }

                // Randomly mark bill as paid (if not already)
                if (rand(1, 10) <= 7) {
                    DB::table('bills')
                        ->where('id', $billId)
                        ->update([
                            'status'         => 'paid',
                            'payment_method' => $faker->randomElement(['cash', 'bank_transfer', 'qris', 'insurance']),
                            'payment_date'   => now(),
                            'reference_number' => $faker->optional()->bothify('REF-########'),
                        ]);
                }
            }

            // Create queue entry for each appointment
            $queueDate = date('Y-m-d', strtotime($app['scheduled_at']));
            $queueNumber = DB::table('queues')->where('queue_date', $queueDate)->max('queue_number') + 1 ?? 1;
            $type = ($app['schedule_id'] ?? false) ? 'appointment' : 'walk_in';
            $queueStatus = 'waiting';
            $priority = ($type === 'appointment' && $app['status'] !== 'cancelled') ? 1 : 2;
            if ($app['status'] === 'completed') $queueStatus = 'completed';
            elseif ($app['status'] === 'cancelled') $queueStatus = 'skipped';

            DB::table('queues')->insert([
                'appointment_id' => $app['id'],
                'queue_date'     => $queueDate,
                'queue_number'   => $queueNumber,
                'type'           => $type,
                'status'         => $queueStatus,
                'priority'       => $priority,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * Optional: truncate tables (uncomment if needed for fresh seed)
     */
    private function truncateTables()
    {
        DB::table('queues')->truncate();
        DB::table('prescriptions')->truncate();
        DB::table('medical_records')->truncate();
        DB::table('bill_items')->truncate();
        DB::table('bills')->truncate();
        DB::table('appointments')->truncate();
        DB::table('schedules')->truncate();
        DB::table('medications')->truncate();
        DB::table('patient_enrollments')->truncate();
        DB::table('patient_medical_infos')->truncate();
        DB::table('staff')->truncate();
        DB::table('doctors')->truncate();
        DB::table('users')->truncate();
        DB::table('specializations')->truncate();
        DB::table('hospitals')->truncate();
    }
}