@include('doctor.includes.header')
@include('doctor.includes.sidebar')

@php
    $doctorUser = auth()->user();
    $doctorName = $doctorUser?->name ?? 'Dr. Budi Santoso';
    $doctorRoleLabel = $doctorUser?->doctor?->specialization?->name ?? 'Spesialis Penyakit Dalam';
@endphp

<div class="max-w-7xl mx-auto space-y-6">

    {{-- ===== WELCOME BANNER (sama seperti admin) ===== --}}
    <div class="rounded-2xl p-8 text-white shadow-lg" style="background:linear-gradient(90deg,#14b8a6,#06b6d4)">
        <h1 class="text-3xl font-extrabold leading-tight">
            Welcome back, {{ $doctorName }} 👋
        </h1>
        <p class="mt-2 text-base opacity-95">
            Logged in as <strong>DOCTOR — {{ strtoupper($doctorRoleLabel) }}</strong>
        </p>
        <p class="mt-1 opacity-80 text-sm">
            Here's what's happening with your HealthMesh practice today.
        </p>
    </div>

    {{-- ===== STAT CARDS ROW 1 — mirip DashboardStats admin ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- Antrean Hari Ini --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#eff6ff">
                <i class="fa-solid fa-calendar-day text-blue-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Antrean Hari Ini</p>
                <p class="mt-1 text-3xl font-extrabold text-gray-800 leading-none">{{ $todayQueue }}</p>
                <p class="mt-1 text-xs text-blue-500 font-medium">Today's schedule</p>
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
                <p class="mt-1 text-xs text-emerald-500 font-medium">Completed visits</p>
            </div>
        </div>

        {{-- Tarif Konsultasi --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#fffbeb">
                <i class="fa-solid fa-sack-dollar text-amber-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Tarif Konsultasi</p>
                <p class="mt-1 text-2xl font-extrabold text-gray-800 leading-none">
                    Rp {{ number_format($consultationFee, 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs text-amber-500 font-medium">Per consultation</p>
            </div>
        </div>

        {{-- Total Kunjungan --}}
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#fdf4ff">
                <i class="fa-solid fa-users text-purple-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide">Total Kunjungan</p>
                <p class="mt-1 text-3xl font-extrabold text-gray-800 leading-none">{{ $todayQueue + $completedVisits }}
                </p>
                <p class="mt-1 text-xs text-purple-500 font-medium">All-time visits</p>
            </div>
        </div>
    </div>

    {{-- ===== CHARTS ROW (mirip RevenueChart + VisitsChart admin) ===== --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Visits per Bulan (line chart) --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-base font-bold text-gray-800 mb-1">Kunjungan per Bulan</h3>
            <p class="text-xs text-gray-400 mb-5">Jumlah janji temu yang dijadwalkan tahun ini.</p>
            <canvas id="visitsChart" class="w-full" style="max-height:230px"></canvas>
        </div>

        {{-- Ringkasan Kinerja (bar chart) --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <h3 class="text-base font-bold text-gray-800 mb-1">Ringkasan Kinerja</h3>
            <p class="text-xs text-gray-400 mb-5">Perbandingan antrean, selesai, dan total kunjungan.</p>
            <canvas id="perfChart" class="w-full" style="max-height:230px"></canvas>
        </div>
    </div>

    {{-- ===== MINI STATS ROW 2 (mirip MiniStats admin) ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-cyan-50 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-clock text-cyan-500 text-base"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Aktif di Antrian</p>
                <p class="text-2xl font-bold text-gray-800">{{ $todayQueue }}</p>
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
                <p class="text-2xl font-bold text-gray-800">{{ $completedVisits }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-chart-bar text-amber-500 text-base"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Pendapatan Hari Ini</p>
                <p class="text-lg font-bold text-gray-800">Rp
                    {{ number_format($consultationFee * $completedVisits, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    {{-- ===== RECENT APPOINTMENTS TABLE ===== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
            <div>
                <h3 class="text-base font-bold text-gray-800">Recent Appointments</h3>
                <p class="text-xs text-gray-400 mt-0.5">Daftar pasien yang baru dijadwalkan</p>
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
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Pasien</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Rumah Sakit</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Tanggal</th>
                        <th class="text-left py-3 px-5 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                            Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse(($recentAppointments ?? collect()) as $apt)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-5">
                                <div class="flex items-center gap-3">
                                    @php
                                        $pName = $apt->patientEnrollment?->user?->name;
                                        $rm = $apt->patientEnrollment?->medical_record_number;
                                        $initial = $pName ? strtoupper(substr($pName, 0, 1)) : '--';
                                    @endphp
                                    <div
                                        class="w-7 h-7 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 text-xs font-bold">
                                        {{ $initial }}</div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $pName ?? '-' }}</p>
                                        <p class="text-xs text-gray-400">No. RM: {{ $rm ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-5 text-gray-600">{{ $apt->patientEnrollment?->hospital?->name ?? '-' }}
                            </td>
                            <td class="py-4 px-5 text-gray-600">
                                {{ \Carbon\Carbon::parse($apt->scheduled_at)->format('d M Y') }}</td>
                            <td class="py-4 px-5">
                                @php
                                    $status = $apt->status;
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $status === 'completed' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
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
    document.addEventListener('DOMContentLoaded', function() {

        // Line chart — Visits per Month
        new Chart(document.getElementById('visitsChart'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov',
                    'Des'
                ],
                datasets: [{
                    label: 'Kunjungan',
                    data: [2, 5, 3, 8, 6, {{ $todayQueue + $completedVisits }}, 0, 0, 0, 0, 0,
                        0
                    ],
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
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // Bar chart — Performance summary
        new Chart(document.getElementById('perfChart'), {
            type: 'bar',
            data: {
                labels: ['Antrean Hari Ini', 'Selesai Diperiksa', 'Total Kunjungan'],
                datasets: [{
                    label: 'Jumlah',
                    data: [{{ $todayQueue }}, {{ $completedVisits }},
                        {{ $todayQueue + $completedVisits }}
                    ],
                    backgroundColor: ['#14b8a6', '#06b6d4', '#8b5cf6'],
                    borderRadius: {
                        topLeft: 10,
                        topRight: 10,
                        bottomLeft: 0,
                        bottomRight: 0
                    },
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: {
                                size: 11
                            }
                        }
                    }
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
