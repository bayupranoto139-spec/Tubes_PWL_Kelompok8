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

                /*
                |--------------------------------------------------------------------------
                | PATIENT
                |--------------------------------------------------------------------------
                */

                Select::make('patient_enrollment_id')
                    ->label('Patient')
                    ->options(

                        PatientEnrollment::with('user')
                            ->get()
                            ->pluck('user.name', 'id')

                    )
                    ->searchable()
                    ->preload()
                    ->required(),


                /*
                |--------------------------------------------------------------------------
                | DOCTOR
                |--------------------------------------------------------------------------
                */

                Select::make('doctor_id')
                    ->label('Doctor')
                    ->options(

                        Doctor::with('user')
                            ->get()
                            ->pluck('user.name', 'id')

                    )
                    ->searchable()
                    ->preload()
                    ->required(),


                /*
                |--------------------------------------------------------------------------
                | SCHEDULE DATE
                |--------------------------------------------------------------------------
                */

                // Tanggal appointment (tidak boleh hari ini- lewat)
AppointmentDatePicker::make('appointment_date')
                    ->label('Appointment Date')
                    ->required()
                    ->minDate(now())
                    ->native(false),

                // Slot jam mengikuti schedule dokter yang dipilih
                Select::make('appointment_slot')
                    ->label('Appointment Time')
                    ->required()
                    ->options(function ($get) {
                        $doctorId = $get('doctor_id');
                        $date = $get('appointment_date');

                        if (! $doctorId || ! $date) {
                            return [];
                        }

                        $dayOfWeek = Carbon::parse($date)->dayOfWeekIso;

                        $schedule = Schedule::query()
                            ->where('doctor_id', $doctorId)
                            ->where('day_of_week', $dayOfWeek)
                            ->where('is_active', true)
                            ->first();

                        if (! $schedule) {
                            return [];
                        }

                        // Granularity slot: 30 menit
                        $slots = [];
                        $start = Carbon::parse($schedule->start_time->format('H:i'));
                        $end = Carbon::parse($schedule->end_time->format('H:i'));

                        while ($start->lt($end)) {
                            $slotTime = $start->format('H:i');

                            $slots[$slotTime] = $slotTime;

                            $start->addMinutes(30);
                        }

                        return $slots;
                    }),

                // scheduled_at tetap tersimpan ke kolom model (dibentuk dari date+slot)
                Hidden::make('scheduled_at')
                    ->dehydrated()
                    ->default(fn ($get) =>
                        $get('appointment_date') && $get('appointment_slot')
                            ? Carbon::parse($get('appointment_date')->format('Y-m-d').' '.$get('appointment_slot'))
                            : null
                    )
                    ->required(),

                Placeholder::make('appointment_rule')
                    ->content('Jam otomatis mengikuti schedule dokter yang dipilih.')
                    ->columnSpanFull(),


                /*
                |--------------------------------------------------------------------------
                | STATUS
                |--------------------------------------------------------------------------
                */

                Select::make('status')
                    ->options([

                        'scheduled' => 'Scheduled',

                        'completed' => 'Completed',

                        'cancelled' => 'Cancelled',

                    ])
                    ->default('scheduled')
                    ->required(),


                /*
                |--------------------------------------------------------------------------
                | COMPLAINT
                |--------------------------------------------------------------------------
                */

                Textarea::make('complaint')
                    ->label('Complaint')
                    ->rows(4)
                    ->columnSpanFull(),

            ])

            ->columns(2);
    }
}