<?php

namespace App\Filament\Resources\Bills\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class BillsTable
{
    public static function configure(Table $table): Table
    {
        return $table

            ->defaultSort('id', 'desc')

            ->columns([

                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                TextColumn::make('patientEnrollment.user.name')
                    ->label('Patient')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('appointment.id')
                    ->label('Appointment')
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->money('IDR')
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'partial' => 'warning',
                        'unpaid' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('payment_due_date')
                    ->label('Due Date')
                    ->date()
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->placeholder('-'),

                TextColumn::make('reference_number')
                    ->label('Reference')
                    ->placeholder('-'),

                TextColumn::make('payment_date')
                    ->label('Payment Date')
                    ->dateTime('d M Y H:i')
                    ->placeholder('-'),

                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

            ])

            ->filters([
                TrashedFilter::make(),
            ])

            ->recordActions([
                \Filament\Actions\ViewAction::make()->color('warning'),
                EditAction::make(),
                DeleteAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}