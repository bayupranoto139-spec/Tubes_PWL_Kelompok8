@extends('layouts.patient')

@section('title', 'My Prescriptions')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-10">
        <h1 class="text-5xl font-black text-slate-800">Prescriptions</h1>
        <p class="text-slate-500 text-lg mt-2">Your current active medications prescribed by doctors.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-xl border border-slate-100 flex justify-between items-start">
            <div>
                <h3 class="text-xl font-bold text-slate-800">Amlodipine 5mg</h3>
                <p class="text-slate-400 text-xs mt-1">1x Daily after breakfast</p>
                <span class="inline-block mt-4 text-xs font-bold text-blue-600 bg-blue-50 px-3 py-1 rounded-full">Duration: 30 Days</span>
            </div>
            <div class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-md">Active</div>
        </div>
    </div>
</div>
@endsection