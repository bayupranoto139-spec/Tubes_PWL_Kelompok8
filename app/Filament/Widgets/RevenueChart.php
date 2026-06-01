<?php

namespace App\Filament\Widgets;

use App\Models\Bill;
use Filament\Widgets\ChartWidget;

class RevenueChart extends ChartWidget
{
    protected ?string $heading = 'Revenue by Hospital';

    protected ?string $maxHeight = '320px';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $user = filament()->auth()->user();

        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'super_admin') {

            $bills = Bill::with(
                'patientEnrollment.hospital'
            )
            ->get()
            ->groupBy(function ($bill) {

                return $bill->patientEnrollment?->hospital?->name
                    ?? 'Unknown Hospital';
            });

            $labels = [];

            $revenues = [];

            foreach ($bills as $hospital => $items) {

                $labels[] = $hospital;

                $revenues[] = $items->sum('total_amount');
            }

            return [

                'datasets' => [
                    [
                        'label' => 'Revenue',

                        'data' => $revenues,

                        'backgroundColor' => [
                            '#14b8a6',
                            '#0ea5e9',
                            '#8b5cf6',
                            '#f59e0b',
                            '#ef4444',
                        ],

                        'borderRadius' => 12,
                    ],
                ],

                'labels' => $labels,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | ADMIN RS
        |--------------------------------------------------------------------------
        */

        $hospitalRevenue = Bill::whereHas(
            'patientEnrollment',
            fn ($q) => $q->where(
                'hospital_id',
                $user->hospital_id
            )
        )->sum('total_amount');

        return [

            'datasets' => [
                [
                    'label' => 'Revenue',

                    'data' => [
                        $hospitalRevenue,
                    ],

                    'backgroundColor' => [
                        '#14b8a6',
                    ],

                    'borderRadius' => 12,
                ],
            ],

            'labels' => [
                'My Hospital',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}