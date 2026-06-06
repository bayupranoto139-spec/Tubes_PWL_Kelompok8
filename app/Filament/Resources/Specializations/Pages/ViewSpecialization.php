<?php

namespace App\Filament\Resources\Specializations\Pages;

use App\Filament\Resources\Specializations\SpecializationResource;
use Filament\Resources\Pages\ViewRecord;

class ViewSpecialization extends ViewRecord
{
    protected static string $resource = SpecializationResource::class;

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

