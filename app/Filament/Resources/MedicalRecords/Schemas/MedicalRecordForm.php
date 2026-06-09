<?php

namespace App\Filament\Resources\MedicalRecords\Schemas;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\PatientEnrollment;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MedicalRecordForm
{
    protected static ?int $navigationSort = 6;

    public static function configure(Schema $schema): Schema
    {
        return $schema

            ->components([

                Section::make('Medical Record Information')

                    ->schema([

                        Grid::make(2)

                            ->schema([

                                Hidden::make('appointment_id')
                                    ->required()
                                    ->dehydrated(),

                                Hidden::make('hospital_id')
                                    ->default(fn () => filament()->auth()->user()?->hospital_id)
                                    ->dehydrated(false),

                                Select::make('doctor_id')
                                    ->label('Doctor')
                                    ->options(function () {

                                        $hospitalId = filament()->auth()->user()?->hospital_id;

                                        return Doctor::query()
                                            ->with('user')
                                            ->whereHas(
                                                'user',
                                                fn ($q) => $q->where(
                                                    'hospital_id',
                                                    $hospitalId
                                                )
                                            )
                                            ->get()
                                            ->mapWithKeys(fn ($doctor) => [
                                                $doctor->id => $doctor->user->name,
                                            ])
                                            ->toArray();
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->required()
                                    ->dehydrated(false),

                                Select::make('patient_picker')
                                    ->label('Patient')
                                    ->options(function ($get) {

                                        $doctorId = $get('doctor_id');

                                        if (! $doctorId) {
                                            return [];
                                        }

                                        return PatientEnrollment::query()
                                            ->with('user')
                                            ->whereHas('user')
                                            ->whereHas('appointments', function ($q) use ($doctorId) {

                                                $q->where('doctor_id', $doctorId)
                                                    ->where('status', 'scheduled')
                                                    ->doesntHave('medicalRecord');
                                            })
                                            ->get()
                                            ->mapWithKeys(fn ($patient) => [
                                                $patient->id => $patient->user->name,
                                            ])
                                            ->toArray();
                                    })
                                    ->searchable()
                                    ->preload()
                                    ->live()
                                    ->required()
                                    ->dehydrated(false)
                                    ->afterStateUpdated(function ($state, $get, $set) {

                                        $appointment = Appointment::query()
                                            ->where('patient_enrollment_id', $state)
                                            ->where('doctor_id', $get('doctor_id'))
                                            ->where('status', 'scheduled')
                                            ->doesntHave('medicalRecord')
                                            ->first();

                                        $set('appointment_id', $appointment?->id);
                                    }),

                                Select::make('case_status')
                                    ->label('Case Status')
                                    ->options([
                                        'active' => 'Active',
                                        'healed' => 'Healed',
                                    ])
                                    ->default('active')
                                    ->required(),

                            ]),

                        Textarea::make('diagnosis')
                            ->label('Diagnosis')
                            ->rows(4)
                            ->required(),

                        Textarea::make('treatment_plan')
                            ->label('Treatment Plan')
                            ->rows(4)
                            ->required(),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(4),

                    ]),

            ]);
    }
}
