<?php

namespace App\Filament\Resources\Prescriptions\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PrescriptionsTable
{
    public static function configure(Table $table): Table
    {
        $isSuperAdmin = filament()->auth()->user()?->role === 'super_admin';

        return $table

            ->defaultSort('id', 'asc')

            ->columns([

                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('medicalRecord.patientEnrollment.user.name')
                    ->label('Patient')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('medicalRecord.doctor.user.name')
                    ->label('Doctor')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('medicalRecord.diagnosis')
                    ->label('Diagnosis')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->medicalRecord?->diagnosis),

                TextColumn::make('medication_id')
                    ->label('Medicine ID')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                TextColumn::make('dosage')
                    ->label('Dosage')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('duration')
                    ->label('Duration')
                    ->badge()
                    ->color('warning')
                    ->sortable(),

                TextColumn::make('quantity')
                    ->label('Qty')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('notes')
                    ->label('Notes')
                    ->placeholder('-')
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->notes),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

            ])

            ->recordActions([
                \Filament\Actions\ViewAction::make()->color('warning'),
                EditAction::make()
                    ->visible(! $isSuperAdmin),
                DeleteAction::make()
                    ->requiresConfirmation()
                    ->visible(! $isSuperAdmin),
            ])

            ->emptyStateHeading('No Prescriptions Found')

            ->emptyStateDescription('No prescription records have been created yet.');
    }
}