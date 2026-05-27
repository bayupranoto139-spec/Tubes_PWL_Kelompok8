<?php

namespace App\Filament\Resources\Staff\Schemas;

use Filament\Schemas\Schema;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class StaffForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema

            ->components([

                /*
                |--------------------------------------------------------------------------
                | STAFF INFORMATION
                |--------------------------------------------------------------------------
                */

                Section::make('Staff Information')

                    ->description('Manage staff data')

                    ->columnSpanFull()

                    ->schema([

                        Grid::make(2)

                            ->schema([

                                /*
                                |--------------------------------------------------------------------------
                                | NAME
                                |--------------------------------------------------------------------------
                                */

                                TextInput::make('name')

                                    ->label('Full Name')

                                    ->required()

                                    ->maxLength(255),

                                /*
                                |--------------------------------------------------------------------------
                                | EMAIL
                                |--------------------------------------------------------------------------
                                */

                                TextInput::make('email')

                                    ->label('Email')

                                    ->email()

                                    ->required(),

                                /*
                                |--------------------------------------------------------------------------
                                | ROLE
                                |--------------------------------------------------------------------------
                                */

                                Select::make('role')

                                    ->label('Role')

                                    ->options([

                                        'super_admin' => 'Super Admin',

                                        'admin_rs' => 'Admin RS',

                                        'dokter' => 'Dokter',

                                        'staff' => 'Staff',

                                    ])

                                    ->required(),

                                /*
                                |--------------------------------------------------------------------------
                                | PHONE
                                |--------------------------------------------------------------------------
                                */

                                TextInput::make('phone')

                                    ->label('Phone Number')

                                    ->tel(),

                                /*
                                |--------------------------------------------------------------------------
                                | POSITION
                                |--------------------------------------------------------------------------
                                */

                                TextInput::make('position')

                                    ->label('Position')

                                    ->placeholder('Example: Resepsionis'),

                                /*
                                |--------------------------------------------------------------------------
                                | DEPARTMENT
                                |--------------------------------------------------------------------------
                                */

                                TextInput::make('department')

                                    ->label('Department')

                                    ->placeholder('Example: Front Office'),

                            ]),

                    ]),

            ]);
    }
}