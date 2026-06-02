@extends('layouts.app')

@section('title', 'Patient Management')

@section('content')
    <div class="space-y-8">
        <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
            <div>
                <h1 class="text-5xl font-black text-slate-800">Patient Management</h1>
                <p class="mt-3 text-slate-500 text-lg">View all patient records and manage their profiles.</p>
            </div>

            <a href="{{ route('patients.create') }}" class="inline-flex items-center rounded-3xl bg-blue-700 px-6 py-3 text-white font-semibold shadow-lg transition hover:bg-blue-800">
                + Add Patient
            </a>
        </div>

        @if(session('success'))
            <div class="rounded-3xl bg-emerald-50 border border-emerald-200 p-5 text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-7 rounded-3xl shadow-xl">
                <p class="text-slate-400">Total Patients</p>
                <h2 class="text-5xl font-black text-blue-600 mt-3">{{ $totalPatients }}</h2>
            </div>

            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 p-7 rounded-3xl shadow-xl text-white">
                <p class="opacity-80">Hospital Status</p>
                <h2 class="text-4xl font-black mt-3">Active</h2>
            </div>

            <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-7 rounded-3xl shadow-xl text-white">
                <p class="opacity-80">System</p>
                <h2 class="text-4xl font-black mt-3">Online</h2>
            </div>
        </div>

        <div class="bg-white rounded-[30px] shadow-2xl overflow-hidden">
            <div class="p-7 border-b">
                <h2 class="text-3xl font-bold text-slate-700">Patient Records</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[720px] text-sm text-slate-600">
                    <thead class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white">
                        <tr>
                            <th class="px-8 py-5 text-left">ID</th>
                            <th class="px-8 py-5 text-left">Patient Name</th>
                            <th class="px-8 py-5 text-left">Gender</th>
                            <th class="px-8 py-5 text-left">Phone</th>
                            <th class="px-8 py-5 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($patients as $patient)
                            <tr class="hover:bg-blue-50 transition duration-200">
                                <td class="px-8 py-6 font-bold text-slate-700">#{{ $patient->id }}</td>
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-full bg-gradient-to-r from-blue-500 to-cyan-500 flex items-center justify-center text-white font-black text-xl shadow-lg">
                                            {{ strtoupper(substr($patient->user->name ?? 'P', 0, 1)) }}
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-lg text-slate-800">{{ $patient->user->name ?? '-' }}</h3>
                                            <p class="text-slate-400 text-sm">Medical record: {{ $patient->medical_record_number }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-bold">{{ $patient->user->gender ?? '-' }}</span>
                                </td>
                                <td class="px-8 py-6 text-slate-600 font-medium">{{ $patient->user->phone ?? '-' }}</td>
                                <td class="px-8 py-6 text-center">
                                    <div class="flex flex-wrap justify-center gap-2">
                                        <a href="{{ route('patients.show', $patient->id) }}" class="border border-slate-200 text-slate-700 hover:bg-slate-50 px-4 py-2 rounded-xl transition">View</a>
                                        <a href="{{ route('patients.edit', $patient->id) }}" class="border border-blue-200 text-blue-600 hover:bg-blue-50 px-4 py-2 rounded-xl transition">Edit</a>
                                        <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Delete patient?')" class="border border-red-200 text-red-600 hover:bg-red-50 px-4 py-2 rounded-xl transition">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center text-slate-400 text-xl">No patient data found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
