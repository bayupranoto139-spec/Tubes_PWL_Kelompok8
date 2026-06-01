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
        return $schema->components([

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

                            TextInput::make('name')
                                ->label('Full Name')
                                ->placeholder('Enter full name')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('email')
                                ->label('Email Address')
                                ->email()
                                ->placeholder('example@email.com')
                                ->required()
                                ->unique(ignoreRecord: true),

                            TextInput::make('password')
                                ->label('Password')
                                ->password()
                                ->placeholder('Enter password')
                                ->required(fn ($record) => $record === null)
                                ->dehydrated(fn ($state) => filled($state))
                                ->dehydrateStateUsing(
                                    fn ($state) => filled($state)
                                        ? Hash::make($state)
                                        : null
                                ),

                            Select::make('role')
                                ->label('Role')
                                ->options(
                                    filament()->auth()->user()?->role === 'super_admin'
                                        ? [
                                            'admin_rs' => 'Admin Rumah Sakit',
                                            'dokter' => 'Dokter',
                                            'staff' => 'Staff',
                                            'pasien' => 'Pasien',
                                        ]
                                        : [
                                            'dokter' => 'Dokter',
                                            'staff' => 'Staff',
                                            'pasien' => 'Pasien',
                                        ]
                                )
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
                ->description('Assign hospital to user')
                ->schema([

                    Select::make('hospital_id')
                        ->label('Hospital')
                        ->options(
                            Hospital::query()
                                ->pluck('name', 'id')
                                ->toArray()
                        )
                        ->default(
                            filament()->auth()->user()?->hospital_id
                        )
                        ->disabled(
                            filament()->auth()->user()?->role === 'admin_rs'
                        )
                        ->required()
                        ->searchable()
                        ->preload()
                        ->native(false),

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

                            TextInput::make('phone')
                                ->label('Phone Number')
                                ->tel()
                                ->placeholder('08xxxxxxxxxx'),

                            Select::make('gender')
                                ->label('Gender')
                                ->options([
                                    'L' => 'Laki-laki',
                                    'P' => 'Perempuan',
                                ])
                                ->native(false),

                            DatePicker::make('date_of_birth')
                                ->label('Date of Birth'),

                            Toggle::make('is_active')
                                ->label('Active User')
                                ->default(true),

                        ]),

                    Textarea::make('address')
                        ->label('Address')
                        ->rows(4)
                        ->placeholder('Enter full address')
                        ->columnSpanFull(),

                ]),

        ]);
    }
}