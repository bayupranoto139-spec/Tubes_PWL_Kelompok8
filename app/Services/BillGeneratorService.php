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

            $appointment->loadMissing([
                'bill',
                'doctor.user',
                'patientEnrollment',
                'medicalRecord.prescriptions.medication',
            ]);

            // Idempotent: jika bill sudah ada, kembalikan yang existing
            if ($existing = $appointment->bill) {
                return $existing;
            }

            $doctor        = $appointment->doctor;
            $enrollment    = $appointment->patientEnrollment;
            $medicalRecord = $appointment->medicalRecord;

            // Kumpulkan dulu semua item beserta nilainya
            $lineItems = [];

            // Item 1: Biaya konsultasi dokter
            $consultationFee = (float) ($doctor->consultation_fee ?? 0);
            $lineItems[] = [
                'item_type'   => 'consultation',
                'description' => 'Biaya Konsultasi - Dr. ' . ($doctor->user->name ?? 'Dokter'),
                'quantity'    => 1,
                'unit_price'  => $consultationFee,
                'subtotal'    => $consultationFee,
            ];

            // Item 2+: Biaya obat dari prescription (opsional)
            if ($medicalRecord) {
                foreach ($medicalRecord->prescriptions as $prescription) {
                    $medication = $prescription->medication;
                    if (! $medication) {
                        continue;
                    }

                    $qty      = (int) $prescription->quantity;
                    $price    = (float) ($medication->price ?? 0);
                    $subtotal = $qty * $price;

                    $lineItems[] = [
                        'item_type'   => 'medication',
                        'description' => $medication->name
                            . ' (' . $prescription->dosage . ')'
                            . ($prescription->duration ? ', ' . $prescription->duration : ''),
                        'quantity'    => $qty,
                        'unit_price'  => $price,
                        'subtotal'    => $subtotal,
                    ];
                }
            }

            $totalAmount = collect($lineItems)->sum('subtotal');

            $bill = Bill::create([
                'patient_enrollment_id' => $enrollment->id,
                'appointment_id'        => $appointment->id,
                'total_amount'          => $totalAmount,
                'status'                => 'unpaid',
                'payment_due_date'      => now()->addDays(7),
            ]);

            foreach ($lineItems as $item) {
                BillItem::create(array_merge(['bill_id' => $bill->id], $item));
            }

            // Satu kali recalculate untuk memastikan sinkron dengan DB
            $bill->recalculateTotal();

            return $bill->refresh();
        });
    }
}