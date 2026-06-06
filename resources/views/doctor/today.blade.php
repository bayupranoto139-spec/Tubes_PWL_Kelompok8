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

    {{-- Stats mini row --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center">
                <i class="fa-solid fa-hourglass-half text-blue-500"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Menunggu</p>
                <p class="text-xl font-bold text-gray-800">1</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                <i class="fa-solid fa-circle-check text-emerald-500"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Selesai</p>
                <p class="text-xl font-bold text-gray-800">1</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
                <i class="fa-solid fa-clock text-amber-500"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Dalam Proses</p>
                <p class="text-xl font-bold text-gray-800">0</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-200 flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-purple-50 flex items-center justify-center">
                <i class="fa-solid fa-users text-purple-500"></i>
            </div>
            <div>
                <p class="text-xs text-gray-400">Total Antrian</p>
                <p class="text-xl font-bold text-gray-800">2</p>
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
                        <th class="px-6 py-3 text-left">No. Antrean</th>
                        <th class="px-6 py-3 text-left">Nama Pasien</th>
                        <th class="px-6 py-3 text-left">Jam Periksa</th>
                        <th class="px-6 py-3 text-left">Keluhan Medis</th>
                        <th class="px-6 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-gray-700">
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900">01</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 text-xs font-bold flex-shrink-0">AS</div>
                                <div>
                                    <p class="font-semibold text-gray-900">Andi Saputra</p>
                                    <p class="text-xs text-gray-400">No. RM: RM0001</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-500">08:30 WIB</td>
                        <td class="px-6 py-4 italic text-gray-500">"Demam tinggi selama 3 hari"</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                Selesai
                            </span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-gray-900">02</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 text-xs font-bold flex-shrink-0">SR</div>
                                <div>
                                    <p class="font-semibold text-gray-900">Siti Rahayu</p>
                                    <p class="text-xs text-gray-400">No. RM: RM0002</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-500">09:00 WIB</td>
                        <td class="px-6 py-4 italic text-gray-500">"Batuk dan pilek"</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                Menunggu
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

    </main>
</div>
</div>
</body>
</html>
