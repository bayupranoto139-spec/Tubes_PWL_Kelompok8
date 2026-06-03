@include('doctor.includes.header')
@include('doctor.includes.sidebar')

<div class="max-w-7xl mx-auto space-y-6">

    {{-- Page header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-800">Daftar Resep Obat</h1>
            <p class="text-sm text-gray-400 mt-1">Riwayat instruksi dosis obat untuk pasien.</p>
        </div>
        <button class="inline-flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-xl text-white shadow-sm w-fit transition-all hover:opacity-90"
                style="background:linear-gradient(90deg,#14b8a6,#06b6d4)">
            <i class="fa-solid fa-plus"></i> Tambah Resep
        </button>
    </div>

    {{-- Stats mini --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-teal-50 flex items-center justify-center">
                <i class="fa-solid fa-prescription-bottle-medical text-teal-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Total Resep</p>
                <p class="text-2xl font-bold text-gray-800">1</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center">
                <i class="fa-solid fa-pills text-emerald-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Total Item Obat</p>
                <p class="text-2xl font-bold text-gray-800">2</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-center gap-4">
            <div class="w-11 h-11 rounded-xl bg-blue-50 flex items-center justify-center">
                <i class="fa-solid fa-user-injured text-blue-500 text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Pasien Diresepkan</p>
                <p class="text-2xl font-bold text-gray-800">1</p>
            </div>
        </div>
    </div>

    {{-- Prescription cards --}}
    <div class="space-y-4">

        {{-- Prescription Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">

            {{-- Card header --}}
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between"
                 style="background:linear-gradient(90deg,rgba(20,184,166,.06),rgba(6,182,212,.06))">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 text-xs font-bold">AS</div>
                    <div>
                        <p class="text-xs font-bold text-teal-600 uppercase tracking-wide">Pasien: Andi Saputra</p>
                        <p class="text-sm font-semibold text-gray-800 mt-0.5">Diagnosis: Demam Virus</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400">{{ now()->format('d M Y') }}</p>
                    <span class="mt-1 inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">Aktif</span>
                </div>
            </div>

            {{-- Medication list --}}
            <div class="p-6 space-y-3">
                <div class="flex items-start justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-teal-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fa-solid fa-capsules text-teal-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Paracetamol 500mg</p>
                            <p class="text-xs text-gray-400 mt-0.5">Aturan Pakai: 3×1 tablet (Sesudah makan)</p>
                        </div>
                    </div>
                    <span class="flex-shrink-0 px-3 py-1 rounded-lg text-xs font-bold bg-white border border-gray-200 text-gray-700 shadow-sm">15 Unit</span>
                </div>

                <div class="flex items-start justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fa-solid fa-pills text-blue-600 text-sm"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">Amoxicillin 500mg</p>
                            <p class="text-xs text-gray-400 mt-0.5">Aturan Pakai: 2x1 kapsul (Habiskan)</p>
                        </div>
                    </div>
                    <span class="flex-shrink-0 px-3 py-1 rounded-lg text-xs font-bold bg-white border border-gray-200 text-gray-700 shadow-sm">10 Unit</span>
                </div>
            </div>

            {{-- Card footer --}}
            <div class="px-6 py-3 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                <span class="text-xs text-gray-400">Dokter: Dr. Budi Santoso • SIP-DOK-001</span>
            </div>
        </div>

    </div>

</div>

    </main>
</div>
</div>
</body>
</html>
