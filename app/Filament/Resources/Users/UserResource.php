<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
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
                'staff',
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
                'staff',
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
                'staff',
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
                'staff',
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

        // Eager-load hospital relations for performance.
        // For pasien, hospital will be resolved through patient_enrollments (handled in queries below).
        $query->with(['hospital']);

        // Exclude pasien users that do not have an enrollment/hospital.
        // This ensures: pasien must exist in patient_enrollments (with hospital_id), otherwise not shown.
        // Note: for filtering by hospital on the table, hospital filter is handled in UsersTable.
        // Here we only enforce existence of an enrollment record with hospital_id.
        $query->where(function ($q) {
            $q
                ->where('role', '!=', 'pasien')
                ->orWhereHas('patientEnrollments', function ($sub) {
                    $sub->whereNotNull('hospital_id');
                });
        });

        // If the UI applies role=pasien filter + hospital filter, force-synchronize them
        // by applying hospital constraint on patient_enrollments.
        $filters = request()->input('tableFilters', []);
        $hospitalId = $filters['hospital_id'] ?? null;
        $roleFilter = $filters['role'] ?? null;

        if ($roleFilter === 'pasien' && filled($hospitalId)) {
            $query->whereHas('patientEnrollments', function ($sub) use ($hospitalId) {
                $sub->where('hospital_id', $hospitalId);
            });
        }

        // Final guard (to avoid UI filter order/interaction issues):
        // if role filter is pasien, ensure user has enrollment for the selected hospital.
        // If hospital_id filter is not set to specific value (e.g. "all"), then only require hospital_id not null.
        if ($roleFilter === 'pasien') {
            if (filled($hospitalId)) {
                $query->whereHas('patientEnrollments', function ($sub) use ($hospitalId) {
                    $sub->where('hospital_id', $hospitalId);
                });
            } else {
                $query->whereHas('patientEnrollments', function ($sub) {
                    $sub->whereNotNull('hospital_id');
                });
            }
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

        if (in_array($user->role, ['admin_rs', 'staff'])) {
            // Both admin_rs and staff only see users within their own hospital.
            // Wrap both conditions in a SINGLE where() so the OR stays scoped
            // and does not escape earlier constraints (fixes view/edit record bug).
            return $query->where(function ($q) use ($user) {
                // doctors & staff: hospital stored in users.hospital_id
                $q->where(function ($sub) use ($user) {
                    $sub->whereIn('role', ['dokter', 'staff'])
                        ->where('hospital_id', $user->hospital_id);
                })
                // patients: hospital stored in patient_enrollments.hospital_id
                ->orWhere(function ($sub) use ($user) {
                    $sub->where('role', 'pasien')
                        ->whereHas('patientEnrollments', function ($enrollment) use ($user) {
                            $enrollment->where('hospital_id', $user->hospital_id);
                        });
                });
            });
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}