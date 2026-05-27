<?php

namespace App\Filament\Widgets;

use App\Models\Bill;
use App\Models\Queue;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MiniStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [

            Stat::make(
                "Today's Visits",
                Queue::whereDate('created_at', today())->count()
            )
                ->description('Daily visits')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->chart([2, 4, 6, 8, 10, 12])
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
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->chart([5, 10, 15, 20, 25, 30])
                ->color('warning'),

            Stat::make(
                'Active Queues',
                Queue::count()
            )
                ->description('Current queues')
                ->descriptionIcon('heroicon-m-clock')
                ->chart([1, 3, 5, 7, 9, 11])
                ->color('danger'),

            Stat::make(
                'Pending Payments',
                Bill::whereIn('status', ['pending', 'unpaid'])->count()
            )
                ->description('Waiting payments')
                ->descriptionIcon('heroicon-m-exclamation-circle')
                ->chart([1, 2, 3, 4, 5, 6])
                ->color('danger'),

        ];
    }

    protected function getColumns(): int
    {
        return 4;
    }
}