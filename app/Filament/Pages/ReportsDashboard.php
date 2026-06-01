<?php

namespace App\Filament\Pages;

use App\Models\Appointment;
use App\Models\Bill;
use App\Models\User;
use BackedEnum;
use Filament\Pages\Page;

class ReportsDashboard extends Page
{
    /*
    |--------------------------------------------------------------------------
    | NAVIGATION
    |--------------------------------------------------------------------------
    */

    protected static string|BackedEnum|null $navigationIcon =
        'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Reports';

    protected static ?string $title = 'Reports Dashboard';

    protected static ?int $navigationSort = 9;

    /*
    |--------------------------------------------------------------------------
    | ACCESS CONTROL
    |--------------------------------------------------------------------------
    */

    public static function shouldRegisterNavigation(): bool
    {
        return filament()->auth()->user()?->role === 'super_admin';
    }

    public static function canAccess(): bool
    {
        return filament()->auth()->user()?->role === 'super_admin';
    }

    /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */

    protected string $view = 'filament.pages.reports-dashboard';

    /*
    |--------------------------------------------------------------------------
    | SUMMARY CARDS
    |--------------------------------------------------------------------------
    */

    public int $totalVisits = 0;

    public float $totalRevenue = 0;

    public int $newPatients = 0;

    public int $appointments = 0;

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ARRAYS
    |--------------------------------------------------------------------------
    */

    public array $topDoctors = [];

    public array $hospitalRevenue = [];

    public array $recentAppointments = [];

    /*
    |--------------------------------------------------------------------------
    | LOAD DATA
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        /*
        |--------------------------------------------------------------------------
        | TOTAL PATIENTS
        |--------------------------------------------------------------------------
        */

        $this->totalVisits = User::query()
            ->where('role', 'pasien')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL REVENUE
        |--------------------------------------------------------------------------
        */

        $this->totalRevenue = (float) Bill::query()
            ->sum('total_amount');

        /*
        |--------------------------------------------------------------------------
        | NEW PATIENTS
        |--------------------------------------------------------------------------
        */

        $this->newPatients = User::query()
            ->where('role', 'pasien')
            ->whereMonth('created_at', now()->month)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | TOTAL APPOINTMENTS
        |--------------------------------------------------------------------------
        */

        $this->appointments = Appointment::query()
            ->count();

        /*
        |--------------------------------------------------------------------------
        | TOP DOCTORS
        |--------------------------------------------------------------------------
        */

        $this->topDoctors = Appointment::query()
            ->selectRaw('doctor_id, COUNT(*) as total_visits')
            ->with('doctor.user')
            ->groupBy('doctor_id')
            ->orderByDesc('total_visits')
            ->limit(5)
            ->get()
            ->map(function ($item) {

                return [

                    'name' => $item->doctor?->user?->name ?? '-',

                    'visits' => $item->total_visits,

                ];
            })
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | HOSPITAL REVENUE
        |--------------------------------------------------------------------------
        */

        $this->hospitalRevenue = Bill::query()
            ->with('patientEnrollment.hospital')
            ->get()
            ->groupBy(function ($bill) {

                return $bill
                    ->patientEnrollment?->hospital?->name
                    ?? 'Unknown Hospital';
            })
            ->map(function ($bills, $hospitalName) {

                return [

                    'hospital' => $hospitalName,

                    'revenue' => $bills->sum('total_amount'),

                ];
            })
            ->values()
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | RECENT APPOINTMENTS
        |--------------------------------------------------------------------------
        */

        $this->recentAppointments = Appointment::query()
            ->with([
                'patientEnrollment.user',
                'doctor.user.hospital',
            ])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($appointment) {

                return [

                    'patient' =>
                        $appointment->patientEnrollment?->user?->name
                        ?? '-',

                    'doctor' =>
                        $appointment->doctor?->user?->name
                        ?? '-',

                    'hospital' =>
                        $appointment->doctor?->user?->hospital?->name
                        ?? '-',

                    'date' =>
                        $appointment->scheduled_at
                            ? $appointment->scheduled_at->format('d M Y H:i')
                            : '-',

                    'status' =>
                        ucfirst($appointment->status ?? '-'),

                ];
            })
            ->toArray();
    }
}