<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon =
        'heroicon-o-users';

    protected static ?string $navigationLabel = 'Users';

    protected static ?string $modelLabel = 'User';

    protected static ?string $pluralModelLabel = 'Users';

    protected static ?int $navigationSort = 2;

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

    public static function canEdit($record): bool
    {
        return in_array(
            filament()->auth()->user()?->role,
            [
                'super_admin',
                'admin_rs',
            ]
        );
    }

    public static function canDelete($record): bool
    {
        return filament()->auth()->user()?->role === 'super_admin';
    }

    /*
    |--------------------------------------------------------------------------
    | FORM
    |--------------------------------------------------------------------------
    */

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY
    |--------------------------------------------------------------------------
    */

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = filament()->auth()->user();

        if (! $user) {
            return $query;
        }

        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN
        |--------------------------------------------------------------------------
        |
        | Melihat semua user seluruh rumah sakit
        |
        */

        if ($user->role === 'super_admin') {
            return $query;
        }

        /*
        |--------------------------------------------------------------------------
        | ADMIN RS
        |--------------------------------------------------------------------------
        |
        | Hanya melihat user pada rumah sakitnya
        |
        */

        if ($user->role === 'admin_rs') {
            return $query->where(
                'hospital_id',
                $user->hospital_id
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
            'index'  => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit'   => EditUser::route('/{record}/edit'),
        ];
    }
}