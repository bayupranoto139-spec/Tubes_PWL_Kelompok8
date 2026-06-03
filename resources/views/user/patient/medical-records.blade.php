@extends('layouts.patient')

@section('title', 'My Medical Records - MedVerse')
@section('page_title', 'Medical History')

@section('content')
<div class="space-y-4">
    <div>
        <p class="text-sm text-slate-400 dark:text-gray-400">Review your past diagnoses, treatment programs, and physician comments</p>
    </div>

    <!-- Medical Record list -->
    <div class="space-y-4">
        @forelse ($records as $record)
            <!-- Single Medical Record item -->
            <div class="bg-white dark:bg-gray-900 border border-slate-200/60 dark:border-gray-800/60 rounded-3xl shadow-sm overflow-hidden hover:shadow-md transition-all duration-300">
                
                <!-- Card Header (Clickable for Expand/Collapse) -->
                <button onclick="toggleRecord('record-{{ $record->id }}', this)" class="w-full text-left p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-slate-50/50 dark:hover:bg-gray-800/20 transition-colors cursor-pointer outline-none select-none">
                    
                    <div class="flex items-start gap-3.5">
                        <!-- Chevron toggle icon -->
                        <span class="p-2 bg-slate-100 dark:bg-gray-800 text-slate-400 dark:text-gray-400 rounded-xl group mt-0.5 transform transition-transform duration-300 flex-shrink-0" id="chevron-record-{{ $record->id }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                        </span>
                        
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h3 class="text-base font-extrabold text-slate-800 dark:text-white leading-tight">{{ $record->diagnosis }}</h3>
                                
                                <span class="px-2.5 py-0.5 text-[10px] font-bold uppercase rounded-md tracking-wider {{ 
                                    $record->case_status === 'active' ? 'bg-amber-100 text-amber-700 dark:bg-amber-950/20 dark:text-amber-400' : 'bg-green-100 text-green-700 dark:bg-green-950/20 dark:text-green-400'
                                }}">
                                    {{ $record->case_status }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-gray-300 font-medium">Consultation with <span class="font-bold text-slate-700 dark:text-white">{{ $record->appointment->doctor->name }}</span> &bull; {{ $record->appointment->doctor->specialization->name }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 self-start md:self-center text-xs font-semibold text-slate-400 dark:text-gray-400">
                        <span class="flex items-center gap-1">
                            <!-- Location Icon -->
                            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $record->appointment->patientEnrollment->hospital->name }}
                        </span>
                        <span class="text-slate-300 dark:text-gray-700">&bull;</span>
                        <span class="flex items-center gap-1">
                            <!-- Calendar Icon -->
                            <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            {{ $record->visit_date->format('d M Y') }}
                        </span>
                    </div>

                </button>

                <!-- Expandable Details Content (Hidden by default) -->
                <div id="record-{{ $record->id }}" class="hidden border-t border-slate-100 dark:border-gray-800/60 bg-slate-50/30 dark:bg-gray-900/40 p-6 space-y-6">
                    
                    <!-- diagnosis and treatment section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Diagnosis Panel -->
                        <div class="space-y-2 p-5 bg-white dark:bg-gray-800/40 border border-slate-200/50 dark:border-gray-800/50 rounded-2xl shadow-sm">
                            <h4 class="text-xs font-bold text-slate-400 dark:text-gray-400 uppercase tracking-widest flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                Full Diagnosis
                            </h4>
                            <p class="text-sm font-semibold text-slate-800 dark:text-white leading-relaxed">{{ $record->diagnosis }}</p>
                            @if ($record->notes)
                                <div class="mt-4 pt-3 border-t border-slate-100 dark:border-gray-700/50 space-y-1">
                                    <h5 class="text-[10px] font-bold text-slate-400 dark:text-gray-400 uppercase tracking-wider">Doctor Notes</h5>
                                    <p class="text-xs text-slate-600 dark:text-gray-300 italic">"{{ $record->notes }}"</p>
                                </div>
                            @endif
                        </div>

                        <!-- Treatment Program -->
                        <div class="space-y-2 p-5 bg-white dark:bg-gray-800/40 border border-slate-200/50 dark:border-gray-800/50 rounded-2xl shadow-sm">
                            <h4 class="text-xs font-bold text-slate-400 dark:text-gray-400 uppercase tracking-widest flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                Treatment Plan
                            </h4>
                            <p class="text-sm text-slate-600 dark:text-gray-200 leading-relaxed">{{ $record->treatment_plan }}</p>
                        </div>

                    </div>

                    <!-- Associated Prescriptions (Nested RX List) -->
                    <div class="space-y-3">
                        <h4 class="text-xs font-bold text-slate-400 dark:text-gray-400 uppercase tracking-widest flex items-center gap-1.5 pl-1">
                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            Associated Prescriptions (Rx)
                        </h4>

                        @if ($record->prescriptions->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach ($record->prescriptions as $rx)
                                    <div class="p-4 bg-white dark:bg-gray-800 border border-slate-100 dark:border-gray-800 rounded-2xl shadow-sm flex items-start gap-3">
                                        <!-- Medicine pill icon -->
                                        <div class="p-2 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 rounded-xl">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                                        </div>
                                        <div class="space-y-1 flex-1">
                                            <div class="flex items-center justify-between gap-2">
                                                <h5 class="font-bold text-slate-800 dark:text-white text-sm">{{ $rx->medication->name }}</h5>
                                                <span class="text-[10px] text-slate-400 dark:text-gray-400 bg-slate-50 dark:bg-gray-900 border border-slate-100 dark:border-gray-800 px-2 py-0.5 rounded font-semibold">{{ $rx->medication->category }}</span>
                                            </div>
                                            @if($rx->medication->generic_name)
                                                <p class="text-[10px] text-slate-400 dark:text-gray-500 font-medium">Generic: {{ $rx->medication->generic_name }}</p>
                                            @endif
                                            
                                            <!-- Dosage details -->
                                            <div class="grid grid-cols-3 gap-2 mt-2 pt-2 border-t border-slate-50 dark:border-gray-700/30 text-[11px] font-semibold text-slate-600 dark:text-gray-300">
                                                <div>
                                                    <span class="block text-[9px] text-slate-400 uppercase">Dosage</span>
                                                    {{ $rx->dosage }}
                                                </div>
                                                <div>
                                                    <span class="block text-[9px] text-slate-400 uppercase">Duration</span>
                                                    {{ $rx->duration }}
                                                </div>
                                                <div>
                                                    <span class="block text-[9px] text-slate-400 uppercase">Quantity</span>
                                                    {{ $rx->quantity }} {{ $rx->medication->unit }}
                                                </div>
                                            </div>

                                            @if($rx->notes)
                                                <p class="text-[10px] text-slate-500 dark:text-gray-400 bg-slate-50 dark:bg-gray-800/80 p-2 rounded-lg mt-2 italic leading-relaxed">
                                                    * {{ $rx->notes }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-slate-400 italic pl-1">No medication was prescribed for this case</p>
                        @endif
                    </div>

                </div>

            </div>
        @empty
            <!-- Empty state -->
            <div class="bg-white dark:bg-gray-900 rounded-3xl border border-slate-200/60 dark:border-gray-800/60 p-12 text-center space-y-4 shadow-sm">
                <div class="inline-flex p-5 bg-teal-50 dark:bg-teal-950/20 text-teal-600 dark:text-teal-400 rounded-full">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="space-y-1 max-w-sm mx-auto">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">No medical history found</h3>
                    <p class="text-sm text-slate-400 dark:text-gray-400 leading-normal">You haven't completed any medical consultations yet. Consultations completed will register logs here automatically.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Expand & Collapse Medical Record Item
    function toggleRecord(id, headerBtn) {
        const content = document.getElementById(id);
        const chevron = document.getElementById('chevron-' + id);
        
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            chevron.classList.add('rotate-180');
        } else {
            content.classList.add('hidden');
            chevron.classList.remove('rotate-180');
        }
    }
</script>
@endsection
