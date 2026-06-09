<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — HealthMesh</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 50%, #ecfeff 100%);
        }

        @keyframes loginPop {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.96);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .login-popup {
            animation: loginPop 0.6s ease-out forwards;
        }

        @keyframes slideRight {
            from {
                opacity: 0;
                transform: translateX(40px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .hero-animation {
            animation: slideRight 0.8s ease-out;
        }
    </style>
</head>

<body class="min-h-screen">

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-white to-cyan-50 flex">

        {{-- Left Side - Login Form --}}
        <div class="flex-1 flex items-center justify-center p-8">
            <div
                class="login-popup w-full max-w-md bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl border border-gray-100 p-8 lg:p-10">

                {{-- Logo --}}
                <div class="mb-8">
                    <div class="flex items-center space-x-3 mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-[#14b8a6] to-[#0d9488] rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-stethoscope text-white text-xl"></i>
                        </div>

                        <div>
                            <h1
                                class="text-2xl font-bold bg-gradient-to-r from-[#14b8a6] to-[#0d9488] bg-clip-text text-transparent">
                                HealthMesh
                            </h1>
                            <p class="text-sm text-gray-500">
                                Hospital Management System
                            </p>
                        </div>
                    </div>

                    <h2 class="text-3xl font-bold text-gray-900 mb-2">
                        Welcome Back
                    </h2>

                    <p class="text-gray-600">
                        Sign in to your account to continue
                    </p>
                </div>

                {{-- Error --}}
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm">
                        {{ $errors->first() }}

                        {{-- Tombol kirim ulang khusus untuk pasien belum verif --}}
                        @if (session('unverified_email'))
                            <form method="POST" action="{{ route('verification.resend.guest') }}" class="mt-3">
                                @csrf
                                <input type="hidden" name="email" value="{{ session('unverified_email') }}">
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-red-300 text-red-600 hover:bg-red-50 rounded-lg text-xs font-semibold transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Kirim Ulang Email Verifikasi
                                </button>
                            </form>
                        @endif
                    </div>
                @endif

                {{-- Success (misal setelah resend) --}}
                @if (session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>

                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>

                            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                                placeholder="you@example.com"
                                class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-[#14b8a6]
                                focus:ring-[#14b8a6]/20 outline-none transition-all">
                        </div>
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>

                        <div class="relative">
                            <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                    </path>
                                </svg>
                            </div>

                            <input type="password" name="password" required placeholder="••••••••"
                                class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-[#14b8a6]
                                focus:ring-[#14b8a6]/20 outline-none transition-all">
                        </div>
                    </div>

                    {{-- Remember --}}
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" name="remember" id="remember"
                                class="w-4 h-4 text-[#14b8a6] focus:ring-[#14b8a6] border-gray-300 rounded">

                            <label for="remember" class="ml-2 text-sm text-gray-600">
                                Remember me
                            </label>
                        </div>

                        <a href="{{ route('password.request') }}"
                            class="text-sm font-medium text-[#14b8a6] hover:text-[#0d9488] transition-colors">
                            Lupa password?
                        </a>
                    </div>

                    {{-- Button --}}
                    <button type="submit"
                        class="w-full py-3 rounded-xl bg-[#14b8a6] hover:bg-[#0d9488] text-white font-semibold transition-all"">
                        Sign In
                    </button>
                    <div class="relative my-5">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center">
                            <span class="bg-white px-4 text-sm text-gray-500">
                                or
                            </span>
                        </div>
                    </div>

                    <div class="text-center">
                        <a href="{{ url('/') }}"
                            class="inline-flex items-center gap-2 text-sm font-medium text-[#14b8a6] hover:text-[#0d9488] transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                            Continue as Guest
                        </a>
                    </div>
                </form>

                <p class="mt-6 text-center text-gray-600 text-sm">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-medium text-[#14b8a6] hover:text-[#0d9488]">Daftar
                        sebagai pasien</a>
                </p>

            </div>
        </div>

        {{-- Right Side --}}
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden hero-animation">

            <div class="absolute inset-0 bg-gradient-to-br from-[#14b8a6] via-[#0f9f90] to-[#0d9488]">
            </div>

            <div class="absolute inset-0 bg-black/10"></div>

            <div class="relative z-10 flex items-center justify-center p-12">
                <div class="text-center text-white">

                    <div
                        class="w-28 h-28 bg-white/20 rounded-3xl flex items-center justify-center mx-auto mb-8 backdrop-blur-sm">
                        <i class="fa-solid fa-stethoscope text-white text-5xl"></i>
                    </div>

                    <h2 class="text-4xl font-bold mb-4">
                        HealthMesh
                    </h2>

                    <p class="text-xl text-white max-w-md">
                        Access patient records, appointments, billing,
                        and hospital management tools in one platform.
                    </p>

                </div>
            </div>

        </div>

    </div>

</body>

</html>
