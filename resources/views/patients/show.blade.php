@extends('layouts.app')

@section('title', 'Patient Details')

@section('content')
    <div class="space-y-8">
        <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="text-5xl font-black text-slate-800">Patient Details</h1>
                <p class="mt-3 text-slate-500 text-lg">Review the full patient profile and contact details.</p>
            </div>

            <a href="{{ route('patients.edit', $patient->id) }}" class="inline-flex items-center rounded-3xl bg-blue-700 px-6 py-3 text-white font-semibold shadow-lg transition hover:bg-blue-800">
                Edit Patient
            </a>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-[30px] bg-white p-8 shadow-2xl">
                <h2 class="text-2xl font-bold text-slate-800 mb-6">Personal Information</h2>
                <div class="space-y-4 text-slate-600">
                    <div><span class="font-semibold text-slate-800">Name:</span> {{ $patient->user->name ?? '-' }}</div>
                    <div><span class="font-semibold text-slate-800">Email:</span> {{ $patient->user->email ?? '-' }}</div>
                    <div><span class="font-semibold text-slate-800">Phone:</span> {{ $patient->user->phone ?? '-' }}</div>
                    <div><span class="font-semibold text-slate-800">Gender:</span> {{ $patient->user->gender == 'L' ? 'Laki-laki' : ($patient->user->gender == 'P' ? 'Perempuan' : '-') }}</div>
                    <div><span class="font-semibold text-slate-800">Medical Record:</span> {{ $patient->medical_record_number }}</div>
                </div>
            </div>

            <div class="rounded-[30px] bg-white p-8 shadow-2xl">
                <h2 class="text-2xl font-bold text-slate-800 mb-6">Insurance & Emergency</h2>
                <div class="space-y-4 text-slate-600">
                    <div><span class="font-semibold text-slate-800">Blood Type:</span> {{ $patient->blood_type ?? '-' }}</div>
                    <div><span class="font-semibold text-slate-800">Insurance:</span> {{ $patient->insurance_provider ?? '-' }}</div>
                    <div><span class="font-semibold text-slate-800">Policy #:</span> {{ $patient->insurance_policy_number ?? '-' }}</div>
                    <div><span class="font-semibold text-slate-800">Emergency Contact:</span> {{ $patient->emergency_contact_name ?? '-' }}</div>
                    <div><span class="font-semibold text-slate-800">Emergency Phone:</span> {{ $patient->emergency_contact_phone ?? '-' }}</div>
                </div>
            </div>
        </div>
    </div>
@endsection
