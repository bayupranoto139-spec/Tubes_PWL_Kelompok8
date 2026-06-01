<?php
// Mendapatkan nama file saat ini untuk mendeteksi menu aktif
$current_page = basename($_SERVER['PHP_SELF']);

// Simulasi Data Dokter Aktif (Idealnya diambil dari session $_SESSION['user_name'])
$doctor_name = "Dr. Budi Santoso";
$doctor_role = "Spesialis Penyakit Dalam";
?>

<!-- Sidebar Container -->
<aside class="w-64 bg-slate-900 text-white flex flex-col fixed h-full z-10 shadow-xl">
    <!-- Brand / Logo -->
    <div class="p-5 flex items-center gap-3 border-b border-slate-800">
        <div class="bg-blue-600 p-2 rounded-lg text-white">
            <i class="fa-solid : fa-heart-pulse text-xl"></i>
        </div>
        <div>
            <h1 class="text-lg font-bold tracking-wider">HealthMesh</h1>
            <p class="text-xs text-slate-400">Doctor Portal v1.0</p>
        </div>
    </div>

    <!-- Profil Singkat Dokter -->
    <div class="p-4 mx-4 my-4 bg-slate-800/50 rounded-xl flex items-center gap-3 border border-slate-700/30">
        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center font-bold text-white shadow-md">
            BS
        </div>
        <div class="overflow-hidden">
            <h2 class="text-sm font-semibold truncate"><?= $doctor_name; ?></h2>
            <p class="text-xs text-slate-400 truncate"><?= $doctor_role; ?></p>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 space-y-1">
    
    <a href="{{ route('doctor.dashboard') }}" 
       class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('doctor.dashboard') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
        <i class="fa-solid fa-chart-pie w-5"></i> Dashboard
    </a>
    
    <a href="{{ route('doctor.today') }}" 
       class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('doctor.today') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
        <i class="fa-solid fa-calendar-day w-5"></i> Jadwal Hari Ini
    </a>
    
    <a href="{{ route('doctor.schedule') }}" 
       class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('doctor.schedule') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
        <i class="fa-solid fa-calendar-alt w-5"></i> Semua Jadwal
    </a>
    
    <a href="{{ route('doctor.prescription') }}" 
       class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('doctor.prescription') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
        <i class="fa-solid fa-prescription-bottle-medical w-5"></i> Resep Obat
    </a>
    
    <a href="{{ route('doctor.profile') }}" 
       class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('doctor.profile') ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
        <i class="fa-solid fa-user-md w-5"></i> Profil Saya
    </a>
    
    </nav>
    
    <!-- Footer Sidebar / Logout -->
    <div class="p-4 border-t border-slate-800">
        <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-red-400 hover:bg-red-500/10 transition-all">
            <i class="fa-solid : fa-right-from-bracket w-5"></i> Keluar Sistem
        </a>
    </div>
</aside>

<!-- Main Content Area Wrapper (Memberikan margin-left agar tidak tertutup sidebar fixed) -->
<main class="flex-1 ml-64 min-h-screen flex flex-col bg-slate-50">
    <!-- Topbar Header -->
    <header class="bg-white border-b border-gray-200 h-16 flex items-center justify-between px-8 sticky top-0 z-0">
        <div class="flex items-center gap-2">
            <span class="text-sm text-gray-500">Pusat Navigasi</span>
            <i class="fa-solid : fa-chevron-right text-xs text-gray-400"></i>
            <span class="text-sm font-semibold text-slate-800 capitalize"><?= str_replace('.php', '', $current_page); ?></span>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right">
                <p class="text-xs text-gray-400">Senin, 1 Juni 2026</p>
            </div>
        </div>
    </header>
    
    <!-- Inner Content Container -->
    <div class="p-8 flex-1">