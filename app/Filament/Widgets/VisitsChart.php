<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class VisitsChart extends ChartWidget
{
    protected ?string $heading = 'Visits per Month';

    protected ?string $maxHeight = '250px';

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Visits',
                    'data' => [50, 80, 120, 150, 200, 250],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}