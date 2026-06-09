@extends('layouts.patient')

@section('title', 'My Hospitals - HealthMesh')

@section('content')
    {{-- HERO --}}
    <div class="relative overflow-hidden rounded-3xl p-8 text-white mb-8"
        style="background:linear-gradient(135deg,#14b8a6,#06b6d4);
               box-shadow:0 20px 40px rgba(20,184,166,.25);">

        <div class="relative z-10 max-w-2xl">
            <h2 class="text-4xl font-extrabold mb-3">
                My Hospitals
            </h2>

            <p class="text-white/90 leading-relaxed">
                Manage your hospital memberships. You can enroll in multiple hospitals
                using a single HealthMesh account and access appointments, prescriptions,
                billing, and medical records across facilities.
            </p>
        </div>

        <div class="absolute -right-10 -top-10 w-48 h-48 bg-white/10 rounded-full"></div>
        <div class="absolute right-20 bottom-0 w-32 h-32 bg-white/10 rounded-full"></div>
        <div class="absolute right-40 top-10 w-16 h-16 bg-white/20 rounded-full"></div>
    </div>

    {{-- STATS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

        <div
            class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 flex items-center gap-4">

            <div class="p-3 bg-teal-500/10 text-teal-600 rounded-xl">
                <i class="fas fa-hospital text-xl"></i>
            </div>

            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">
                    Enrolled Hospitals
                </p>
                <h3 class="text-3xl font-bold text-gray-800">
                    {{ $joinedHospitals->count() }}
                </h3>
            </div>
        </div>

        <div
            class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 flex items-center gap-4">

            <div class="p-3 bg-blue-500/10 text-blue-600 rounded-xl">
                <i class="fas fa-plus-circle text-xl"></i>
            </div>

            <div>
                <p class="text-xs text-gray-400 uppercase tracking-wider font-medium">
                    Available Hospitals
                </p>
                <h3 class="text-3xl font-bold text-gray-800">
                    {{ $availableHospitals->count() }}
                </h3>
            </div>
        </div>

    </div>

    {{-- CONTENT --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- ENROLLED --}}
        <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm">

            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-teal-500 rounded-full"></span>
                    Registered Hospitals
                </h3>

                <p class="text-xs text-gray-400 mt-1">
                    Hospitals currently linked to your account.
                </p>
            </div>

            <div class="space-y-4">

                @forelse($joinedHospitals as $hospital)

                    @php
                        $enrollment = auth()->user()
                            ->patientEnrollments
                            ->where('hospital_id', $hospital->id)
                            ->first();
                    @endphp

                    <div
                        class="border border-gray-200 rounded-2xl p-5 hover:border-teal-200 hover:bg-teal-50/30 transition-all">

                        <div class="flex justify-between items-start">

                            <div>
                                <h4 class="font-bold text-gray-800">
                                    {{ $hospital->name }}
                                </h4>

                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $hospital->city }}
                                </p>

                                <p class="text-xs text-gray-400 mt-2">
                                    {{ $hospital->address }}
                                </p>
                            </div>

                            <span
                                class="px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-semibold">
                                Active
                            </span>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-100">

                            <div class="flex items-center gap-2 text-sm">

                                <span class="font-semibold text-gray-700">
                                    Medical Record:
                                </span>

                                <span class="text-teal-600 font-bold">
                                    {{ $enrollment?->medical_record_number }}
                                </span>
                            </div>
                        </div>
                    </div>

                @empty

                    <div class="text-center py-12">

                        <div
                            class="w-16 h-16 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">

                            <i class="fas fa-hospital text-gray-400 text-xl"></i>

                        </div>

                        <p class="font-semibold text-gray-500">
                            No hospital enrollment found
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

        {{-- AVAILABLE --}}
        <div class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm">

            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <span class="w-1.5 h-4 bg-blue-500 rounded-full"></span>
                    Available Hospitals
                </h3>

                <p class="text-xs text-gray-400 mt-1">
                    Enroll yourself into additional hospitals.
                </p>
            </div>

            <div class="space-y-4">

                @forelse($availableHospitals as $hospital)

                    <div
                        class="border border-gray-200 rounded-2xl p-5 hover:border-blue-200 hover:bg-blue-50/30 transition-all">

                        <div class="flex justify-between gap-4">

                            <div>
                                <h4 class="font-bold text-gray-800">
                                    {{ $hospital->name }}
                                </h4>

                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $hospital->city }}
                                </p>

                                <p class="text-xs text-gray-400 mt-2">
                                    {{ $hospital->address }}
                                </p>
                            </div>

                            <form
                                action="{{ route('patient.hospitals.enroll') }}"
                                method="POST">

                                @csrf

                                <input
                                    type="hidden"
                                    name="hospital_id"
                                    value="{{ $hospital->id }}">

                                <button
                                    type="submit"
                                    class="px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-xl text-sm font-semibold transition-colors whitespace-nowrap">

                                    Enroll

                                </button>
                            </form>
                        </div>
                    </div>

                @empty

                    <div class="text-center py-12">

                        <div
                            class="w-16 h-16 mx-auto rounded-full bg-green-50 flex items-center justify-center mb-4">

                            <i class="fas fa-check text-green-500 text-xl"></i>

                        </div>

                        <p class="font-semibold text-gray-600">
                            You are already enrolled in all available hospitals
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>
@endsection