@php
    $doctorUser = auth()->user();
    $doctorName = $doctorUser?->name ?? 'Dr. Budi Santoso';
    $doctorRole = $doctorUser?->doctor?->specialization?->name ?? 'Spesialis Penyakit Dalam';
    $initials = collect(explode(' ', $doctorName))->map(fn($w) => strtoupper($w[0]))->take(2)->join('');
@endphp

{{-- ====================== SIDEBAR ====================== --}}
<aside class="w-[17.5rem] bg-[#111827] text-white flex flex-col fixed h-full z-20 shadow-2xl">

    {{-- Brand --}}
    <div class="px-6 py-5 flex items-center gap-3 border-b border-white/10">
        <div class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center"
             style="background:linear-gradient(135deg,#14b8a6,#06b6d4)">
            <i class="fa-solid fa-heart-pulse text-white text-base"></i>
        </div>
        <div>
            <span class="text-base font-bold tracking-wide text-white">HealthMesh</span>
            <p class="text-[11px] text-gray-400 leading-tight">Doctor Portal</p>
        </div>
    </div>

    {{-- Doctor mini-profile --}}
    <div class="mx-4 mt-4 mb-2 p-3 rounded-xl bg-white/5 border border-white/10 flex items-center gap-3">
        <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold text-white flex-shrink-0"
             style="background:linear-gradient(135deg,#14b8a6,#06b6d4)">
            {{ $initials }}
        </div>
        <div class="min-w-0">
            <p class="text-sm font-semibold text-white truncate">{{ $doctorName }}</p>
            <p class="text-[11px] text-gray-400 truncate">{{ $doctorRole }}</p>
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
                ['route' => 'doctor.dashboard',    'icon' => 'fa-chart-pie',                     'label' => 'Dashboard'],
                ['route' => 'doctor.today',        'icon' => 'fa-calendar-day',                  'label' => 'Jadwal Hari Ini'],
                ['route' => 'doctor.schedule',     'icon' => 'fa-calendar-alt',                  'label' => 'Semua Jadwal'],
                ['route' => 'doctor.prescription', 'icon' => 'fa-prescription-bottle-medical',   'label' => 'Resep Obat'],
                ['route' => 'doctor.profile',      'icon' => 'fa-user-doctor',                   'label' => 'Profil Saya'],
            ];
        @endphp

        @foreach($navItems as $item)
            @php $isActive = request()->routeIs($item['route']); @endphp
            <a href="{{ route($item['route']) }}"
               class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150
                      {{ $isActive
                          ? 'text-white shadow-lg'
                          : 'text-gray-400 hover:bg-white/5 hover:text-white' }}"
               @if($isActive) style="background:linear-gradient(90deg,#14b8a6,#06b6d4)" @endif>
                <i class="fa-solid {{ $item['icon'] }} w-4 text-center text-[15px]
                           {{ $isActive ? 'text-white' : 'text-gray-500 group-hover:text-teal-400' }}"></i>
                <span>{{ $item['label'] }}</span>
                @if($isActive)
                    <span class="ml-auto w-1.5 h-1.5 rounded-full bg-white/80"></span>
                @endif
            </a>
        @endforeach

    </nav>

    {{-- Logout (tanpa alert/confirm) --}}
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

{{-- ====================== TOPBAR + MAIN WRAPPER ====================== --}}
<div class="flex-1 ml-[17.5rem] flex flex-col min-h-screen">

    {{-- Topbar --}}
    <header class="bg-white border-b border-gray-200 h-14 flex items-center justify-between px-6 sticky top-0 z-10 shadow-sm">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-1.5 text-sm text-gray-500">
            <i class="fa-solid fa-house text-xs text-gray-400"></i>
            <i class="fa-solid fa-chevron-right text-[10px] text-gray-300"></i>
            <span class="font-semibold text-gray-700 capitalize">
                @php
                    $pageNames = [
                        'doctor.dashboard'    => 'Dashboard',
                        'doctor.today'        => 'Jadwal Hari Ini',
                        'doctor.schedule'     => 'Semua Jadwal',
                        'doctor.prescription' => 'Resep Obat',
                        'doctor.profile'      => 'Profil Saya',
                    ];
                    echo $pageNames[request()->route()->getName()] ?? 'Panel Dokter';
                @endphp
            </span>
        </nav>

        {{-- Right side --}}
        <div class="flex items-center gap-4">
            {{-- Tanggal tetap ada, profile doctor dihilangkan --}}
            <div class="text-right hidden sm:block">
                <p class="text-xs text-gray-400">{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
            </div>
        </div>
    </header>

    {{-- Page content --}}
    <main class="flex-1 p-6 bg-gray-50">
