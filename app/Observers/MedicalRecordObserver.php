<?php

namespace App\Observers;

use App\Models\Bill;
use App\Models\MedicalRecord;
use Carbon\Carbon;

class MedicalRecordObserver
{
    /**
     * Handle the MedicalRecord "created" event.
     */
    public function created(MedicalRecord $medicalRecord): void
    {
        // Ambil biaya konsultasi dari dokter
        $consultationFee = $medicalRecord->doctor->consultation_fee ?? 0;

        // Hitung total biaya obat jika ada relasi (sederhana, abaikan dulu)
        $total = $consultationFee;

        // Cek apakah sudah ada bill untuk appointment atau kunjungan ini? 
        // Kita asumsikan setiap medical record menghasilkan bill baru.
        Bill::create([
            'patient_id' => $medicalRecord->patient_id,
            'appointment_id' => $medicalRecord->appointment_id,
            'total_amount' => $total,
            'status' => 'unpaid',
            'payment_due_date' => Carbon::now()->addDays(7),
        ]);
    }

    /**
     * Handle the MedicalRecord "updated" event.
     */
    public function updated(MedicalRecord $medicalRecord): void
    {
        //
    }

    /**
     * Handle the MedicalRecord "deleted" event.
     */
    public function deleted(MedicalRecord $medicalRecord): void
    {
        //
    }

    /**
     * Handle the MedicalRecord "restored" event.
     */
    public function restored(MedicalRecord $medicalRecord): void
    {
        //
    }

    /**
     * Handle the MedicalRecord "force deleted" event.
     */
    public function forceDeleted(MedicalRecord $medicalRecord): void
    {
        //
    }
}
