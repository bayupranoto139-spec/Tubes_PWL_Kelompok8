<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — HealthMesh</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(135deg, #0891b2 0%, #06b6d4 100%);
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">

        {{-- Logo Section --}}
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 backdrop-blur-md rounded-2xl mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4">
                    </path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-1">HealthMesh</h1>
            <p class="text-cyan-50 text-sm">Sistem Informasi Manajemen Rumah Sakit</p>
        </div>

        {{-- Login Card --}}
        <div class="bg-white rounded-2xl shadow-2xl p-8 backdrop-blur-sm">
            <h2 class="text-2xl font-bold text-gray-900 mb-1">Selamat Datang</h2>
            <p class="text-gray-500 text-sm mb-6">Masuk ke akun Anda untuk melanjutkan</p>

            {{-- Error Message --}}
            @if ($errors->any())
                <div
                    class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-6 flex items-start gap-3">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email Input --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            autofocus
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm
                                   focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent
                                   transition duration-200
                                   @error('email') border-red-400 focus:ring-red-500 @enderror"
                            placeholder="nama@email.com">
                    </div>
                </div>

                {{-- Password Input --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                </path>
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" required
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm
                                   focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-transparent
                                   transition duration-200"
                            placeholder="••••••••">
                    </div>
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember"
                        class="h-4 w-4 text-cyan-600 border-gray-300 rounded focus:ring-cyan-500 cursor-pointer">
                    <label for="remember" class="ml-2.5 text-sm text-gray-600 cursor-pointer">
                        Ingat saya di perangkat ini
                    </label>
                </div>

                {{-- Submit Button --}}
                <button type="submit"
                    class="w-full bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700
                           text-white font-semibold py-2.5 rounded-xl text-sm
                           transition duration-200 transform hover:scale-105 active:scale-95
                           shadow-lg hover:shadow-xl">
                    <span class="flex items-center justify-center gap-2">
                        <span>Masuk</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </span>
                </button>

            </form>

            {{-- Divider --}}
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
            </div>

            {{-- Info --}}
            <p class="text-center text-xs text-gray-500">
                Hubungi administrator jika Anda lupa password
            </p>
        </div>

        {{-- Footer --}}
        <p class="text-center text-xs text-white/70 mt-8">
            &copy; {{ date('Y') }} HealthMesh. Semua hak dilindungi.
        </p>

    </div>

</body>

</html>
