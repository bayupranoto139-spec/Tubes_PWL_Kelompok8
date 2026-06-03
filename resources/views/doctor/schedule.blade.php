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
                <p class="text-2xl font-extrabold text-gray-800">2</p>
                <p class="text-xs text-teal-500 font-medium">Hari per minggu</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-clock text-blue-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Total Jam Praktik</p>
                <p class="text-2xl font-extrabold text-gray-800">8 Jam</p>
                <p class="text-xs text-blue-500 font-medium">Per minggu</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-users text-amber-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Kuota Mingguan</p>
                <p class="text-2xl font-extrabold text-gray-800">40</p>
                <p class="text-xs text-amber-500 font-medium">Pasien per minggu</p>
            </div>
        </div>
    </div>

    {{-- Schedule cards grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        @php
            $schedules = [
                ['day' => 'Senin', 'session' => 'Pagi', 'start' => '08:00', 'end' => '12:00', 'quota' => 20, 'active' => true],
                ['day' => 'Rabu',  'session' => 'Pagi', 'start' => '08:00', 'end' => '12:00', 'quota' => 20, 'active' => true],
                ['day' => 'Jumat', 'session' => 'Sore', 'start' => '14:00', 'end' => '17:00', 'quota' => 15, 'active' => false],
                ['day' => 'Sabtu', 'session' => 'Pagi', 'start' => '08:00', 'end' => '11:00', 'quota' => 10, 'active' => false],
            ];
        @endphp

        @foreach($schedules as $sched)
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <span class="text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-lg bg-blue-50 text-blue-600">Hari Praktik</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full {{ $sched['active'] ? 'bg-emerald-500' : 'bg-gray-300' }}"></span>
                    <span class="text-xs font-medium {{ $sched['active'] ? 'text-emerald-600' : 'text-gray-400' }}">
                        {{ $sched['active'] ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </div>
            </div>

            <h3 class="text-xl font-bold text-gray-900 mb-4">{{ $sched['day'] }} ({{ $sched['session'] }})</h3>

            <div class="space-y-3">
                <div class="flex items-center justify-between py-2.5 border-b border-gray-100 text-sm">
                    <span class="text-gray-400 flex items-center gap-2">
                        <i class="fa-solid fa-clock text-xs"></i> Jam Operasional
                    </span>
                    <strong class="text-gray-800">{{ $sched['start'] }} – {{ $sched['end'] }} WIB</strong>
                </div>
                <div class="flex items-center justify-between py-2.5 border-b border-gray-100 text-sm">
                    <span class="text-gray-400 flex items-center gap-2">
                        <i class="fa-solid fa-users text-xs"></i> Kuota Maksimal
                    </span>
                    <strong class="text-gray-800">{{ $sched['quota'] }} Pasien</strong>
                </div>
                <div class="flex items-center justify-between py-2.5 text-sm">
                    <span class="text-gray-400 flex items-center gap-2">
                        <i class="fa-solid fa-hourglass text-xs"></i> Durasi Sesi
                    </span>
                    <strong class="text-gray-800">
                        @php
                            [$sh, $sm] = explode(':', $sched['start']);
                            [$eh, $em] = explode(':', $sched['end']);
                            $dur = ((int)$eh * 60 + (int)$em) - ((int)$sh * 60 + (int)$sm);
                        @endphp
                        {{ $dur }} Menit
                    </strong>
                </div>
            </div>
        </div>
        @endforeach

    </div>

</div>

    </main>
</div>
</div>
</body>
</html>
