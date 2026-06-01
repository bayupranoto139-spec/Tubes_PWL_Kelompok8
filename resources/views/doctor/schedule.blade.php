@include('doctor.includes.header')
@include('doctor.includes.sidebar')

<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Semua Jadwal Praktik Mingguan</h2>
        <p class="text-sm text-gray-500">Konfigurasi waktu operasional dokter di rumah sakit.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Jadwal Hari Senin -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="bg-blue-50 text-blue-700 font-bold text-xs px-3 py-1 rounded-md uppercase">Hari Praktik</span>
                <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></span>
            </div>
            <h3 class="text-xl font-bold text-slate-900">Senin (Pagi)</h3>
            <div class="mt-4 space-y-2 text-sm text-slate-600">
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span>Jam Operasional:</span>
                    <strong class="text-slate-800">08:00 - 12:00 WIB</strong>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span>Kuota Maksimal:</span>
                    <strong class="text-slate-800">20 Pasien</strong>
                </div>
            </div>
        </div>

        <!-- Jadwal Hari Rabu -->
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <span class="bg-blue-50 text-blue-700 font-bold text-xs px-3 py-1 rounded-md uppercase">Hari Praktik</span>
                <span class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></span>
            </div>
            <h3 class="text-xl font-bold text-slate-900">Rabu (Pagi)</h3>
            <div class="mt-4 space-y-2 text-sm text-slate-600">
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span>Jam Operasional:</span>
                    <strong class="text-slate-800">08:00 - 12:00 WIB</strong>
                </div>
                <div class="flex justify-between border-b border-gray-100 pb-2">
                    <span>Kuota Maksimal:</span>
                    <strong class="text-slate-800">20 Pasien</strong>
                </div>
            </div>
        </div>
    </div>
</div>

</div> </main> </div> </body> </html>