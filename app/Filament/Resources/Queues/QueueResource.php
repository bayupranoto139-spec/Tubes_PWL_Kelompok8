<?php

namespace App\Filament\Resources\Queues;

use App\Filament\Resources\Queues\Pages\ListQueues;
use App\Filament\Resources\Queues\Pages\WalkInPage;
use App\Models\Queue;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class QueueResource extends Resource
{
    protected static ?string $model = Queue::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    protected static ?string $navigationLabel = 'Queue Management';

    protected static ?string $modelLabel = 'Queue';

    protected static ?string $pluralModelLabel = 'Queues';

    protected static string|\UnitEnum|null $navigationGroup = 'Operations';

    protected static ?int $navigationSort = 10;

    public static function shouldRegisterNavigation(): bool
    {
        return in_array(
            filament()->auth()->user()?->role,
            ['admin_rs', 'staff']
        );
    }

    public static function canCreate(): bool
    {
        return false; // Walk-in dibuat via halaman khusus WalkInPage
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return \App\Filament\Resources\Queues\Tables\QueuesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'   => ListQueues::route('/'),
            'walk-in' => WalkInPage::route('/walk-in'),
        ];
    }

    /**
     * Filter queue hanya untuk hospital yang dikelola admin/staff yang login.
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user  = filament()->auth()->user();

        if ($user->role === 'admin_rs' || $user->role === 'staff') {
            $query->whereHas(
                'appointment.patientEnrollment',
                fn ($q) => $q->where('hospital_id', $user->hospital_id)
            );
        }

        return $query;
    }
}