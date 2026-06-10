<?php

namespace App\Filament\Resources\Bills\Pages;

use App\Filament\Resources\Bills\BillResource;
use Filament\Resources\Pages\EditRecord;

class EditBill extends EditRecord
{
    protected static string $resource = BillResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (
            $data['status'] === 'paid'
            && empty($data['payment_date'])
        ) {
            $data['payment_date'] = now();
        }

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->record->status === 'paid') {
            $this->record
                ->appointment
                ?->update(['status' => 'completed']);
        }
    }
}