@extends('layouts.patient')

@section('title', 'My Bills & Payments - MedVerse')
@section('page_title', 'Billing & Invoices')

@section('content')
<!-- Spacing Details -->
<div class="space-y-6">
    <div>
        <p class="text-sm text-slate-400 dark:text-gray-400">Manage your medical statements, payments, and invoices securely via Midtrans</p>
    </div>

    <!-- Summary Metrics (3 columns) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        
        <!-- Outstanding Statement count -->
        <div class="bg-white dark:bg-gray-900 border border-slate-200/60 dark:border-gray-800/60 rounded-3xl p-6 shadow-sm flex items-center gap-4 hover:shadow-md transition-all duration-300">
            <div class="p-3 bg-amber-500/10 text-amber-600 dark:text-amber-400 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-400 dark:text-gray-400 font-semibold uppercase tracking-wider">Unpaid Bills</p>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white mt-0.5">{{ $unpaidCount }}</h3>
            </div>
        </div>

        <!-- Total Outstanding statement cost -->
        <div class="bg-white dark:bg-gray-900 border border-slate-200/60 dark:border-gray-800/60 rounded-3xl p-6 shadow-sm flex items-center gap-4 hover:shadow-md transition-all duration-300">
            <div class="p-3 bg-red-500/10 text-red-600 dark:text-red-400 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-400 dark:text-gray-400 font-semibold uppercase tracking-wider">Total Outstanding</p>
                <h3 class="text-xl font-black text-red-600 dark:text-red-400 mt-0.5">Rp {{ number_format($totalUnpaidAmount, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Paid statement count -->
        <div class="bg-white dark:bg-gray-900 border border-slate-200/60 dark:border-gray-800/60 rounded-3xl p-6 shadow-sm flex items-center gap-4 hover:shadow-md transition-all duration-300">
            <div class="p-3 bg-green-500/10 text-green-600 dark:text-green-400 rounded-2xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-slate-400 dark:text-gray-400 font-semibold uppercase tracking-wider">Paid Bills</p>
                <h3 class="text-2xl font-black text-slate-800 dark:text-white mt-0.5">{{ $paidCount }}</h3>
            </div>
        </div>

    </div>

    <!-- 1. Unpaid Invoices Listing -->
    <div class="space-y-4">
        <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
            <span class="w-1.5 h-4 bg-amber-500 rounded-full"></span>
            Outstanding Statement Tagihan
        </h3>

        @forelse ($unpaidBills as $bill)
            <div class="bg-white dark:bg-gray-900 border border-slate-200/60 dark:border-gray-800/60 rounded-3xl shadow-sm overflow-hidden hover:shadow-md transition-all duration-300">
                
                <!-- Invoice General Info -->
                <div class="p-6 flex flex-col md:flex-row md:items-center justify-between gap-6 border-b border-slate-100 dark:border-gray-800/60 bg-slate-50/20 dark:bg-gray-900/30">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-black text-slate-800 dark:text-white">INV-{{ sprintf('%06d', $bill->id) }}</span>
                            <span class="px-2 py-0.5 bg-amber-50 text-amber-700 dark:bg-amber-950/20 dark:text-amber-400 text-[10px] font-bold rounded uppercase">Unpaid</span>
                        </div>
                        <p class="text-xs text-slate-400 dark:text-gray-400">Statement Date: {{ $bill->created_at->format('d M Y') }} &bull; Hospital: {{ $bill->patientEnrollment->hospital->name }}</p>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center md:justify-end gap-4">
                        <div class="space-y-0.5 sm:text-right">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Amount Due</span>
                            <h4 class="text-lg font-black text-slate-800 dark:text-white">Rp {{ number_format($bill->total_amount, 0, ',', '.') }}</h4>
                        </div>
                        
                        <a href="{{ route('payment.create', $bill->id) }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 text-white rounded-2xl text-xs font-semibold shadow-md shadow-teal-500/10 hover:shadow-teal-500/25 hover:-translate-y-0.5 transition-all text-center cursor-pointer">
                            <!-- Pay Card Icon -->
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            Pay with Midtrans
                        </a>
                    </div>
                </div>

                <!-- Breakdown of Bill Items (Accordion styled) -->
                <div class="px-6 py-4 space-y-3">
                    <h5 class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest pl-1">Statements Breakdown</h5>
                    
                    <div class="divide-y divide-slate-100 dark:divide-gray-800 border border-slate-100 dark:border-gray-800/80 rounded-2xl overflow-hidden bg-slate-50/20 dark:bg-gray-900/10">
                        @foreach ($bill->billItems as $item)
                            <div class="px-4 py-3 flex items-center justify-between text-xs font-medium">
                                <div class="space-y-0.5 max-w-sm">
                                    <span class="text-slate-800 dark:text-white font-semibold">{{ $item->description }}</span>
                                    <span class="block text-[9px] font-bold uppercase tracking-wider {{ 
                                        $item->item_type === 'consultation' ? 'text-teal-600 dark:text-teal-400' : (
                                        $item->item_type === 'medication' ? 'text-emerald-600 dark:text-emerald-400' : (
                                        $item->item_type === 'procedure' ? 'text-blue-600 dark:text-blue-400' : 'text-slate-400')
                                        )
                                    }}">
                                        {{ $item->item_type }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <span class="block text-slate-800 dark:text-white font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    <span class="block text-[9px] text-slate-400 font-semibold">{{ $item->quantity }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex items-center justify-between text-xs font-semibold text-slate-500 dark:text-gray-400 px-1 pt-2">
                        <span>Due Date: <span class="text-red-500 font-bold">{{ $bill->payment_due_date->format('d M Y') }}</span></span>
                        <span>Total Items: {{ $bill->billItems->count() }}</span>
                    </div>
                </div>

            </div>
        @empty
            <div class="bg-white dark:bg-gray-900 rounded-3xl border border-slate-200/60 dark:border-gray-800/60 p-8 text-center space-y-3 shadow-sm">
                <div class="inline-flex p-3 bg-green-50 dark:bg-green-950/20 text-green-600 dark:text-green-400 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-slate-500 dark:text-gray-400">All bills are fully settled! No outstanding amounts.</p>
            </div>
        @endforelse
    </div>

    <!-- 2. Paid Statement History -->
    <div class="space-y-4 pt-2">
        <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2">
            <span class="w-1.5 h-4 bg-green-500 rounded-full"></span>
            Paid Invoices History
        </h3>

        @if ($paidBills->count() > 0)
            <!-- Invoice History Table -->
            <div class="bg-white dark:bg-gray-900 border border-slate-200/60 dark:border-gray-800/60 rounded-3xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse m-0">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-gray-800/40 border-b border-slate-100 dark:border-gray-800 text-[10px] font-extrabold text-slate-400 dark:text-gray-400 uppercase tracking-widest">
                                <th class="px-6 py-4">Invoice #</th>
                                <th class="px-6 py-4">Settle Date</th>
                                <th class="px-6 py-4">Hospital</th>
                                <th class="px-6 py-4">Payment Method</th>
                                <th class="px-6 py-4">Reference #</th>
                                <th class="px-6 py-4 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-gray-800/60 text-xs font-semibold text-slate-700 dark:text-gray-300">
                            @foreach ($paidBills as $paid)
                                <tr class="hover:bg-slate-50/30 dark:hover:bg-gray-800/20 transition-colors">
                                    <td class="px-6 py-4 text-slate-800 dark:text-white font-bold">INV-{{ sprintf('%06d', $paid->id) }}</td>
                                    <td class="px-6 py-4">{{ $paid->payment_date ? $paid->payment_date->format('d M Y \a\t H:i') : $paid->updated_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 max-w-xs truncate">{{ $paid->patientEnrollment->hospital->name }}</td>
                                    <td class="px-6 py-4 uppercase">
                                        <span class="px-2 py-0.5 bg-slate-100 dark:bg-gray-800 border border-slate-200/40 dark:border-gray-700/50 text-[10px] rounded text-slate-500 font-bold uppercase">{{ str_replace('_', ' ', $paid->payment_method ?? 'Cash') }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-mono text-[10px] text-slate-400 dark:text-gray-500 truncate max-w-[120px]" title="{{ $paid->reference_number }}">{{ $paid->reference_number ?? '-' }}</td>
                                    <td class="px-6 py-4 text-right text-green-600 dark:text-green-400 font-bold">Rp {{ number_format($paid->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-gray-900 border border-slate-200/60 dark:border-gray-800/60 rounded-3xl p-8 text-center space-y-2 shadow-sm">
                <p class="text-xs text-slate-400 dark:text-gray-500 italic">No paid transactions on record</p>
            </div>
        @endif
    </div>

</div>
@endsection
