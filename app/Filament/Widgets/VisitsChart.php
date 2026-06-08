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
        $user  = filament()->auth()->user();
        $year  = now()->year;
        $query = Appointment::query();

        /*
        |--------------------------------------------------------------------------
        | ADMIN RS — filter via patient_enrollment ke hospital ini
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin_rs') {
            $query->whereHas(
                'patientEnrollment',
                fn ($q) => $q->where('hospital_id', $user->hospital_id)
            );
        }

        /*
        |--------------------------------------------------------------------------
        | MONTHLY DATA — hanya appointment yang selesai (completed)
        |--------------------------------------------------------------------------
        */

        $monthlyVisits = [];

        for ($month = 1; $month <= 12; $month++) {
            $monthlyVisits[] = (clone $query)
                ->whereMonth('scheduled_at', $month)
                ->whereYear('scheduled_at', $year)
                ->where('status', 'completed')
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label'   => 'Completed Visits ' . $year,
                    'data'    => $monthlyVisits,
                    'fill'    => false,
                    'tension' => 0.4,
                ],
            ],
            'labels' => [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
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
            ['super_admin', 'admin_rs']
        );
    }
}