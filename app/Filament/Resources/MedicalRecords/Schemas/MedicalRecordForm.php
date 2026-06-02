<?php

namespace App\Filament\Resources\MedicalRecords\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use App\Models\Appointment;

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

                                Select::make('appointment_id')
                                    ->label('Appointment')
                                    ->options(fn () => Appointment::doesntHave('medicalRecord')->pluck('id', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->rules(['unique:medical_records,appointment_id']),

                                DateTimePicker::make('visit_date')
                                    ->label('Visit Date')
                                    ->required(),

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