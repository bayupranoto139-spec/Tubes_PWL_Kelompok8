<?php

namespace App\Filament\Resources\Prescriptions;

use App\Filament\Resources\Prescriptions\Pages\CreatePrescription;
use App\Filament\Resources\Prescriptions\Pages\EditPrescription;
use App\Filament\Resources\Prescriptions\Pages\ListPrescriptions;
use App\Filament\Resources\Prescriptions\Schemas\PrescriptionForm;
use App\Filament\Resources\Prescriptions\Tables\PrescriptionsTable;
use App\Models\Prescription;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PrescriptionResource extends Resource
{
    /*
    |--------------------------------------------------------------------------
    | MODEL
    |--------------------------------------------------------------------------
    */

    protected static ?string $model = Prescription::class;

    /*
    |--------------------------------------------------------------------------
    | NAVIGATION
    |--------------------------------------------------------------------------
    */

    protected static string|BackedEnum|null $navigationIcon =
        'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel =
        'Prescriptions';

    protected static ?string $modelLabel =
        'Prescription';

    protected static ?string $pluralModelLabel =
        'Prescriptions';

    protected static ?int $navigationSort = 7;

    /*
    |--------------------------------------------------------------------------
    | ACCESS CONTROL
    |--------------------------------------------------------------------------
    */

    public static function shouldRegisterNavigation(): bool
    {
        return in_array(
            filament()->auth()->user()?->role,
            [
                'super_admin',
                'admin_rs',
            ]
        );
    }

    public static function canViewAny(): bool
    {
        return in_array(
            filament()->auth()->user()?->role,
            [
                'super_admin',
                'admin_rs',
            ]
        );
    }

    public static function canCreate(): bool
    {
        return in_array(
            filament()->auth()->user()?->role,
            [
                'super_admin',
                'admin_rs',
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | FORM
    |--------------------------------------------------------------------------
    */

    public static function form(Schema $schema): Schema
    {
        return PrescriptionForm::configure($schema);
    }

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    public static function table(Table $table): Table
    {
        return PrescriptionsTable::configure($table);
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY FILTER
    |--------------------------------------------------------------------------
    */

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = filament()->auth()->user();

        if (! $user) {
            return $query;
        }

        if ($user->role === 'admin_rs') {

            $query->whereHas(
                'medicalRecord.appointment.patientEnrollment',
                fn ($q) => $q->where(
                    'hospital_id',
                    $user->hospital_id
                )
            );
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public static function getRelations(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | PAGES
    |--------------------------------------------------------------------------
    */

    public static function getPages(): array
    {
        return [
            'index' => ListPrescriptions::route('/'),
            'create' => CreatePrescription::route('/create'),
            'edit' => EditPrescription::route('/{record}/edit'),
        ];
    }
}