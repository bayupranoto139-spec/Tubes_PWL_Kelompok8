<?php

namespace App\Filament\Resources\Staff\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

use Filament\Tables\Table;

class StaffTable
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

                TextColumn::make('id')

                    ->label('ID')

                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | NAME
                |--------------------------------------------------------------------------
                */

                TextColumn::make('name')

                    ->label('Name')

                    ->searchable()

                    ->sortable(),

                /*
                |--------------------------------------------------------------------------
                | EMAIL
                |--------------------------------------------------------------------------
                */

                TextColumn::make('email')

                    ->label('Email')

                    ->searchable(),

                /*
                |--------------------------------------------------------------------------
                | ROLE
                |--------------------------------------------------------------------------
                */

                TextColumn::make('role')

                    ->label('Role')

                    ->badge()

                    ->formatStateUsing(fn ($state) => match ($state) {

                        'super_admin' => 'Super Admin',

                        'admin_rs' => 'Admin RS',

                        'dokter' => 'Doctor',

                        'staff' => 'Staff',

                        default => ucfirst($state),

                    })

                    ->color(fn (string $state): string => match ($state) {

                        'super_admin' => 'danger',

                        'admin_rs' => 'warning',

                        'dokter' => 'success',

                        'staff' => 'info',

                        default => 'gray',

                    }),

                /*
                |--------------------------------------------------------------------------
                | POSITION
                |--------------------------------------------------------------------------
                */

                TextColumn::make('staff.position')

                    ->label('Position')

                    ->badge()

                    ->placeholder('-')

                    ->color('primary'),

                /*
                |--------------------------------------------------------------------------
                | DEPARTMENT
                |--------------------------------------------------------------------------
                */

                TextColumn::make('staff.department')

                    ->label('Department')

                    ->badge()

                    ->placeholder('-')

                    ->color('gray'),

                /*
                |--------------------------------------------------------------------------
                | PHONE
                |--------------------------------------------------------------------------
                */

                TextColumn::make('phone')

                    ->label('Phone'),

                /*
                |--------------------------------------------------------------------------
                | ACTIVE
                |--------------------------------------------------------------------------
                */

                IconColumn::make('is_active')

                    ->label('Active')

                    ->boolean(),

                /*
                |--------------------------------------------------------------------------
                | CREATED
                |--------------------------------------------------------------------------
                */

                TextColumn::make('created_at')

                    ->label('Created')

                    ->date('d M Y'),

            ])

            ->filters([

                //

            ])

            ->recordActions([

                EditAction::make(),

            ])

            ->toolbarActions([

                BulkActionGroup::make([

                    DeleteBulkAction::make(),

                ]),

            ]);
    }
}