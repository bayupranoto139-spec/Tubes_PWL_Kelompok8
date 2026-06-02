@extends('layouts.patient')

@section('title', 'My Appointments')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-5xl font-black text-slate-800">Appointments</h1>
            <p class="text-slate-500 text-lg mt-2">Manage and schedule your visits with our doctors.</p>
        </div>
        <button class="bg-blue-700 hover:bg-blue-800 text-white px-6 py-3.5 rounded-xl font-bold transition duration-300 shadow-md">
            + Book Appointment
        </button>
    </div>

    <div class="bg-white rounded-[30px] shadow-2xl p-8">
        <p class="text-slate-400 text-center py-10">You have no upcoming appointments scheduled at the moment.</p>
    </div>
</div>
@endsection