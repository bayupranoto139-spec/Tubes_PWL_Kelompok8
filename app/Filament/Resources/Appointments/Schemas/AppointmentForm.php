<?php

namespace App\Filament\Resources\Appointments\Schemas;

use App\Models\Doctor;
use App\Models\PatientEnrollment;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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

                DateTimePicker::make('scheduled_at')
                    ->label('Schedule Date')
                    ->required(),


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