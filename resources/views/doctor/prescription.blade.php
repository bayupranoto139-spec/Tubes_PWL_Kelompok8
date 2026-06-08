@include('doctor.includes.header')
@include('doctor.includes.sidebar')

<div class="max-w-7xl mx-auto space-y-6">

    {{-- Page header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-800">Daftar Resep Obat</h1>
            <p class="text-sm text-gray-400 mt-1">Riwayat instruksi dosis obat untuk pasien.</p>
        </div>
        <a href="{{ route('doctor.today') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-700 text-sm font-semibold hover:bg-gray-50 shadow-sm transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Kembali ke Jadwal Hari Ini
        </a>
    </div>

    {{-- Stats mini --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-teal-50 flex items-center justify-center">
                <i class="fa-solid fa-prescription-bottle-medical text-teal-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Total Resep</p>
                <p class="text-2xl font-bold text-gray-800">{{ isset($prescriptions) ? $prescriptions->total() : 0 }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center">
                <i class="fa-solid fa-pills text-emerald-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Total Item Obat</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ isset($prescriptions) ? $prescriptions->sum('quantity') : 0 }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center">
                <i class="fa-solid fa-user-injured text-blue-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Pasien Diresepkan</p>
                <p class="text-2xl font-bold text-gray-800">
                    {{ isset($prescriptions) ? $prescriptions->pluck('patient_name')->unique()->count() : 0 }}</p>
            </div>
        </div>
    </div>

    {{-- Form Tambah Resep --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-2">Tambah Resep</h3>
        <p class="text-sm text-gray-400 mb-5">Pilih pasien dari appointment hari ini yang sudah memiliki rekam medis dan belum diselesaikan.</p>

        <form method="POST" action="{{ route('doctor.prescription.store') }}"
            class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Pasien (Appointment hari ini)</label>
                <select name="appointment_id"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/30"
                    required>
                    <option value="">-- Pilih Appointment --</option>
                    @foreach ($appointments ?? [] as $a)
                        <option value="{{ $a->appointment_id }}">
                            {{ $a->patient_name }}
                            @if (!empty($a->visit_date))
                                - {{ \Carbon\Carbon::parse($a->visit_date)->format('d M Y') }}
                            @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Obat</label>
                <select name="medication_id"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/30"
                    required>
                    @foreach ($medications ?? [] as $m)
                        <option value="{{ $m->id }}">{{ $m->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah (quantity)</label>
                <input name="quantity" type="number" min="1"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500/30"
                    required />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dosage</label>
                <input name="dosage" type="text"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500/30"
                    required />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                <input name="duration" type="text"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500/30"
                    required />
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Notes (opsional)</label>
                <textarea name="notes" rows="3"
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500/30"></textarea>
            </div>

            <div class="md:col-span-2 flex items-center justify-end gap-3">
                <a href="{{ route('doctor.prescription') }}"
                    class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50">Batal</a>
                <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold hover:from-teal-600 hover:to-cyan-700 shadow-sm">
                    Simpan Resep
                </button>
            </div>
        </form>
    </div>

    {{-- Search + filter + list resep (pagination) --}}
    <div class="mt-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-4 mb-4">
            <form method="GET" action="{{ route('doctor.prescription') }}"
                class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Search</label>
                    <input type="text" name="search" value="{{ $search ?? '' }}"
                        placeholder="Nama pasien / obat / diagnosis"
                        class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/30 text-sm" />
                </div>

                <div>
                    <label
                        class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">Medication</label>
                    <select name="medication_id"
                        class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/30 text-sm">
                        <option value="">-- Semua Obat --</option>
                        @foreach ($medications ?? [] as $m)
                            <option value="{{ $m->id }}"
                                {{ isset($filterMedicationId) && (string) $filterMedicationId === (string) $m->id ? 'selected' : '' }}>
                                {{ $m->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-end justify-end gap-2">
                    <button type="submit"
                        class="px-4 py-2 rounded-xl bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold hover:from-teal-600 hover:to-cyan-700 shadow-sm">
                        Terapkan
                    </button>
                    <a href="{{ route('doctor.prescription') }}"
                        class="px-4 py-2 rounded-xl border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 font-semibold">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="space-y-4">
            @forelse(($prescriptions ?? []) as $p)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between"
                        style="background:linear-gradient(90deg,rgba(20,184,166,.06),rgba(6,182,212,.06))">
                        <div>
                            <p class="text-xs font-bold text-teal-600 uppercase tracking-wide">Pasien:
                                {{ $p->patient_name }}</p>
                            <p class="text-sm font-semibold text-gray-800 mt-0.5">Diagnosis: {{ $p->diagnosis }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-gray-400">
                                {{ !empty($p->visit_date) ? \Carbon\Carbon::parse($p->visit_date)->format('d M Y') : now()->format('d M Y') }}
                            </p>
                            <span
                                class="mt-1 inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">Aktif</span>
                        </div>
                    </div>

                    <div class="p-6 space-y-3">
                        <div class="flex items-start justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <div class="flex items-start gap-3">
                                <div
                                    class="w-9 h-9 rounded-lg bg-teal-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fa-solid fa-capsules text-teal-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $p->medication_name }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Aturan Pakai: {{ $p->dosage }}
                                        ({{ $p->duration }})
                                    </p>
                                </div>
                            </div>
                            <span
                                class="flex-shrink-0 px-3 py-1 rounded-lg text-xs font-bold bg-white border border-gray-200 text-gray-700 shadow-sm">{{ $p->quantity }}
                                Unit</span>
                        </div>
                    </div>

                    <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                        <span class="text-xs text-gray-400">Catatan: {{ $p->notes ?? '-' }}</span>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 text-gray-500 text-sm">
                    Belum ada resep.
                </div>
            @endforelse
        </div>

        @if (isset($prescriptions) && method_exists($prescriptions, 'links'))
            <div class="pt-4">
                {{ $prescriptions->links() }}
            </div>
        @endif
    </div>

</div>

</main>
</div>
</div>
</body>

</html>