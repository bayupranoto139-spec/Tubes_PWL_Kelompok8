<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Bill;
use App\Models\BillItem;
use Illuminate\Support\Facades\DB;

class BillGeneratorService
{
    /**
     * Generate bill dari appointment yang sudah completed.
     * Idempotent — jika bill sudah ada, kembalikan yang existing.
     */
    public function generate(Appointment $appointment): Bill
    {
        return DB::transaction(function () use ($appointment) {

            // Idempotent: jika bill sudah ada, kembalikan yang existing
            $appointment->loadMissing(['bill', 'doctor.user', 'patientEnrollment', 'medicalRecord.prescriptions.medication']);

            if ($existing = $appointment->bill) {
                return $existing;
            }

            $doctor        = $appointment->doctor;
            $enrollment    = $appointment->patientEnrollment;
            $medicalRecord = $appointment->medicalRecord;

            // Buat bill header
            $bill = Bill::create([
                'patient_enrollment_id' => $enrollment->id,
                'appointment_id'        => $appointment->id,
                'total_amount'          => 0, // akan di-recalculate otomatis via BillItem::saved()
                'status'                => 'unpaid',
                'payment_due_date'      => now()->addDays(7),
            ]);

            // Item 1: Biaya konsultasi dokter
            BillItem::create([
                'bill_id'     => $bill->id,
                'item_type'   => 'consultation',
                'description' => 'Biaya Konsultasi - Dr. ' . ($doctor->user->name ?? 'Dokter'),
                'quantity'    => 1,
                'unit_price'  => $doctor->consultation_fee ?? 0,
            ]);

            // Item 2+: Biaya obat dari prescription (opsional)
            if ($medicalRecord) {
                foreach ($medicalRecord->prescriptions as $prescription) {
                    $medication = $prescription->medication;
                    if (! $medication) {
                        continue;
                    }

                    BillItem::create([
                        'bill_id'     => $bill->id,
                        'item_type'   => 'medication',
                        'description' => $medication->name
                            . ' (' . $prescription->dosage . ')'
                            . ($prescription->duration ? ', ' . $prescription->duration : ''),
                        'quantity'    => $prescription->quantity,
                        'unit_price'  => $medication->price ?? 0,
                    ]);
                }
            }

            // Refresh agar total_amount terbaru (sudah di-trigger BillItem::saved())
            return $bill->refresh();
        });
    }
}