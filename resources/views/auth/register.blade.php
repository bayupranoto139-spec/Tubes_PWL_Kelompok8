<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — HealthMesh</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <style>
        body {
            background: linear-gradient(135deg, #f0fdfa 0%, #ffffff 50%, #ecfeff 100%);
        }

        @keyframes loginPop {
            from {
                opacity: 0;
                transform: translateY(20px) scale(.96);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .login-popup {
            animation: loginPop .6s ease-out forwards;
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
            animation: slideRight .8s ease-out;
        }
    </style>
</head>

<body class="min-h-screen">

    <div class="min-h-screen bg-gradient-to-br from-teal-50 via-white to-cyan-50 flex">

        {{-- LEFT --}}
        <div class="flex-1 flex items-center justify-center p-8">

            <div
                class="login-popup w-full max-w-2xl bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl border border-gray-100 p-8 lg:p-10">

                {{-- Logo --}}
                <div class="mb-8">

                    <div class="flex items-center space-x-3 mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-[#14b8a6] to-[#0d9488] rounded-xl flex items-center justify-center">
                            <i class="fa-solid fa-stethoscope text-white text-lg"></i>
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
                        Create Account
                    </h2>

                    <p class="text-gray-600">
                        Register as a patient and access HealthMesh services.
                    </p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-600 text-sm">
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
                    @csrf

                    <div class="grid md:grid-cols-2 gap-5">

                        {{-- Nama --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Full Name
                            </label>

                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#14b8a6] focus:ring-[#14b8a6]/20">
                        </div>

                        {{-- Email --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Email Address
                            </label>

                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#14b8a6] focus:ring-[#14b8a6]/20">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Hospital
                            </label>

                            <select name="hospital_id"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#14b8a6] focus:ring-[#14b8a6]/20">
                                <option value="">Select Hospital</option>

                                @foreach ($hospitals as $hospital)
                                    <option value="{{ $hospital->id }}">
                                        {{ $hospital->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number
                            </label>

                            <input type="text" name="phone" value="{{ old('phone') }}" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#14b8a6] focus:ring-[#14b8a6]/20">
                        </div>

                        {{-- Gender --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Gender
                            </label>

                            <select name="gender" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#14b8a6] focus:ring-[#14b8a6]/20">
                                <option value="">Select Gender</option>
                                <option value="L">Male</option>
                                <option value="P">Female</option>
                            </select>
                        </div>

                        {{-- Birth --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Date of Birth
                            </label>

                            <input type="date" name="date_of_birth" required value="{{ old('date_of_birth') }}"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#14b8a6] focus:ring-[#14b8a6]/20">
                        </div>

                        {{-- Password --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Password
                            </label>

                            <input type="password" name="password" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#14b8a6] focus:ring-[#14b8a6]/20">
                        </div>

                    </div>

                    {{-- Address --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Address
                        </label>

                        <textarea name="address" rows="3" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#14b8a6] focus:ring-[#14b8a6]/20">{{ old('address') }}</textarea>
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Confirm Password
                        </label>

                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-[#14b8a6] focus:ring-[#14b8a6]/20">
                    </div>

                    <button type="submit"
                        class="w-full py-3 rounded-xl bg-[#14b8a6] hover:bg-[#0d9488] text-white font-semibold transition-all">
                        Create Account
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
                            Continue as guest
                        </a>
                    </div>

                    <p class="mt-6 text-center text-gray-600 text-sm">
                        Already have an account?
                        <a href="{{ route('login') }}" class="font-medium text-[#14b8a6] hover:text-[#0d9488]">Sign
                            In</a>
                    </p>

                </form>

            </div>
        </div>

        {{-- RIGHT --}}
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden hero-animation">

            <div class="absolute inset-0 bg-gradient-to-br from-[#14b8a6] via-[#0f9f90] to-[#0d9488]"></div>

            <div class="relative z-10 flex items-center justify-center p-12">
                <div class="text-center text-white">

                    <div
                        class="w-28 h-28 bg-white/20 rounded-3xl flex items-center justify-center mx-auto mb-8 backdrop-blur-sm">
                        <i class="fa-solid fa-stethoscope text-white text-6xl drop-shadow-lg"></i>
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
