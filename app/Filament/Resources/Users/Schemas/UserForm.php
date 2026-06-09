<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\Hospital;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Infolists\Components\TextEntry;
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
                ->columnSpanFull()
                ->schema([

                    Grid::make(2)
                        ->schema([

                            TextInput::make('name')
                                ->label('Full Name')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('email')
                                ->label('Email Address')
                                ->email()
                                ->required()
                                ->unique(ignoreRecord: true),

                            TextInput::make('password')
                                ->label('Password')
                                ->password()
                                ->required(fn ($record) => ! ($record?->exists ?? false))
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
                                            'dokter'   => 'Dokter',
                                            'staff'    => 'Staff',
                                            'pasien'   => 'Pasien',
                                        ]
                                        : [
                                            'dokter' => 'Dokter',
                                            'staff'  => 'Staff',
                                            'pasien' => 'Pasien',
                                        ]
                                )
                                ->required()
                                ->searchable()
                                ->native(false)
                                ->live(),

                        ]),
                ]),

            /*
            |--------------------------------------------------------------------------
            | HOSPITAL INFORMATION
            |--------------------------------------------------------------------------
            */

            Section::make('Hospital Information')
                ->description('Assign hospital to user')
                ->columnSpanFull()
                ->schema([

                    // Untuk role dokter/staff/admin_rs: single hospital select
                    Select::make('hospital_id')
                        ->label('Hospital')
                        ->relationship('hospital', 'name')
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->hidden(fn (Get $get, $record) => ($get('role') ?? $record?->role) === 'pasien')
                        ->default(filament()->auth()->user()?->hospital_id)
                        ->disabled(filament()->auth()->user()?->role === 'admin_rs')
                        ->dehydrated()
                        ->required(fn (Get $get, $record) => ($get('role') ?? $record?->role) !== 'pasien'),

                    // Untuk CREATE pasien: pilih rumah sakit (untuk patient_enrollment)
                    Select::make('patient_hospital_id')
                        ->label('Hospital (Pendaftaran Pasien)')
                        ->helperText('Pasien akan didaftarkan ke rumah sakit ini. Email verifikasi akan dikirim setelah disimpan.')
                        ->options(function () {
                            $authUser = filament()->auth()->user();
                            if ($authUser?->role === 'admin_rs') {
                                // Admin RS hanya bisa daftarkan ke RS-nya sendiri
                                return Hospital::where('id', $authUser->hospital_id)
                                    ->where('is_active', true)
                                    ->pluck('name', 'id');
                            }
                            return Hospital::where('is_active', true)->pluck('name', 'id');
                        })
                        ->searchable()
                        ->preload()
                        ->native(false)
                        ->visible(fn (Get $get, $record) => ($get('role') ?? $record?->role) === 'pasien' && ! $record?->exists)
                        ->required(fn (Get $get, $record) => ($get('role') ?? $record?->role) === 'pasien' && ! $record?->exists)
                        ->dehydrated(false), // Tidak disimpan ke kolom users, ditangani di afterCreate()

                    // Untuk VIEW/EDIT pasien: tampilkan badge RS yang sudah terdaftar
                    TextEntry::make('enrolled_hospital_names')
                        ->label('Registered Hospitals')
                        ->visible(fn (Get $get, $record) => $record?->exists && ($get('role') ?? $record?->role) === 'pasien')
                        ->state(fn ($record) => $record
                            ?->patientEnrollments()
                            ->with('hospital')
                            ->get()
                            ->pluck('hospital.name')
                            ->filter()
                            ->values()
                            ->all()
                        )
                        ->badge()
                        ->color('primary')
                        ->separator(',')
                        ->columnSpanFull(),

                ]),

            /*
            |--------------------------------------------------------------------------
            | PERSONAL INFORMATION
            |--------------------------------------------------------------------------
            */

            Section::make('Personal Information')
                ->description('User personal details')
                ->columnSpanFull()
                ->schema([

                    Grid::make(2)
                        ->schema([

                            TextInput::make('phone')
                                ->label('Phone Number')
                                ->tel()
                                ->maxLength(20),

                            Select::make('gender')
                                ->label('Gender')
                                ->options([
                                    'L' => 'Laki-laki',
                                    'P' => 'Perempuan',
                                ])
                                ->native(false),

                            DatePicker::make('date_of_birth')
                                ->label('Date of Birth')
                                ->maxDate(now()),

                            Toggle::make('is_active')
                                ->label('Active User')
                                ->default(true),

                        ]),

                    Textarea::make('address')
                        ->label('Address')
                        ->rows(4)
                        ->columnSpanFull(),

                ]),
        ]);
    }
}