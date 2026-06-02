@include('doctor.includes.header')
@include('doctor.includes.sidebar')

<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-slate-800">Profil Akun Dokter</h2>
        <p class="text-sm text-gray-500">Kelola dan lihat informasi kredensial kedokteran Anda.</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <!-- Cover Banner Kecil -->
        <div class="h-32 bg-slate-900 flex items-end p-6 relative">
            <div class="w-20 h-20 bg-blue-600 rounded-full border-4 border-white flex items-center justify-center font-bold text-2xl text-white shadow-md absolute -bottom-10 left-6">
                BS
            </div>
        </div>

        <div class="pt-14 p-6">
            <h3 class="text-xl font-bold text-slate-900">Dr. Budi Santoso</h3>
            <p class="text-sm text-gray-400">SIP-DOK-001 • Spesialis Penyakit Dalam</p>

            <hr class="my-6 border-gray-100">

            <div class="space-y-4 text-sm text-slate-700">
                <div class="grid grid-cols-3 py-2 border-b border-gray-50">
                    <span class="text-gray-400 font-medium">Alamat Email</span>
                    <span class="col-span-2 font-semibold text-slate-900">budi@healthmesh.com</span>
                </div>
                <div class="grid grid-cols-3 py-2 border-b border-gray-50">
                    <span class="text-gray-400 font-medium">Nomor Telepon</span>
                    <span class="col-span-2 font-semibold text-slate-900">081111111113</span>
                </div>
                <div class="grid grid-cols-3 py-2 border-b border-gray-50">
                    <span class="text-gray-400 font-medium">Pengalaman Klinis</span>
                    <span class="col-span-2 font-semibold text-slate-900">10 Tahun Kerja</span>
                </div>
                <div class="grid grid-cols-3 py-2">
                    <span class="text-gray-400 font-medium">Alamat Rumah</span>
                    <span class="col-span-2 text-slate-600">Medan, Indonesia</span>
                </div>
            </div>
        </div>
    </div>
</div>

</div> </main> </div> </body> </html>