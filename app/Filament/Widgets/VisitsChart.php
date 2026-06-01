<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\ChartWidget;

class VisitsChart extends ChartWidget
{
    protected ?string $heading = 'Visits per Month';

    protected ?string $maxHeight = '250px';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $user = filament()->auth()->user();

        $query = Appointment::query();

        /*
        |--------------------------------------------------------------------------
        | ADMIN RS
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin_rs') {

            $query->whereHas(
                'doctor.user',
                fn ($q) => $q->where(
                    'hospital_id',
                    $user->hospital_id
                )
            );
        }

        /*
        |--------------------------------------------------------------------------
        | MONTHLY DATA
        |--------------------------------------------------------------------------
        */

        $monthlyVisits = [];

        for ($month = 1; $month <= 12; $month++) {

            $monthlyVisits[] = (clone $query)
                ->whereMonth('scheduled_at', $month)
                ->whereYear('scheduled_at', now()->year)
                ->count();
        }

        return [

            'datasets' => [
                [
                    'label' => 'Visits',

                    'data' => $monthlyVisits,

                    'fill' => false,

                    'tension' => 0.4,
                ],
            ],

            'labels' => [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun',
                'Jul',
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public static function canView(): bool
    {
        return in_array(
            filament()->auth()->user()?->role,
            [
                'super_admin',
                'admin_rs',
            ]
        );
    }
}