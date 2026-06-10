<?php

namespace App\Filament\Resources\MedicalRecords\Pages;

use App\Filament\Resources\MedicalRecords\MedicalRecordResource;
use App\Models\Appointment;
use Filament\Resources\Pages\CreateRecord;

class CreateMedicalRecord extends CreateRecord
{
    protected static string $resource = MedicalRecordResource::class;

    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
{
    if (! isset($data['appointment_id'])) {
        throw new \Exception('Appointment tidak ditemukan.');
    }

    $appointment = Appointment::findOrFail($data['appointment_id']);

    $data['visit_date'] = $appointment->scheduled_at;

    return $data;
}
}