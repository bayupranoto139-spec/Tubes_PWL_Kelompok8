<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\Hospital;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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
                    ->placeholder('-')
                    ->getStateUsing(function ($record) {
                        // For pasien role, hospital name should come from patient_enrollments.
                        // A patient can be enrolled in multiple hospitals; show all matching names.
                        if (($record->role ?? null) === 'pasien') {
                            $hospitalId = request()->input('tableFilters.hospital_id');

                            $query = $record->patientEnrollments()
                                ->with('hospital')
                                ->whereNotNull('hospital_id');

                            // If hospital filter is applied, only show names for that hospital.
                            if (filled($hospitalId)) {
                                $query->where('hospital_id', $hospitalId);
                            }

                            $names = $query
                                ->get()
                                ->pluck('hospital.name')
                                ->filter()
                                ->unique()
                                ->values();

                            // If there are many hospitals, show a compact preview to avoid an overly long single row.
                            if ($names->isEmpty()) {
                                return null;
                            }

                            $max = 2;
                            if ($names->count() <= $max) {
                                return $names->join(', ');
                            }

                            $preview = $names->take($max)->join(', ');

                            return $preview.' + '.($names->count() - $max).' lainnya';
                        }

                        return $record->hospital?->name;
                    }),

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
                        'admin_rs' => 'Admin RS',
                        'dokter' => 'Dokter',
                        'staff' => 'Staff',
                        'pasien' => 'Pasien',
                        default => ucfirst($state),
                    })
                    ->color(fn ($state) => match ($state) {
                        'super_admin' => 'danger',
                        'admin_rs' => 'warning',
                        'dokter' => 'success',
                        'staff' => 'info',
                        'pasien' => 'gray',
                        default => 'gray',
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
                Tables\Filters\SelectFilter::make('role')
                    ->label('Role')
                    ->options([
                        'admin_rs' => 'Admin RS',
                        'dokter' => 'Dokter',
                        'staff' => 'Staff',
                        'pasien' => 'Pasien',
                        'super_admin' => 'Super Admin',
                    ]),

                Tables\Filters\SelectFilter::make('hospital_id')
                    ->label('Hospital')
                    ->options(
                        function () {
                            $authUser = filament()->auth()->user();

                            if ($authUser && $authUser->role !== 'super_admin') {
                                return Hospital::query()
                                    ->where('id', $authUser->hospital_id)
                                    ->pluck('name', 'id');
                            }

                            return Hospital::query()->pluck('name', 'id');
                        }
                    )
                    ->query(function ($query, $data) {
                        $hospitalId = $data['value'] ?? null;

                        if (! filled($hospitalId)) {
                            return $query;
                        }

                        return $query->where(function ($q) use ($hospitalId) {

                            $q->where(function ($sub) use ($hospitalId) {
                                $sub->where('role', 'pasien')
                                    ->whereHas('patientEnrollments', function ($enrollment) use ($hospitalId) {
                                        $enrollment->where('hospital_id', $hospitalId);
                                    });
                            });

                            $q->orWhere(function ($sub) use ($hospitalId) {
                                $sub->where('role', '!=', 'pasien')
                                    ->where('hospital_id', $hospitalId);
                            });

                        });
                    }),

            ])

            ->actions([

                ViewAction::make()->color('warning'),
                EditAction::make(),
                DeleteAction::make(),

            ])

            ->bulkActions([

                DeleteBulkAction::make(),

            ]);
    }
}
