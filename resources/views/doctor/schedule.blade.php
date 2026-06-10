@include('doctor.includes.header')
@include('doctor.includes.sidebar')

<div class="max-w-7xl mx-auto space-y-6">

    {{-- Page header --}}
    <div>
        <h1 class="text-2xl font-extrabold text-gray-800">Semua Jadwal Praktik</h1>
        <p class="text-sm text-gray-400 mt-1">Konfigurasi waktu operasional dokter di rumah sakit.</p>
    </div>

    {{-- Summary stat cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-teal-50 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-calendar-check text-teal-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Hari Aktif</p>
                <p class="text-2xl font-extrabold text-gray-800">{{ $stats['active_days'] }}</p>
                <p class="text-xs text-teal-500 font-medium">Hari per minggu</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-clock text-blue-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Total Jam Praktik</p>
                <p class="text-2xl font-extrabold text-gray-800">{{ $stats['total_hours'] }} Jam</p>
                <p class="text-xs text-blue-500 font-medium">Per minggu</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-users text-amber-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Kuota Mingguan</p>
                <p class="text-2xl font-extrabold text-gray-800">{{ $stats['weekly_quota'] }}</p>
                <p class="text-xs text-amber-500 font-medium">Pasien per minggu</p>
            </div>
        </div>
    </div>

    {{-- Schedule cards grid --}}
    @if($schedules->isEmpty())
        <div class="bg-white rounded-2xl p-10 shadow-sm border border-gray-200 text-center">
            <i class="fa-solid fa-calendar-xmark text-gray-300 text-4xl mb-3"></i>
            <p class="text-gray-400 font-medium">Belum ada jadwal praktik yang terdaftar.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($schedules as $sched)
                @php
                    $dayLabel  = $daysMapping[$sched->day_of_week] ?? '—';
                    $startTime = \Carbon\Carbon::parse($sched->start_time);
                    $endTime   = \Carbon\Carbon::parse($sched->end_time);
                    [$sh, $sm] = array_map('intval', explode(':', substr($sched->start_time, 0, 5)));
                    [$eh, $em] = array_map('intval', explode(':', substr($sched->end_time,   0, 5)));
                    $durMenit  = max(0, ($eh * 60 + $em) - ($sh * 60 + $sm));

                    // Tentukan sesi berdasarkan jam mulai
                    $startHour = (int) $startTime->format('H');
                    $session = match(true) {
                        $startHour < 12 => 'Pagi',
                        $startHour < 15 => 'Siang',
                        $startHour < 18 => 'Sore',
                        default         => 'Malam',
                    };
                @endphp

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-lg bg-blue-50 text-blue-600">Hari Praktik</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full {{ $sched->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                            <span class="text-xs font-medium {{ $sched->is_active ? 'text-emerald-600' : 'text-gray-400' }}">
                                {{ $sched->is_active ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </div>
                    </div>

                    <h3 class="text-xl font-bold text-gray-900 mb-4">{{ $dayLabel }} ({{ $session }})</h3>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between py-2.5 border-b border-gray-100 text-sm">
                            <span class="text-gray-400 flex items-center gap-2">
                                <i class="fa-solid fa-clock text-xs"></i> Jam Operasional
                            </span>
                            <strong class="text-gray-800">
                                {{ $startTime->format('H:i') }} – {{ $endTime->format('H:i') }} WIB
                            </strong>
                        </div>
                        <div class="flex items-center justify-between py-2.5 border-b border-gray-100 text-sm">
                            <span class="text-gray-400 flex items-center gap-2">
                                <i class="fa-solid fa-users text-xs"></i> Kuota Maksimal
                            </span>
                            <strong class="text-gray-800">{{ $sched->max_patients }} Pasien</strong>
                        </div>
                        <div class="flex items-center justify-between py-2.5 text-sm">
                            <span class="text-gray-400 flex items-center gap-2">
                                <i class="fa-solid fa-hourglass text-xs"></i> Durasi Sesi
                            </span>
                            <strong class="text-gray-800">{{ $durMenit }} Menit</strong>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>

    </main>
</div>
</div>
</body>
</html>