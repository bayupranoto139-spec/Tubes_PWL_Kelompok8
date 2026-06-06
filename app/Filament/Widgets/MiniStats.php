<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Bill;
use App\Models\Queue;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MiniStats extends StatsOverviewWidget
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

                Stat::make(
                    "Today's Visits",
                    Queue::whereDate('created_at', today())->count()
                )
                    ->description('Daily visits')
                    ->descriptionIcon('heroicon-m-chart-bar')
                    ->color('info'),

                Stat::make(
                    'Monthly Revenue',
                    'Rp ' . number_format(
                        Bill::whereMonth('created_at', now()->month)
                            ->sum('total_amount'),
                        0,
                        ',',
                        '.'
                    )
                )
                    ->description('Revenue this month')
                    ->descriptionIcon('heroicon-m-banknotes')
                    ->color('warning'),

                Stat::make(
                    'Active Queues',
                    Queue::count()
                )
                    ->description('Current queues')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('danger'),

                Stat::make(
                    'Pending Payments',
                    Bill::whereIn(
                        'status',
                        ['pending', 'unpaid']
                    )->count()
                )
                    ->description('Waiting payments')
                    ->descriptionIcon('heroicon-m-exclamation-circle')
                    ->color('danger'),

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
                    'Appointments Today',
                    Appointment::whereDate(
                        'scheduled_at',
                        today()
                    )->count()
                )
                    ->description('Hospital appointments')
                    ->descriptionIcon('heroicon-m-calendar-days')
                    ->color('info'),

                

                Stat::make(
                    'Pending Bills',
                    Bill::whereHas(
                        'patientEnrollment',
                        fn ($q) => $q->where(
                            'hospital_id',
                            $user->hospital_id
                        )
                    )
                    ->whereIn(
                        'status',
                        ['pending', 'unpaid']
                    )
                    ->count()
                )
                    ->description('Bills waiting payment')
                    ->descriptionIcon('heroicon-m-receipt-percent')
                    ->color('warning'),

                Stat::make(
                    'Queue Today',
                    Queue::whereDate(
                        'created_at',
                        today()
                    )->count()
                )
                    ->description('Patient queue')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('danger'),

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | STAFF
        |--------------------------------------------------------------------------
        */

        return [

            Stat::make(
                'Today Queue',
                Queue::whereDate(
                    'created_at',
                    today()
                )->count()
            )
                ->description('Patients waiting')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),

            Stat::make(
                'Today Visits',
                Queue::whereDate(
                    'created_at',
                    today()
                )->count()
            )
                ->description('Visits today')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('info'),

            Stat::make(
                'Pending Bills',
                Bill::whereIn(
                    'status',
                    ['pending', 'unpaid']
                )->count()
            )
                ->description('Need payment')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),


        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}