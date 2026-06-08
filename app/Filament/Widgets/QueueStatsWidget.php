<?php

namespace App\Filament\Widgets;

use App\Models\Queue;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QueueStatsWidget extends StatsOverviewWidget
{
    // Hanya tampil di halaman ListQueues, bukan di dashboard global
    protected static bool $isLazy = false;

    public static function canView(): bool
    {
        return in_array(
            filament()->auth()->user()?->role,
            ['admin_rs', 'staff']
        );
    }

    protected function getStats(): array
    {
        $hospitalId = filament()->auth()->user()?->hospital_id;

        $base = Queue::whereDate('queue_date', today())
            ->whereHas(
                'appointment.patientEnrollment',
                fn ($q) => $q->where('hospital_id', $hospitalId)
            );

        $waiting    = (clone $base)->where('status', 'waiting')->count();
        $called     = (clone $base)->where('status', 'called')->count();
        $inProgress = (clone $base)->where('status', 'in_progress')->count();
        $completed  = (clone $base)->where('status', 'completed')->count();
        $skipped    = (clone $base)->where('status', 'skipped')->count();
        $total      = (clone $base)->count();

        $walkIn      = (clone $base)->where('type', 'walk_in')->count();
        $appointment = (clone $base)->where('type', 'appointment')->count();

        return [

            Stat::make('Menunggu', $waiting)
                ->description("$appointment appointment · $walkIn walk-in")
                ->descriptionIcon('heroicon-m-clock')
                ->color('gray'),

            Stat::make('Dipanggil / Dalam Pemeriksaan', $called + $inProgress)
                ->description("$called dipanggil, $inProgress sedang diperiksa")
                ->descriptionIcon('heroicon-m-megaphone')
                ->color('warning'),

            Stat::make('Selesai Hari Ini', $completed)
                ->description("$skipped pasien dilewati · Total $total antrian")
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

        ];
    }
}