<?php

namespace App\Filament\Resources\Bills\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema

            ->components([

                Section::make('Bill Information')

                    ->schema([

                        Grid::make(2)

                            ->schema([

                                Select::make('patient_enrollment_id')
                                    ->label('Patient')
                                    ->relationship(
                                        'patientEnrollment',
                                        'medical_record_number'
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('appointment_id')
                                    ->label('Appointment')
                                    ->relationship(
                                        'appointment',
                                        'id'
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                TextInput::make('total_amount')
                                    ->label('Total Amount')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),

                                Select::make('status')
                                    ->options([
                                        'unpaid' => 'Unpaid',
                                        'partial' => 'Partial',
                                        'paid' => 'Paid',
                                    ])
                                    ->required(),

                                DatePicker::make('payment_due_date')
                                    ->label('Payment Due Date')
                                    ->required(),

                                TextInput::make('payment_method')
                                    ->label('Payment Method')
                                    ->placeholder(
                                        'Cash, Transfer, QRIS'
                                    ),

                                TextInput::make('reference_number')
                                    ->label('Reference Number')
                                    ->placeholder(
                                        'TRX-001'
                                    ),

                                DateTimePicker::make('payment_date')
                                    ->label('Payment Date'),

                            ]),

                    ]),

            ]);
    }
}