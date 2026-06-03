<?php

namespace App\Filament\Widgets;

use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Bill;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $user = filament()->auth()->user();

        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'super_admin') {

            return [

                Stat::make('Total Hospitals', Hospital::count())
                    ->description('Healthcare centers')
                    ->descriptionIcon('heroicon-m-building-office-2')
                    ->color('success'),

                Stat::make('Total Users', User::count())
                    ->description('Registered accounts')
                    ->descriptionIcon('heroicon-m-users')
                    ->color('info'),

                Stat::make('Total Doctors', Doctor::count())
                    ->description('Available doctors')
                    ->descriptionIcon('heroicon-m-heart')
                    ->color('warning'),

                Stat::make(
                    'Total Patients',
                    User::where('role', 'pasien')->count()
                )
                    ->description('Active patients')
                    ->descriptionIcon('heroicon-m-user')
                    ->color('primary'),

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | ADMIN RS
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin_rs') {

            return [

                Stat::make(
                    'Hospital Staff',
                    User::where('hospital_id', $user->hospital_id)
                        ->whereIn('role', [
                            'admin_rs',
                            'staff',
                        ])
                        ->count()
                )
                    ->description('Staff in hospital')
                    ->descriptionIcon('heroicon-m-user-group')
                    ->color('success'),

                Stat::make(
                    'Patients',
                    User::where('hospital_id', $user->hospital_id)
                        ->where('role', 'pasien')
                        ->count()
                )
                    ->description('Registered patients')
                    ->descriptionIcon('heroicon-m-user')
                    ->color('primary'),

                Stat::make(
                    'Appointments',
                    Appointment::count()
                )
                    ->description('Appointments')
                    ->descriptionIcon('heroicon-m-calendar-days')
                    ->color('warning'),

                Stat::make(
                    'Hospital Revenue',
                    'Rp ' . number_format(
                        Bill::whereHas(
                            'patientEnrollment',
                            fn ($q) =>
                                $q->where(
                                    'hospital_id',
                                    $user->hospital_id
                                )
                        )->sum('total_amount')
                    )
                )
                    ->description('Hospital revenue')
                    ->descriptionIcon('heroicon-m-banknotes')
                    ->color('success'),

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | STAFF
        |--------------------------------------------------------------------------
        */

        return [

            Stat::make(
                'Appointments Today',
                Appointment::whereDate(
                    'scheduled_at',
                    today()
                )->count()
            )
                ->description('Today schedule')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),

            Stat::make(
                'Pending Bills',
                Bill::where('status', 'unpaid')->count()
            )
                ->description('Waiting payment')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('danger'),

            Stat::make(
                'Patients',
                User::where('role', 'pasien')->count()
            )
                ->description('Registered patients')
                ->descriptionIcon('heroicon-m-user')
                ->color('success'),

            Stat::make(
                'Doctors',
                Doctor::count()
            )
                ->description('Available doctors')
                ->descriptionIcon('heroicon-m-heart')
                ->color('warning'),

        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}