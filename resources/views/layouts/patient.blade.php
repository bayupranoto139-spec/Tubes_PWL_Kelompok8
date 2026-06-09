<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HealthMesh - Patient Portal')</title>

    <!-- Tailwind CDN (same as doctor panel) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Midtrans Snap.js -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <!-- Vite assets (CSS/JS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a'
                        },
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] {
            display: none !important;
        }

        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(20, 184, 166, .25);
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(20, 184, 166, .45);
        }
    </style>
    @yield('head')
</head>

<body class="bg-gray-100 font-sans antialiased overflow-x-hidden">
    <div class="flex min-h-screen w-screen overflow-x-hidden">

        {{-- ====================== SIDEBAR ====================== --}}
        @php
            $patientUser = auth()->user();
            $patientName = $patientUser?->name ?? 'Patient';
            $initials = collect(explode(' ', $patientName))->map(fn($w) => strtoupper($w[0]))->take(2)->join('');
        @endphp

        <aside id="patient-sidebar"
            class="w-[17.5rem] bg-[#111827] text-white flex flex-col fixed h-full z-20 shadow-2xl transition-transform duration-300 -trangray-x-full md:trangray-x-0">

            {{-- Brand --}}
            <div class="px-6 py-5 flex items-center gap-3 border-b border-white/10">
                <div class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center"
                    style="background:linear-gradient(135deg,#14b8a6,#06b6d4)">
                    <i class="fa-solid fa-heart-pulse text-white text-base"></i>
                </div>
                <div>
                    <span class="text-base font-bold tracking-wide text-white">HealthMesh</span>
                    <p class="text-[11px] text-gray-400 leading-tight">Patient Portal</p>
                </div>
            </div>

            {{-- Patient mini-profile --}}
            <div class="mx-4 mt-4 mb-2 p-3 rounded-xl bg-white/5 border border-white/10 flex items-center gap-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold text-white flex-shrink-0"
                    style="background:linear-gradient(135deg,#14b8a6,#06b6d4)">
                    {{ $initials }}
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ $patientName }}</p>
                    <p class="text-[11px] text-white/70 truncate">Patient Account</p>
                </div>
            </div>

            {{-- Nav group label --}}
            <div class="px-6 pt-4 pb-1">
                <p class="text-[10px] font-semibold uppercase tracking-widest text-gray-500">Menu Utama</p>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 px-3 space-y-0.5 overflow-y-auto">
                @php
                    $navItems = [
                        ['route' => 'patient.dashboard', 'icon' => 'fa-chart-pie', 'label' => 'Dashboard'],
                        [
                            'route' => 'patient.hospitals',
                            'icon' => 'fa-hospital',
                            'label' => 'My Hospitals',
                        ],
                        ['route' => 'patient.appointments', 'icon' => 'fa-calendar-check', 'label' => 'Appointments'],
                        [
                            'route' => 'patient.medical-records',
                            'icon' => 'fa-file-medical',
                            'label' => 'Medical Records',
                        ],
                        ['route' => 'patient.bills', 'icon' => 'fa-file-invoice-dollar', 'label' => 'Billing & Bills'],
                        [
                            'route' => 'patient.prescriptions',
                            'icon' => 'fa-prescription-bottle-medical',
                            'label' => 'Prescriptions',
                        ],
                        ['route' => 'patient.profile', 'icon' => 'fa-user-injured', 'label' => 'Profile Settings'],
                    ];
                @endphp

                @foreach ($navItems as $item)
                    @php
                        $isActive = request()->routeIs($item['route']) || request()->routeIs($item['route'] . '*');
                    @endphp
                    <a href="{{ route($item['route']) }}"
                        class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                          {{ $isActive ? 'text-white shadow-lg' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}"
                        @if ($isActive) style="background:linear-gradient(90deg,#14b8a6,#06b6d4)" @endif>
                        <i
                            class="fa-solid {{ $item['icon'] }} w-4 text-center text-[15px]
                               {{ $isActive ? 'text-white' : 'text-gray-500 group-hover:text-teal-400' }}"></i>
                        <span>{{ $item['label'] }}</span>
                        @if ($isActive)
                            <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white/80"></span>
                        @endif
                    </a>
                @endforeach
            </nav>

            {{-- Logout --}}
            <div class="px-3 py-4 border-t border-white/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-red-400
                               hover:bg-red-500/10 hover:text-red-300 transition-all">
                        <i class="fa-solid fa-right-from-bracket w-4 text-center"></i>
                        <span>Log Out</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- Backdrop overlay for mobile --}}
        <div id="sidebar-overlay" class="fixed inset-0 z-10 bg-black/50 hidden md:hidden"></div>

        {{-- ====================== TOPBAR + MAIN WRAPPER ====================== --}}
        <div class="flex-1 md:ml-[17.5rem] flex flex-col min-h-screen">

            {{-- Topbar --}}
            <header
                class="bg-white border-b border-gray-200 h-14 flex items-center justify-between px-6 sticky top-0 z-10 shadow-sm">

                {{-- Left: Burger (mobile) + Breadcrumb --}}
                <div class="flex items-center gap-3">
                    <button id="burger-btn"
                        class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                        <i class="fa-solid fa-bars text-base"></i>
                    </button>
                    <nav class="flex items-center gap-1.5 text-sm text-gray-500">
                        <i class="fa-solid fa-house text-xs text-gray-400"></i>
                        <i class="fa-solid fa-chevron-right text-[10px] text-gray-300"></i>
                        <span class="font-semibold text-gray-700 capitalize">
                            @php
                                $pageNames = [
                                    'patient.dashboard' => 'Dashboard',
                                    'patient.appointments' => 'Appointments',
                                    'patient.medical-records' => 'Medical Records',
                                    'patient.bills' => 'Billing & Bills',
                                    'patient.prescriptions' => 'Prescriptions',
                                    'patient.profile' => 'Profile Settings',
                                ];
                                $routeName = request()->route()?->getName() ?? '';
                                // strip trailing .* for wildcard routes
                                $label = collect($pageNames)->first(fn($v, $k) => str_starts_with($routeName, $k));
                                echo $label ?? 'Patient Portal';
                            @endphp
                        </span>
                    </nav>
                </div>

                {{-- Right side --}}
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-xs text-gray-400">{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
                    </div>
                </div>
            </header>

            {{-- Page content --}}
            <main class="flex-1 p-6 bg-gray-50">

                {{-- Flash Alerts --}}
                @if (session('success'))
                    <div
                        class="flex items-center gap-3 mb-5 p-4 bg-teal-50 border border-teal-200 rounded-xl text-teal-800 shadow-sm">
                        <i class="fa-solid fa-circle-check text-teal-500"></i>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('error'))
                    <div
                        class="flex items-center gap-3 mb-5 p-4 bg-red-50 border border-red-200 rounded-xl text-red-800 shadow-sm">
                        <i class="fa-solid fa-triangle-exclamation text-red-500"></i>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="bg-white border-t border-gray-200 py-4 px-6 text-center text-xs text-gray-400">
                <p>&copy; {{ date('Y') }} HealthMesh Health Management System. All rights reserved.</p>
            </footer>
        </div>

    </div>

    {{-- Sidebar toggle scripts --}}
    <script>
        const sidebar = document.getElementById('patient-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const burgerBtn = document.getElementById('burger-btn');

        function openSidebar() {
            sidebar.classList.remove('-trangray-x-full');
            overlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-trangray-x-full');
            overlay.classList.add('hidden');
        }
        if (burgerBtn) burgerBtn.addEventListener('click', openSidebar);
        if (overlay) overlay.addEventListener('click', closeSidebar);

        // Profile dropdown
        const dropBtn = document.getElementById('profile-dropdown-btn');
        const dropMenu = document.getElementById('profile-dropdown-menu');
        const dropWrapper = document.getElementById('profile-dropdown-wrapper');
        if (dropBtn && dropMenu) {
            dropBtn.addEventListener('click', e => {
                e.stopPropagation();
                dropMenu.classList.toggle('hidden');
            });
            document.addEventListener('click', e => {
                if (dropWrapper && !dropWrapper.contains(e.target)) dropMenu.classList.add('hidden');
            });
        }
    </script>
    @yield('scripts')
</body>

</html>
