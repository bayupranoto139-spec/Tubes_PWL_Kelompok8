<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Hospital;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;

use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema

            ->components([

                /*
                |--------------------------------------------------------------------------
                | ACCOUNT INFORMATION
                |--------------------------------------------------------------------------
                */

                Section::make('Account Information')

                    ->description('Manage user login credentials')

                    ->schema([

                        Grid::make(2)

                            ->schema([

                                /*
                                |--------------------------------------------------------------------------
                                | FULL NAME
                                |--------------------------------------------------------------------------
                                */

                                TextInput::make('name')

                                    ->label('Full Name')

                                    ->placeholder('Enter full name')

                                    ->required()

                                    ->maxLength(255),

                                /*
                                |--------------------------------------------------------------------------
                                | EMAIL
                                |--------------------------------------------------------------------------
                                */

                                TextInput::make('email')

                                    ->label('Email Address')

                                    ->email()

                                    ->placeholder('example@email.com')

                                    ->required()

                                    ->unique(ignoreRecord: true),

                                /*
                                |--------------------------------------------------------------------------
                                | PASSWORD
                                |--------------------------------------------------------------------------
                                */

                                TextInput::make('password')

                                    ->label('Password')

                                    ->password()

                                    ->placeholder('Enter password')

                                    ->required(fn ($record) => $record === null)

                                    ->dehydrated(fn ($state) => filled($state))

                                    ->dehydrateStateUsing(
                                        fn ($state) => Hash::make($state)
                                    ),

                                /*
                                |--------------------------------------------------------------------------
                                | ROLE
                                |--------------------------------------------------------------------------
                                */

                                Select::make('role')

                                    ->label('Role')

                                    ->options([

                                        'super_admin' => 'Super Admin',

                                        'admin_rs' => 'Admin Rumah Sakit',

                                        'dokter' => 'Dokter',

                                        'staff' => 'Staff',

                                        'pasien' => 'Pasien',

                                    ])

                                    ->searchable()

                                    ->native(false)

                                    ->required(),

                            ]),

                    ]),

                /*
                |--------------------------------------------------------------------------
                | HOSPITAL INFORMATION
                |--------------------------------------------------------------------------
                */

                Section::make('Hospital Information')

                    ->description('Assign hospital to doctor or staff')

                    ->schema([

                        Select::make('hospital_id')

                            ->label('Hospital')

                            ->options(

                                Hospital::query()
                                    ->pluck('name', 'id')

                            )

                            ->searchable()

                            ->preload()

                            ->native(false)

                            ->placeholder('Select hospital'),

                    ]),

                /*
                |--------------------------------------------------------------------------
                | PERSONAL INFORMATION
                |--------------------------------------------------------------------------
                */

                Section::make('Personal Information')

                    ->description('User personal details')

                    ->schema([

                        Grid::make(2)

                            ->schema([

                                /*
                                |--------------------------------------------------------------------------
                                | PHONE
                                |--------------------------------------------------------------------------
                                */

                                TextInput::make('phone')

                                    ->label('Phone Number')

                                    ->tel()

                                    ->placeholder('08xxxxxxxxxx'),

                                /*
                                |--------------------------------------------------------------------------
                                | GENDER
                                |--------------------------------------------------------------------------
                                */

                                Select::make('gender')

                                    ->label('Gender')

                                    ->options([

                                        'L' => 'Laki-laki',

                                        'P' => 'Perempuan',

                                    ])

                                    ->native(false),

                                /*
                                |--------------------------------------------------------------------------
                                | DATE OF BIRTH
                                |--------------------------------------------------------------------------
                                */

                                DatePicker::make('date_of_birth')

                                    ->label('Date of Birth'),

                                /*
                                |--------------------------------------------------------------------------
                                | ACTIVE STATUS
                                |--------------------------------------------------------------------------
                                */

                                Toggle::make('is_active')

                                    ->label('Active User')

                                    ->default(true),

                            ]),

                        /*
                        |--------------------------------------------------------------------------
                        | ADDRESS
                        |--------------------------------------------------------------------------
                        */

                        Textarea::make('address')

                            ->label('Address')

                            ->rows(4)

                            ->placeholder('Enter full address')

                            ->columnSpanFull(),

                    ]),

            ]);
    }
}