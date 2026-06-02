<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'MedVerse - Patient Portal')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Midtrans Snap.js -->
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>

    <!-- Tailwind & Vite Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
        .sidebar-transition {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        /* Custom scrollbar for premium feel */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(20, 184, 166, 0.2);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(20, 184, 166, 0.4);
        }
    </style>

    <!-- Dark Mode Initializer -->
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="h-full bg-slate-50 text-slate-800 dark:bg-gray-950 dark:text-gray-100 transition-colors duration-200">

    <!-- Overall Shell -->
    <div class="min-h-screen flex flex-col">
        
        <!-- SIDEBAR (Fixed Desktop, Slide-over Mobile) -->
        <aside id="sidebar" class="fixed top-0 bottom-0 left-0 z-50 w-64 bg-white dark:bg-gray-900 border-r border-slate-200/80 dark:border-gray-800/80 sidebar-transition -translate-x-full md:translate-x-0 shadow-lg md:shadow-none flex flex-col justify-between">
            <div>
                <!-- Sidebar Header / Logo -->
                <div class="h-16 flex items-center px-6 border-b border-slate-100 dark:border-gray-800/50">
                    <a href="{{ route('patient.dashboard') }}" class="flex items-center gap-2 group">
                        <span class="p-2 rounded-xl bg-teal-500/10 text-teal-600 dark:text-teal-400 group-hover:scale-105 transition-transform">
                            <!-- Stethoscope Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.871 4A17.926 17.926 0 003 12c0 5.171 2.425 9.78 6.19 12.75a1 1 0 001.278-.043l2.828-2.829a1 1 0 00.293-.707V15a4 4 0 014-4h1.586a1 1 0 00.707-.293l2.829-2.828a1 1 0 00-.043-1.278A17.926 17.926 0 0012 3a17.926 17.926 0 00-7.129 1z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 3v8a4 4 0 004 4h4"></path>
                            </svg>
                        </span>
                        <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-teal-500 to-cyan-500 bg-clip-text text-transparent">MedVerse</span>
                    </a>
                </div>

                <!-- Sidebar Nav links -->
                <nav class="p-4 space-y-1">
                    <!-- Dashboard -->
                    <a href="{{ route('patient.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('patient.dashboard') ? 'bg-gradient-to-r from-teal-500 to-cyan-500 text-white shadow-md shadow-teal-500/15' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"></path>
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Appointments -->
                    <a href="{{ route('patient.appointments') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('patient.appointments*') ? 'bg-gradient-to-r from-teal-500 to-cyan-500 text-white shadow-md shadow-teal-500/15' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>Appointments</span>
                    </a>

                    <!-- Medical Records -->
                    <a href="{{ route('patient.medical-records') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('patient.medical-records*') ? 'bg-gradient-to-r from-teal-500 to-cyan-500 text-white shadow-md shadow-teal-500/15' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span>Medical Records</span>
                    </a>

                    <!-- Bills -->
                    <a href="{{ route('patient.bills') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('patient.bills*') ? 'bg-gradient-to-r from-teal-500 to-cyan-500 text-white shadow-md shadow-teal-500/15' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        <span>Billing & Bills</span>
                    </a>

                    <!-- Prescriptions -->
                    <a href="{{ route('patient.prescriptions') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('patient.prescriptions*') ? 'bg-gradient-to-r from-teal-500 to-cyan-500 text-white shadow-md shadow-teal-500/15' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                        </svg>
                        <span>Prescriptions</span>
                    </a>

                    <!-- Profile -->
                    <a href="{{ route('patient.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all {{ request()->routeIs('patient.profile*') ? 'bg-gradient-to-r from-teal-500 to-cyan-500 text-white shadow-md shadow-teal-500/15' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-white' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span>Profile Settings</span>
                    </a>
                </nav>
            </div>

            <!-- Sidebar Footer / Logout -->
            <div class="p-4 border-t border-slate-100 dark:border-gray-800/50">
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-950/30 transition-all cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        <span>Log Out</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Backdrop overlay for mobile drawer -->
        <div id="sidebar-overlay" class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-sm hidden md:hidden"></div>

        <!-- MAIN LAYOUT AREA -->
        <div class="flex-1 md:pl-64 flex flex-col min-h-screen">
            
            <!-- STICKY TOP NAVBAR -->
            <header class="sticky top-0 z-30 h-16 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-slate-200/80 dark:border-gray-800/80 flex items-center justify-between px-6">
                <!-- Left Content: Burger and Title -->
                <div class="flex items-center gap-4">
                    <button id="burger-btn" class="p-2 rounded-xl text-slate-600 hover:bg-slate-100 dark:text-gray-300 dark:hover:bg-gray-800 md:hidden cursor-pointer">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h1 class="text-xl font-bold tracking-tight text-slate-800 dark:text-white">@yield('page_title', 'Dashboard')</h1>
                </div>

                <!-- Right Content: Actions & Dropdown -->
                <div class="flex items-center gap-3">
                    
                    <!-- Dark Mode Toggle Button -->
                    <button id="theme-toggle" class="p-2.5 rounded-xl text-slate-500 hover:text-slate-800 hover:bg-slate-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800 transition-colors cursor-pointer" aria-label="Toggle dark mode">
                        <!-- Sun Icon (Hidden by default) -->
                        <svg id="theme-toggle-light-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 100 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                        <!-- Moon Icon (Hidden by default) -->
                        <svg id="theme-toggle-dark-icon" class="w-5 h-5 hidden" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                    </button>

                    <!-- Profile Dropdown -->
                    <div class="relative" id="profile-dropdown-wrapper">
                        <button id="profile-dropdown-btn" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-gray-800 transition-colors cursor-pointer">
                            <span class="w-8 h-8 rounded-lg bg-teal-500 text-white font-semibold text-sm flex items-center justify-center uppercase shadow-sm shadow-teal-500/20">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </span>
                            <span class="hidden sm:inline-block text-sm font-semibold text-slate-700 dark:text-gray-200 pr-1">{{ Auth::user()->name }}</span>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div id="profile-dropdown-menu" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-900 border border-slate-200/80 dark:border-gray-800/80 rounded-xl shadow-xl py-1.5 hidden">
                            <div class="px-4 py-2 border-b border-slate-100 dark:border-gray-800/50">
                                <p class="text-xs text-slate-400">Logged in as</p>
                                <p class="text-xs font-semibold text-slate-800 dark:text-white truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('patient.profile') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-slate-700 dark:text-gray-200 hover:bg-slate-50 dark:hover:bg-gray-800/50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                My Profile
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 text-left cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            </header>

            <!-- MAIN CONTENT WRAPPER -->
            <main class="flex-grow p-6 space-y-6 max-w-7xl w-full mx-auto animate-fade-in">
                
                <!-- Flash Alerts -->
                @if (session('success'))
                    <div class="flex items-center gap-3 p-4 bg-teal-50 border border-teal-200 dark:bg-teal-950/20 dark:border-teal-900/50 rounded-2xl text-teal-800 dark:text-teal-300 shadow-sm shadow-teal-500/5 animate-pulse">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-200 dark:bg-red-950/20 dark:border-red-900/50 rounded-2xl text-red-800 dark:text-red-300 shadow-sm shadow-red-500/5">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Content Slot -->
                @yield('content')

            </main>

            <!-- FOOTER -->
            <footer class="bg-white dark:bg-gray-900 border-t border-slate-200/80 dark:border-gray-800/80 py-4 px-6 text-center text-xs text-slate-400 dark:text-gray-500">
                <p>&copy; {{ date('Y') }} MedVerse Health Management System. All rights reserved.</p>
            </footer>

        </div>

    </div>

    <!-- Layout Functionality Scripts -->
    <script>
        // Sidebar Toggle for Mobile
        const burgerBtn = document.getElementById('burger-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        if(burgerBtn && sidebar && overlay) {
            const toggleSidebar = () => {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            };
            burgerBtn.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', toggleSidebar);
        }

        // Profile Dropdown Toggle
        const dropdownBtn = document.getElementById('profile-dropdown-btn');
        const dropdownMenu = document.getElementById('profile-dropdown-menu');
        const dropdownWrapper = document.getElementById('profile-dropdown-wrapper');

        if(dropdownBtn && dropdownMenu) {
            dropdownBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdownMenu.classList.toggle('hidden');
            });
            document.addEventListener('click', (e) => {
                if (dropdownWrapper && !dropdownWrapper.contains(e.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        }

        // Dark Mode persists toggle
        const themeToggleBtn = document.getElementById('theme-toggle');
        const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        themeToggleBtn.addEventListener('click', function() {
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            if (localStorage.getItem('color-theme')) {
                if (localStorage.getItem('color-theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                }
            } else {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
