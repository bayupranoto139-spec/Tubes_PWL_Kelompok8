@extends('layouts.patient')

@section('title', 'My Bills & Payments - HealthMesh')
@section('page_title', 'Billing & Invoices')

@section('content')
<div class="space-y-4">

    <p class="text-sm text-gray-400">Manage your medical statements, payments, and invoices securely via Midtrans</p>

    {{-- Summary Metrics --}}
    <div class="grid grid-cols-3 gap-2 sm:gap-4">

        <div class="bg-white border border-gray-200 rounded-2xl p-3 sm:p-5 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
            <div class="p-2 sm:p-3 bg-amber-500/10 text-amber-600 rounded-xl shrink-0">
                <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-[9px] sm:text-xs text-gray-400 font-semibold uppercase tracking-wider">Unpaid</p>
                <h3 class="text-xl sm:text-2xl font-black text-gray-800">{{ $unpaidCount }}</h3>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-3 sm:p-5 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
            <div class="p-2 sm:p-3 bg-red-500/10 text-red-600 rounded-xl shrink-0">
                <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-[9px] sm:text-xs text-gray-400 font-semibold uppercase tracking-wider">Outstanding</p>
                <h3 class="text-sm sm:text-xl font-black text-red-600">Rp {{ number_format($totalUnpaidAmount, 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-3 sm:p-5 shadow-sm flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
            <div class="p-2 sm:p-3 bg-green-500/10 text-green-600 rounded-xl shrink-0">
                <svg class="w-4 h-4 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-[9px] sm:text-xs text-gray-400 font-semibold uppercase tracking-wider">Paid</p>
                <h3 class="text-xl sm:text-2xl font-black text-gray-800">{{ $paidCount }}</h3>
            </div>
        </div>
    </div>

    {{-- Unpaid Bills --}}
    <div class="space-y-3">
        <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
            <span class="w-1.5 h-4 bg-amber-500 rounded-full"></span>
            Outstanding Statement Tagihan
        </h3>

        @forelse ($unpaidBills as $bill)
            <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

                {{-- Invoice header --}}
                <div class="p-3 sm:p-5 border-b border-gray-100 bg-gray-50/40">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="text-sm font-black text-gray-800">INV-{{ sprintf('%06d', $bill->id) }}</span>
                                <span class="px-2 py-0.5 bg-amber-50 text-amber-700 text-[10px] font-bold rounded uppercase">Unpaid</span>
                            </div>
                            <p class="text-[11px] text-gray-400 mt-0.5">
                                {{ $bill->created_at->format('d M Y') }} &bull; {{ $bill->patientEnrollment->hospital->name }}
                            </p>
                        </div>
                        <div class="flex items-center justify-between sm:flex-col sm:items-end gap-3">
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase">Amount Due</p>
                                <p class="text-base sm:text-lg font-black text-gray-800">Rp {{ number_format($bill->total_amount, 0, ',', '.') }}</p>
                            </div>
                            <a href="{{ route('payment.create', $bill->id) }}"
                                class="shrink-0 inline-flex items-center gap-1.5 px-4 py-2 bg-[#14b8a6] hover:bg-[#0d9488] text-white rounded-xl text-xs font-semibold shadow-sm transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                Pay via Midtrans
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Bill items --}}
                <div class="p-3 sm:p-4 space-y-2">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Breakdown</p>
                    <div class="divide-y divide-gray-100 border border-gray-100 rounded-xl overflow-hidden">
                        @foreach ($bill->billItems as $item)
                            <div class="px-3 py-2.5 flex items-start justify-between gap-2 text-xs">
                                <div>
                                    <p class="font-semibold text-gray-800 leading-tight">{{ $item->description }}</p>
                                    <span class="text-[9px] font-bold uppercase tracking-wide
                                        {{ $item->item_type === 'consultation' ? 'text-teal-600' :
                                          ($item->item_type === 'medication'   ? 'text-emerald-600' :
                                          ($item->item_type === 'procedure'    ? 'text-blue-600' : 'text-gray-400')) }}">
                                        {{ $item->item_type }}
                                    </span>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="font-bold text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                                    <p class="text-[9px] text-gray-400">{{ $item->quantity }} x Rp {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-[11px] text-gray-500 px-1">
                        Due: <span class="text-red-500 font-bold">{{ $bill->payment_due_date->format('d M Y') }}</span>
                        &bull; {{ $bill->billItems->count() }} item(s)
                    </p>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-200 p-8 text-center shadow-sm">
                <div class="inline-flex p-3 bg-green-50 text-green-600 rounded-full mb-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-500">All bills are fully settled!</p>
            </div>
        @endforelse
    </div>

    {{-- Paid History --}}
    <div class="space-y-3 pt-1">
        <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
            <span class="w-1.5 h-4 bg-green-500 rounded-full"></span>
            Paid Invoices History
        </h3>

        @if ($paidBills->count() > 0)

            {{-- Mobile: card list --}}
            <div class="sm:hidden space-y-2">
                @foreach ($paidBills as $paid)
                    <div class="bg-white border border-gray-200 rounded-2xl p-3 shadow-sm">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="text-xs font-black text-gray-800">INV-{{ sprintf('%06d', $paid->id) }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">
                                    {{ $paid->payment_date ? $paid->payment_date->format('d M Y') : $paid->updated_at->format('d M Y') }}
                                </p>
                                <p class="text-[11px] text-gray-500 truncate max-w-[180px]">{{ $paid->patientEnrollment->hospital->name }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 bg-gray-100 text-[10px] font-bold uppercase rounded text-gray-500">
                                    {{ str_replace('_', ' ', $paid->payment_method ?? 'Cash') }}
                                </span>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-sm font-black text-green-600">Rp {{ number_format($paid->total_amount, 0, ',', '.') }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 bg-green-50 text-green-700 text-[10px] font-bold rounded-full">Paid</span>
                            </div>
                        </div>
                        @if($paid->reference_number)
                            <p class="mt-2 text-[10px] text-gray-400 font-mono truncate">Ref: {{ $paid->reference_number }}</p>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Desktop: table --}}
            <div class="hidden sm:block bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse m-0 text-xs">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-100 text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">
                                <th class="px-4 py-3">Invoice #</th>
                                <th class="px-4 py-3">Settle Date</th>
                                <th class="px-4 py-3">Hospital</th>
                                <th class="px-4 py-3">Method</th>
                                <th class="px-4 py-3">Reference #</th>
                                <th class="px-4 py-3 text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 font-semibold text-gray-700">
                            @foreach ($paidBills as $paid)
                                <tr class="hover:bg-gray-50/50 transition-colors">
                                    <td class="px-4 py-3 font-bold text-gray-800">INV-{{ sprintf('%06d', $paid->id) }}</td>
                                    <td class="px-4 py-3">{{ $paid->payment_date ? $paid->payment_date->format('d M Y H:i') : $paid->updated_at->format('d M Y') }}</td>
                                    <td class="px-4 py-3 max-w-[160px] truncate">{{ $paid->patientEnrollment->hospital->name }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-0.5 bg-gray-100 border border-gray-200 text-[10px] rounded text-gray-500 font-bold uppercase">
                                            {{ str_replace('_', ' ', $paid->payment_method ?? 'Cash') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 font-mono text-[10px] text-gray-400 truncate max-w-[120px]" title="{{ $paid->reference_number }}">
                                        {{ $paid->reference_number ?? '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-right text-green-600 font-bold">Rp {{ number_format($paid->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @else
            <div class="bg-white border border-gray-200 rounded-2xl p-8 text-center shadow-sm">
                <p class="text-xs text-gray-400 italic">No paid transactions on record</p>
            </div>
        @endif
    </div>

</div>
@endsection