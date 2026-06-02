@extends('layouts.patient')

@section('title', 'Patient Dashboard - Home')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-10">
        <h1 class="text-5xl font-black text-slate-800">Welcome Back!</h1>
        <p class="text-slate-500 text-lg mt-2">Here is your health summary updates for today.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-7 rounded-[24px] shadow-xl border border-slate-50">
            <p class="text-slate-400 font-medium">Upcoming Appointment</p>
            <h2 class="text-3xl font-black text-blue-600 mt-3">02 June 2026</h2>
            <span class="text-xs font-bold text-slate-400">Dr. Andi Wijaya (Cardiology)</span>
        </div>
        <div class="bg-gradient-to-r from-blue-600 to-cyan-500 p-7 rounded-[24px] shadow-xl text-white">
            <p class="opacity-80 font-medium">Active Prescriptions</p>
            <h2 class="text-5xl font-black mt-2">3</h2>
            <span class="text-xs opacity-70">Medications to take today</span>
        </div>
        <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-7 rounded-[24px] shadow-xl text-white">
            <p class="opacity-80 font-medium">Unpaid Bills</p>
            <h2 class="text-4xl font-black mt-3">Rp 150.000</h2>
        </div>
    </div>

    <div class="bg-white rounded-[30px] shadow-2xl p-8 border border-slate-50">
        <h3 class="text-2xl font-bold text-slate-700 mb-6">Recent Activity Logs</h3>
        <div class="text-center py-12 text-slate-400">
            No recent major activity logs found. You are in good condition!
        </div>
    </div>
</div>
@endsection