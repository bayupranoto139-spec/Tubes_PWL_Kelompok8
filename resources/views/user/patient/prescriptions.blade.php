@extends('layouts.patient')

@section('title', 'My Prescriptions - MedVerse')
@section('page_title', 'Prescriptions & Rx')

@section('content')
<div class="space-y-6">
    <div>
        <p class="text-sm text-slate-400 dark:text-gray-400">Track your current active medications, instructions, and historical clinical prescriptions</p>
    </div>

    <!-- Summary Cards (3 Columns) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        
        <!-- Active Prescription card -->
        <div class="bg-white dark:bg-gray-900 border border-slate-200/60 dark:border-gray-800/60 rounded-3xl p-6 shadow-sm flex items-center gap-4 hover:shadow-md transition-all duration-300">
            <div class="p-3 bg-green-500/10 text-green-600 dark:text-green-400 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-400 dark:text-gray-400 font-semibold uppercase tracking-wider">Active Prescriptions</p>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white mt-0.5">{{ $activeCount }}</h3>
            </div>
        </div>

        <!-- Completed Prescription card -->
        <div class="bg-white dark:bg-gray-900 border border-slate-200/60 dark:border-gray-800/60 rounded-3xl p-6 shadow-sm flex items-center gap-4 hover:shadow-md transition-all duration-300">
            <div class="p-3 bg-slate-500/10 text-slate-500 dark:text-slate-400 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-400 dark:text-gray-400 font-semibold uppercase tracking-wider">Completed Prescriptions</p>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white mt-0.5">{{ $completedCount }}</h3>
            </div>
        </div>

        <!-- Cancelled Prescription card -->
        <div class="bg-white dark:bg-gray-900 border border-slate-200/60 dark:border-gray-800/60 rounded-3xl p-6 shadow-sm flex items-center gap-4 hover:shadow-md transition-all duration-300">
            <div class="p-3 bg-red-500/10 text-red-500 dark:text-red-400 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-400 dark:text-gray-400 font-semibold uppercase tracking-wider">Cancelled Rx</p>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white mt-0.5">{{ $cancelledCount }}</h3>
            </div>
        </div>

    </div>

    <!-- Prescriptions Grid (2 Columns) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse ($prescriptions as $p)
            <!-- Single Prescription card -->
            <div class="bg-white dark:bg-gray-900 border border-slate-200/60 dark:border-gray-800/60 rounded-3xl p-6 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 flex flex-col justify-between gap-6">
                
                <div class="space-y-4">
                    <!-- Prescription Header -->
                    <div class="flex items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="p-2.5 bg-teal-50 dark:bg-teal-950/20 text-teal-600 dark:text-teal-400 rounded-2xl">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                            </span>
                            <div>
                                <h4 class="font-extrabold text-slate-800 dark:text-white text-base leading-snug">{{ $p->medication->name }}</h4>
                                <span class="text-[9px] text-slate-400 dark:text-gray-500 font-bold uppercase tracking-wider bg-slate-50 dark:bg-gray-800 border border-slate-100 dark:border-gray-700/60 px-2 py-0.5 rounded">{{ $p->medication->category ?? 'Generic' }}</span>
                            </div>
                        </div>
                        
                        <span class="px-2.5 py-0.5 text-[10px] font-extrabold uppercase rounded tracking-wider {{ 
                            $p->medicalRecord->case_status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-950/20 dark:text-green-400' : 'bg-slate-100 text-slate-500 dark:bg-slate-950/20 dark:text-slate-400'
                        }}">
                            {{ $p->medicalRecord->case_status === 'active' ? 'Active' : 'Completed' }}
                        </span>
                    </div>

                    <!-- Drug Generic Name & Price sub-details -->
                    <div class="text-xs text-slate-400 dark:text-gray-400 font-semibold space-y-1 pl-1">
                        @if($p->medication->generic_name)
                            <p>Generic Name: <span class="text-slate-700 dark:text-gray-300">{{ $p->medication->generic_name }}</span></p>
                        @endif
                        <p>Cost per Unit: <span class="text-slate-700 dark:text-gray-300">Rp {{ number_format($p->medication->price, 0, ',', '.') }}</span></p>
                    </div>

                    <!-- Dosage Information -->
                    <div class="grid grid-cols-3 gap-3 p-4 bg-slate-50/50 dark:bg-gray-800/30 rounded-2xl text-xs font-bold text-slate-700 dark:text-gray-200 border border-slate-50 dark:border-gray-800/80">
                        <div class="space-y-0.5">
                            <span class="block text-[9px] text-slate-400 font-semibold uppercase tracking-wider">Dosage (Aturan)</span>
                            <span class="text-sm font-black text-teal-600 dark:text-teal-400">{{ $p->dosage }}</span>
                        </div>
                        <div class="space-y-0.5">
                            <span class="block text-[9px] text-slate-400 font-semibold uppercase tracking-wider">Duration</span>
                            <span class="text-sm font-black text-slate-800 dark:text-white">{{ $p->duration }}</span>
                        </div>
                        <div class="space-y-0.5">
                            <span class="block text-[9px] text-slate-400 font-semibold uppercase tracking-wider">Quantity</span>
                            <span class="text-sm font-black text-slate-800 dark:text-white">{{ $p->quantity }} {{ $p->medication->unit }}</span>
                        </div>
                    </div>

                    <!-- Instruction Box -->
                    @if ($p->notes)
                        <div class="p-3 bg-amber-500/5 dark:bg-amber-500/10 border border-amber-500/10 rounded-2xl text-[11px] text-amber-800 dark:text-amber-300 leading-relaxed font-semibold italic flex gap-2">
                            <svg class="w-4 h-4 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span>Instructions: "{{ $p->notes }}"</span>
                        </div>
                    @endif
                </div>

                <!-- Footer: Prescribed By details -->
                <div class="pt-4 border-t border-slate-100 dark:border-gray-800/60 flex items-center justify-between text-xs text-slate-400 dark:text-gray-400 pl-1 font-semibold">
                    <div class="space-y-0.5">
                        <span class="block text-[9px] text-slate-400 uppercase tracking-widest font-bold">Prescribed By</span>
                        <span>{{ $p->medicalRecord->appointment->doctor->name }}</span>
                    </div>
                    
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">{{ $p->created_at->format('d M Y') }}</span>
                </div>

            </div>
        @empty
            <!-- Empty state -->
            <div class="bg-white dark:bg-gray-900 border border-slate-200/60 dark:border-gray-800/60 rounded-3xl p-12 text-center space-y-4 shadow-sm col-span-2">
                <div class="inline-flex p-5 bg-teal-50 dark:bg-teal-950/20 text-teal-600 dark:text-teal-400 rounded-full">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>
                <div class="space-y-1 max-w-sm mx-auto">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">No active prescriptions</h3>
                    <p class="text-sm text-slate-400 dark:text-gray-400 leading-normal">Your drug recipes and pharmaceutical orders are blank. Any items prescribed during checks will register automatically.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection
