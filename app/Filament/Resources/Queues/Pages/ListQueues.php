<?php

namespace App\Filament\Resources\Queues\Pages;

use App\Filament\Resources\Queues\QueueResource;
use App\Models\Queue;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListQueues extends ListRecords
{
    protected static string $resource = QueueResource::class;

    /**
     * Tombol "Daftarkan Walk-in" di header halaman
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('walk_in')
                ->label('+ Daftarkan Walk-in')
                ->icon('heroicon-o-user-plus')
                ->color('warning')
                ->url(QueueResource::getUrl('walk-in')),
        ];
    }

    /**
     * Widget ringkasan antrian hari ini di atas tabel
     */
    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Widgets\QueueStatsWidget::class,
        ];
    }
}