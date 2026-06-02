<?php

namespace App\Filament\Resources\Prescriptions\Schemas;

use App\Models\MedicalRecord;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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

                        Select::make('medical_record_id')
                            ->label('Medical Record')
                            ->relationship(
                                'medicalRecord',
                                'id'
                            )
                            ->searchable()
                            ->preload()
                            ->required(),

                        TextInput::make('medication_name')
                            ->label('Medication Name')
                            ->required()
                            ->maxLength(255),

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