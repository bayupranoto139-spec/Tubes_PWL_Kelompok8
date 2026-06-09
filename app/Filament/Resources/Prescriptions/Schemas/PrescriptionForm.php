<?php

namespace App\Filament\Resources\Prescriptions\Schemas;

use App\Models\Doctor;
use App\Models\MedicalRecord;
use App\Models\PatientEnrollment;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PrescriptionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Prescription Information')
                    ->schema([

                        Hidden::make('medical_record_id')
                            ->required()
                            ->dehydrated(),

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
                                    ->whereHas('appointments', function ($q) use ($doctorId) {

                                        $q->where('doctor_id', $doctorId)
                                            ->where('status', 'scheduled')
                                            ->has('medicalRecord');
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

                                $medicalRecord = MedicalRecord::query()
                                    ->whereHas('appointment', function ($q) use ($state, $get) {

                                        $q->where('patient_enrollment_id', $state)
                                            ->where('doctor_id', $get('doctor_id'))
                                            ->where('status', 'scheduled');
                                    })
                                    ->latest()
                                    ->first();

                                $set('medical_record_id', $medicalRecord?->id);
                            }),

                        Select::make('medication_id')
                            ->label('Medication')
                            ->relationship('medication', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('quantity')
                            ->label('Quantity')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required(),

                        TextInput::make('dosage')
                            ->label('Dosage')
                            ->required(),

                        TextInput::make('duration')
                            ->label('Duration')
                            ->required(),

                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(4),

                    ]),
            ]);
    }
}
