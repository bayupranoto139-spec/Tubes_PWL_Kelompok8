<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Doctor;
use App\Models\Hospital;
use App\Models\Queue;
use App\Models\User;

class GuestDashboardController extends Controller
{
    public function index()
    {
        // Jika sudah login, langsung ke panel admin Filament
        if (auth()->check()) {
            return redirect('/admin');
        }

        $stats = [
            'total_hospitals' => Hospital::count(),
            'total_users'     => User::count(),
            'total_doctors'   => Doctor::count(),
            'total_patients'  => User::where('role', 'pasien')->count(),
        ];

        $miniStats = [
            'today_visits'     => Queue::whereDate('created_at', today())->count(),
            'monthly_revenue'  => Bill::whereMonth('created_at', now()->month)->sum('total_amount'),
            'active_queues'    => Queue::count(),
            'pending_payments' => Bill::whereIn('status', ['pending', 'unpaid'])->count(),
        ];

        // Revenue chart — sama persis RevenueChart widget di Filament
        $bills = Bill::with('patientEnrollment.hospital')->get()
            ->groupBy(fn($b) => $b->patientEnrollment?->hospital?->name ?? 'Unknown');
        $revenueLabels = $bills->keys()->toArray();
        $revenueData   = $bills->map(fn($items) => $items->sum('total_amount'))->values()->toArray();

        // Visits chart — sama persis VisitsChart widget
        $visitsLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        $visitsData   = [50, 80, 120, 150, 200, 250];

        return view('guest.dashboard', compact(
            'stats', 'miniStats',
            'revenueLabels', 'revenueData',
            'visitsLabels', 'visitsData',
        ));
    }
}
