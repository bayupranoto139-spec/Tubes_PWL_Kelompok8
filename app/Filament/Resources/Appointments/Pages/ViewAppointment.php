<?php

namespace App\Filament\Resources\Appointments\Pages;

use App\Filament\Resources\Appointments\AppointmentResource;
use App\Services\BillGeneratorService;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\DB;

class ViewAppointment extends ViewRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getHeaderActions(): array
    {
        $role = filament()->auth()->user()?->role;
        $isSuperAdmin = $role === 'super_admin';

        return [
            Action::make('complete_appointment')
                ->label('Selesaikan & Buat Tagihan')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Selesaikan Appointment')
                ->modalDescription('Appointment akan ditandai selesai dan tagihan akan dibuat otomatis berdasarkan biaya konsultasi dan resep obat (jika ada). Lanjutkan?')
                ->modalSubmitActionLabel('Ya, Selesaikan')
                ->visible(fn () => ! $isSuperAdmin && $this->getRecord()->status !== 'completed')
                ->action(function () {
                    $appointment = $this->getRecord()->load([
                        'medicalRecord.prescriptions.medication',
                        'doctor.user',
                        'patientEnrollment',
                        'bill',
                    ]);

                    if (! $appointment->medicalRecord) {
                        Notification::make()
                            ->title('Rekam medis belum diisi')
                            ->body('Isi rekam medis terlebih dahulu sebelum menyelesaikan appointment.')
                            ->danger()
                            ->send();

                        return;
                    }

                    DB::transaction(function () use ($appointment) {
                        $appointment->update(['status' => 'completed']);
                        app(BillGeneratorService::class)->generate($appointment->fresh([
                            'medicalRecord.prescriptions.medication',
                            'doctor.user',
                            'patientEnrollment',
                            'bill',
                        ]));
                    });

                    Notification::make()
                        ->title('Appointment selesai')
                        ->body('Tagihan telah dibuat dan langsung tersedia di panel pasien dan admin.')
                        ->success()
                        ->send();

                    $this->refreshFormData(['status']);
                }),

            EditAction::make()
                ->visible(! $isSuperAdmin),
        ];
    }
}