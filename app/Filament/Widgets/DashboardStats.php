<?php

namespace App\Filament\Widgets;

use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\PatientEnrollment;
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
                    // Pasien unik yang terdaftar di sistem (berdasarkan role)
                    User::where('role', 'pasien')->count()
                )
                    ->description('Registered patients')
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

            $hospitalId = $user->hospital_id;

            // Staff dihitung dari users yang terikat ke hospital ini (role staff atau admin_rs)
            $staffCount = User::where('hospital_id', $hospitalId)
                ->whereIn('role', ['admin_rs', 'staff'])
                ->count();

            // Pasien dihitung dari patient_enrollments ke hospital ini (bukan users.hospital_id)
            $patientCount = PatientEnrollment::where('hospital_id', $hospitalId)
                ->distinct('user_id')
                ->count('user_id');

            // Appointment difilter berdasarkan doctor yang bekerja di hospital ini
            $appointmentCount = Appointment::whereHas(
                'patientEnrollment',
                fn ($q) => $q->where('hospital_id', $hospitalId)
            )->count();

            // Revenue hanya dari bill yang sudah paid
            $revenue = Bill::whereHas(
                'patientEnrollment',
                fn ($q) => $q->where('hospital_id', $hospitalId)
            )
            ->where('status', 'paid')
            ->sum('total_amount');

            return [

                Stat::make('Hospital Staff', $staffCount)
                    ->description('Staff in hospital')
                    ->descriptionIcon('heroicon-m-user-group')
                    ->color('success'),

                Stat::make('Patients', $patientCount)
                    ->description('Enrolled patients')
                    ->descriptionIcon('heroicon-m-user')
                    ->color('primary'),

                Stat::make('Appointments', $appointmentCount)
                    ->description('Total appointments')
                    ->descriptionIcon('heroicon-m-calendar-days')
                    ->color('warning'),

                Stat::make(
                    'Hospital Revenue',
                    'Rp ' . number_format($revenue, 0, ',', '.')
                )
                    ->description('Revenue from paid bills')
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
                Appointment::whereDate('scheduled_at', today())->count()
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