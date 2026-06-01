<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->columns([

                /*
                |--------------------------------------------------------------------------
                | ID
                |--------------------------------------------------------------------------
                */



                /*
                |--------------------------------------------------------------------------
                | HOSPITAL
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make('hospital.name')
                    ->label('Hospital')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                /*
                |--------------------------------------------------------------------------
                | NAME
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | EMAIL
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),

                /*
                |--------------------------------------------------------------------------
                | ROLE
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'super_admin' => 'Super Admin',
                        'admin_rs'    => 'Admin RS',
                        'dokter'      => 'Dokter',
                        'staff'       => 'Staff',
                        'pasien'      => 'Pasien',
                        default       => ucfirst($state),
                    })
                    ->color(fn ($state) => match ($state) {
                        'super_admin' => 'danger',
                        'admin_rs'    => 'warning',
                        'dokter'      => 'success',
                        'staff'       => 'info',
                        'pasien'      => 'gray',
                        default       => 'gray',
                    }),

                /*
                |--------------------------------------------------------------------------
                | PHONE
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->placeholder('-'),

                /*
                |--------------------------------------------------------------------------
                | GENDER
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make('gender')
                    ->label('Gender')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'L' => 'Male',
                        'P' => 'Female',
                        default => '-',
                    }),

                /*
                |--------------------------------------------------------------------------
                | DATE OF BIRTH
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make('date_of_birth')
                    ->label('Birth Date')
                    ->date('d M Y')
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                /*
                |--------------------------------------------------------------------------
                | ACTIVE
                |--------------------------------------------------------------------------
                */

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),

                /*
                |--------------------------------------------------------------------------
                | CREATED
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->date('d M Y')
                    ->sortable(),

            ])

            ->defaultSort('id', 'desc')

            ->filters([
                //
            ])

            ->actions([

                EditAction::make(),

            ])

            ->bulkActions([

                DeleteBulkAction::make(),

            ]);
    }
}