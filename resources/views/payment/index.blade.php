<!DOCTYPE html>
<html>
<head>
    <title>Halaman Pembayaran</title>
    <!-- Sertakan library Snap Midtrans -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <!-- CDN Tailwind CSS atau Bootstrap bisa ditambahkan di sini -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md text-center">
        <h1 class="text-2xl font-bold mb-4">Konfirmasi Pembayaran</h1>
        <p class="mb-2">Tagihan: <strong>#{{ $bill->id }}</strong></p>
        <p class="mb-4">Total: <strong class="text-xl">Rp {{ number_format($bill->total_amount, 0, ',', '.') }}</strong></p>
        <button id="pay-button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Bayar Sekarang</button>
    </div>

    <script type="text/javascript">
        var payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function () {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    window.location.href = '{{ route("payment.success") }}?order_id=' + result.order_id;
                },
                onPending: function(result){
                    window.location.href = '{{ route("payment.unfinish") }}?order_id=' + result.order_id;
                },
                onError: function(result){
                    window.location.href = '{{ route("payment.error") }}?order_id=' + result.order_id;
                },
                onClose: function(){
                    alert('Anda menutup popup tanpa menyelesaikan pembayaran.');
                }
            });
        });
    </script>
</body>
</html>