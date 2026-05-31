<?php

namespace App\Filament\Pages;

use App\Models\Appointment;
use App\Models\Bill;
use App\Models\User;
use Filament\Pages\Page;
use BackedEnum;

class ReportsDashboard extends Page
{
    /*
    |--------------------------------------------------------------------------
    | SIDEBAR
    |--------------------------------------------------------------------------
    */

    protected static string | BackedEnum | null $navigationIcon =
        'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Reports';

    protected static ?string $title = 'Reports';

    protected static ?int $navigationSort = 4;


    /*
    |--------------------------------------------------------------------------
    | BLADE VIEW
    |--------------------------------------------------------------------------
    */

    protected string $view = 'filament.pages.reports-dashboard';


    /*
    |--------------------------------------------------------------------------
    | CARD STATS
    |--------------------------------------------------------------------------
    */

    public int $totalVisits = 0;

    public int $totalRevenue = 0;

    public int $newPatients = 0;

    public int $appointments = 0;


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD DATA
    |--------------------------------------------------------------------------
    */

    public array $topDoctors = [];

    public array $hospitalRevenue = [];

    public array $recentAppointments = [];


    /*
    |--------------------------------------------------------------------------
    | LOAD DASHBOARD DATA
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        /*
        |--------------------------------------------------------------------------
        | TOTAL VISITS
        |--------------------------------------------------------------------------
        */

        $this->totalVisits = User::where('role', 'pasien')->count();


        /*
        |--------------------------------------------------------------------------
        | TOTAL REVENUE
        |--------------------------------------------------------------------------
        */

        $this->totalRevenue = Bill::sum('total_amount') ?? 0;


        /*
        |--------------------------------------------------------------------------
        | NEW PATIENTS
        |--------------------------------------------------------------------------
        */

        $this->newPatients = User::where('role', 'pasien')->count();


        /*
        |--------------------------------------------------------------------------
        | TOTAL APPOINTMENTS
        |--------------------------------------------------------------------------
        */

        $this->appointments = Appointment::count();


        /*
        |--------------------------------------------------------------------------
        | TOP DOCTORS BY VISITS
        |--------------------------------------------------------------------------
        */

        $this->topDoctors = Appointment::selectRaw(
                'doctor_id,
                COUNT(*) as total_visits'
            )
            ->with('doctor.user')
            ->groupBy('doctor_id')
            ->orderByDesc('total_visits')
            ->take(5)
            ->get()
            ->map(function ($item) {

                return [

                    'name' =>
                        $item->doctor?->name ?? '-',

                    'visits' =>
                        $item->total_visits,

                ];
            })
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | HOSPITAL REVENUE
        |--------------------------------------------------------------------------
        */

        $this->hospitalRevenue = Bill::with(
                'patientEnrollment.hospital'
            )
            ->get()
            ->groupBy(function ($bill) {

                return $bill->patientEnrollment?->hospital?->name
                    ?? 'Unknown Hospital';

            })
            ->map(function ($bills, $hospitalName) {

                return [

                    'hospital' =>
                        $hospitalName,

                    'revenue' =>
                        $bills->sum('total_amount'),

                ];

            })
            ->values()
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | RECENT APPOINTMENTS
        |--------------------------------------------------------------------------
        */

        $this->recentAppointments = Appointment::with([
                'patientEnrollment.user',
                'doctor.user.hospital',
            ])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($appointment) {

                return [

                    'patient' =>
                        $appointment->patientEnrollment?->user?->name ?? '-',

                    'doctor' =>
                        $appointment->doctor?->name ?? '-',

                    'hospital' =>
                        $appointment->doctor?->user?->hospital?->name ?? '-',

                    'date' =>
                        $appointment->scheduled_at
                            ? $appointment->scheduled_at
                                ->format('d M Y H:i')
                            : '-',

                    'status' =>
                        ucfirst($appointment->status),

                ];

            })
            ->toArray();
    }
}