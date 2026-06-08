@include('doctor.includes.header')
@include('doctor.includes.sidebar')

<div class="max-w-7xl mx-auto space-y-6">

    {{-- Page header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-800">Jadwal Periksa Hari Ini</h1>
            <p class="text-sm text-gray-400 mt-1">
                Daftar pasien yang terdaftar dalam antrean periksa per {{ now()->locale('id')->isoFormat('D MMMM Y') }}.
            </p>
        </div>
    </div>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="flex items-center gap-3 rounded-2xl bg-emerald-50 border border-emerald-200 px-5 py-3 text-sm text-emerald-700">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="flex items-center gap-3 rounded-2xl bg-red-50 border border-red-200 px-5 py-3 text-sm text-red-700">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ session('error') }}
        </div>
    @endif
    @if (session('info'))
        <div class="flex items-center gap-3 rounded-2xl bg-blue-50 border border-blue-200 px-5 py-3 text-sm text-blue-700">
            <i class="fa-solid fa-circle-info"></i>
            {{ session('info') }}
        </div>
    @endif

    {{-- Next Queue Banner --}}
    @if ($nextQueue)
        @php
            $nqPatient = $nextQueue->appointment?->patientEnrollment?->user?->name ?? 'Pasien';
            $nqType    = $nextQueue->type === 'walk_in' ? 'Walk-in' : 'Appointment';
            $nqBadge   = $nextQueue->type === 'walk_in'
                ? 'bg-amber-100 text-amber-700'
                : 'bg-blue-100 text-blue-700';
        @endphp
        <div class="flex items-center gap-4 rounded-2xl bg-gradient-to-r from-teal-50 to-cyan-50 border border-teal-200 px-5 py-4">
            <div class="w-12 h-12 rounded-xl bg-teal-500 flex items-center justify-center shrink-0">
                <i class="fa-solid fa-bell text-white text-lg"></i>
            </div>
            <div class="flex-1">
                <p class="text-xs text-teal-600 font-semibold uppercase tracking-wide mb-0.5">Pasien Berikutnya di Antrian</p>
                <p class="text-base font-bold text-gray-800">
                    No. {{ $nextQueue->queue_number }} — {{ $nqPatient }}
                </p>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-xs px-2 py-0.5 rounded-full font-medium {{ $nqBadge }}">{{ $nqType }}</span>
                    <span class="text-xs text-gray-500">
                        Jam {{ \Carbon\Carbon::parse($nextQueue->appointment?->scheduled_at)->format('H:i') }}
                        · Status: <span class="font-medium capitalize">{{ $nextQueue->status }}</span>
                    </span>
                </div>
            </div>
        </div>
    @endif

    {{-- Stats mini row --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @php
            $waitingCount   = $todayAppointments->where('status', 'scheduled')->count();
            $doneCount      = $todayAppointments->where('status', 'completed')->count();
            $totalCount     = $todayAppointments->count();
        @endphp

        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                <i class="fa-solid fa-hourglass-half text-blue-500"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Menunggu</p>
                <p class="text-xl font-bold text-gray-800">{{ $waitingCount }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                <i class="fa-solid fa-circle-check text-emerald-500"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Selesai</p>
                <p class="text-xl font-bold text-gray-800">{{ $doneCount }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-violet-50 flex items-center justify-center">
                <i class="fa-solid fa-file-medical text-violet-500"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Ada Rekam Medis</p>
                <p class="text-xl font-bold text-gray-800">
                    {{ $todayAppointments->filter(fn($a) => $a->medicalRecord)->count() }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-purple-50 flex items-center justify-center">
                <i class="fa-solid fa-users text-purple-500"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Total Antrian</p>
                <p class="text-xl font-bold text-gray-800">{{ $totalCount }}</p>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Daftar Pasien Antrean</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        <th class="px-6 py-3 text-left">No. Antrian</th>
                        <th class="px-6 py-3 text-left">Nama Pasien</th>
                        <th class="px-6 py-3 text-left">Jam Periksa</th>
                        <th class="px-6 py-3 text-left">Keluhan</th>
                        <th class="px-6 py-3 text-left">Rekam Medis</th>
                        <th class="px-6 py-3 text-left">Resep</th>
                        <th class="px-6 py-3 text-left">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50 text-gray-700">
                    @forelse($todayAppointments as $i => $apt)
                        @php
                            $patientName   = optional(optional($apt->patientEnrollment)->user)->name;
                            $rm            = optional($apt->patientEnrollment)->medical_record_number;
                            $hasMedRecord  = (bool) $apt->medicalRecord;
                            $hasPrescription = $hasMedRecord && $apt->medicalRecord->prescriptions->isNotEmpty();
                            $isCompleted   = $apt->status === 'completed';
                            $hasBill       = (bool) $apt->bill;
                            $canComplete   = ! $isCompleted && $hasMedRecord;
                            $queue         = $apt->queue;
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">

                            {{-- No. Antrian --}}
                            <td class="px-6 py-4">
                                @if ($queue)
                                    <div class="flex flex-col items-start gap-1">
                                        <span class="font-bold text-gray-900 text-base">
                                            {{ str_pad((string) $queue->queue_number, 2, '0', STR_PAD_LEFT) }}
                                        </span>
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
                                            {{ ucfirst(str_replace('_', ' ', $queue->status)) }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400 italic">—</span>
                                @endif
                            </td>

                            {{-- Pasien --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 text-xs font-bold flex-shrink-0">
                                        {{ $patientName ? strtoupper(substr($patientName, 0, 1)) : '??' }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $patientName ?? '-' }}</p>
                                        <p class="text-xs text-gray-400">No. RM: {{ $rm ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>

                            {{-- Jam --}}
                            <td class="px-6 py-4 text-gray-500">
                                {{ \Carbon\Carbon::parse($apt->scheduled_at)->format('H:i') }} WIB
                            </td>

                            {{-- Keluhan --}}
                            <td class="px-6 py-4 italic text-gray-500 max-w-[160px] truncate">
                                "{{ $apt->complaint }}"
                            </td>

                            {{-- Rekam Medis --}}
                            <td class="px-6 py-4">
                                @if ($hasMedRecord)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                        <i class="fa-solid fa-check text-[10px]"></i> Ada
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-600">
                                        <i class="fa-solid fa-xmark text-[10px]"></i> Belum
                                    </span>
                                @endif
                            </td>

                            {{-- Resep --}}
                            <td class="px-6 py-4">
                                @if (! $hasMedRecord)
                                    <span class="text-xs text-gray-400">-</span>
                                @elseif ($hasPrescription)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                                        <i class="fa-solid fa-capsules text-[10px]"></i>
                                        {{ $apt->medicalRecord->prescriptions->count() }} obat
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">
                                        Tanpa resep
                                    </span>
                                @endif
                            </td>

                            {{-- Status & Action --}}
                            <td class="px-6 py-4">
                                @if ($isCompleted)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Selesai
                                    </span>
                                    @if ($hasBill)
                                        <p class="text-xs text-gray-400 mt-1">
                                            <i class="fa-solid fa-receipt"></i> Tagihan dibuat
                                        </p>
                                    @endif
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                        Menunggu
                                    </span>

                                    <div class="mt-2 flex flex-col gap-1.5">
                                        @if ($canComplete)
                                            {{-- Sudah ada rekam medis → tampilkan tombol tambah resep + selesaikan --}}
                                            <a href="{{ route('doctor.prescription') }}"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white border border-blue-300 text-blue-600 font-semibold text-xs hover:bg-blue-50 shadow-sm transition-all">
                                                <i class="fa-solid fa-capsules text-[10px]"></i>
                                                {{ $hasPrescription ? 'Tambah Resep' : 'Beri Resep' }}
                                            </a>
                                            <form method="POST"
                                                action="{{ route('doctor.appointments.complete', $apt->id) }}"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full px-3 py-1.5 rounded-xl bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold text-xs hover:from-teal-600 hover:to-cyan-700 shadow-sm flex items-center gap-1.5">
                                                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                                                    Selesaikan & Buat Tagihan
                                                </button>
                                            </form>
                                        @else
                                            {{-- Belum ada rekam medis → arahkan ke form create --}}
                                            <a href="{{ route('doctor.medical-records.create', $apt->id) }}"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-gradient-to-r from-violet-500 to-purple-600 text-white font-semibold text-xs hover:from-violet-600 hover:to-purple-700 shadow-sm transition-all">
                                                <i class="fa-solid fa-file-medical text-[10px]"></i>
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

    {{-- Legend --}}
    <div class="bg-blue-50 border border-blue-100 rounded-2xl px-5 py-4 text-xs text-blue-700">
        <p class="font-semibold mb-1"><i class="fa-solid fa-circle-info mr-1"></i> Alur Penyelesaian Appointment</p>
        <ol class="list-decimal list-inside space-y-0.5 text-blue-600">
            <li>Isi <strong>Rekam Medis</strong> terlebih dahulu (wajib).</li>
            <li>Tambahkan <strong>Resep Obat</strong> jika pasien membutuhkan (opsional).</li>
            <li>Klik <strong>Selesaikan & Buat Tagihan</strong> — tagihan akan otomatis muncul di panel pasien dan admin.</li>
        </ol>
    </div>

</div>