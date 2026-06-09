<?php

namespace App\Filament\Widgets;

use App\Models\Bill;
use Filament\Widgets\ChartWidget;

class RevenueChart extends ChartWidget
{
    protected ?string $heading = 'Revenue by Hospital (Paid Bills)';

    protected ?string $maxHeight = '320px';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $user = filament()->auth()->user();

        /*
        |--------------------------------------------------------------------------
        | SUPER ADMIN — semua hospital, hanya bill paid
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'super_admin') {

            $bills = Bill::with('patientEnrollment.hospital')
                ->where('status', 'paid')   // hanya yang sudah dibayar
                ->get()
                ->groupBy(function ($bill) {
                    return $bill->patientEnrollment?->hospital?->name
                        ?? 'Unknown Hospital';
                });

            $labels   = [];
            $revenues = [];

            foreach ($bills as $hospital => $items) {
                $labels[]   = $hospital;
                $revenues[] = (float) $items->sum('total_amount');
            }

            // Jika tidak ada data, tampilkan placeholder
            if (empty($labels)) {
                $labels   = ['No Data'];
                $revenues = [0];
            }

            return [
                'datasets' => [
                    [
                        'label'           => 'Revenue (Paid)',
                        'data'            => $revenues,
                        'backgroundColor' => [
                            '#14b8a6',
                            '#0ea5e9',
                            '#8b5cf6',
                            '#f59e0b',
                            '#ef4444',
                            '#10b981',
                            '#f97316',
                        ],
                        'borderRadius' => 12,
                    ],
                ],
                'labels' => $labels,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | ADMIN RS — revenue hospital sendiri per bulan (12 bulan terakhir)
        |--------------------------------------------------------------------------
        */

        $hospitalId = $user->hospital_id;

        $monthlyRevenue = [];
        $monthLabels    = [];

        for ($i = 11; $i >= 0; $i--) {
            $date          = now()->subMonths($i);
            $monthLabels[] = $date->format('M Y');

            $monthlyRevenue[] = (float) Bill::whereHas(
                'patientEnrollment',
                fn ($q) => $q->where('hospital_id', $hospitalId)
            )
            ->where('status', 'paid')
            ->whereMonth('payment_date', $date->month)
            ->whereYear('payment_date', $date->year)
            ->sum('total_amount');
        }

        return [
            'datasets' => [
                [
                    'label'           => 'Monthly Revenue (Paid)',
                    'data'            => $monthlyRevenue,
                    'backgroundColor' => '#14b8a6',
                    'borderRadius'    => 8,
                ],
            ],
            'labels' => $monthLabels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public static function canView(): bool
    {
        return in_array(
            filament()->auth()->user()?->role,
            ['super_admin', 'admin_rs']
        );
    }
}