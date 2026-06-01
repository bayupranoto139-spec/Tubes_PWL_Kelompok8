@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <div class="mb-4">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 rounded-full">
                        <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <h1 class="text-2xl font-bold text-gray-800 mb-2">Pembayaran Gagal</h1>
                <p class="text-gray-600 mb-6">Maaf, pembayaran Anda tidak dapat diproses.</p>

                <div class="bg-red-50 p-4 rounded-lg mb-6 text-left">
                    <p class="text-sm text-gray-600">Silakan periksa data Anda dan coba lagi, atau hubungi customer support
                        jika masalah berlanjut.</p>
                </div>

                <div class="flex gap-3">
                    <a href="/"
                        class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                        Beranda
                    </a>
                    <button onclick="window.history.back()"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                        Coba Lagi
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
