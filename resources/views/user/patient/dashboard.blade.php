@extends('layouts.patient')

@section('title', 'Patient Dashboard - HealthMesh')
@section('page_title', 'Dashboard Overview')

@section('content')

    {{-- Hero Banner --}}
    <div class="relative overflow-hidden rounded-2xl p-4 sm:p-6 md:p-8 text-white mb-4 sm:mb-6"
        style="background:linear-gradient(135deg,#14b8a6,#06b6d4)">
        <div class="relative z-10">
            <h2 class="text-xl sm:text-3xl md:text-4xl font-extrabold tracking-tight leading-tight">
                Hello, {{ Auth::user()->name }}! 👋
            </h2>
            <p class="text-white/80 text-xs sm:text-sm mt-1 sm:mt-2 max-w-lg leading-relaxed">
                Welcome to your HealthMesh health portal. View clinical logs, schedule checkups, and manage bills.
            </p>
        </div>
        {{-- Decorative circles --}}
        <div class="absolute -right-6 -top-6 w-32 h-32 bg-white/10 rounded-full pointer-events-none"></div>
        <div class="absolute right-12 -bottom-4 w-20 h-20 bg-white/10 rounded-full pointer-events-none"></div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4 mb-4 sm:mb-6">

        <div class="bg-white rounded-2xl p-3 sm:p-4 border border-gray-200 shadow-sm flex items-center gap-2 sm:gap-3">
            <div class="p-2 bg-blue-50 text-blue-600 rounded-xl shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] sm:text-xs text-gray-400 font-medium uppercase tracking-wide leading-tight">Appointments</p>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-800">{{ $totalAppointments }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-3 sm:p-4 border border-gray-200 shadow-sm flex items-center gap-2 sm:gap-3">
            <div class="p-2 bg-purple-50 text-purple-600 rounded-xl shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] sm:text-xs text-gray-400 font-medium uppercase tracking-wide leading-tight">Records</p>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-800">{{ $medicalRecordsCount }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-3 sm:p-4 border border-gray-200 shadow-sm flex items-center gap-2 sm:gap-3">
            <div class="p-2 bg-amber-50 text-amber-600 rounded-xl shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] sm:text-xs text-gray-400 font-medium uppercase tracking-wide leading-tight">Unpaid Bills</p>
                <h3 class="text-sm sm:text-base font-bold text-gray-800 leading-tight">
                    @if ($unpaidBillsCount > 0)
                        <span class="text-amber-600">{{ $unpaidBillsCount }} tagihan</span>
                    @else
                        <span class="text-green-600">Lunas ✓</span>
                    @endif
                </h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-3 sm:p-4 border border-gray-200 shadow-sm flex items-center gap-2 sm:gap-3">
            <div class="p-2 bg-emerald-50 text-emerald-600 rounded-xl shrink-0">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] sm:text-xs text-gray-400 font-medium uppercase tracking-wide leading-tight">Active Rx</p>
                <h3 class="text-xl sm:text-2xl font-bold text-gray-800">{{ $activePrescriptionsCount }}</h3>
            </div>
        </div>

    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

        {{-- Upcoming Appointments --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-5 lg:col-span-2 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm sm:text-base font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-teal-500 rounded-full shrink-0"></span>
                    Upcoming Schedules
                </h3>
                <a href="{{ route('patient.appointments') }}"
                    class="text-xs font-semibold text-teal-600 hover:text-teal-700 shrink-0">View All →</a>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse ($upcomingAppointments as $apt)
                    <div class="py-3 first:pt-0 last:pb-0">
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-start gap-2.5 min-w-0">
                                <div class="p-2 bg-teal-50 text-teal-600 rounded-xl shrink-0 mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-800 text-sm truncate">{{ $apt->doctor->name }}</p>
                                    <p class="text-[11px] text-gray-400 truncate">
                                        {{ $apt->doctor->specialization->name }} · {{ $apt->patientEnrollment->hospital->name }}
                                    </p>
                                    <p class="text-[11px] text-gray-500 mt-0.5">
                                        {{ $apt->scheduled_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full uppercase
                                    {{ $apt->status === 'confirmed' ? 'bg-green-50 text-green-700' : 'bg-blue-50 text-blue-700' }}">
                                    {{ $apt->status }}
                                </span>
                                @if ($apt->status === 'scheduled')
                                    <form action="{{ route('patient.appointments.cancel', $apt->id) }}" method="POST" class="m-0">
                                        @csrf
                                        <button type="submit"
                                            class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center">
                        <div class="inline-flex p-3 bg-gray-50 text-gray-300 rounded-full mb-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-sm text-gray-400">No upcoming appointments</p>
                        <a href="{{ route('patient.appointments') }}"
                            class="inline-flex items-center gap-1.5 mt-2 px-4 py-2 bg-teal-500 text-white rounded-xl text-xs font-semibold hover:bg-teal-600 transition-colors">
                            Book Now →
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-4 sm:p-5 shadow-sm">
            <h3 class="text-sm sm:text-base font-bold text-gray-800 flex items-center gap-2 mb-3">
                <span class="w-1.5 h-4 bg-teal-500 rounded-full shrink-0"></span>
                Quick Access
            </h3>

            <div class="grid grid-cols-2 lg:grid-cols-1 gap-2">
                @foreach([
                    ['route' => 'patient.appointments',   'color' => 'teal',    'icon' => 'M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Schedule Checkup',      'sub' => 'Book consultations'],
                    ['route' => 'patient.medical-records','color' => 'blue',    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'title' => 'Medical Records',       'sub' => 'View diagnoses'],
                    ['route' => 'patient.bills',          'color' => 'amber',   'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'title' => 'Bills & Payments',     'sub' => 'Pay via Midtrans'],
                    ['route' => 'patient.prescriptions',  'color' => 'emerald', 'icon' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z', 'title' => 'Prescriptions',         'sub' => 'Active medications'],
                ] as $action)
                    <a href="{{ route($action['route']) }}"
                        class="group flex items-center gap-2.5 p-3 rounded-xl bg-{{ $action['color'] }}-50/60 hover:bg-{{ $action['color'] }}-500 border border-{{ $action['color'] }}-100 transition-all duration-200">
                        <span class="p-2 bg-{{ $action['color'] }}-500 text-white group-hover:bg-white group-hover:text-{{ $action['color'] }}-600 rounded-lg transition-colors shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $action['icon'] }}"/>
                            </svg>
                        </span>
                        <div class="min-w-0">
                            <p class="font-bold text-gray-800 text-xs sm:text-sm group-hover:text-white leading-tight truncate">{{ $action['title'] }}</p>
                            <p class="text-[10px] text-gray-400 group-hover:text-white/80 hidden sm:block">{{ $action['sub'] }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

    </div>

@endsection