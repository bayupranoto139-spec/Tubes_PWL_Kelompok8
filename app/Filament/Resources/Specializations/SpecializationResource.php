<?php

namespace App\Filament\Resources\Specializations;

use App\Filament\Resources\Specializations\Pages\CreateSpecialization;
use App\Filament\Resources\Specializations\Pages\EditSpecialization;
use App\Filament\Resources\Specializations\Pages\ListSpecializations;
use App\Filament\Resources\Specializations\Schemas\SpecializationForm;
use App\Filament\Resources\Specializations\Tables\SpecializationsTable;
use App\Models\Specialization;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class SpecializationResource extends Resource
{
    /*
    |--------------------------------------------------------------------------
    | MODEL
    |--------------------------------------------------------------------------
    */

    protected static ?string $model = Specialization::class;

    /*
    |--------------------------------------------------------------------------
    | NAVIGATION
    |--------------------------------------------------------------------------
    */

    protected static string|BackedEnum|null $navigationIcon =
    'heroicon-o-identification';

    protected static ?string $navigationLabel =
        'Specializations';

    protected static ?string $modelLabel =
        'Specialization';

    protected static ?string $pluralModelLabel =
        'Specializations';

    protected static ?int $navigationSort = 4;

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
        return SpecializationForm::configure($schema);
    }

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    public static function table(Table $table): Table
    {
        return SpecializationsTable::configure($table);
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
            'index' => ListSpecializations::route('/'),
            'create' => CreateSpecialization::route('/create'),
            'view' => \App\Filament\Resources\Specializations\Pages\ViewSpecialization::route('/{record}'),
            'edit' => EditSpecialization::route('/{record}/edit'),
        ];
    }
}