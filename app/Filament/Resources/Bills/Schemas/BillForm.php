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

                                TextInput::make('total_amount')
                                    ->label('Total Amount')
                                    ->prefix('Rp')
                                    ->disabled(),

                                Select::make('status')
                                    ->options([
                                        'unpaid' => 'Unpaid',
                                        'paid' => 'Paid',
                                    ])
                                    ->required(),

                                DatePicker::make('payment_due_date')
                                    ->disabled(),

                                Select::make('payment_method')
                                    ->options([
                                        'cash' => 'Cash',
                                        'bank_transfer' => 'Bank Transfer',
                                        'qris' => 'QRIS',
                                        'insurance' => 'Insurance',
                                    ])
                                    ->required(),

                                DateTimePicker::make('payment_date')
                                    ->disabled(),

                            ]),

                    ]),

            ]);
    }
}
