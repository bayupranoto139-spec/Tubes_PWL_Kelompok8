<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\Doctor;
use App\Models\Schedule;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }

    /*
    |--------------------------------------------------------------------------
    | Pre-fill doctor fields when opening edit form
    |--------------------------------------------------------------------------
    */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();

        if ($record->role === 'dokter') {
            $doctor = $record->doctor;

            if ($doctor) {
                $data['doctor'] = [
                    'specialization_id'   => $doctor->specialization_id,
                    'licence_number'      => $doctor->licence_number,
                    'consultation_fee'    => $doctor->consultation_fee,
                    'years_of_experience' => $doctor->years_of_experience,
                ];

                $data['doctor_schedules'] = $doctor->schedules()
                    ->orderBy('day_of_week')
                    ->get()
                    ->map(fn ($s) => [
                        'id'           => $s->id,
                        'day_of_week'  => $s->day_of_week,
                        'start_time'   => $s->start_time,
                        'end_time'     => $s->end_time,
                        'max_patients' => $s->max_patients,
                        'is_active'    => $s->is_active,
                    ])
                    ->toArray();
            }
        }

        return $data;
    }

    /*
    |--------------------------------------------------------------------------
    | Save doctor fields after user record is updated
    |--------------------------------------------------------------------------
    */
    protected function afterSave(): void
    {
        $record = $this->record;

        if ($record->role !== 'dokter') {
            return;
        }

        $doctorData = $this->data['doctor'] ?? [];

        $doctor = Doctor::updateOrCreate(
            ['user_id' => $record->id],
            [
                'specialization_id'   => $doctorData['specialization_id'] ?? null,
                'licence_number'      => $doctorData['licence_number'] ?? '',
                'consultation_fee'    => $doctorData['consultation_fee'] ?? 0,
                'years_of_experience' => $doctorData['years_of_experience'] ?? 0,
            ]
        );

        $incoming   = collect($this->data['doctor_schedules'] ?? []);
        $incomingIds = $incoming->pluck('id')->filter()->values()->toArray();

        // Remove schedules deleted in the repeater
        $doctor->schedules()->whereNotIn('id', $incomingIds)->delete();

        foreach ($incoming as $row) {
            Schedule::updateOrCreate(
                ['id' => $row['id'] ?? null],
                [
                    'doctor_id'    => $doctor->id,
                    'day_of_week'  => $row['day_of_week'],
                    'start_time'   => $row['start_time'],
                    'end_time'     => $row['end_time'],
                    'max_patients' => $row['max_patients'] ?? 10,
                    'is_active'    => $row['is_active'] ?? true,
                ]
            );
        }
    }
}