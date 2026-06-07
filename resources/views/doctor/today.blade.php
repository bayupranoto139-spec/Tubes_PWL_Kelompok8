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

    {{-- Stats mini row --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @php
            $waitingCount   = $todayAppointments->whereIn('status', ['scheduled', 'confirmed'])->count();
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
                        <th class="px-6 py-3 text-left">No.</th>
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
                            // Boleh complete jika sudah ada rekam medis (prescription opsional)
                            $canComplete   = ! $isCompleted && $hasMedRecord;
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">

                            {{-- No --}}
                            <td class="px-6 py-4 font-bold text-gray-900">
                                {{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}
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

                                    <div class="mt-2">
                                        @if ($canComplete)
                                            {{-- Sudah ada rekam medis → boleh complete --}}
                                            <form method="POST"
                                                action="{{ route('doctor.appointments.complete', $apt->id) }}"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="px-3 py-1.5 rounded-xl bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold text-xs hover:from-teal-600 hover:to-cyan-700 shadow-sm flex items-center gap-1.5">
                                                    <i class="fa-solid fa-circle-check text-[10px]"></i>
                                                    Selesaikan & Buat Tagihan
                                                </button>
                                            </form>
                                        @else
                                            {{-- Belum ada rekam medis → disable --}}
                                            <div class="group relative inline-block">
                                                <button type="button" disabled
                                                    class="px-3 py-1.5 rounded-xl bg-gray-200 text-gray-400 font-semibold text-xs cursor-not-allowed flex items-center gap-1.5">
                                                    <i class="fa-solid fa-lock text-[10px]"></i>
                                                    Selesaikan
                                                </button>
                                                <div class="absolute bottom-full left-0 mb-1 hidden group-hover:block z-10 w-48 rounded-lg bg-gray-800 text-white text-xs px-3 py-2 shadow-lg">
                                                    Isi rekam medis terlebih dahulu sebelum menyelesaikan appointment.
                                                </div>
                                            </div>
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