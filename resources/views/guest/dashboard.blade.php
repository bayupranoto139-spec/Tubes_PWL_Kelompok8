<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard – Health Mesh</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .fade-in { animation: fadeIn 0.5s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
        .card-hover { transition: transform 0.2s, box-shadow 0.2s; }
        .card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }

        /* Toast */
        #toast {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            transform: translateY(6rem);
            opacity: 0;
            transition: transform .35s cubic-bezier(.34,1.56,.64,1), opacity .25s;
        }
        #toast.show { transform: translateY(0); opacity: 1; }

        /* Sparkline */
        canvas.spark { height: 44px !important; }
    </style>
</head>
<body class="antialiased bg-gray-50" x-data="{ mobileOpen: false }">

{{-- ============================================================ --}}
{{--  NAVBAR  (identik public.blade.php exampleProject)           --}}
{{-- ============================================================ --}}
<nav class="bg-white/95 backdrop-blur-md shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- Logo --}}
            <div class="flex items-center">
                <a href="#home" onclick="smoothScroll('home'); return false;" class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-stethoscope text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-bold bg-gradient-to-r from-teal-600 to-cyan-600 bg-clip-text text-transparent">
                        Health Mesh
                    </span>
                </a>
            </div>

            {{-- Desktop Nav --}}
            <div class="hidden md:flex items-center space-x-8">
                <a href="#home" onclick="smoothScroll('home'); return false;"
                   class="text-gray-600 hover:text-teal-600 transition-colors font-medium {{ Request::is('dashboard') ? 'text-teal-600' : '' }}">
                    Home
                </a>
                <a href="#"
                   onclick="requireLogin('Hospitals'); return false;"
                   class="text-gray-600 hover:text-teal-600 transition-colors font-medium">
                    Hospitals
                </a>
                <a href="#"
                   onclick="requireLogin('Doctors'); return false;"
                   class="text-gray-600 hover:text-teal-600 transition-colors font-medium">
                    Doctors
                </a>
                <a href="#about" onclick="smoothScroll('about'); return false;"
                   class="text-gray-600 hover:text-teal-600 transition-colors font-medium">
                    About
                </a>
                <a href="#contact" onclick="smoothScroll('contact'); return false;"
                   class="text-gray-600 hover:text-teal-600 transition-colors font-medium">
                    Contact
                </a>
            </div>

            {{-- Auth Button (desktop) --}}
            <div class="hidden md:flex items-center space-x-4">
                <a href="/login"
                   class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-teal-500 to-teal-600 text-white font-medium hover:from-teal-600 hover:to-teal-700 transition-all shadow-lg shadow-teal-500/25">
                    Sign In
                </a>

            </div>

            {{-- Mobile Hamburger --}}
            <div class="md:hidden flex items-center">
                <button @click="mobileOpen = !mobileOpen" class="p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                    <i x-show="!mobileOpen" class="fa-solid fa-bars"></i>
                    <i x-show="mobileOpen" x-cloak class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="mobileOpen" x-cloak class="md:hidden bg-white border-t">
        <div class="px-4 py-4 space-y-2">
            <a href="#" onclick="smoothScroll('home'); return false;"
               class="block px-4 py-2 rounded-lg text-gray-600 hover:bg-teal-50 hover:text-teal-600">Home</a>
            <button onclick="requireLogin('Hospitals')"
               class="w-full text-left px-4 py-2 rounded-lg text-gray-600 hover:bg-teal-50 hover:text-teal-600">
               Hospitals <i class="fa-solid fa-lock text-xs text-gray-300 ml-1"></i>
            </button>
            <button onclick="requireLogin('Doctors')"
               class="w-full text-left px-4 py-2 rounded-lg text-gray-600 hover:bg-teal-50 hover:text-teal-600">
               Doctors <i class="fa-solid fa-lock text-xs text-gray-300 ml-1"></i>
            </button>
            <a href="#about" @click="mobileOpen=false"
               class="block px-4 py-2 rounded-lg text-gray-600 hover:bg-teal-50 hover:text-teal-600">About</a>
            <a href="#contact" @click="mobileOpen=false"
               class="block px-4 py-2 rounded-lg text-gray-600 hover:bg-teal-50 hover:text-teal-600">Contact</a>
            <div class="pt-3 border-t">
                <a href="/login" @click="mobileOpen=false"
                   class="block px-4 py-2.5 rounded-xl bg-gradient-to-r from-teal-500 to-teal-600 text-white text-center font-medium">
                    Sign In
                </a>

            </div>
        </div>
    </div>
</nav>

{{-- ============================================================ --}}
{{--  HERO / WELCOME BANNER                                       --}}
{{-- ============================================================ --}}
<section id="home" class="relative overflow-hidden bg-gradient-to-br from-teal-50 via-white to-cyan-50">
    <div class="absolute inset-0 opacity-30"
         style="background-image: radial-gradient(circle at 1px 1px, rgba(20,184,166,.15) 1px, transparent 0); background-size: 40px 40px;"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20 relative z-10">
        <div class="fade-in">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-teal-100 text-teal-700 text-sm font-medium mb-5">
                <i class="fa-solid fa-eye mr-2"></i>
                Mode Tamu — Hanya dapat melihat data
            </div>
            <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 leading-tight mb-4">
                Selamat Datang di
                <span class="bg-gradient-to-r from-teal-500 to-cyan-500 bg-clip-text text-transparent">Health Mesh</span>
            </h1>
            <p class="text-lg text-gray-600 mb-8 max-w-2xl">
                Platform manajemen layanan kesehatan terpadu. Pantau statistik rumah sakit, dokter, dan pasien secara real-time.
                <a href="/login" class="text-teal-600 font-semibold hover:underline">Sign in</a>

                untuk mengakses semua fitur.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="/login"
                   class="inline-flex items-center px-8 py-3.5 rounded-2xl bg-gradient-to-r from-teal-500 to-teal-600 text-white font-semibold shadow-lg shadow-teal-500/30 hover:shadow-xl transition-all">

                    <i class="fa-solid fa-right-to-bracket mr-2"></i>
                    Login ke Dashboard
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================ --}}
{{--  PLATFORM STATS (4 angka besar)                              --}}
{{-- ============================================================ --}}
<section id="stats" class="py-12 bg-white border-y border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 fade-in">
            @foreach([
                ['label'=>'Hospitals',         'value'=> $stats['total_hospitals'], 'icon'=>'fa-hospital',    'color'=>'teal'],
                ['label'=>'Doctors',           'value'=> $stats['total_doctors'],   'icon'=>'fa-stethoscope', 'color'=>'cyan'],
                ['label'=>'Total Users',       'value'=> $stats['total_users'],     'icon'=>'fa-users',       'color'=>'blue'],
                ['label'=>'Active Patients',   'value'=> $stats['total_patients'],  'icon'=>'fa-user-injured','color'=>'purple'],
            ] as $s)
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-{{ $s['color'] }}-100 text-{{ $s['color'] }}-600 mb-3">
                    <i class="fa-solid {{ $s['icon'] }} text-xl"></i>
                </div>
                <div class="text-3xl font-bold text-gray-900">{{ $s['value'] }}</div>
                <div class="text-gray-500 mt-1">{{ $s['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================ --}}
{{--  FITUR TERKUNCI — Quick Actions (mirip user dashboard)       --}}
{{-- ============================================================ --}}
<section class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900">Fitur Tersedia</h2>
            <p class="text-gray-500 mt-1">Login untuk mengakses fitur-fitur berikut</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 fade-in">
            @foreach([
                ['icon'=>'fa-hospital',               'label'=>'Hospitals',       'color'=>'teal'],
                ['icon'=>'fa-user-doctor',             'label'=>'Doctors',         'color'=>'cyan'],
                ['icon'=>'fa-calendar-check',          'label'=>'Appointments',    'color'=>'blue'],
                ['icon'=>'fa-file-medical',            'label'=>'Medical Records', 'color'=>'purple'],
                ['icon'=>'fa-receipt',                 'label'=>'Billing',         'color'=>'amber'],
                ['icon'=>'fa-ticket',                   'label'=>'Queue',           'color'=>'rose'],
            ] as $f)
            <button onclick="requireLogin('{{ $f['label'] }}')"
                    class="relative flex flex-col items-center p-5 rounded-2xl bg-white border border-gray-100 shadow-sm hover:shadow-md transition-all group card-hover">
                <div class="w-12 h-12 rounded-xl bg-{{ $f['color'] }}-100 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <i class="fa-solid {{ $f['icon'] }} text-{{ $f['color'] }}-500 text-lg"></i>
                </div>
                <span class="text-sm font-medium text-gray-700">{{ $f['label'] }}</span>
                <span class="absolute top-2.5 right-2.5">
                    <i class="fa-solid fa-lock text-gray-200 text-xs"></i>
                </span>
            </button>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================ --}}
{{--  ABOUT SECTION (#about)                                      --}}
{{-- ============================================================ --}}
<section id="about" class="py-16 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div class="fade-in">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-teal-100 text-teal-700 text-sm font-medium mb-5">
                    <i class="fa-solid fa-info-circle mr-2"></i>
                    Tentang Kami
                </div>
                <h2 class="text-3xl font-bold text-gray-900 mb-5">
                    Platform Manajemen Kesehatan <span class="text-teal-600">Terpadu</span>
                </h2>
                <p class="text-gray-600 leading-relaxed mb-6">
                    Health Mesh adalah solusi terintegrasi untuk menghubungkan pasien, dokter, dan fasilitas kesehatan dalam satu ekosistem digital yang efisien dan mudah digunakan.
                </p>
                <ul class="space-y-3">
                    @foreach([
                        'Manajemen antrian real-time',
                        'Rekam medis digital yang aman',
                        'Sistem pembayaran terintegrasi',
                        'Laporan analitik mendalam',
                    ] as $item)
                    <li class="flex items-center gap-3 text-gray-700">
                        <div class="w-5 h-5 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-check text-teal-600 text-xs"></i>
                        </div>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="grid grid-cols-2 gap-4 fade-in">
                @foreach([
                    ['icon'=>'fa-shield-halved','title'=>'Keamanan Data',  'desc'=>'Data pasien dilindungi enkripsi tingkat enterprise', 'color'=>'teal'],
                    ['icon'=>'fa-bolt',         'title'=>'Real-time',      'desc'=>'Update antrian dan status secara langsung',          'color'=>'cyan'],
                    ['icon'=>'fa-chart-line',   'title'=>'Analytics',      'desc'=>'Dashboard laporan komprehensif untuk manajemen',     'color'=>'blue'],
                    ['icon'=>'fa-mobile-screen','title'=>'Mobile Ready',   'desc'=>'Antarmuka responsif di semua perangkat',             'color'=>'purple'],
                ] as $card)
                <div class="bg-gradient-to-br from-{{ $card['color'] }}-50 to-{{ $card['color'] }}-100/50 rounded-2xl p-5 card-hover">
                    <div class="w-10 h-10 rounded-xl bg-{{ $card['color'] }}-500 flex items-center justify-center mb-3">
                        <i class="fa-solid {{ $card['icon'] }} text-white"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-1">{{ $card['title'] }}</h4>
                    <p class="text-xs text-gray-500 leading-relaxed">{{ $card['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ============================================================ --}}
{{--  CONTACT SECTION (#contact)                                  --}}
{{-- ============================================================ --}}
<section id="contact" class="py-16 lg:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-teal-100 text-teal-700 text-sm font-medium mb-4">
                <i class="fa-solid fa-envelope mr-2"></i>
                Hubungi Kami
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Ada Pertanyaan?</h2>
            <p class="text-gray-500 mt-2">Tim kami siap membantu Anda</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6 mb-10 fade-in">
            @foreach([
                ['icon'=>'fa-location-dot', 'title'=>'Alamat',   'detail'=>'Jl. Kesehatan No. 123, Jakarta 12345',  'color'=>'teal'],
                ['icon'=>'fa-phone',        'title'=>'Telepon',  'detail'=>'+62 21 1234 5678',                      'color'=>'cyan'],
                ['icon'=>'fa-envelope',     'title'=>'Email',    'detail'=>'info@healthmesh.id',                    'color'=>'blue'],
            ] as $c)
            <div class="text-center p-6 rounded-2xl bg-gray-50 border border-gray-100 card-hover">
                <div class="w-12 h-12 rounded-xl bg-{{ $c['color'] }}-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid {{ $c['icon'] }} text-{{ $c['color'] }}-600"></i>
                </div>
                <h4 class="font-semibold text-gray-900 mb-1">{{ $c['title'] }}</h4>
                <p class="text-gray-500 text-sm">{{ $c['detail'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Contact Form (disabled untuk guest, redirect login) --}}
        <div class="max-w-xl mx-auto bg-gray-50 rounded-2xl p-8 border border-gray-100 fade-in">
            <h3 class="font-bold text-gray-900 text-lg mb-5">Kirim Pesan</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                    <input type="text" placeholder="Nama lengkap Anda"
                           onclick="requireLogin('Contact Form')"
                           readonly
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-400 cursor-pointer focus:outline-none focus:ring-2 focus:ring-teal-500/30 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" placeholder="email@contoh.com"
                           onclick="requireLogin('Contact Form')"
                           readonly
                           class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-400 cursor-pointer focus:outline-none focus:ring-2 focus:ring-teal-500/30 transition">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pesan</label>
                    <textarea rows="4" placeholder="Tulis pesan Anda..."
                              onclick="requireLogin('Contact Form')"
                              readonly
                              class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-gray-400 cursor-pointer focus:outline-none focus:ring-2 focus:ring-teal-500/30 transition resize-none"></textarea>
                </div>
                <button onclick="requireLogin('Contact Form')"
                        class="w-full py-3.5 rounded-xl bg-gradient-to-r from-teal-500 to-teal-600 text-white font-semibold hover:from-teal-600 hover:to-teal-700 transition-all shadow-lg shadow-teal-500/25 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-lock text-sm opacity-70"></i>
                    Kirim Pesan (Login diperlukan)
                </button>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================ --}}
{{--  CTA LOGIN                                                    --}}
{{-- ============================================================ --}}
<section class="py-16 bg-gradient-to-br from-teal-600 via-teal-700 to-cyan-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white fade-in">
        <i class="fa-solid fa-lock-open text-4xl mb-4 opacity-80"></i>
        <h2 class="text-3xl font-bold mb-4">Siap Mengakses Semua Fitur?</h2>
        <p class="text-teal-100 text-lg max-w-2xl mx-auto mb-8">
            Login sekarang untuk mengelola rumah sakit, dokter, pasien, antrian, rekam medis, dan lebih banyak lagi.
        </p>
        <a href="/login"
           class="inline-flex items-center gap-2 px-10 py-4 rounded-2xl bg-white text-teal-600 font-bold hover:bg-teal-50 transition-all shadow-xl">

            <i class="fa-solid fa-right-to-bracket"></i>
            Login ke Dashboard
        </a>
    </div>
</section>

{{-- ============================================================ --}}
{{--  FOOTER (identik exampleProject)                             --}}
{{-- ============================================================ --}}
<footer class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

            {{-- Brand --}}
            <div class="space-y-4">
                <a href="{{ url('/') }}" class="flex items-center space-x-2">
                    <div class="w-10 h-10 bg-gradient-to-br from-teal-400 to-teal-500 rounded-xl flex items-center justify-center">
                        <i class="fa-solid fa-stethoscope text-white text-lg"></i>
                    </div>
                    <span class="text-xl font-bold">Health Mesh</span>
                </a>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Platform kesehatan terpercaya Anda. Menghubungkan pasien dengan dokter dan rumah sakit terbaik.
                </p>
                <div class="flex space-x-3">
                    @foreach(['facebook-f','twitter','instagram','linkedin-in'] as $social)
                    <a href="#" class="w-9 h-9 rounded-lg bg-gray-700 hover:bg-teal-500 flex items-center justify-center transition-colors">
                        <i class="fab fa-{{ $social }} text-sm"></i>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Quick Links --}}
            <div>
                <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2">
                    <li><a href="#home" onclick="smoothScroll('home'); return false;" class="text-gray-400 hover:text-teal-400 transition-colors">Home</a></li>
                    <li><a href="#" onclick="requireLogin('Hospitals'); return false;" class="text-gray-400 hover:text-teal-400 transition-colors">Hospitals</a></li>
                    <li><a href="#" onclick="requireLogin('Doctors'); return false;" class="text-gray-400 hover:text-teal-400 transition-colors">Doctors</a></li>
                    <li><a href="#about" onclick="smoothScroll('about'); return false;" class="text-gray-400 hover:text-teal-400 transition-colors">About Us</a></li>
                    <li><a href="#contact" onclick="smoothScroll('contact'); return false;" class="text-gray-400 hover:text-teal-400 transition-colors">Contact</a></li>
                </ul>
            </div>

            {{-- Services --}}
            <div>
                <h3 class="text-lg font-semibold mb-4">Services</h3>
                <ul class="space-y-2">
                    @foreach(['Online Appointment','Medical Records','Health Consultation','Emergency Care','Lab Tests'] as $svc)
                    <li class="text-gray-400">{{ $svc }}</li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact Info --}}
            <div>
                <h3 class="text-lg font-semibold mb-4">Contact</h3>
                <ul class="space-y-3">
                    <li class="flex items-start space-x-3">
                        <i class="fa-solid fa-location-dot text-teal-400 mt-1 flex-shrink-0"></i>
                        <span class="text-gray-400 text-sm">Jl. Kesehatan No. 123, Jakarta 12345</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i class="fa-solid fa-phone text-teal-400 flex-shrink-0"></i>
                        <span class="text-gray-400 text-sm">+62 21 1234 5678</span>
                    </li>
                    <li class="flex items-center space-x-3">
                        <i class="fa-solid fa-envelope text-teal-400 flex-shrink-0"></i>
                        <span class="text-gray-400 text-sm">info@healthmesh.id</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-10 pt-8 text-center text-gray-400 text-sm">
            <p>&copy; {{ date('Y') }} Health Mesh. All rights reserved. Made with ❤️ for better healthcare.</p>
        </div>
    </div>
</footer>

{{-- ============================================================ --}}
{{--  TOAST                                                        --}}
{{-- ============================================================ --}}
<div id="toast" class="bg-white border border-gray-200 rounded-2xl shadow-2xl px-5 py-4 flex items-start gap-3 max-w-xs">
    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-teal-500 to-teal-600 flex items-center justify-center flex-shrink-0">
        <i class="fa-solid fa-lock text-white text-sm"></i>
    </div>
    <div>
        <p class="font-semibold text-gray-900 text-sm" id="toastTitle">Login Diperlukan</p>
        <p class="text-gray-500 text-xs mt-0.5">Mengarahkan ke halaman login…</p>
        <a href="/login"
           class="mt-2 inline-block text-xs font-semibold text-teal-600 hover:underline">
            Login sekarang →
        </a>

    </div>
</div>

{{-- ============================================================ --}}
{{--  SCRIPTS                                                      --}}
{{-- ============================================================ --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.5/cdn.min.js" defer></script>
<script>
// ---- Redirect to login ----
function requireLogin(feature) {
    const toast = document.getElementById('toast');
    document.getElementById('toastTitle').textContent = '🔒 ' + feature;
    toast.classList.add('show');
    setTimeout(() => { window.location.href = '/login'; }, 1800);

    setTimeout(() => toast.classList.remove('show'), 5000);
}

// ---- Sparklines ----
function spark(id, data, color) {
    const ctx = document.getElementById(id);
    if (!ctx) return;
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.map((_,i) => i),
            datasets: [{ data, borderColor: color, backgroundColor: color+'26', borderWidth: 2, fill: true, tension: 0.4, pointRadius: 0 }],
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { enabled: false } },
            scales: { x: { display: false }, y: { display: false } },
            animation: { duration: 0 },
        },
    });
}
spark('spkVisits',  [2,4,6,8,10,12], '#06b6d4');
spark('spkRevenue', [5,10,15,20,25,30], '#f59e0b');
spark('spkQueues',  [1,3,5,7,9,11], '#f43f5e');
spark('spkPending', [1,2,3,4,5,6], '#f97316');

// ---- Revenue Bar Chart ----
const rLabels = @json($revenueLabels ?: ['No Data']);
const rData   = @json($revenueData   ?: [0]);
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: rLabels,
        datasets: [{
            label: 'Revenue (Rp)',
            data: rData,
            backgroundColor: ['#14b8a6','#0ea5e9','#8b5cf6','#f59e0b','#ef4444'],
            borderRadius: 10,
        }],
    },
    options: {
        responsive: true, maintainAspectRatio: false,
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

// ---- Visits Line Chart ----
const vLabels = @json($visitsLabels);
const vData   = @json($visitsData);
new Chart(document.getElementById('visitsChart'), {
    type: 'line',
    data: {
        labels: vLabels,
        datasets: [{
            label: 'Visits',
            data: vData,
            borderColor: '#14b8a6',
            backgroundColor: 'rgba(20,184,166,.1)',
            borderWidth: 2.5,
            fill: true,
            tension: 0.4,
            pointRadius: 5,
            pointBackgroundColor: '#14b8a6',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
        }],
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 } } },
            y: { grid: { color: '#f3f4f6' }, ticks: { font: { size: 11 } } },
        },
    },
});

function smoothScroll(id) {
    const target = document.getElementById(id);
    if (!target) return;
    const offset = 72; // tinggi navbar sticky (px)
    const top = target.getBoundingClientRect().top + window.scrollY - offset;
    window.scrollTo({ top, behavior: 'smooth' });
}

</script>
</body>
</html>
