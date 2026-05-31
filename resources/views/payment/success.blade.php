@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <div class="mb-4">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full">
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>

                <h1 class="text-2xl font-bold text-gray-800 mb-2">Pembayaran Berhasil</h1>
                <p class="text-gray-600 mb-6">Terima kasih! Pembayaran Anda telah berhasil diproses.</p>

                <div class="bg-green-50 p-4 rounded-lg mb-6 text-left">
                    <p class="text-sm text-gray-600">Status pembayaran Anda akan diperbarui dalam beberapa saat.</p>
                </div>

                <a href="/"
                    class="inline-block bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg transition duration-200">
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
@endsection
