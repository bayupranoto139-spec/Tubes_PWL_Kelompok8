<?php

namespace App\Filament\Resources\Bills\Pages;

use App\Filament\Resources\Bills\BillResource;
use App\Models\Appointment;
use Filament\Resources\Pages\CreateRecord;

class CreateBill extends CreateRecord
{
    protected static string $resource = BillResource::class;

    protected function getRedirectUrl(): string
    {
        return static::$resource::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $appointment = Appointment::with([
            'doctor',
            'medicalRecord.prescriptions.medication',
        ])->find($data['appointment_id']);

        $total = (float) $appointment->doctor->consultation_fee;

        foreach (
            $appointment->medicalRecord->prescriptions as $prescription
        ) {
            $total +=
                $prescription->quantity *
                $prescription->medication->price;
        }

        $data['total_amount'] = $total;

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record
            ->appointment
            ->update([
                'status' => 'completed',
            ]);
    }
}
