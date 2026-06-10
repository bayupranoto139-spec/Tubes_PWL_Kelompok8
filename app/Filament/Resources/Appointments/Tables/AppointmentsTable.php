<?php

namespace App\Filament\Resources\Appointments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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

                // DATE — unique name avoids Filament key collision
                Tables\Columns\TextColumn::make('scheduled_at_date')
                    ->label('Date')
                    ->getStateUsing(fn ($record) => $record->scheduled_at)
                    ->dateTime('d M Y')
                    ->sortable(query: function ($query, $direction) {
                        $query->orderBy('scheduled_at', $direction);
                    }),

                // TIME
                Tables\Columns\TextColumn::make('scheduled_at_time')
                    ->label('Time')
                    ->getStateUsing(fn ($record) => $record->scheduled_at)
                    ->dateTime('H:i'),

                // STATUS — TextColumn + badge() replaces deprecated BadgeColumn
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'scheduled' => 'primary',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                // COMPLAINT
                Tables\Columns\TextColumn::make('complaint')
                    ->label('Complaint')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->complaint), // show full text on hover

                // CREATED
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // keeps table uncluttered

            ])

            ->filters([

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),

            ])

            // Eager-load all nested relations to prevent N+1 queries
            ->modifyQueryUsing(
                fn ($query) => $query->with([
                    'patientEnrollment.user',
                    'doctor.user.hospital',
                    'schedule',
                ])
            )

            ->recordActions([
                ViewAction::make()->color('warning'),

                EditAction::make()
                    ->visible(fn () => in_array(
                        filament()->auth()->user()?->role,
                        ['admin_rs', 'staff']
                    )),

                DeleteAction::make()
                    ->visible(fn () => in_array(
                        filament()->auth()->user()?->role,
                        ['admin_rs', 'staff']
                    )),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn () => in_array(
                            filament()->auth()->user()?->role,
                            ['admin_rs', 'staff']
                        )),
                ]),
            ])

            ->defaultSort('scheduled_at', 'asc');
    }
}
