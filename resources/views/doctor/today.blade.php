@include('doctor.includes.header')
@include('doctor.includes.sidebar')

<div class="max-w-7xl mx-auto space-y-4">

    {{-- Page header --}}
    <div>
        <h1 class="text-xl sm:text-2xl font-extrabold text-gray-800">Jadwal Periksa Hari Ini</h1>
        <p class="text-sm text-gray-400 mt-1">
            {{ now()->locale('id')->isoFormat('D MMMM Y') }}
        </p>
    </div>

    {{-- Flash messages --}}
    @foreach(['success' => ['emerald','circle-check'], 'error' => ['red','circle-exclamation'], 'info' => ['blue','circle-info']] as $type => [$color, $icon])
        @if(session($type))
            <div class="flex items-center gap-3 rounded-2xl bg-{{ $color }}-50 border border-{{ $color }}-200 px-4 py-3 text-sm text-{{ $color }}-700">
                <i class="fa-solid fa-{{ $icon }}"></i> {{ session($type) }}
            </div>
        @endif
    @endforeach

    {{-- Next Queue Banner --}}
    @if ($nextQueue)
        @php
            $nqPatient = $nextQueue->appointment?->patientEnrollment?->user?->name ?? 'Pasien';
            $nqType    = $nextQueue->type === 'walk_in' ? 'Walk-in' : 'Appointment';
            $nqBadge   = $nextQueue->type === 'walk_in' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700';
        @endphp
        <div class="flex items-center gap-3 rounded-2xl bg-gradient-to-r from-teal-50 to-cyan-50 border border-teal-200 px-4 py-3.5">
            <div class="w-10 h-10 rounded-xl bg-teal-500 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-bell text-white"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[11px] text-teal-600 font-semibold uppercase tracking-wide">Pasien Berikutnya</p>
                <p class="text-sm font-bold text-gray-800 truncate">No. {{ $nextQueue->queue_number }} — {{ $nqPatient }}</p>
                <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                    <span class="text-[11px] px-2 py-0.5 rounded-full font-medium {{ $nqBadge }}">{{ $nqType }}</span>
                    <span class="text-[11px] text-gray-500">
                        {{ \Carbon\Carbon::parse($nextQueue->appointment?->scheduled_at)->format('H:i') }}
                        · {{ ucfirst(str_replace('_', ' ', $nextQueue->status)) }}
                    </span>
                </div>
            </div>
        </div>
    @endif

    {{-- Stats row --}}
    @php
        $waitingCount = $todayAppointments->where('status', 'scheduled')->count();
        $doneCount    = $todayAppointments->where('status', 'completed')->count();
        $totalCount   = $todayAppointments->count();
        $hasRecordCount = $todayAppointments->filter(fn($a) => $a->medicalRecord)->count();
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
        @foreach([
            ['Menunggu',       $waitingCount,    'bg-blue-50',   'text-blue-500',   'fa-hourglass-half'],
            ['Selesai',        $doneCount,       'bg-emerald-50','text-emerald-500','fa-circle-check'],
            ['Ada Rekam',      $hasRecordCount,  'bg-violet-50', 'text-violet-500', 'fa-file-medical'],
            ['Total Antrian',  $totalCount,      'bg-purple-50', 'text-purple-500', 'fa-users'],
        ] as [$label, $val, $bg, $fg, $icon])
            <div class="bg-white rounded-2xl p-3.5 shadow-sm border border-gray-200 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl {{ $bg }} flex items-center justify-center shrink-0">
                    <i class="fa-solid {{ $icon }} {{ $fg }} text-sm"></i>
                </div>
                <div>
                    <p class="text-[11px] text-gray-400 leading-tight">{{ $label }}</p>
                    <p class="text-xl font-bold text-gray-800">{{ $val }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ===== MOBILE: Card List (hidden md+) ===== --}}
    <div class="md:hidden space-y-3">
        @forelse($todayAppointments as $apt)
            @php
                $patientName    = optional(optional($apt->patientEnrollment)->user)->name;
                $rm             = optional($apt->patientEnrollment)->medical_record_number;
                $hasMedRecord   = (bool) $apt->medicalRecord;
                $hasPrescription= $hasMedRecord && $apt->medicalRecord->prescriptions->isNotEmpty();
                $isCompleted    = $apt->status === 'completed';
                $hasBill        = (bool) $apt->bill;
                $canComplete    = !$isCompleted && $hasMedRecord;
                $queue          = $apt->queue;
            @endphp
            <div class="bg-white rounded-2xl border border-gray-200 p-4 shadow-sm space-y-3">

                {{-- Top: queue number + patient name + time --}}
                <div class="flex items-start justify-between gap-2">
                    <div class="flex items-center gap-2.5">
                        @if($queue)
                            <div class="w-9 h-9 rounded-xl bg-gray-100 flex items-center justify-center shrink-0">
                                <span class="text-sm font-extrabold text-gray-700">
                                    {{ str_pad($queue->queue_number, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <p class="font-bold text-gray-900 text-sm">{{ $patientName ?? '-' }}</p>
                            <p class="text-[11px] text-gray-400">RM: {{ $rm ?? '-' }}</p>
                        </div>
                    </div>
                    <span class="text-xs font-semibold text-gray-500 shrink-0">
                        {{ \Carbon\Carbon::parse($apt->scheduled_at)->format('H:i') }} WIB
                    </span>
                </div>

                {{-- Badges row --}}
                <div class="flex flex-wrap gap-1.5">
                    @if($queue)
                        <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold
                            {{ $queue->type === 'walk_in' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ $queue->type === 'walk_in' ? 'Walk-in' : 'Appt' }}
                        </span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold
                            {{ match($queue->status) {
                                'waiting'     => 'bg-gray-100 text-gray-600',
                                'called'      => 'bg-sky-100 text-sky-700',
                                'in_progress' => 'bg-yellow-100 text-yellow-700',
                                'completed'   => 'bg-green-100 text-green-700',
                                'skipped'     => 'bg-red-100 text-red-700',
                                default       => 'bg-gray-100 text-gray-600',
                            } }}">
                            {{ ucfirst(str_replace('_', ' ', $queue->status)) }}
                        </span>
                    @endif
                    <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold
                        {{ $hasMedRecord ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' }}">
                        {{ $hasMedRecord ? 'Ada Rekam' : 'Belum Rekam' }}
                    </span>
                    @if($hasMedRecord)
                        <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold bg-blue-50 text-blue-700">
                            {{ $hasPrescription ? $apt->medicalRecord->prescriptions->count().' resep' : 'Tanpa resep' }}
                        </span>
                    @endif
                </div>

                {{-- Keluhan --}}
                <p class="text-xs text-gray-500 italic bg-gray-50 rounded-xl px-3 py-2 line-clamp-2">
                    "{{ $apt->complaint }}"
                </p>

                {{-- Actions --}}
                @if($isCompleted)
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl">✓ Selesai</span>
                        @if($hasBill)
                            <span class="text-xs text-gray-400"><i class="fa-solid fa-receipt mr-1"></i>Tagihan dibuat</span>
                        @endif
                    </div>
                @else
                    <div class="flex flex-wrap gap-2">
                        @if($canComplete)
                            <a href="{{ route('doctor.prescription') }}"
                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-white border border-blue-300 text-blue-600 font-semibold text-xs hover:bg-blue-50 transition-all">
                                <i class="fa-solid fa-capsules text-[10px]"></i>
                                {{ $hasPrescription ? 'Tambah Resep' : 'Beri Resep' }}
                            </a>
                            <form method="POST" action="{{ route('doctor.appointments.complete', $apt->id) }}" class="m-0">
                                @csrf
                                <button type="submit"
                                    class="px-3 py-2 rounded-xl bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold text-xs hover:from-teal-600 hover:to-cyan-700 shadow-sm flex items-center gap-1.5">
                                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                                    Selesaikan & Tagihan
                                </button>
                            </form>
                        @else
                            <a href="{{ route('doctor.medical-records.create', $apt->id) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-gradient-to-r from-violet-500 to-purple-600 text-white font-semibold text-xs hover:from-violet-600 hover:to-purple-700 shadow-sm transition-all">
                                <i class="fa-solid fa-file-medical text-[10px]"></i>
                                Isi Rekam Medis
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-200 p-8 text-center text-sm text-gray-400">
                <i class="fa-solid fa-calendar-xmark text-2xl text-gray-300 mb-2 block"></i>
                Belum ada antrean untuk hari ini.
            </div>
        @endforelse
    </div>

    {{-- ===== DESKTOP: Table (hidden on mobile) ===== --}}
    <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Daftar Pasien Antrean</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        <th class="px-4 py-3 text-left">No.</th>
                        <th class="px-4 py-3 text-left">Pasien</th>
                        <th class="px-4 py-3 text-left">Jam</th>
                        <th class="px-4 py-3 text-left">Keluhan</th>
                        <th class="px-4 py-3 text-left">Rekam Medis</th>
                        <th class="px-4 py-3 text-left">Resep</th>
                        <th class="px-4 py-3 text-left">Status / Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700">
                    @forelse($todayAppointments as $apt)
                        @php
                            $patientName    = optional(optional($apt->patientEnrollment)->user)->name;
                            $rm             = optional($apt->patientEnrollment)->medical_record_number;
                            $hasMedRecord   = (bool) $apt->medicalRecord;
                            $hasPrescription= $hasMedRecord && $apt->medicalRecord->prescriptions->isNotEmpty();
                            $isCompleted    = $apt->status === 'completed';
                            $hasBill        = (bool) $apt->bill;
                            $canComplete    = !$isCompleted && $hasMedRecord;
                            $queue          = $apt->queue;
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4">
                                @if($queue)
                                    <div class="flex flex-col items-start gap-1">
                                        <span class="font-bold text-gray-900">{{ str_pad($queue->queue_number, 2, '0', STR_PAD_LEFT) }}</span>
                                        <span class="text-[10px] px-1.5 py-0.5 rounded-full font-semibold
                                            {{ $queue->type === 'walk_in' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                                            {{ $queue->type === 'walk_in' ? 'Walk-in' : 'Appt' }}
                                        </span>
                                        <span class="text-[10px] px-1.5 py-0.5 rounded-full font-semibold
                                            {{ match($queue->status) {
                                                'waiting'     => 'bg-gray-100 text-gray-600',
                                                'called'      => 'bg-sky-100 text-sky-700',
                                                'in_progress' => 'bg-yellow-100 text-yellow-700',
                                                'completed'   => 'bg-green-100 text-green-700',
                                                'skipped'     => 'bg-red-100 text-red-700',
                                                default       => 'bg-gray-100 text-gray-600',
                                            } }}">
                                            {{ ucfirst(str_replace('_',' ',$queue->status)) }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 text-xs font-bold shrink-0">
                                        {{ $patientName ? strtoupper(substr($patientName,0,1)) : '?' }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">{{ $patientName ?? '-' }}</p>
                                        <p class="text-xs text-gray-400">{{ $rm ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-gray-500 text-xs">{{ \Carbon\Carbon::parse($apt->scheduled_at)->format('H:i') }} WIB</td>
                            <td class="px-4 py-4 text-xs italic text-gray-500 max-w-[140px] truncate">"{{ $apt->complaint }}"</td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold
                                    {{ $hasMedRecord ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' }}">
                                    <i class="fa-solid {{ $hasMedRecord ? 'fa-check' : 'fa-xmark' }} text-[9px]"></i>
                                    {{ $hasMedRecord ? 'Ada' : 'Belum' }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                @if(!$hasMedRecord)
                                    <span class="text-xs text-gray-400">-</span>
                                @elseif($hasPrescription)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                                        <i class="fa-solid fa-capsules text-[9px]"></i>
                                        {{ $apt->medicalRecord->prescriptions->count() }} obat
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400">Tanpa resep</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if($isCompleted)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai
                                    </span>
                                    @if($hasBill)
                                        <p class="text-[10px] text-gray-400 mt-1"><i class="fa-solid fa-receipt"></i> Tagihan dibuat</p>
                                    @endif
                                @else
                                    <div class="flex flex-col gap-1.5">
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span> Menunggu
                                        </span>
                                        @if($canComplete)
                                            <a href="{{ route('doctor.prescription') }}"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-white border border-blue-300 text-blue-600 font-semibold text-xs hover:bg-blue-50 transition-all">
                                                <i class="fa-solid fa-capsules text-[9px]"></i>
                                                {{ $hasPrescription ? 'Tambah Resep' : 'Beri Resep' }}
                                            </a>
                                            <form method="POST" action="{{ route('doctor.appointments.complete', $apt->id) }}" class="m-0">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full px-2.5 py-1.5 rounded-xl bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold text-xs hover:from-teal-600 hover:to-cyan-700 shadow-sm flex items-center gap-1">
                                                    <i class="fa-solid fa-circle-check text-[9px]"></i>
                                                    Selesaikan & Tagihan
                                                </button>
                                            </form>
                                        @else
                                            <a href="{{ route('doctor.medical-records.create', $apt->id) }}"
                                                class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl bg-gradient-to-r from-violet-500 to-purple-600 text-white font-semibold text-xs hover:from-violet-600 hover:to-purple-700 shadow-sm transition-all">
                                                <i class="fa-solid fa-file-medical text-[9px]"></i>
                                                Isi Rekam Medis
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-10 text-center text-sm text-gray-400" colspan="7">
                                Belum ada antrean untuk hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Info box --}}
    <div class="bg-blue-50 border border-blue-100 rounded-2xl px-4 py-3.5 text-xs text-blue-700">
        <p class="font-semibold mb-1"><i class="fa-solid fa-circle-info mr-1"></i> Alur Penyelesaian Appointment</p>
        <ol class="list-decimal list-inside space-y-0.5 text-blue-600">
            <li>Isi <strong>Rekam Medis</strong> terlebih dahulu (wajib).</li>
            <li>Tambahkan <strong>Resep Obat</strong> jika perlu (opsional).</li>
            <li>Klik <strong>Selesaikan & Buat Tagihan</strong>.</li>
        </ol>
    </div>

</div>

    </main>
</div>
</div>
</body>
</html>