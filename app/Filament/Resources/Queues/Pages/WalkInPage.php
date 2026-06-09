<?php

namespace App\Filament\Resources\Queues\Pages;

use App\Filament\Resources\Queues\QueueResource;
use App\Models\Doctor;
use App\Models\PatientEnrollment;
use App\Models\Schedule;
use Filament\Resources\Pages\Page;

class WalkInPage extends Page
{
    protected static string $resource = QueueResource::class;

    protected string $view = 'filament.resources.queues.pages.walk-in';

    protected static ?string $title = 'Daftarkan Pasien Walk-in';

    /**
     * Kirim data pasien & dokter ke blade view.
     * Form dihandle via POST biasa (bukan Livewire).
     */
    public function getViewData(): array
    {
        $hospitalId = filament()->auth()->user()?->hospital_id;
        $todayDow   = now()->dayOfWeekIso;

        $patients = PatientEnrollment::with('user')
            ->where('hospital_id', $hospitalId)
            ->get()
            ->mapWithKeys(fn ($e) => [
                $e->id => $e->user->name . ' (' . $e->medical_record_number . ')',
            ]);

        $doctors = Doctor::with(['user', 'specialization'])
            ->whereHas('user', fn ($q) => $q->where('hospital_id', $hospitalId))
            ->whereHas(
                'schedules',
                fn ($q) => $q->where('day_of_week', $todayDow)->where('is_active', true)
            )
            ->get();

        return compact('patients', 'doctors');
    }
}