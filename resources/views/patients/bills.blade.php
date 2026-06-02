@extends('layouts.patient')

@section('title', 'Invoices & Bills')
@section('page_title', 'Invoices & Bills')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-10">
        <h1 class="text-5xl font-black text-slate-800">Invoices & Bills</h1>
        <p class="text-slate-500 text-lg mt-2">Track your active medical payments and statement histories.</p>
    </div>

    <div class="bg-white rounded-[30px] shadow-2xl p-8">
        <div class="border-l-4 border-yellow-500 bg-yellow-50 p-4 rounded-xl text-yellow-800 font-semibold text-sm">
            You have 1 pending invoice that requires settlement.
        </div>
    </div>
</div>
@endsection