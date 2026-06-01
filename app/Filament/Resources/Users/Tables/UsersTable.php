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

                Tables\Columns\TextColumn::make('id')

                    ->label('ID')

                    ->sortable(),

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

                    ->searchable(),

                /*
                |--------------------------------------------------------------------------
                | ROLE
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make('role')

                    ->label('Role')

                    ->badge()

                    ->formatStateUsing(fn ($state) => match ($state) {

                        'pasien' => 'Patient',

                        default => ucfirst($state),

                    })

                    ->color(fn (string $state): string => match ($state) {
                        'super_admin' => 'danger',
                        'admin_rs'    => 'warning',
                        'dokter'      => 'success',
                        'staff'       => 'primary',
                        'pasien'      => 'info',
                        default       => 'gray',
                    }),

                /*
                |--------------------------------------------------------------------------
                | PHONE
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make('phone')

                    ->label('Phone'),

                /*
                |--------------------------------------------------------------------------
                | GENDER
                |--------------------------------------------------------------------------
                */

                Tables\Columns\TextColumn::make('gender')

                    ->label('Gender')

                    ->formatStateUsing(fn ($state) => match ($state) {

                        'L' => 'Male',

                        'P' => 'Female',

                        default => '-',

                    }),

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

            ->defaultSort('id', 'asc')

            ->actions([

                EditAction::make(),

            ])

            ->bulkActions([

                DeleteBulkAction::make(),

            ]);
    }
}