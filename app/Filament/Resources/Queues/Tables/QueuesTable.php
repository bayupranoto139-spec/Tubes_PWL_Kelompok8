<?php

namespace App\Filament\Resources\Queues\Tables;

use App\Models\Queue;
use Filament\Actions\Action;
use Filament\Support\Enums\Size;
use Filament\Tables;
use Filament\Tables\Table;

class QueuesTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->columns([

                // NO. ANTRIAN
                Tables\Columns\TextColumn::make('queue_number')
                    ->label('No.')
                    ->sortable()
                    ->weight('bold')
                    ->size('lg'),

                // PRIORITAS
                Tables\Columns\BadgeColumn::make('priority')
                    ->label('Priority')
                    ->formatStateUsing(fn ($state) => $state === 1 ? 'Appointment' : 'Walk-in')
                    ->colors([
                        'primary' => 1,
                        'warning' => 2,
                    ]),

                // PASIEN
                Tables\Columns\TextColumn::make('appointment.patientEnrollment.user.name')
                    ->label('Patient')
                    ->searchable()
                    ->sortable(),

                // DOKTER
                Tables\Columns\TextColumn::make('appointment.doctor.user.name')
                    ->label('Doctor')
                    ->searchable(),

                // KELUHAN
                Tables\Columns\TextColumn::make('appointment.complaint')
                    ->label('Complaint')
                    ->limit(35)
                    ->tooltip(fn ($record) => $record->appointment?->complaint),

                // JAM APPOINTMENT
                Tables\Columns\TextColumn::make('appointment.scheduled_at')
                    ->label('Scheduled')
                    ->dateTime('H:i')
                    ->sortable(),

                // TIPE
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn ($state) => $state === 'appointment' ? 'primary' : 'warning'),

                // STATUS
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'waiting'     => 'gray',
                        'called'      => 'info',
                        'in_progress' => 'warning',
                        'completed'   => 'success',
                        'skipped'     => 'danger',
                        default       => 'gray',
                    }),

                // TANGGAL ANTRIAN
                Tables\Columns\TextColumn::make('queue_date')
                    ->label('Date')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])

            ->filters([

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'waiting'     => 'Waiting',
                        'called'      => 'Called',
                        'in_progress' => 'In Progress',
                        'completed'   => 'Completed',
                        'skipped'     => 'Skipped',
                    ]),

                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'appointment' => 'Appointment',
                        'walk_in'     => 'Walk-in',
                    ]),

                Tables\Filters\Filter::make('today')
                    ->label('Today Only')
                    ->query(fn ($query) => $query->whereDate('queue_date', today()))
                    ->default(true),

            ])

            ->recordActions([

                // PANGGIL pasien (waiting → called)
                Action::make('call')
                    ->label('Panggil')
                    ->icon('heroicon-o-megaphone')
                    ->color('info')
                    ->size(Size::Small)
                    ->visible(fn (Queue $record) => $record->status === 'waiting')
                    ->requiresConfirmation()
                    ->modalHeading('Panggil Pasien')
                    ->modalDescription(fn (Queue $record) => 'Panggil ' .
                        ($record->appointment?->patientEnrollment?->user?->name ?? 'pasien') .
                        ' (No. ' . $record->queue_number . ')?')
                    ->action(fn (Queue $record) => $record->call()),

                // MULAI pemeriksaan (called → in_progress)
                Action::make('start')
                    ->label('Mulai')
                    ->icon('heroicon-o-play')
                    ->color('warning')
                    ->size(Size::Small)
                    ->visible(fn (Queue $record) => $record->status === 'called')
                    ->action(fn (Queue $record) => $record->start()),

                // SELESAI pemeriksaan (in_progress → completed)
                Action::make('done')
                    ->label('Selesai')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->size(Size::Small)
                    ->visible(fn (Queue $record) => $record->status === 'in_progress')
                    ->action(fn (Queue $record) => $record->complete()),

                // SKIP (waiting/called → skipped)
                Action::make('skip')
                    ->label('Skip')
                    ->icon('heroicon-o-forward')
                    ->color('danger')
                    ->size(Size::Small)
                    ->visible(fn (Queue $record) => in_array($record->status, ['waiting', 'called']))
                    ->requiresConfirmation()
                    ->modalHeading('Skip Pasien?')
                    ->modalDescription('Pasien ini akan dilewati dan bisa dipanggil kembali nanti.')
                    ->action(fn (Queue $record) => $record->skip()),

            ])

            ->defaultSort('priority', 'asc')
            ->defaultSort('queue_number', 'asc')

            ->modifyQueryUsing(
                fn ($query) => $query->with([
                    'appointment.patientEnrollment.user',
                    'appointment.doctor.user',
                ])
            )

            ->poll('10s'); // auto-refresh setiap 10 detik
    }
}