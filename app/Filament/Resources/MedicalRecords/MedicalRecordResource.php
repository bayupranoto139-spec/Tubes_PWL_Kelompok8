<?php

namespace App\Filament\Resources\MedicalRecords;

use App\Filament\Resources\MedicalRecords\Pages\CreateMedicalRecord;
use App\Filament\Resources\MedicalRecords\Pages\EditMedicalRecord;
use App\Filament\Resources\MedicalRecords\Pages\ListMedicalRecords;
use App\Filament\Resources\MedicalRecords\Pages\ViewMedicalRecord;
use App\Filament\Resources\MedicalRecords\Schemas\MedicalRecordForm;
use App\Filament\Resources\MedicalRecords\Tables\MedicalRecordsTable;
use App\Models\MedicalRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MedicalRecordResource extends Resource
{
    protected static ?string $model = MedicalRecord::class;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedHeart;

    protected static ?string $navigationLabel = 'Medical Records';

    protected static ?string $modelLabel = 'Medical Record';

    protected static ?string $pluralModelLabel = 'Medical Records';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return MedicalRecordForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MedicalRecordsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListMedicalRecords::route('/'),
            'create' => CreateMedicalRecord::route('/create'),
            'view'   => ViewMedicalRecord::route('/{record}'),
            'edit'   => EditMedicalRecord::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESS CONTROL
    |--------------------------------------------------------------------------
    | super_admin  → view only (no create / edit / delete)
    | admin_rs     → create, edit, delete
    | staff        → create, edit, delete
    |--------------------------------------------------------------------------
    */

    public static function canCreate(): bool
    {
        return in_array(
            filament()->auth()->user()?->role,
            ['admin_rs', 'staff']
        );
    }

    public static function canEdit($record): bool
    {
        return in_array(
            filament()->auth()->user()?->role,
            ['admin_rs', 'staff']
        );
    }

    public static function canDelete($record): bool
    {
        return in_array(
            filament()->auth()->user()?->role,
            ['admin_rs', 'staff']
        );
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

        if ($user->role === 'super_admin') {
            return $query;
        }

        return $query->whereHas(
            'appointment.patientEnrollment',
            fn ($q) => $q->where('hospital_id', $user->hospital_id)
        );
    }
}