<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\Doctor;
use App\Models\PatientEnrollment;
use App\Models\Schedule;
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
        $record = $this->record;

        /*
        |--------------------------------------------------------------------------
        | PASIEN — buat PatientEnrollment & kirim verifikasi email
        |--------------------------------------------------------------------------
        */
        if ($record->role === 'pasien') {
            $hospitalId = $this->data['patient_hospital_id'] ?? null;

            if ($hospitalId) {
                PatientEnrollment::firstOrCreate(
                    [
                        'user_id'     => $record->id,
                        'hospital_id' => $hospitalId,
                    ],
                    [
                        'medical_record_number' => 'MRN-' . now()->timestamp . $record->id,
                    ]
                );
            }

            if (empty($record->email_verified_at)) {
                $record->sendEmailVerificationNotification();
            }

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | DOKTER — buat Doctor record + Schedules
        |--------------------------------------------------------------------------
        */
        if ($record->role === 'dokter') {
            $doctorData = $this->data['doctor'] ?? [];

            $doctor = Doctor::create([
                'user_id'              => $record->id,
                'specialization_id'    => $doctorData['specialization_id'] ?? null,
                'licence_number'       => $doctorData['licence_number'] ?? '',
                'consultation_fee'     => $doctorData['consultation_fee'] ?? 0,
                'years_of_experience'  => $doctorData['years_of_experience'] ?? 0,
            ]);

            $schedules = $this->data['doctor_schedules'] ?? [];

            foreach ($schedules as $row) {
                Schedule::create([
                    'doctor_id'    => $doctor->id,
                    'day_of_week'  => $row['day_of_week'],
                    'start_time'   => $row['start_time'],
                    'end_time'     => $row['end_time'],
                    'max_patients' => $row['max_patients'] ?? 10,
                    'is_active'    => $row['is_active'] ?? true,
                ]);
            }
        }
    }
}