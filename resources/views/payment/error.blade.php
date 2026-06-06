<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Pembayaran Gagal - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl p-8 text-center space-y-6">

            <div class="inline-flex items-center justify-center w-20 h-20 bg-red-100 rounded-full">
                <svg class="w-10 h-10 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                        clip-rule="evenodd" />
                </svg>
            </div>

            <div>
                <h1 class="text-2xl font-black text-gray-800 mb-2">Pembayaran Gagal</h1>
                <p class="text-gray-500 text-sm">Maaf, transaksi Anda tidak dapat diproses.</p>
            </div>

            <div class="bg-red-50 border border-red-100 rounded-xl p-4 text-left space-y-1">
                <p class="text-sm text-red-800 font-semibold">❌ Transaksi ditolak</p>
                <p class="text-xs text-red-700">Periksa detail kartu/rekening Anda dan coba lagi. Jika masalah berlanjut, hubungi customer support.</p>
            </div>

            <div class="flex flex-col gap-3">
                <a href="{{ route('patient.bills') }}"
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-200 text-sm">
                    Coba Lagi
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
