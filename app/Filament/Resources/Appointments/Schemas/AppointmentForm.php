<?php

namespace App\Filament\Resources\Appointments\Schemas;

use App\Models\Doctor;
use App\Models\PatientEnrollment;
use App\Models\Schedule;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker as AppointmentDatePicker;
use Illuminate\Support\Carbon;
use Filament\Schemas\Schema;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // ── PATIENT ──────────────────────────────────────────────
                Select::make('patient_enrollment_id')
                    ->label('Patient')
                    ->options(
                        PatientEnrollment::with('user')->get()->pluck('user.name', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->required(),

                // ── DOCTOR ───────────────────────────────────────────────
                Select::make('doctor_id')
                    ->label('Doctor')
                    ->options(
                        Doctor::with('user')->get()->pluck('user.name', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive(),                           // ← must be reactive

                // ── APPOINTMENT DATE ─────────────────────────────────────
                AppointmentDatePicker::make('appointment_date')
                    ->label('Appointment Date')
                    ->required()
                    ->minDate(now())
                    ->native(false)
                    ->reactive(),                           // ← must be reactive

                // ── APPOINTMENT SLOT ─────────────────────────────────────
                Select::make('appointment_slot')
                    ->label('Appointment Time')
                    ->required()
                    ->reactive()
                    ->options(function ($get) {
                        return self::getSlots($get('doctor_id'), $get('appointment_date'));
                    })
                    ->afterStateUpdated(function ($get, $set, $state) {
                        // Keep hidden fields in sync whenever the slot changes
                        self::syncHiddenFields($get, $set);
                    }),

                // ── HIDDEN: schedule_id ───────────────────────────────────
                // Use afterStateUpdated on upstream fields (doctor + date) to set this.
                Hidden::make('schedule_id')
                    ->dehydrated(),

                // ── HIDDEN: scheduled_at ──────────────────────────────────
                Hidden::make('scheduled_at')
                    ->dehydrated(),

                Placeholder::make('appointment_rule')
                    ->content('Appointment time follows the selected doctor\'s schedule.')
                    ->columnSpanFull(),

                // ── STATUS ───────────────────────────────────────────────
                Select::make('status')
                    ->options([
                        'scheduled'  => 'Scheduled',
                        'completed'  => 'Completed',
                        'cancelled'  => 'Cancelled',
                    ])
                    ->default('scheduled')
                    ->required(),

                // ── COMPLAINT ────────────────────────────────────────────
                Textarea::make('complaint')
                    ->label('Complaint')
                    ->rows(4)
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────────────────────────────────

    /** Resolve the correct Schedule model for a doctor + date combo. */
    private static function resolveSchedule(?string $doctorId, mixed $date): ?Schedule
    {
        if (! $doctorId || ! $date) {
            return null;
        }

        $dateString = $date instanceof \Carbon\Carbon
            ? $date->format('Y-m-d')
            : (string) $date;

        $dayOfWeek = Carbon::parse($dateString)->dayOfWeekIso;

        // Prefer a schedule that matches the exact day; fall back to any active one.
        return Schedule::query()
            ->where('doctor_id', $doctorId)
            ->where('is_active', true)
            ->orderByRaw("CASE WHEN day_of_week = ? THEN 0 ELSE 1 END", [$dayOfWeek])
            ->first();
    }

    /** Build the 30-minute slot options for a doctor + date. */
    private static function getSlots(?string $doctorId, mixed $date): array
    {
        $schedule = self::resolveSchedule($doctorId, $date);

        if (! $schedule) {
            return [];
        }

        $toCarbon = fn($t) => $t instanceof \Carbon\Carbon
            ? Carbon::parse($t->format('H:i'))
            : Carbon::parse($t);

        $cursor = $toCarbon($schedule->start_time);
        $end    = $toCarbon($schedule->end_time);
        $slots  = [];

        while ($cursor->lt($end)) {
            $label         = $cursor->format('H:i');
            $slots[$label] = $label;
            $cursor->addMinutes(30);
        }

        return $slots;
    }

    /**
     * Sync schedule_id and scheduled_at hidden fields.
     * Call this from afterStateUpdated on doctor_id, appointment_date, and appointment_slot.
     */
    private static function syncHiddenFields($get, $set): void
    {
        $doctorId = $get('doctor_id');
        $date     = $get('appointment_date');
        $slot     = $get('appointment_slot');

        $schedule = self::resolveSchedule($doctorId, $date);

        $set('schedule_id', $schedule?->id);

        if ($date) {
            $dateString  = $date instanceof \Carbon\Carbon ? $date->format('Y-m-d') : (string) $date;
            $slotString  = $slot ?: '00:00';
            $set('scheduled_at', Carbon::parse("{$dateString} {$slotString}")->toDateTimeString());
        } else {
            $set('scheduled_at', null);
        }
    }
}