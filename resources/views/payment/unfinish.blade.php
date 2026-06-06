<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pembayaran Belum Selesai - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center space-y-6">

            <div class="inline-flex items-center justify-center w-20 h-20 bg-yellow-100 rounded-full">
                <svg class="w-10 h-10 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
            </div>

            <div>
                <h1 class="text-2xl font-black text-gray-800 mb-2">Pembayaran Belum Selesai</h1>
                <p class="text-gray-500 text-sm">Anda menutup halaman pembayaran sebelum transaksi selesai.</p>
            </div>

            <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4 text-left space-y-1">
                <p class="text-sm text-yellow-800 font-semibold">⏳ Tagihan masih aktif</p>
                <p class="text-xs text-yellow-700">Tagihan Anda belum dibayar. Kembali ke halaman tagihan untuk mencoba pembayaran lagi.</p>
            </div>

            <div class="flex flex-col gap-3">
                <a href="{{ route('patient.bills') }}"
                    class="w-full bg-teal-500 hover:bg-teal-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-200 text-sm">
                    Coba Bayar Lagi
                </a>
                <a href="{{ route('patient.dashboard') }}"
                    class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition-all duration-200 text-sm">
                    Kembali ke Dashboard
                </a>
            </div>

        </div>
    </div>

</body>
</html>
