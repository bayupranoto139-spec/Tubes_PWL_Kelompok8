<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Bill;
use App\Models\PatientEnrollment;
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
                    // Queue hari ini (semua status selain skipped)
                    Queue::whereDate('queue_date', today())
                        ->whereNotIn('status', ['skipped'])
                        ->count()
                )
                    ->description('Daily visits')
                    ->descriptionIcon('heroicon-m-chart-bar')
                    ->color('info'),

                Stat::make(
                    'Monthly Revenue',
                    'Rp ' . number_format(
                        // Revenue hanya dari bill paid bulan ini
                        Bill::where('status', 'paid')
                            ->whereMonth('payment_date', now()->month)
                            ->whereYear('payment_date', now()->year)
                            ->sum('total_amount'),
                        0,
                        ',',
                        '.'
                    )
                )
                    ->description('Revenue this month (paid)')
                    ->descriptionIcon('heroicon-m-banknotes')
                    ->color('warning'),

                Stat::make(
                    'Active Queues',
                    Queue::whereDate('queue_date', today())
                        ->whereIn('status', ['waiting', 'called', 'in_progress'])
                        ->count()
                )
                    ->description('Queues in progress today')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('danger'),

                Stat::make(
                    'Pending Payments',
                    // Status valid: unpaid (tidak ada 'pending' di enum)
                    Bill::where('status', 'unpaid')->count()
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

            $hospitalId = $user->hospital_id;

            // Appointment hari ini difilter per hospital via patient_enrollment
            $appointmentsToday = Appointment::whereHas(
                'patientEnrollment',
                fn ($q) => $q->where('hospital_id', $hospitalId)
            )
            ->whereDate('scheduled_at', today())
            ->count();

            // Bill belum bayar untuk hospital ini
            $pendingBills = Bill::whereHas(
                'patientEnrollment',
                fn ($q) => $q->where('hospital_id', $hospitalId)
            )
            ->where('status', 'unpaid')
            ->count();

            // Queue hari ini untuk hospital ini (via appointment -> patient_enrollment)
            $queueToday = Queue::whereDate('queue_date', today())
                ->whereHas(
                    'appointment.patientEnrollment',
                    fn ($q) => $q->where('hospital_id', $hospitalId)
                )
                ->count();

            return [

                Stat::make('Appointments Today', $appointmentsToday)
                    ->description('Hospital appointments today')
                    ->descriptionIcon('heroicon-m-calendar-days')
                    ->color('info'),

                Stat::make('Pending Bills', $pendingBills)
                    ->description('Bills waiting payment')
                    ->descriptionIcon('heroicon-m-receipt-percent')
                    ->color('warning'),

                Stat::make('Queue Today', $queueToday)
                    ->description('Patient queue today')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('danger'),

            ];
        }

        /*
        |--------------------------------------------------------------------------
        | STAFF
        |--------------------------------------------------------------------------
        */

        $queueToday = Queue::whereDate('queue_date', today())->count();

        $pendingBills = Bill::where('status', 'unpaid')->count();

        $appointmentsCompleted = Appointment::whereDate('scheduled_at', today())
            ->where('status', 'completed')
            ->count();

        return [

            Stat::make('Today Queue', $queueToday)
                ->description('Patients in queue today')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),

            Stat::make('Completed Today', $appointmentsCompleted)
                ->description('Appointments done today')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Pending Bills', $pendingBills)
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