<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – Health Mesh</title>

    {{-- Tailwind via CDN (production: ganti dengan asset build) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Heroicons via Unpkg --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        :root {
            --primary: #06b6d4;   /* cyan-500 – warna primer Filament Health Mesh */
            --primary-dark: #0891b2;
        }

        /* Sidebar aktif & warna aksen Filament */
        .fi-sidebar-item-active { background-color: rgb(236 254 255); color: var(--primary); }
        .fi-topbar { background: white; border-bottom: 1px solid #e5e7eb; }

        /* Stat card mirip Filament StatsOverviewWidget */
        .fi-stat-card {
            background: white;
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.07);
            border: 1px solid #f3f4f6;
            transition: box-shadow .2s;
        }
        .fi-stat-card:hover { box-shadow: 0 4px 12px rgba(0,0,0,.1); }

        /* Sparkline canvas */
        canvas.sparkline { height: 40px !important; }

        /* Sidebar Filament-like */
        #sidebar {
            background: white;
            border-right: 1px solid #e5e7eb;
            width: 260px;
            min-height: 100vh;
            flex-shrink: 0;
        }

        /* Overlay untuk fitur terkunci */
        .feature-overlay {
            position: relative;
        }
        .feature-overlay::after {
            content: '🔒 Login untuk akses';
            position: absolute;
            inset: 0;
            background: rgba(255,255,255,.85);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #6b7280;
            font-size: .875rem;
            border-radius: inherit;
            opacity: 0;
            transition: opacity .2s;
            pointer-events: none;
        }
        .feature-overlay:hover::after { opacity: 1; }

        /* Toast notif */
        #toast {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            transform: translateY(6rem);
            transition: transform .35s cubic-bezier(.34,1.56,.64,1);
        }
        #toast.show { transform: translateY(0); }
    </style>
</head>
<body class="bg-gray-50 font-sans antialiased">

{{-- ====== LAYOUT WRAPPER ====== --}}
<div class="flex min-h-screen">

    {{-- ====== SIDEBAR (mirip Filament) ====== --}}
    <aside id="sidebar" class="hidden md:flex flex-col">

        {{-- Brand --}}
        <div class="flex items-center gap-3 px-5 py-5 border-b border-gray-100">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                 style="background: linear-gradient(135deg,#14b8a6,#06b6d4);">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5"
                     viewBox="0 0 24 24"><path d="M4.5 12.5l3 3 4.5-5 3 3 4.5-5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <span class="font-bold text-gray-800 text-lg tracking-tight">Health Mesh</span>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto text-sm">

            {{-- Dashboard (bisa diakses) --}}
            <a href="{{ route('guest.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-cyan-600 bg-cyan-50 font-medium">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>
                </svg>
                Dashboard
            </a>

            <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-widest">Management</p>

            {{-- Fitur-fitur yang butuh login --}}
            @foreach([
                ['icon'=>'building-2','label'=>'Hospitals'],
                ['icon'=>'users','label'=>'Users'],
                ['icon'=>'stethoscope','label'=>'Doctors'],
                ['icon'=>'calendar','label'=>'Appointments'],
                ['icon'=>'clipboard','label'=>'Medical Records'],
                ['icon'=>'receipt','label'=>'Bills'],
            ] as $nav)
            <button onclick="requireLogin('{{ $nav['label'] }}')"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 hover:bg-gray-50 hover:text-gray-900 transition-colors text-left">
                <svg class="w-5 h-5 shrink-0 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    @if($nav['icon']==='building-2')
                        <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/><path d="M10 14h4"/><path d="M10 18h4"/>
                    @elseif($nav['icon']==='users')
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    @elseif($nav['icon']==='stethoscope')
                        <path d="M4.8 2.3A.3.3 0 1 0 5 2H4a2 2 0 0 0-2 2v5a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6V4a2 2 0 0 0-2-2h-1a.2.2 0 1 0 .3.3"/><path d="M8 15v1a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6v-4"/><circle cx="20" cy="10" r="2"/>
                    @elseif($nav['icon']==='calendar')
                        <rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>
                    @elseif($nav['icon']==='clipboard')
                        <rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                    @else
                        <path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1Z"/><path d="M14 8H8"/><path d="M16 12H8"/><path d="M13 16H8"/>
                    @endif
                </svg>
                <span>{{ $nav['label'] }}</span>
                <svg class="w-3.5 h-3.5 ml-auto text-gray-300" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-7l4-3-4-3v6z" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            @endforeach

        </nav>

        {{-- Login CTA di bawah sidebar --}}
        <div class="p-4 border-t border-gray-100">
            <div class="rounded-xl bg-gradient-to-r from-teal-500 to-cyan-500 p-4 text-white text-sm">
                <p class="font-semibold mb-1">👤 Mode Guest</p>
                <p class="opacity-90 text-xs mb-3">Login untuk mengakses semua fitur platform.</p>
                <a href="{{ route('filament.admin.auth.login') }}"
                   class="block text-center bg-white text-cyan-600 font-semibold text-xs rounded-lg py-2 hover:bg-cyan-50 transition-colors">
                    Login Sekarang
                </a>
            </div>
        </div>

    </aside>

    {{-- ====== MAIN ====== --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- ====== TOPBAR (mirip Filament) ====== --}}
        <header class="fi-topbar sticky top-0 z-40 px-4 md:px-6 h-16 flex items-center justify-between gap-4">

            {{-- Mobile menu --}}
            <button onclick="document.getElementById('mobileSidebar').classList.toggle('hidden')"
                    class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>

            {{-- Search bar (decoratif, redirect login) --}}
            <button onclick="requireLogin('Search')"
                    class="hidden sm:flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 text-gray-400 text-sm hover:bg-gray-200 transition-colors flex-1 max-w-xs">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                </svg>
                Search…
                <kbd class="ml-auto text-xs bg-white border border-gray-200 rounded px-1.5 py-0.5">⌘K</kbd>
            </button>

            {{-- Right actions --}}
            <div class="flex items-center gap-2 ml-auto">
                {{-- Notifications (butuh login) --}}
                <button onclick="requireLogin('Notifikasi')"
                        class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 relative">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                    </svg>
                    <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                </button>

                {{-- Avatar guest --}}
                <button onclick="requireLogin('Profil')"
                        class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                    <div class="w-6 h-6 rounded-full bg-cyan-500 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/>
                        </svg>
                    </div>
                    <span class="text-sm text-gray-600 hidden sm:block">Guest</span>
                </button>

                <a href="{{ route('filament.admin.auth.login') }}"
                   class="flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium text-white transition-colors"
                   style="background: linear-gradient(135deg,#14b8a6,#06b6d4);">
                    Login
                </a>
            </div>

        </header>

        {{-- ====== KONTEN DASHBOARD ====== --}}
        <main class="flex-1 p-4 md:p-6 space-y-6 max-w-screen-xl mx-auto w-full">

            {{-- Welcome Banner (identik WelcomeBanner widget) --}}
            <div class="w-full rounded-2xl p-8 text-white"
                 style="background: linear-gradient(90deg, #14b8a6, #06b6d4);">
                <h1 class="text-3xl md:text-4xl font-extrabold leading-tight">
                    Selamat Datang, Guest! 👋
                </h1>
                <p class="mt-3 text-lg opacity-95">
                    Berikut gambaran umum platform Health Mesh.
                    <a href="{{ route('filament.admin.auth.login') }}" class="underline font-semibold">Login</a>
                    untuk akses fitur lengkap.
                </p>
            </div>

            {{-- Info guest banner --}}
            <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 text-sm text-amber-800">
                <svg class="w-5 h-5 shrink-0 mt-0.5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span>
                    Anda sedang mengakses sebagai <strong>Guest</strong>. Data yang ditampilkan bersifat read-only.
                    Klik pada tombol atau menu manapun untuk diarahkan ke halaman login.
                </span>
            </div>

            {{-- ====== STAT CARDS ROW 1 (DashboardStats widget) ====== --}}
            <div>
                <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Overview</h2>
                <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

                    {{-- Total Hospitals --}}
                    <div class="fi-stat-card feature-overlay">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm text-gray-500 font-medium">Total Hospitals</span>
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-50">
                                <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/><path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/><path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/><path d="M10 6h4"/><path d="M10 10h4"/>
                                </svg>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_hospitals'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">Healthcare centers</p>
                        <canvas class="sparkline mt-3" id="sparkHospitals"></canvas>
                    </div>

                    {{-- Total Users --}}
                    <div class="fi-stat-card feature-overlay">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm text-gray-500 font-medium">Total Users</span>
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-cyan-50">
                                <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                </svg>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_users'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">Registered accounts</p>
                        <canvas class="sparkline mt-3" id="sparkUsers"></canvas>
                    </div>

                    {{-- Total Doctors --}}
                    <div class="fi-stat-card feature-overlay">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm text-gray-500 font-medium">Total Doctors</span>
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                </svg>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_doctors'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">Available doctors</p>
                        <canvas class="sparkline mt-3" id="sparkDoctors"></canvas>
                    </div>

                    {{-- Total Patients --}}
                    <div class="fi-stat-card feature-overlay">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm text-gray-500 font-medium">Total Patients</span>
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                </svg>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_patients'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">Active patients</p>
                        <canvas class="sparkline mt-3" id="sparkPatients"></canvas>
                    </div>

                </div>
            </div>

            {{-- ====== STAT CARDS ROW 2 (MiniStats widget) ====== --}}
            <div>
                <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Operational</h2>
                <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">

                    <div class="fi-stat-card feature-overlay">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm text-gray-500 font-medium">Today's Visits</span>
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-cyan-50">
                                <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
                                </svg>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-gray-800">{{ $miniStats['today_visits'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">Daily visits</p>
                        <canvas class="sparkline mt-3" id="sparkVisits"></canvas>
                    </div>

                    <div class="fi-stat-card feature-overlay">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm text-gray-500 font-medium">Monthly Revenue</span>
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                </svg>
                            </span>
                        </div>
                        <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($miniStats['monthly_revenue'], 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-1">Revenue this month</p>
                        <canvas class="sparkline mt-3" id="sparkRevenue"></canvas>
                    </div>

                    <div class="fi-stat-card feature-overlay">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm text-gray-500 font-medium">Active Queues</span>
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                                </svg>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-gray-800">{{ $miniStats['active_queues'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">Current queues</p>
                        <canvas class="sparkline mt-3" id="sparkQueues"></canvas>
                    </div>

                    <div class="fi-stat-card feature-overlay">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm text-gray-500 font-medium">Pending Payments</span>
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50">
                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                            </span>
                        </div>
                        <p class="text-3xl font-bold text-gray-800">{{ $miniStats['pending_payments'] }}</p>
                        <p class="text-xs text-gray-400 mt-1">Waiting payments</p>
                        <canvas class="sparkline mt-3" id="sparkPending"></canvas>
                    </div>

                </div>
            </div>

            {{-- ====== CHARTS (RevenueChart + VisitsChart) ====== --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                {{-- Revenue by Hospital (RevenueChart) --}}
                <div class="fi-stat-card feature-overlay">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-700 text-sm">Revenue by Hospital</h3>
                        <button onclick="requireLogin('Revenue Chart')"
                                class="text-xs text-cyan-500 hover:underline">Detail →</button>
                    </div>
                    <div style="height:280px; position:relative;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                {{-- Visits per Month (VisitsChart) --}}
                <div class="fi-stat-card feature-overlay">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-semibold text-gray-700 text-sm">Visits per Month</h3>
                        <button onclick="requireLogin('Visits Chart')"
                                class="text-xs text-cyan-500 hover:underline">Detail →</button>
                    </div>
                    <div style="height:280px; position:relative;">
                        <canvas id="visitsChart"></canvas>
                    </div>
                </div>

            </div>

            {{-- ====== CTA LOGIN ====== --}}
            <div class="rounded-2xl border border-dashed border-gray-200 bg-white p-8 text-center">
                <div class="w-14 h-14 mx-auto rounded-full flex items-center justify-center mb-4"
                     style="background: linear-gradient(135deg,#14b8a6,#06b6d4);">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Akses Fitur Lengkap</h3>
                <p class="text-gray-500 text-sm mb-5 max-w-sm mx-auto">
                    Login untuk mengelola rumah sakit, dokter, pasien, antrian, rekam medis, dan lebih banyak lagi.
                </p>
                <a href="{{ route('filament.admin.auth.login') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 rounded-xl text-white font-semibold text-sm transition-opacity hover:opacity-90"
                   style="background: linear-gradient(135deg,#14b8a6,#06b6d4);">
                    Login ke Dashboard
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M5 12h14m-7-7 7 7-7 7" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>

        </main>
    </div>
</div>

{{-- ====== MOBILE SIDEBAR ====== --}}
<div id="mobileSidebar" class="hidden fixed inset-0 z-50 md:hidden">
    <div class="absolute inset-0 bg-black/40" onclick="document.getElementById('mobileSidebar').classList.add('hidden')"></div>
    <aside class="relative z-10 w-72 h-full bg-white shadow-xl flex flex-col">
        <div class="flex items-center justify-between px-5 py-5 border-b border-gray-100">
            <span class="font-bold text-gray-800 text-lg">Health Mesh</span>
            <button onclick="document.getElementById('mobileSidebar').classList.add('hidden')" class="p-1 text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M18 6 6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <nav class="flex-1 px-3 py-4 space-y-1 text-sm overflow-y-auto">
            <a href="{{ route('guest.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-cyan-600 bg-cyan-50 font-medium">Dashboard</a>
            @foreach(['Hospitals','Users','Doctors','Appointments','Medical Records','Bills'] as $item)
            <button onclick="requireLogin('{{ $item }}')" class="w-full text-left flex items-center gap-3 px-3 py-2.5 rounded-lg text-gray-600 hover:bg-gray-50">
                {{ $item }}
                <span class="ml-auto text-gray-300 text-xs">🔒</span>
            </button>
            @endforeach
        </nav>
        <div class="p-4 border-t border-gray-100">
            <a href="{{ route('filament.admin.auth.login') }}"
               class="block text-center py-2.5 rounded-xl text-white font-semibold text-sm"
               style="background: linear-gradient(135deg,#14b8a6,#06b6d4);">
                Login Sekarang
            </a>
        </div>
    </aside>
</div>

{{-- ====== TOAST NOTIFIKASI ====== --}}
<div id="toast" class="bg-white border border-gray-200 rounded-2xl shadow-xl px-5 py-4 flex items-start gap-3 max-w-xs">
    <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center"
         style="background: linear-gradient(135deg,#14b8a6,#06b6d4);">
        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
        </svg>
    </div>
    <div>
        <p class="font-semibold text-gray-800 text-sm" id="toastTitle">Login Diperlukan</p>
        <p class="text-gray-500 text-xs mt-0.5" id="toastMsg">Arahkan ke halaman login…</p>
        <a href="{{ route('filament.admin.auth.login') }}"
           class="mt-2 inline-block text-xs font-semibold text-cyan-600 hover:underline">
            Login sekarang →
        </a>
    </div>
</div>

{{-- ====== SCRIPTS ====== --}}
<script>
// ---- Toast + redirect ke login ----
function requireLogin(feature) {
    const toast   = document.getElementById('toast');
    const title   = document.getElementById('toastTitle');
    const msg     = document.getElementById('toastMsg');

    title.textContent = '🔒 ' + feature;
    msg.textContent   = 'Silakan login untuk mengakses fitur ini.';
    toast.classList.add('show');

    // Redirect setelah 1.8 detik
    setTimeout(() => {
        window.location.href = '{{ route("filament.admin.auth.login") }}';
    }, 1800);

    // Tutup toast setelah 5 detik jika user tidak redirect
    setTimeout(() => toast.classList.remove('show'), 5000);
}

// ---- Sparkline mini charts ----
const sparkColor = {
    green : { border:'#22c55e', bg:'rgba(34,197,94,.15)' },
    cyan  : { border:'#06b6d4', bg:'rgba(6,182,212,.15)' },
    amber : { border:'#f59e0b', bg:'rgba(245,158,11,.15)' },
    blue  : { border:'#3b82f6', bg:'rgba(59,130,246,.15)' },
    red   : { border:'#ef4444', bg:'rgba(239,68,68,.15)' },
};

function makeSparkline(id, data, color) {
    const ctx = document.getElementById(id);
    if (!ctx) return;
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map((_, i) => i),
            datasets: [{
                data,
                borderColor: color.border,
                backgroundColor: color.bg,
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointRadius: 0,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { enabled: false } },
            scales: { x: { display: false }, y: { display: false } },
            animation: { duration: 0 },
        },
    });
}

makeSparkline('sparkHospitals', [7,10,12,15,18,20], sparkColor.green);
makeSparkline('sparkUsers',     [5,8,10,14,18,22],  sparkColor.cyan);
makeSparkline('sparkDoctors',   [2,4,6,8,10,12],    sparkColor.amber);
makeSparkline('sparkPatients',  [3,6,9,12,16,20],   sparkColor.blue);
makeSparkline('sparkVisits',    [2,4,6,8,10,12],    sparkColor.cyan);
makeSparkline('sparkRevenue',   [5,10,15,20,25,30], sparkColor.amber);
makeSparkline('sparkQueues',    [1,3,5,7,9,11],     sparkColor.red);
makeSparkline('sparkPending',   [1,2,3,4,5,6],      sparkColor.red);

// ---- Revenue Chart (Bar) ----
const revenueLabels = @json($revenueLabels ?: ['No Data']);
const revenueData   = @json($revenueData   ?: [0]);

new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: revenueLabels,
        datasets: [{
            label: 'Revenue',
            data: revenueData,
            backgroundColor: ['#14b8a6','#0ea5e9','#8b5cf6','#f59e0b','#ef4444'],
            borderRadius: 10,
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 } } },
            y: {
                grid: { color: '#f3f4f6' },
                ticks: {
                    font: { size: 11 },
                    callback: v => 'Rp ' + (v >= 1e6 ? (v/1e6).toFixed(1)+'jt' : v.toLocaleString('id')),
                },
            },
        },
    },
});

// ---- Visits Chart (Line) ----
const visitsLabels = @json($visitsLabels);
const visitsData   = @json($visitsData);

new Chart(document.getElementById('visitsChart'), {
    type: 'line',
    data: {
        labels: visitsLabels,
        datasets: [{
            label: 'Visits',
            data: visitsData,
            borderColor: '#06b6d4',
            backgroundColor: 'rgba(6,182,212,.1)',
            borderWidth: 2.5,
            fill: true,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: '#06b6d4',
        }],
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 } } },
            y: { grid: { color: '#f3f4f6' }, ticks: { font: { size: 11 } } },
        },
    },
});
</script>

</body>
</html>
