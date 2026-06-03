@include('doctor.includes.header')
@include('doctor.includes.sidebar')

@php
    $doctorUser  = auth()->user();
    $doctorName  = $doctorUser?->name ?? 'Dr. Budi Santoso';
    $doctorRole  = $doctorUser?->doctor?->specialization?->name ?? 'Spesialis Penyakit Dalam';
    $initials    = collect(explode(' ', $doctorName))->map(fn($w) => strtoupper($w[0]))->take(2)->join('');
    $email       = $doctorUser?->email ?? 'budi@healthmesh.com';
    $phone       = $doctorUser?->doctor?->phone ?? '081111111113';
    $sip         = $doctorUser?->doctor?->sip_number ?? 'SIP-DOK-001';
    $experience  = $doctorUser?->doctor?->experience ?? '10 Tahun Kerja';
    $address     = $doctorUser?->doctor?->address ?? 'Medan, Indonesia';
    $hospital    = $doctorUser?->doctor?->hospital?->name ?? 'RS Sehat Sentosa';
    $fee         = $doctorUser?->doctor?->consultation_fee ?? 150000;
@endphp

<div class="max-w-4xl mx-auto space-y-6">

    {{-- Page header --}}
    <div>
        <h1 class="text-2xl font-extrabold text-gray-800">Profil Akun Dokter</h1>
        <p class="text-sm text-gray-400 mt-1">Kelola dan lihat informasi kredensial kedokteran Anda.</p>
    </div>

    {{-- Profile card --}}
    <div class="rounded-2xl shadow-lg overflow-hidden border border-teal-400/30"
     style="background:linear-gradient(135deg,#14b8a6,#06b6d4)">

        {{-- Cover banner --}}
        <div class="h-32 bg-transparent relative">
            
        {{-- Decorative circles --}}
        <div class="absolute top-4 right-8 w-20 h-20 rounded-full bg-white/10"></div>
        <div class="absolute -top-4 right-20 w-32 h-32 rounded-full bg-white/5"></div>
    </div>

        {{-- Avatar + name --}}
        <div class="px-8 pb-6 -mt-10">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div class="flex items-end gap-4">
                    <div class="w-20 h-20 rounded-2xl border-4 border-white shadow-lg flex items-center justify-center text-2xl font-extrabold text-white flex-shrink-0"
                         style="background:linear-gradient(135deg,#14b8a6,#06b6d4)">
                        {{ $initials }}
                    </div>
                    <div class="pb-1">
                        <h2 class="text-xl font-extrabold text-white">{{ $doctorName }}</h2>
                        <p class="text-sm text-white/80">
                            {{ $sip }} • {{ $doctorRole }}
                        </p>
                    </div>
                </div>
                <button class="flex-shrink-0 inline-flex items-center gap-2 text-sm font-semibold px-4 py-2 rounded-xl text-white shadow-sm transition-all hover:opacity-90 mb-1"
                        style="background:linear-gradient(90deg,#14b8a6,#1498b0)">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Profil
                </button>
            </div>
        </div>
    </div>

    {{-- Info grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Personal info --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-id-card text-teal-500 text-sm"></i>
                Informasi Pribadi
            </h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between py-2.5 border-b border-gray-50">
                    <dt class="text-gray-400 font-medium">Alamat Email</dt>
                    <dd class="font-semibold text-gray-800">{{ $email }}</dd>
                </div>
                <div class="flex justify-between py-2.5 border-b border-gray-50">
                    <dt class="text-gray-400 font-medium">Nomor Telepon</dt>
                    <dd class="font-semibold text-gray-800">{{ $phone }}</dd>
                </div>
                <div class="flex justify-between py-2.5 border-b border-gray-50">
                    <dt class="text-gray-400 font-medium">Pengalaman Klinis</dt>
                    <dd class="font-semibold text-gray-800">{{ $experience }}</dd>
                </div>
                <div class="flex justify-between py-2.5">
                    <dt class="text-gray-400 font-medium">Alamat Domisili</dt>
                    <dd class="font-semibold text-gray-800">{{ $address }}</dd>
                </div>
            </dl>
        </div>

        {{-- Professional info --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200">
            <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-stethoscope text-blue-500 text-sm"></i>
                Informasi Profesional
            </h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between py-2.5 border-b border-gray-50">
                    <dt class="text-gray-400 font-medium">Nomor SIP</dt>
                    <dd class="font-semibold text-gray-800">{{ $sip }}</dd>
                </div>
                <div class="flex justify-between py-2.5 border-b border-gray-50">
                    <dt class="text-gray-400 font-medium">Spesialisasi</dt>
                    <dd class="font-semibold text-gray-800">{{ $doctorRole }}</dd>
                </div>
                <div class="flex justify-between py-2.5 border-b border-gray-50">
                    <dt class="text-gray-400 font-medium">Rumah Sakit</dt>
                    <dd class="font-semibold text-gray-800">{{ $hospital }}</dd>
                </div>
                <div class="flex justify-between py-2.5">
                    <dt class="text-gray-400 font-medium">Tarif Konsultasi</dt>
                    <dd class="font-semibold text-teal-600">Rp {{ number_format($fee, 0, ',', '.') }}</dd>
                </div>
            </dl>
        </div>

    </div>

    {{-- Status badge banner --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200 flex items-center gap-4">
        <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center flex-shrink-0">
            <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
        </div>
        <div>
            <p class="font-semibold text-gray-800 text-sm">Akun Terverifikasi & Aktif</p>
            <p class="text-xs text-gray-400 mt-0.5">
                Anda terdaftar aktif pada sistem administrasi {{ $hospital }} dengan SIP: {{ $sip }}.
            </p>
        </div>
        <div class="ml-auto">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Aktif
            </span>
        </div>
    </div>

</div>

    </main>
</div>
</div>
</body>
</html>
