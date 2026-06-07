<?php

namespace App\Filament\Resources\Appointments\Schemas;

use App\Models\Doctor;
use App\Models\PatientEnrollment;
use App\Models\Schedule;
use Filament\Forms\Components\DatePicker as AppointmentDatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // ── PATIENT ──────────────────────────────────────────────
                Select::make('patient_enrollment_id')
                    ->label('Patient')
                    ->options(function () {
                        $authUser = filament()->auth()->user();

                        $query = PatientEnrollment::with('user');

                        // staff/admin_rs must only select patients within their hospital
                        // (hospital_id lives on patient_enrollments)
                        if ($authUser && $authUser->hospital_id) {
                            $query->where('hospital_id', $authUser->hospital_id);
                        }

                        return $query->get()->pluck('user.name', 'id');
                    })

                    ->searchable()
                    ->preload()
                    ->required()
                    ->getSearchResultsUsing(function (string $search) {

                        $user = filament()->auth()->user();

                        $q = PatientEnrollment::query()
                            ->with('user')
                            ->whereHas('user', function ($u) use ($search) {
                                $u->where(function ($w) use ($search) {
                                    $w->where('name', 'like', "%{$search}%")
                                        ->orWhere('email', 'like', "%{$search}%")
                                        ->orWhere('phone', 'like', "%{$search}%");
                                });
                            });

                        if ($user && $user->hospital_id) {
                            $q->whereHas('user', fn ($u) => $u->where('hospital_id', $user->hospital_id));
                        }

                        return $q->limit(20)->get()->pluck('user.name', 'id')->toArray();

                    }),

                // ── DOCTOR ───────────────────────────────────────────────
                Select::make('doctor_id')
                    ->label('Doctor')
                    ->options(function () {
                        $user = filament()->auth()->user();

                        $query = Doctor::with('user');

                        // staff/admin_rs must only select doctors within their hospital
                        if ($user && $user->hospital_id) {
                            $query->whereHas('user', fn ($q) => $q->where('hospital_id', $user->hospital_id));
                        }

                        return $query->get()->pluck('user.name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive()                           // ← must be reactive
                    ->afterStateUpdated(function ($get, $set) {
                        // If doctor changes, ensure slot remains valid for that doctor.
                        // Also clear hidden fields until a valid slot is chosen.
                        $set('appointment_slot', null);
                        $set('schedule_id', null);
                        $set('scheduled_at', null);
                    }),

                // ── APPOINTMENT DATE ─────────────────────────────────────
                AppointmentDatePicker::make('appointment_date')
                    ->label('Appointment Date')
                    ->required()
                    ->minDate(now())
                    ->native(false)
                    ->reactive()                           // ← must be reactive
                    ->disabled(function ($get) {
                        return ! filled($get('doctor_id'));
                    })
                    ->disabledDates(function ($get) {
                        $doctorId = $get('doctor_id');

                        if (! $doctorId) {
                            return [];
                        }

                        // Disable a date if the doctor has no active schedule on that weekday.
                        // Use a rolling window to keep UI responsive.
                        $start = now()->startOfDay();
                        $end = now()->addDays(60)->startOfDay();

                        $disabled = [];
                        $cursor = $start->copy();

                        while ($cursor->lte($end)) {
                            $iso = $cursor->dayOfWeekIso; // 1..7 (Mon..Sun)

                            $hasSchedule = Schedule::query()
                                ->where('doctor_id', $doctorId)
                                ->where('is_active', true)
                                ->where('day_of_week', $iso)
                                ->exists();

                            if (! $hasSchedule) {
                                $disabled[] = $cursor->toDateString();
                            }

                            $cursor->addDay();
                        }

                        return $disabled;
                    })
                    ->afterStateUpdated(function ($get, $set) {
                        // If the chosen date doesn't match doctor's active schedule,
                        // clear slot so user can't submit invalid appointment.
                        $doctorId = $get('doctor_id');
                        $date = $get('appointment_date');

                        $schedule = AppointmentForm::resolveSchedule($doctorId, $date);

                        if (! $schedule) {
                            $set('appointment_slot', null);
                            $set('schedule_id', null);
                            $set('scheduled_at', null);
                        }
                    }),

                // ── APPOINTMENT SLOT ─────────────────────────────────────
                Select::make('appointment_slot')
                    ->label('Appointment Time')
                    ->required()
                    ->reactive()
                    ->disabled(function ($get) {
                        return ! filled($get('appointment_date'));
                    })
                    ->options(function ($get) {

                        $doctorId = $get('doctor_id');
                        $date = $get('appointment_date');

                        return self::getSlots($doctorId, $date);
                    })
                    ->afterStateUpdated(function ($get, $set, $state) {
                        // Keep hidden fields in sync whenever the slot changes
                        self::syncHiddenFields($get, $set);

                        // Extra guard: if date/doctor has no schedule, keep hidden fields null
                        $schedule = self::resolveSchedule($get('doctor_id'), $get('appointment_date'));
                        if (! $schedule) {
                            $set('schedule_id', null);
                            $set('scheduled_at', null);
                        }
                    }),

                // ── HIDDEN: schedule_id ───────────────────────────────────
                // Use afterStateUpdated on upstream fields (doctor + date) to set this.
                Hidden::make('schedule_id')
                    ->dehydrated()
                    ->afterStateHydrated(function ($state, $set, $get) {
                        // ensure when editing/rehydrating, hidden fields remain consistent
                        $doctorId = $get('doctor_id');
                        $date = $get('appointment_date');
                        $slot = $get('appointment_slot');

                        $schedule = self::resolveSchedule($doctorId, $date);

                        if (! $schedule || ! $slot) {
                            $set('schedule_id', null);
                            $set('scheduled_at', null);
                        }
                    }),

                // ── HIDDEN: scheduled_at ──────────────────────────────────
                Hidden::make('scheduled_at')
                    ->dehydrated(),

                Placeholder::make('appointment_rule')
                    ->content('Appointment time follows the selected doctor\'s schedule. Dates outside doctor schedule cannot be selected.')
                    ->columnSpanFull(),

                // ── STATUS ───────────────────────────────────────────────
                Select::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
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
            ->orderByRaw('CASE WHEN day_of_week = ? THEN 0 ELSE 1 END', [$dayOfWeek])
            ->first();
    }

    /** Build the 30-minute slot options for a doctor + date. */
    private static function getSlots(?string $doctorId, mixed $date): array
    {
        $schedule = self::resolveSchedule($doctorId, $date);

        if (! $schedule) {
            return [];
        }

        $toCarbon = fn ($t) => $t instanceof \Carbon\Carbon
            ? Carbon::parse($t->format('H:i'))
            : Carbon::parse($t);

        $cursor = $toCarbon($schedule->start_time);
        $end = $toCarbon($schedule->end_time);
        $slots = [];

        while ($cursor->lt($end)) {
            $label = $cursor->format('H:i');
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
        $date = $get('appointment_date');
        $slot = $get('appointment_slot');

        $schedule = self::resolveSchedule($doctorId, $date);

        $set('schedule_id', $schedule?->id);

        if ($date) {
            $dateString = $date instanceof \Carbon\Carbon ? $date->format('Y-m-d') : (string) $date;
            $slotString = $slot ?: '00:00';
            $set('scheduled_at', Carbon::parse("{$dateString} {$slotString}")->toDateTimeString());
        } else {
            $set('scheduled_at', null);
        }
    }
}
