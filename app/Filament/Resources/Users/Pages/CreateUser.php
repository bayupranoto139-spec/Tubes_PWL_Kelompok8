<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\PatientEnrollment;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    public function hasCreateAnother(): bool
    {
        return false;
    }

    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }

    protected function afterCreate(): void
    {
        if ($this->record->role === 'pasien') {
            // Ambil hospital_id dari field patient_hospital_id (tidak disimpan ke users table)
            $hospitalId = $this->data['patient_hospital_id'] ?? null;

            if ($hospitalId) {
                // Buat PatientEnrollment seperti registrasi mandiri
                PatientEnrollment::firstOrCreate(
                    [
                        'user_id'     => $this->record->id,
                        'hospital_id' => $hospitalId,
                    ],
                    [
                        'medical_record_number' => 'MRN-' . now()->timestamp . $this->record->id,
                    ]
                );
            }

            // Kirim email verifikasi (email_verified_at masih null saat dibuat admin)
            if (empty($this->record->email_verified_at)) {
                $this->record->sendEmailVerificationNotification();
            }
        }
    }
}