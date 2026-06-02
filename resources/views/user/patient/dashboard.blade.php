@extends('layouts.patient')

@section('title', 'Patient Dashboard - MedVerse')
@section('page_title', 'Dashboard Overview')

@section('content')
<!-- Hero Welcome Banner -->
<div class="relative overflow-hidden bg-gradient-to-r from-teal-500 to-cyan-600 rounded-3xl p-6 md:p-8 text-white shadow-xl shadow-teal-500/10">
    <div class="relative z-10 space-y-2 md:max-w-xl">
        <span class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold uppercase tracking-wider">Welcome Back</span>
        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight">Hello, {{ optional(Auth::user())->name ?? 'Guest Patient' }}! 👋</h2>
        <p class="text-teal-50/90 text-sm md:text-base leading-relaxed">
            Welcome to your personalized MedVerse health portal. View your clinical logs, schedule hospital checkups, and manage medical bills in one secure hub.
        </p>
    </div>
    
    <!-- Premium Decorative SVG Icon -->
    <div class="absolute right-0 bottom-0 top-0 opacity-15 md:opacity-20 translate-x-10 translate-y-4 md:translate-x-0 flex items-center justify-center pointer-events-none">
        <svg class="w-64 h-64 md:w-80 md:h-80" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
        </svg>
    </div>
</div>

<!-- Metrics Cards Section (4 columns Grid) -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
    
    <!-- Appointments Card -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 border border-slate-200/60 dark:border-gray-800/60 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex items-center gap-4">
        <div class="p-3 bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-xl">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <div>
            <p class="text-xs text-slate-400 dark:text-gray-400 font-medium uppercase tracking-wider">Appointments</p>
            <h3 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">{{ $totalAppointments }}</h3>
        </div>
    </div>

    <!-- Medical Records Card -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 border border-slate-200/60 dark:border-gray-800/60 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex items-center gap-4">
        <div class="p-3 bg-purple-500/10 text-purple-600 dark:text-purple-400 rounded-xl">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
        <div>
            <p class="text-xs text-slate-400 dark:text-gray-400 font-medium uppercase tracking-wider">Med Records</p>
            <h3 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">{{ $medicalRecordsCount }}</h3>
        </div>
    </div>

    <!-- Unpaid Bills Card -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 border border-slate-200/60 dark:border-gray-800/60 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex items-center gap-4">
        <div class="p-3 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-xl">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div>
            <p class="text-xs text-slate-400 dark:text-gray-400 font-medium uppercase tracking-wider">Unpaid Bills</p>
            <h3 class="text-lg font-bold text-slate-800 dark:text-white mt-1 leading-none">
                @if ($unpaidBillsCount > 0)
                    Rp {{ number_format($totalUnpaidAmount, 0, ',', '.') }}
                @else
                    Lunas ✓
                @endif
            </h3>
            <span class="text-[10px] text-amber-600 dark:text-amber-400 font-semibold">{{ $unpaidBillsCount }} tagihan terutang</span>
        </div>
    </div>

    <!-- Active Prescriptions Card -->
    <div class="bg-white dark:bg-gray-900 rounded-2xl p-5 border border-slate-200/60 dark:border-gray-800/60 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex items-center gap-4">
        <div class="p-3 bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 rounded-xl">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
            </svg>
        </div>
        <div>
            <p class="text-xs text-slate-400 dark:text-gray-400 font-medium uppercase tracking-wider">Active Rx</p>
            <h3 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">{{ $activePrescriptionsCount }}</h3>
        </div>
    </div>

</div>

<!-- Content Grid (Upcoming Appointments & Quick Actions) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Upcoming Appointments Panel -->
    <div class="bg-white dark:bg-gray-900 rounded-3xl border border-slate-200/60 dark:border-gray-800/60 p-6 lg:col-span-2 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <div class="space-y-1">
                <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-teal-500 rounded-full"></span>
                    Upcoming Schedules
                </h3>
                <p class="text-xs text-slate-400 dark:text-gray-400">Your upcoming consultations</p>
            </div>
            <a href="{{ route('patient.appointments') }}" class="text-xs font-semibold text-teal-600 hover:text-teal-700 dark:text-teal-400 dark:hover:text-teal-300 transition-colors">View All &rarr;</a>
        </div>

        <div class="divide-y divide-slate-100 dark:divide-gray-800/60 overflow-hidden">
            @forelse ($upcomingAppointments as $apt)
                <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <div class="p-2.5 bg-teal-50 dark:bg-teal-950/20 text-teal-600 dark:text-teal-400 rounded-xl mt-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-slate-800 dark:text-white text-sm sm:text-base">{{ $apt->doctor->name }}</h4>
                            <p class="text-xs text-slate-400 dark:text-gray-400 mt-0.5">{{ $apt->doctor->specialization->name }} &bull; {{ $apt->patientEnrollment->hospital->name }}</p>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-xs text-slate-500 dark:text-gray-300 font-medium flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ $apt->scheduled_at->format('d M Y \a\t H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2.5 self-end sm:self-center">
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full uppercase tracking-wider {{ $apt->status === 'confirmed' ? 'bg-green-50 text-green-700 dark:bg-green-950/20 dark:text-green-400' : 'bg-blue-50 text-blue-700 dark:bg-blue-950/20 dark:text-blue-400' }}">
                            {{ $apt->status }}
                        </span>
                        
                        @if ($apt->status === 'scheduled')
                            <form action="{{ route('patient.appointments.cancel', $apt->id) }}" method="POST" class="m-0" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan jadwal konsultasi ini?')">
                                @csrf
                                <button type="submit" class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-xl transition-all cursor-pointer" title="Cancel Appointment">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-10 text-center space-y-3">
                    <div class="inline-flex p-4 bg-slate-50 dark:bg-gray-800/40 text-slate-400 dark:text-gray-500 rounded-full">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-500 dark:text-gray-400">No upcoming appointments found</p>
                    <a href="{{ route('patient.appointments') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-teal-500 text-white rounded-xl text-xs font-semibold shadow-md shadow-teal-500/10 hover:bg-teal-600 transition-colors">Book Now &rarr;</a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions Panel -->
    <div class="bg-white dark:bg-gray-900 rounded-3xl border border-slate-200/60 dark:border-gray-800/60 p-6 shadow-sm space-y-4">
        <div class="space-y-1">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                <span class="w-1.5 h-4 bg-teal-500 rounded-full"></span>
                Quick Access
            </h3>
            <p class="text-xs text-slate-400 dark:text-gray-400">Common medical patient actions</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-3">
            <!-- Book Appointment -->
            <a href="{{ route('patient.appointments') }}" class="group flex items-center gap-3 p-3.5 rounded-2xl bg-teal-50/50 hover:bg-teal-500 dark:bg-teal-950/10 dark:hover:bg-teal-500/10 border border-teal-100/50 dark:border-teal-900/30 transition-all duration-300">
                <span class="p-2.5 bg-teal-500 text-white group-hover:bg-white group-hover:text-teal-600 dark:group-hover:bg-teal-500 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
                <div>
                    <h4 class="font-bold text-slate-800 dark:text-white text-sm group-hover:text-teal-900 dark:group-hover:text-teal-300 transition-colors">Schedule Checkup</h4>
                    <p class="text-[11px] text-slate-400 dark:text-gray-400 group-hover:text-teal-800/80 dark:group-hover:text-teal-400/80 mt-0.5">Book consultations & check schedules</p>
                </div>
            </a>

            <!-- Medical History -->
            <a href="{{ route('patient.medical-records') }}" class="group flex items-center gap-3 p-3.5 rounded-2xl bg-blue-50/50 hover:bg-blue-500 dark:bg-blue-950/10 dark:hover:bg-blue-500/10 border border-blue-100/50 dark:border-blue-900/30 transition-all duration-300">
                <span class="p-2.5 bg-blue-500 text-white group-hover:bg-white group-hover:text-blue-600 dark:group-hover:bg-blue-500 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </span>
                <div>
                    <h4 class="font-bold text-slate-800 dark:text-white text-sm group-hover:text-blue-900 dark:group-hover:text-blue-300 transition-colors">Medical Records</h4>
                    <p class="text-[11px] text-slate-400 dark:text-gray-400 group-hover:text-blue-800/80 dark:group-hover:text-blue-400/80 mt-0.5">Review diagnostic & treatment logs</p>
                </div>
            </a>

            <!-- Manage Bills -->
            <a href="{{ route('patient.bills') }}" class="group flex items-center gap-3 p-3.5 rounded-2xl bg-amber-50/50 hover:bg-amber-500 dark:bg-amber-950/10 dark:hover:bg-amber-500/10 border border-amber-100/50 dark:border-amber-900/30 transition-all duration-300">
                <span class="p-2.5 bg-amber-500 text-white group-hover:bg-white group-hover:text-amber-600 dark:group-hover:bg-amber-500 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </span>
                <div>
                    <h4 class="font-bold text-slate-800 dark:text-white text-sm group-hover:text-amber-900 dark:group-hover:text-amber-300 transition-colors">Billing & Payments</h4>
                    <p class="text-[11px] text-slate-400 dark:text-gray-400 group-hover:text-amber-800/80 dark:group-hover:text-amber-400/80 mt-0.5">Settle bills securely with Midtrans</p>
                </div>
            </a>

            <!-- Check Prescriptions -->
            <a href="{{ route('patient.prescriptions') }}" class="group flex items-center gap-3 p-3.5 rounded-2xl bg-emerald-50/50 hover:bg-emerald-500 dark:bg-emerald-950/10 dark:hover:bg-emerald-500/10 border border-emerald-100/50 dark:border-emerald-900/30 transition-all duration-300">
                <span class="p-2.5 bg-emerald-500 text-white group-hover:bg-white group-hover:text-emerald-600 dark:group-hover:bg-emerald-500 rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                </span>
                <div>
                    <h4 class="font-bold text-slate-800 dark:text-white text-sm group-hover:text-emerald-900 dark:group-hover:text-emerald-300 transition-colors">Active Prescriptions</h4>
                    <p class="text-[11px] text-slate-400 dark:text-gray-400 group-hover:text-emerald-800/80 dark:group-hover:text-emerald-400/80 mt-0.5">Check dosages, drugs & rules</p>
                </div>
            </a>
        </div>
    </div>

</div>
@endsection
