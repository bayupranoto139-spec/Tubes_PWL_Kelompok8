<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrescriptionStoreController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => ['required', 'integer'],
            'medication_id'  => ['required', 'integer'],
            'dosage'         => ['required', 'string', 'max:255'],
            'duration'       => ['required', 'string', 'max:255'],
            'quantity'       => ['required', 'integer', 'min:1'],
            'notes'          => ['nullable', 'string', 'max:1000'],
        ]);

        $doctorId = auth()->id();

        // Pastikan user memiliki relasi doctor
        $doctorId = auth()->user()?->doctor?->id;

        DB::transaction(function () use ($request, $doctorId) {
            $appointment = DB::table('appointments')
                ->where('id', $request->input('appointment_id'))
                ->where('doctor_id', $doctorId)
                ->first();

            if (!$appointment) {
                abort(403, 'Appointment tidak ditemukan untuk dokter ini.');
            }

            // medical_record_id diambil dari medical_records berdasarkan appointment_id
            $medicalRecordId = DB::table('medical_records')
                ->where('appointment_id', $request->input('appointment_id'))
                ->value('id');

            if (!$medicalRecordId) {
                abort(422, 'Medical record untuk appointment ini belum ada.');
            }

            DB::table('prescriptions')->insert([
                'medical_record_id' => $medicalRecordId,
                'medication_id'     => $request->input('medication_id'),
                'dosage'            => $request->input('dosage'),
                'duration'          => $request->input('duration'),
                'quantity'          => $request->input('quantity'),
                'notes'             => $request->input('notes'),
                'created_at'        => now(),
                'updated_at'        => now(),
            ]);
        });

        return redirect()->route('doctor.today')
            ->with('success', 'Resep berhasil ditambahkan. Pasien siap diselesaikan.');
    }
}