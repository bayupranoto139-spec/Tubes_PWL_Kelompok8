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

        // If the record is a pasien, set hospital_id just for compatibility,
        // but we will override the view data with hospital list via a form field.
        // (This app currently uses form rendering on View.)
        $payload = [
            ...$record->attributesToArray(),
        ];

        // Provide a virtual field value for hospital list (rendered in UserForm via TextEntry-like component
        // is not currently implemented). For now we ensure the model has hospital_id not null for pasien,
        // by picking first enrollment hospital.
        if (($record?->role ?? null) === 'pasien') {

            $payload['registered_hospitals'] = $record->patientEnrollments()
                ->with('hospital')
                ->get()
                ->pluck('hospital.name')
                ->filter()
                ->implode(', ');

            $first = $record->patientEnrollments()
                ->whereNotNull('hospital_id')
                ->first();

            $payload['hospital_id'] = $first?->hospital_id;
        }

        $this->fillFormWithDataAndCallHooks($record, $payload);
    }
}
