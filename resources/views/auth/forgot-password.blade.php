<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — HealthMesh</title>
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
                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
            </div>

            <h1 class="text-2xl font-bold text-gray-900 mb-1">Lupa Password?</h1>
            <p class="text-gray-500 text-sm">
                Masukkan email akun pasien Anda. Kami akan mengirimkan link untuk mereset password.
            </p>
        </div>

        {{-- Success --}}
        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm flex items-start gap-3">
                <svg class="w-5 h-5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Error --}}
        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Email
                </label>
                <div class="relative">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="email@example.com"
                        class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-[#14b8a6] focus:ring-2 focus:ring-[#14b8a6]/20 outline-none transition-all">
                </div>
            </div>

            <button type="submit"
                class="w-full py-3 rounded-xl bg-[#14b8a6] hover:bg-[#0d9488] text-white font-semibold transition-all">
                Kirim Link Reset Password
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