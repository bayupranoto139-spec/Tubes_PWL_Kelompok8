<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function fillForm(): void
    {
        $record = $this->getRecord();

        $payload = [
            ...$record->attributesToArray(),
        ];

        if (($record?->role ?? null) === 'pasien') {
            $enrollments = $record->patientEnrollments()
                ->with('hospital')
                ->get();

            // Provide all enrolled hospitals as a collection for the repeater-style view
            $payload['enrolled_hospitals'] = $enrollments
                ->map(fn ($enrollment) => [
                    'hospital_id'           => $enrollment->hospital_id,
                    'hospital_name'         => $enrollment->hospital?->name ?? '-',
                    'hospital_city'         => $enrollment->hospital?->city ?? '-',
                    'hospital_code'         => $enrollment->hospital?->code ?? '-',
                    'medical_record_number' => $enrollment->medical_record_number ?? '-',
                    'is_active'             => $enrollment->hospital?->is_active ?? false,
                ])
                ->values()
                ->toArray();

            // Keep hospital_id pointing to the first enrollment for compatibility
            $first = $enrollments->whereNotNull('hospital_id')->first();
            $payload['hospital_id'] = $first?->hospital_id;
        }

        $this->fillFormWithDataAndCallHooks($record, $payload);
    }
}