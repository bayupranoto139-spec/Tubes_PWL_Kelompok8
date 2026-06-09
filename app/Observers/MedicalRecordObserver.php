<?php

namespace App\Observers;

use App\Models\MedicalRecord;

class MedicalRecordObserver
{
    /**
     * Bill TIDAK dibuat di sini.
     *
     * Bill di-generate oleh BillGeneratorService saat dokter
     * menyelesaikan appointment (status → completed) melalui
     * AppointmentCompleteController atau Filament ViewAppointment action.
     * Ini memastikan prescription sudah lengkap sebelum bill dibuat,
     * dan total_amount selalu akurat.
     */
    public function created(MedicalRecord $medicalRecord): void
    {
        // intentionally empty
    }

    public function updated(MedicalRecord $medicalRecord): void
    {
        //
    }

    public function deleted(MedicalRecord $medicalRecord): void
    {
        //
    }

    public function restored(MedicalRecord $medicalRecord): void
    {
        //
    }

    public function forceDeleted(MedicalRecord $medicalRecord): void
    {
        //
    }
}