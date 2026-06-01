<?php include('includes/header.php'); ?>
<?php include('includes/sidebar.php'); ?>

<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Jadwal Periksa Hari Ini</h2>
            <p class="text-sm text-gray-500">Daftar pasien yang terdaftar dalam antrean periksa per tanggal 1 Juni 2026.</p>
        </div>
        <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">Live Queue</span>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-gray-200 text-slate-400 font-semibold text-xs uppercase tracking-wider">
                    <th class="px-6 py-4">No. Antrean</th>
                    <th class="px-6 py-4">Nama Pasien</th>
                    <th class="px-6 py-4">Jam Periksa</th>
                    <th class="px-6 py-4">Keluhan Medis</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm text-slate-700">
                <!-- Data Kunjungan Pasien Andi Saputra (ID 1) -->
                <tr class="hover:bg-slate-50/80 transition-colors">
                    <td class="px-6 py-4 font-bold text-slate-900">#01</td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-slate-900">Andi Saputra</div>
                        <div class="text-xs text-gray-400">No. RM: RM0001</div>
                    </td>
                    <td class="px-6 py-4 text-gray-500">08:30 WIB</td>
                    <td class="px-6 py-4 italic text-gray-600">"Demam tinggi selama 3 hari"</td>
                    <td class="px-6 py-4">
                        <span class="bg-emerald-100 text-emerald-800 text-xs px-2.5 py-1 rounded-full font-medium">Selesai</span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <button class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors">
                            <i class="fa-solid : fa-eye mr-1"></i> Detail Rekam Medis
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

</div> </main> </div> </body> </html>