@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h1 class="text-3xl font-bold mb-6 text-gray-800">Pembayaran Tagihan</h1>

                <!-- Bill Details -->
                <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                    <h2 class="text-xl font-semibold mb-4 text-gray-700">Detail Tagihan</h2>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nomor Tagihan</p>
                            <p class="font-semibold text-gray-800">{{ $bill->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Tagihan</p>
                            <p class="font-semibold text-gray-800">{{ $bill->created_at->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Pasien</p>
                            <p class="font-semibold text-gray-800">{{ $bill->patientEnrollment->user->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email</p>
                            <p class="font-semibold text-gray-800">{{ $bill->patientEnrollment->user->email ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Items List -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-700">Item Tagihan</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-100 border-b">
                                <tr>
                                    <th class="px-4 py-2 text-left">Deskripsi</th>
                                    <th class="px-4 py-2 text-right">Harga</th>
                                    <th class="px-4 py-2 text-right">Qty</th>
                                    <th class="px-4 py-2 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bill->billItems as $item)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">{{ $item->description }}</td>
                                        <td class="px-4 py-2 text-right">Rp
                                            {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                        <td class="px-4 py-2 text-right">{{ $item->quantity }}</td>
                                        <td class="px-4 py-2 text-right font-semibold">Rp
                                            {{ number_format($item->unit_price * $item->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Total Amount -->
                <div class="mb-8 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                    <div class="flex justify-between items-center">
                        <span class="text-xl font-bold text-gray-800">Total Pembayaran</span>
                        <span class="text-3xl font-bold text-blue-600">Rp
                            {{ number_format($bill->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Payment Button -->
                <button id="pay-button"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
                    Lanjutkan ke Pembayaran
                </button>

                <p class="text-xs text-gray-500 mt-4 text-center">
                    Anda akan dialihkan ke halaman pembayaran Midtrans yang aman
                </p>
            </div>
        </div>
    </div>

    <!-- Midtrans Snap JS -->
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
    </script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function() {
            // Trigger snap popup. @TODO: Replace TRANSACTION_TOKEN_HERE with your transaction token.
            // Also checkout snap documentation for more Configuration option
            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    /* You may add your own implementation here */
                    console.log(result);
                    window.location.href = "{{ route('payment.success') }}";
                },
                onPending: function(result) {
                    /* You may add your own implementation here */
                    console.log(result);
                    window.location.href = "{{ route('payment.unfinish') }}";
                },
                onError: function(result) {
                    /* You may add your own implementation here */
                    console.log(result);
                    window.location.href = "{{ route('payment.error') }}";
                },
                onClose: function() {
                    /* You may add your own implementation here */
                    console.log('customer closed the popup without finishing the payment');
                    window.location.href = "{{ route('payment.unfinish') }}";
                }
            });
        };
    </script>
@endsection
