<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Filament\Resources\Appointments\AppointmentResource;
use App\Services\QueueService;
use Filament\Resources\Pages\CreateRecord;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    /**
     * Auto-create queue entry setelah appointment berhasil dibuat.
     * Queue dibuat dengan priority sesuai tipe (appointment=1, walk_in=2).
     */
    protected function afterCreate(): void
    {
        QueueService::createForAppointment($this->getRecord());
    }

    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }
}