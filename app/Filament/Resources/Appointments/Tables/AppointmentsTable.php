<?php

namespace App\Filament\Resources\Appointments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Table;

class AppointmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->columns([

                // PATIENT
                Tables\Columns\TextColumn::make('patientEnrollment.user.name')
                    ->label('Patient')
                    ->searchable()
                    ->sortable(),

                // DOCTOR
                Tables\Columns\TextColumn::make('doctor.user.name')
                    ->label('Doctor')
                    ->searchable()
                    ->sortable(),

                // HOSPITAL
                Tables\Columns\TextColumn::make('doctor.user.hospital.name')
                    ->label('Hospital')
                    ->searchable(),

                // SCHEDULE
                Tables\Columns\TextColumn::make('scheduled_at')
                    ->label('Schedule')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                // STATUS
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'scheduled',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),

                // COMPLAINT
                Tables\Columns\TextColumn::make('complaint')
                    ->label('Complaint')
                    ->limit(30),

                // CREATED
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y'),

            ])

            ->filters([

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),

            ])

            ->recordActions([

                EditAction::make(),
                DeleteAction::make(),

            ])

            ->toolbarActions([

                BulkActionGroup::make([

                    DeleteBulkAction::make(),

                ]),

            ]);
    }
}