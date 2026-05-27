<?php

namespace App\Filament\Widgets;

use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [

            Stat::make('Total Hospitals', Hospital::count())
                ->description('Healthcare centers')
                ->descriptionIcon('heroicon-m-building-office-2')
                ->chart([7, 10, 12, 15, 18, 20])
                ->color('success'),

            Stat::make('Total Users', User::count())
                ->description('Registered accounts')
                ->descriptionIcon('heroicon-m-users')
                ->chart([5, 8, 10, 14, 18, 22])
                ->color('info'),

            Stat::make('Total Doctors', Doctor::count())
                ->description('Available doctors')
                ->descriptionIcon('heroicon-m-heart')
                ->chart([2, 4, 6, 8, 10, 12])
                ->color('warning'),

            Stat::make(
                'Total Patients',
                User::where('role', 'pasien')->count()
            )
                ->description('Active patients')
                ->descriptionIcon('heroicon-m-user')
                ->chart([3, 6, 9, 12, 16, 20])
                ->color('primary'),

        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}