<?php

namespace App\Filament\Resources\Hospitals\Pages;

use App\Filament\Resources\Hospitals\HospitalResource;
use Filament\Resources\Pages\ViewRecord;

class ViewHospital extends ViewRecord
{
    protected static string $resource = HospitalResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function fillForm(): void
    {
        $this->fillFormWithDataAndCallHooks($this->getRecord(), [
            ...$this->getRecord()->attributesToArray(),
        ]);
    }
}

