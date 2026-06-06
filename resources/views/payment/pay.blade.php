<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pembayaran Tagihan - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Midtrans Snap JS --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-2xl">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">

            {{-- Header --}}
            <div class="bg-gradient-to-r from-teal-500 to-cyan-500 px-8 py-6">
                <div class="flex items-center gap-3">
                    <div class="bg-white/20 p-2 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white">Pembayaran Tagihan</h1>
                        <p class="text-teal-100 text-sm">INV-{{ sprintf('%06d', $bill->id) }}</p>
                    </div>
                </div>
            </div>

            <div class="p-8 space-y-6">

                {{-- Flash messages --}}
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Bill Info --}}
                <div class="bg-gray-50 rounded-xl p-5 grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Pasien</p>
                        <p class="font-semibold text-gray-800">{{ $bill->patientEnrollment->user->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Tanggal</p>
                        <p class="font-semibold text-gray-800">{{ $bill->created_at->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Rumah Sakit</p>
                        <p class="font-semibold text-gray-800">{{ $bill->patientEnrollment->hospital->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Status</p>
                        <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-bold rounded uppercase">Unpaid</span>
                    </div>
                </div>

                {{-- Items Table --}}
                <div>
                    <h3 class="text-sm font-bold text-gray-700 mb-3">Rincian Tagihan</h3>
                    <div class="border border-gray-100 rounded-xl overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-3 text-left">Deskripsi</th>
                                    <th class="px-4 py-3 text-right">Harga</th>
                                    <th class="px-4 py-3 text-center">Qty</th>
                                    <th class="px-4 py-3 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($bill->billItems as $item)
                                    <tr>
                                        <td class="px-4 py-3 text-gray-700">{{ $item->description }}</td>
                                        <td class="px-4 py-3 text-right text-gray-600">
                                            Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3 text-center text-gray-600">{{ $item->quantity }}</td>
                                        <td class="px-4 py-3 text-right font-semibold text-gray-800">
                                            Rp {{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Total --}}
                <div class="bg-gradient-to-r from-teal-50 to-cyan-50 border border-teal-100 rounded-xl p-5">
                    <div class="flex justify-between items-center">
                        <span class="font-bold text-gray-700">Total Pembayaran</span>
                        <span class="text-2xl font-black text-teal-600">
                            Rp {{ number_format($bill->total_amount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                {{-- Pay Button --}}
                <button id="pay-button"
                    class="w-full bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 text-white font-bold py-4 px-6 rounded-xl transition-all duration-200 shadow-md shadow-teal-500/20 hover:shadow-teal-500/40 hover:-translate-y-0.5 flex items-center justify-center gap-2 text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Bayar Sekarang dengan Midtrans
                </button>

                <p class="text-xs text-gray-400 text-center">
                    🔒 Pembayaran diproses dengan aman oleh Midtrans · Sandbox Mode
                </p>

                <div class="text-center">
                    <a href="{{ route('patient.bills') }}" class="text-sm text-gray-400 hover:text-gray-600 underline">
                        ← Kembali ke halaman tagihan
                    </a>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('pay-button').onclick = function () {
            snap.pay('{{ $snapToken }}', {
                onSuccess: function (result) {
                    console.log('Payment success:', result);
                    window.location.href = "{{ route('payment.success') }}";
                },
                onPending: function (result) {
                    console.log('Payment pending:', result);
                    window.location.href = "{{ route('payment.unfinish') }}";
                },
                onError: function (result) {
                    console.log('Payment error:', result);
                    window.location.href = "{{ route('payment.error') }}";
                },
                onClose: function () {
                    console.log('Snap closed without completing payment');
                    // Stay on the same page so user can retry
                }
            });
        };
    </script>

</body>
</html>
