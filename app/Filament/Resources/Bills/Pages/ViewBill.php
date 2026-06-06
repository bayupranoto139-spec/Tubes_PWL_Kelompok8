<?php

namespace App\Filament\Resources\Bills\Pages;

use App\Filament\Resources\Bills\BillResource;
use Filament\Resources\Pages\ViewRecord;

class ViewBill extends ViewRecord
{
    protected static string $resource = BillResource::class;

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

