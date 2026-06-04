@extends('layouts.patient')

@section('title', 'Patient Dashboard - HealthMesh')
@section('page_title', 'Dashboard Overview')

@section('content')
    <!-- Hero Welcome Banner -->
    <div class="relative overflow-hidden rounded-3xl p-8 text-white shadow-2xl mb-8 text-white shadow-2xl" style="
         background:linear-gradient(135deg,#14b8a6,#06b6d4);
         box-shadow:0 20px 40px rgba(20,184,166,.25);
         ">
        <div class="relative z-10 max-w-2xl space-y-4">
            <h2 class="text-4xl md:text-5xl font-extrabold tracking-tight">Hello, {{ Auth::user()->name }}! 👋</h2>
            <p class="text-white/90 text-base leading-relaxed max-w-xl">
                Welcome to your personalized HealthMesh health portal. View your clinical logs, schedule hospital checkups,
                and manage medical bills in one secure hub.
            </p>
        </div>

        <!-- Premium Decorative SVG Icon -->
    </div>

    <!-- Metrics Cards Section (4 columns Grid) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8 mb-8">

        <!-- Appointments Card -->
        <div
            class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover:shadow-md hover:-trangray-y-1 transition-all duration-300 flex items-center gap-4">
            <div class="p-3 bg-blue-500/10 text-blue-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Appointments</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $totalAppointments }}</h3>
            </div>
        </div>

        <!-- Medical Records Card -->
        <div
            class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover:shadow-md hover:-trangray-y-1 transition-all duration-300 flex items-center gap-4">
            <div class="p-3 bg-purple-500/10 text-purple-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Med Records</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $medicalRecordsCount }}</h3>
            </div>
        </div>

        <!-- Unpaid Bills Card -->
        <div
            class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover:shadow-md hover:-trangray-y-1 transition-all duration-300 flex items-center gap-4">
            <div class="p-3 bg-amber-500/10 text-amber-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                    </path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Unpaid Bills</p>
                <h3 class="text-lg font-bold text-gray-800 mt-1 leading-none">
                    @if ($unpaidBillsCount > 0)
                        Rp {{ number_format($totalUnpaidAmount, 0, ',', '.') }}
                    @else
                        Lunas ✓
                    @endif
                </h3>
                <span class="text-[10px] text-amber-600 font-semibold">{{ $unpaidBillsCount }} tagihan terutang</span>
            </div>
        </div>

        <!-- Active Prescriptions Card -->
        <div
            class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover:shadow-md hover:-trangray-y-1 transition-all duration-300 flex items-center gap-4">
            <div class="p-3 bg-emerald-500/10 text-emerald-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                    </path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Active Rx</p>
                <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $activePrescriptionsCount }}</h3>
            </div>
        </div>

    </div>

    <!-- Content Grid (Upcoming Appointments & Quick Actions) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Upcoming Appointments Panel -->
        <div class="bg-white rounded-3xl border border-gray-200 p-6 lg:col-span-2 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <span class="w-1.5 h-4 bg-teal-500 rounded-full"></span>
                        Upcoming Schedules
                    </h3>
                    <p class="text-xs text-gray-400">Your upcoming consultations</p>
                </div>
                <a href="{{ route('patient.appointments') }}"
                    class="text-xs font-semibold text-teal-600 hover:text-teal-700 transition-colors">View All &rarr;</a>
            </div>

            <div class="divide-y divide-gray-100 overflow-hidden">
                @forelse ($upcomingAppointments as $apt)
                    <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="flex items-start gap-3">
                            <div class="p-2.5 bg-teal-50 text-teal-600 rounded-xl mt-0.5">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 text-sm sm:text-base">{{ $apt->doctor->name }}</h4>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $apt->doctor->specialization->name }} &bull;
                                    {{ $apt->patientEnrollment->hospital->name }}</p>
                                <div class="flex items-center gap-3 mt-2">
                                    <span class="text-xs text-gray-500 font-medium flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $apt->scheduled_at->format('d M Y \a\t H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-2.5 self-end sm:self-center">
                            <span
                                class="px-2.5 py-1 text-xs font-semibold rounded-full uppercase tracking-wider {{ $apt->status === 'confirmed' ? 'bg-green-50 text-green-700' : 'bg-blue-50 text-blue-700' }}">
                                {{ $apt->status }}
                            </span>

                            @if ($apt->status === 'scheduled')
                                <form action="{{ route('patient.appointments.cancel', $apt->id) }}" method="POST" class="m-0"
                                    onsubmit="return confirm('Apakah Anda yakin ingin membatalkan jadwal konsultasi ini?')">
                                    @csrf
                                    <button type="submit"
                                        class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all cursor-pointer"
                                        title="Cancel Appointment">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center space-y-3">
                        <div class="inline-flex p-4 bg-gray-50 text-gray-400 rounded-full">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <p class="text-sm font-semibold text-gray-500">No upcoming appointments found</p>
                        <a href="{{ route('patient.appointments') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-teal-500 text-white rounded-xl text-xs font-semibold shadow-md shadow-teal-500/10 hover:bg-teal-600 transition-colors">Book
                            Now &rarr;</a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions Panel -->
        <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm space-y-4">
            <div class="space-y-1">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-teal-500 rounded-full"></span>
                    Quick Access
                </h3>
                <p class="text-xs text-gray-400">Common medical patient actions</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-3">
                <!-- Book Appointment -->
                <a href="{{ route('patient.appointments') }}"
                    class="group flex items-center gap-3 p-3.5 rounded-2xl bg-teal-50/50 hover:bg-teal-500 border border-teal-100/50 transition-all duration-300">
                    <span
                        class="p-2.5 bg-teal-500 text-white group-hover:bg-white group-hover:text-teal-600 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </span>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm group-hover:text-teal-900 transition-colors">Schedule
                            Checkup</h4>
                        <p class="text-[11px] text-gray-400 group-hover:text-teal-800/80 mt-0.5">Book consultations & check
                            schedules</p>
                    </div>
                </a>

                <!-- Medical History -->
                <a href="{{ route('patient.medical-records') }}"
                    class="group flex items-center gap-3 p-3.5 rounded-2xl bg-blue-50/50 hover:bg-blue-500 border border-blue-100/50 transition-all duration-300">
                    <span
                        class="p-2.5 bg-blue-500 text-white group-hover:bg-white group-hover:text-blue-600 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </span>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm group-hover:text-blue-900 transition-colors">Medical
                            Records</h4>
                        <p class="text-[11px] text-gray-400 group-hover:text-blue-800/80 mt-0.5">Review diagnostic &
                            treatment logs</p>
                    </div>
                </a>

                <!-- Manage Bills -->
                <a href="{{ route('patient.bills') }}"
                    class="group flex items-center gap-3 p-3.5 rounded-2xl bg-amber-50/50 hover:bg-amber-500 border border-amber-100/50 transition-all duration-300">
                    <span
                        class="p-2.5 bg-amber-500 text-white group-hover:bg-white group-hover:text-amber-600 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </span>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm group-hover:text-amber-900 transition-colors">Billing &
                            Payments</h4>
                        <p class="text-[11px] text-gray-400 group-hover:text-amber-800/80 mt-0.5">Settle bills securely with
                            Midtrans</p>
                    </div>
                </a>

                <!-- Check Prescriptions -->
                <a href="{{ route('patient.prescriptions') }}"
                    class="group flex items-center gap-3 p-3.5 rounded-2xl bg-emerald-50/50 hover:bg-emerald-500 border border-emerald-100/50 transition-all duration-300">
                    <span
                        class="p-2.5 bg-emerald-500 text-white group-hover:bg-white group-hover:text-emerald-600 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                            </path>
                        </svg>
                    </span>
                    <div>
                        <h4 class="font-bold text-gray-800 text-sm group-hover:text-emerald-900 transition-colors">Active
                            Prescriptions</h4>
                        <p class="text-[11px] text-gray-400 group-hover:text-emerald-800/80 mt-0.5">Check dosages, drugs &
                            rules</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/10 rounded-full"></div>

        <div class="absolute right-20 bottom-0 w-32 h-32 bg-white/10 rounded-full"></div>

        <div class="absolute right-40 top-10 w-16 h-16 bg-white/20 rounded-full"></div>
    </div>
@endsection