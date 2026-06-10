<?php

namespace App\Filament\Resources\Appointments;

use App\Filament\Resources\Appointments\Pages\CreateAppointment;
use App\Filament\Resources\Appointments\Pages\EditAppointment;
use App\Filament\Resources\Appointments\Pages\ListAppointments;
use App\Filament\Resources\Appointments\Pages\ViewAppointment;
use App\Filament\Resources\Appointments\Schemas\AppointmentForm;
use App\Filament\Resources\Appointments\Tables\AppointmentsTable;
use App\Models\Appointment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedCalendarDays;

    protected static ?string $navigationLabel = 'Appointments';

    protected static ?string $modelLabel = 'Appointment';

    protected static ?string $pluralModelLabel = 'Appointments';

    protected static ?int $navigationSort = 5;

    /*
    |--------------------------------------------------------------------------
    | ACCESS CONTROL
    |--------------------------------------------------------------------------
    */

    public static function shouldRegisterNavigation(): bool
    {
        return in_array(
            filament()->auth()->user()?->role,
            ['super_admin', 'admin_rs', 'staff']
        );
    }

    public static function canViewAny(): bool
    {
        return in_array(
            filament()->auth()->user()?->role,
            ['super_admin', 'admin_rs', 'staff']
        );
    }

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
    | FORM
    |--------------------------------------------------------------------------
    */

    public static function form(Schema $schema): Schema
    {
        return AppointmentForm::configure($schema);
    }

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    public static function table(Table $table): Table
    {
        return AppointmentsTable::configure($table);
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

        // super_admin sees all appointments across all hospitals
        if ($user->role === 'super_admin') {
            return $query;
        }

        // admin_rs and staff only see appointments within their own hospital
        if (in_array($user->role, ['admin_rs', 'staff'])) {
            return $query->whereHas(
                'patientEnrollment',
                fn ($q) => $q->where('hospital_id', $user->hospital_id)
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
            'index'  => ListAppointments::route('/'),
            'create' => CreateAppointment::route('/create'),
            'view'   => ViewAppointment::route('/{record}'),
            'edit'   => EditAppointment::route('/{record}/edit'),
        ];
    }
}