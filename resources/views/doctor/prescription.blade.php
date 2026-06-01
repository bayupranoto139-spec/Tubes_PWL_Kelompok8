@include('doctor.includes.header')
@include('doctor.includes.sidebar')

<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Daftar Pengeluaran Resep Obat</h2>
        <p class="text-sm text-gray-500">Riwayat instruksi dosis obat untuk pasien.</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <div class="mb-4 bg-slate-50 p-4 rounded-lg flex items-center justify-between border border-gray-100">
            <div>
                <span class="text-xs font-bold text-blue-600 uppercase">Pasien: Andi Saputra</span>
                <h4 class="text-sm font-semibold text-slate-800 mt-1">Diagnosis: Demam Virus</h4>
            </div>
            <span class="text-xs text-gray-400">1 Juni 2026</span>
        </div>

        <!-- Item Obat -->
        <div class="space-y-3">
            <div class="p-3 border border-gray-200 rounded-lg flex justify-between items-center text-sm">
                <div>
                    <strong class="text-slate-800">Paracetamol 500mg</strong>
                    <p class="text-xs text-gray-400 mt-0.5">Aturan Pakai: 3x1 tablet (Sesudah makan)</p>
                </div>
                <span class="bg-slate-100 text-slate-700 text-xs font-bold px-2.5 py-1 rounded">15 Unit</span>
            </div>

            <div class="p-3 border border-gray-200 rounded-lg flex justify-between items-center text-sm">
                <div>
                    <strong class="text-slate-800">Amoxicillin 500mg</strong>
                    <p class="text-xs text-gray-400 mt-0.5">Aturan Pakai: 2x1 kapsul (Habiskan)</p>
                </div>
                <span class="bg-slate-100 text-slate-700 text-xs font-bold px-2.5 py-1 rounded">10 Unit</span>
            </div>
        </div>
    </div>
</div>

</div> </main> </div> </body> </html>