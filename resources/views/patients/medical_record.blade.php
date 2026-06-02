@extends('layouts.patient')

@section('title', 'My Medical Records')
@section('page_title', 'Medical Records')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-10">
        <h1 class="text-5xl font-black text-slate-800">Medical Records</h1>
        <p class="text-slate-500 text-lg mt-2">Your historical health checks and laboratory results.</p>
    </div>

    <div class="bg-white rounded-[30px] shadow-2xl overflow-hidden">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white text-left">
                <tr>
                    <th class="px-8 py-4">Date</th>
                    <th class="px-8 py-4">Diagnosis</th>
                    <th class="px-8 py-4">Doctor</th>
                    <th class="px-8 py-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-slate-600 font-medium">
                <tr>
                    <td class="px-8 py-5 text-slate-800 font-bold">25 May 2026</td>
                    <td class="px-8 py-5">Mild Hypertension</td>
                    <td class="px-8 py-5">Dr. Andi Wijaya</td>
                    <td class="px-8 py-5 text-center">
                        <button class="text-xs bg-blue-50 text-blue-600 px-4 py-2 rounded-lg font-bold hover:bg-blue-100">View Details</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection