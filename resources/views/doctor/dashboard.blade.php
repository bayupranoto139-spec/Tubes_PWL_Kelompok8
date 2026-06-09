@include('doctor.includes.header')
@include('doctor.includes.sidebar')

@php
    $doctorUser      = auth()->user();
    $doctorName      = $doctorUser?->name ?? 'Dokter';
    $doctorSpec      = $doctorUser?->doctor?->specialization?->name ?? 'Dokter';
@endphp

<div class="max-w-7xl mx-auto space-y-6">

    {{-- WELCOME BANNER --}}
    <div class="rounded-2xl p-8 text-white shadow-lg" style="background:linear-gradient(90deg,#14b8a6,#06b6d4)">
        <h1 class="text-3xl font-extrabold leading-tight">
            Selamat datang, {{ $doctorName }}
        </h1>
        <p class="mt-2 text-base opacity-95">
            Login sebagai <strong>DOKTER — {{ strtoupper($doctorSpec) }}</strong>
        </p>
        <p class="mt-1 opacity-80 text-sm">
            Berikut ringkasan praktik Anda hari ini.
        </p>
    </div>

    {{-- STAT CARDS ROW 1 --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- Antrean Hari Ini --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#eff6ff">
                <i class="fa-solid fa-calendar-day text-blue-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Antrean Hari Ini</p>
                <p class="mt-1 text-3xl font-extrabold text-gray-800 leading-none">{{ $todayQueue }}</p>
                <p class="mt-1 text-xs text-blue-500 font-medium">Jadwal hari ini</p>
            </div>
        </div>

        {{-- Menunggu --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#fff7ed">
                <i class="fa-solid fa-clock text-orange-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Menunggu</p>
                <p class="mt-1 text-3xl font-extrabold text-gray-800 leading-none">{{ $waitingCount }}</p>
                <p class="mt-1 text-xs text-orange-500 font-medium">Belum diperiksa</p>
            </div>
        </div>

        {{-- Selesai Diperiksa --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#f0fdf4">
                <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Selesai Diperiksa</p>
                <p class="mt-1 text-3xl font-extrabold text-gray-800 leading-none">{{ $completedVisits }}</p>
                <p class="mt-1 text-xs text-emerald-500 font-medium">Hari ini</p>
            </div>
        </div>

        {{-- Total Kunjungan All-Time --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#fdf4ff">
                <i class="fa-solid fa-users text-purple-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Total Kunjungan</p>
                <p class="mt-1 text-3xl font-extrabold text-gray-800 leading-none">{{ $totalAllTime }}</p>
                <p class="mt-1 text-xs text-purple-500 font-medium">Sepanjang waktu</p>
            </div>
        </div>
    </div>

    {{-- CHARTS ROW --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Kunjungan per Bulan --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-base font-bold text-gray-800 mb-1">Kunjungan per Bulan</h3>
            <p class="text-xs text-gray-400 mb-5">Jumlah janji temu yang dijadwalkan tahun {{ now()->year }}.</p>
            <canvas id="visitsChart" class="w-full" style="max-height:230px"></canvas>
        </div>

        {{-- Ringkasan Hari Ini --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-base font-bold text-gray-800 mb-1">Ringkasan Hari Ini</h3>
            <p class="text-xs text-gray-400 mb-5">Perbandingan antrean, menunggu, dan selesai.</p>
            <canvas id="perfChart" class="w-full" style="max-height:230px"></canvas>
        </div>
    </div>

    {{-- MINI STATS ROW 2 --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-cyan-50 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-clock text-cyan-500 text-base"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Menunggu Hari Ini</p>
                <p class="text-2xl font-bold text-gray-800">{{ $waitingCount }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-rose-50 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-stethoscope text-rose-500 text-base"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Pasien Selesai</p>
                <p class="text-2xl font-bold text-gray-800">{{ $completedVisits }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-prescription-bottle-medical text-green-500 text-base"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Resep Dikeluarkan</p>
                <p class="text-2xl font-bold text-gray-800">{{ $prescriptionsToday }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-chart-bar text-amber-500 text-base"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Pendapatan Hari Ini</p>
                <p class="text-lg font-bold text-gray-800">Rp {{ number_format($revenueToday, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- TARIF KONSULTASI --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-center gap-4 max-w-sm">
        <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-sack-dollar text-amber-500 text-base"></i>
        </div>
        <div>
            <p class="text-xs text-gray-400">Tarif Konsultasi</p>
            <p class="text-xl font-bold text-gray-800">Rp {{ number_format($consultationFee, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- RECENT APPOINTMENTS TABLE --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
            <div>
                <h3 class="text-base font-bold text-gray-800">Appointment Hari Ini</h3>
                <p class="text-xs text-gray-400 mt-0.5">5 appointment terbaru hari ini</p>
            </div>
            <a href="{{ route('doctor.today') }}"
                class="text-xs font-semibold px-4 py-2 rounded-xl text-white shadow-sm transition-all hover:opacity-90"
                style="background:linear-gradient(90deg,#14b8a6,#06b6d4)">
                Lihat Semua →
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Pasien</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Rumah Sakit</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Jam</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentAppointments as $apt)
                        @php
                            $pName   = $apt->patientEnrollment?->user?->name;
                            $rm      = $apt->patientEnrollment?->medical_record_number;
                            $initial = $pName ? strtoupper(substr($pName, 0, 1)) : '?';
                            $status  = $apt->status;
                            $statusColor = match($status) {
                                'completed' => 'bg-emerald-50 text-emerald-700',
                                'cancelled' => 'bg-red-50 text-red-600',
                                default     => 'bg-amber-50 text-amber-700',
                            };
                            $dotColor = match($status) {
                                'completed' => 'bg-emerald-500',
                                'cancelled' => 'bg-red-500',
                                default     => 'bg-amber-500',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-7 h-7 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 text-xs font-bold">
                                        {{ $initial }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $pName ?? '-' }}</p>
                                        <p class="text-xs text-gray-400">No. RM: {{ $rm ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-5 text-gray-600">{{ $apt->patientEnrollment?->hospital?->name ?? '-' }}</td>
                            <td class="py-4 px-5 text-gray-600">{{ \Carbon\Carbon::parse($apt->scheduled_at)->format('H:i') }}</td>
                            <td class="py-4 px-5">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }} mr-1.5"></span>
                                    {{ $status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-sm text-gray-400">
                                <i class="fa-solid fa-calendar-xmark text-2xl mb-2 text-gray-300 block"></i>
                                Tidak ada appointment hari ini
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Charts JS --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {

        // Line chart — Kunjungan per Bulan (data real dari DB)
        new Chart(document.getElementById('visitsChart'), {
            type: 'line',
            data: {
                labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'],
                datasets: [{
                    label: 'Kunjungan',
                    data: @json($monthlyVisits),
                    fill: true,
                    backgroundColor: 'rgba(20,184,166,.12)',
                    borderColor: '#14b8a6',
                    borderWidth: 2,
                    pointBackgroundColor: '#14b8a6',
                    pointRadius: 4,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { color: '#94a3b8', font: { size: 11 } }, grid: { color: '#f1f5f9' } },
                    x: { ticks: { color: '#94a3b8', font: { size: 11 } }, grid: { display: false } }
                }
            }
        });

        // Bar chart — Ringkasan Hari Ini
        new Chart(document.getElementById('perfChart'), {
            type: 'bar',
            data: {
                labels: ['Antrean', 'Menunggu', 'Selesai'],
                datasets: [{
                    label: 'Jumlah',
                    data: [{{ $todayQueue }}, {{ $waitingCount }}, {{ $completedVisits }}],
                    backgroundColor: ['#14b8a6', '#f97316', '#06b6d4'],
                    borderRadius: { topLeft: 10, topRight: 10, bottomLeft: 0, bottomRight: 0 },
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { color: '#94a3b8', font: { size: 11 } }, grid: { color: '#f1f5f9' } },
                    x: { ticks: { color: '#94a3b8', font: { size: 11 } }, grid: { display: false } }
                }
            }
        });
    });
</script>

</main>
</div>
</div>
</body>
</html>