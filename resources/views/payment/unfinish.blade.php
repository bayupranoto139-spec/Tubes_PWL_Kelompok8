@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <div class="mb-4">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-yellow-100 rounded-full">
                        <svg class="w-8 h-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <h1 class="text-2xl font-bold text-gray-800 mb-2">Pembayaran Belum Selesai</h1>
                <p class="text-gray-600 mb-6">Pembayaran Anda masih dalam proses atau belum diselesaikan.</p>

                <div class="bg-yellow-50 p-4 rounded-lg mb-6 text-left">
                    <p class="text-sm text-gray-600">Mohon tunggu beberapa saat atau coba lagi jika Anda ingin melanjutkan
                        pembayaran.</p>
                </div>

                <div class="flex gap-3">
                    <a href="/"
                        class="flex-1 bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                        Beranda
                    </a>
                    <button onclick="window.history.back()"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                        Kembali
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
