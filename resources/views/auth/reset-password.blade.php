<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — HealthMesh</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
    body {
        background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 50%, #ecfeff 100%);
    }

    @keyframes loginPop {
        from { opacity: 0; transform: translateY(20px) scale(0.96); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .card-animation { animation: loginPop 0.6s ease-out forwards; }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

    <div class="card-animation w-full max-w-md bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl border border-gray-100 p-8 lg:p-10">

        {{-- Logo --}}
        <div class="mb-8 text-center">
            <div class="w-16 h-16 bg-gradient-to-br from-[#14b8a6] to-[#0d9488] rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-1">Buat Password Baru</h1>
            <p class="text-gray-500 text-sm">
                Masukkan password baru untuk akun <span class="font-medium text-gray-700">{{ $email }}</span>
            </p>
        </div>

        {{-- Error --}}
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            {{-- Password Baru --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Password Baru
                </label>
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input type="password" name="password" required autofocus
                        placeholder="Minimal 8 karakter"
                        class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-[#14b8a6] focus:ring-2 focus:ring-[#14b8a6]/20 outline-none transition-all">
                </div>
            </div>

            {{-- Konfirmasi Password --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Konfirmasi Password Baru
                </label>
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <input type="password" name="password_confirmation" required
                        placeholder="Ulangi password baru"
                        class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-[#14b8a6] focus:ring-2 focus:ring-[#14b8a6]/20 outline-none transition-all">
                </div>
            </div>

            <button type="submit"
                class="w-full py-3 rounded-xl bg-[#14b8a6] hover:bg-[#0d9488] text-white font-semibold transition-all">
                Reset Password
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="{{ route('login') }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-[#14b8a6] hover:text-[#0d9488] transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali ke Login
            </a>
        </div>

    </div>

</body>
</html>