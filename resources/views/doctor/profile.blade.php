@include('doctor.includes.header')
@include('doctor.includes.sidebar')

@php
    $doctorUser  = auth()->user();
    $doctorName  = $doctorUser?->name ?? 'Dr. Budi Santoso';
    $doctorRole  = $doctorUser?->doctor?->specialization?->name ?? 'Spesialis Penyakit Dalam';
    $initials    = collect(explode(' ', $doctorName))->map(fn($w) => strtoupper($w[0]))->take(2)->join('');
    $email       = $doctorUser?->email ?? 'budi@healthmesh.com';
    $phone       = $doctorUser?->phone ?? '081111111113';
    $sip         = $doctorUser?->doctor?->licence_number ?? 'SIP-DOK-001';
    $experience  = $doctorUser?->doctor?->years_of_experience ?? '10 Tahun Kerja';
    $address     = $doctorUser?->address ?? 'Medan, Indonesia';
    $hospital    = $doctorUser?->hospital?->name ?? 'RS Sehat Sentosa';
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
                    <dd class="font-semibold text-gray-800">{{ $experience }} tahun</dd>
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

    {{-- ===================== EDIT PROFILE (Fit Content Blade) ===================== --}}
    <div id="edit-profile" class="scroll-mt-24 pt-2 scroll-smooth">
        <div class="p-6 rounded-2xl bg-white border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div>
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-pen-to-square text-teal-500"></i>
                        Edit Profil Dokter
                    </h3>
                    <p class="text-sm text-gray-400 mt-1">Form ini mengikuti tombol “Edit Profil”.</p>
                </div>
            </div>

            {{-- NOTE: Backend update belum ada di controller saat ini.
                Form disetel sebagai POST ke /doctor/profile/update (silakan buat route/controller jika diperlukan). --}}
            <form method="POST" action="{{ url('/doctor/profile') }}/update" class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Dokter</label>
                    <input name="name" type="text" value="{{ $doctorName }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/30" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input name="email" type="email" value="{{ $email }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/30" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                    <input name="phone" type="text" value="{{ $phone }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/30" />
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                    <textarea name="address" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500/30">{{ $address }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor SIP</label>
                    <input name="sip_number" type="text" value="{{ $sip }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white" disabled />
                    <p class="text-xs text-gray-400 mt-1">SIP dikunci (read-only).</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Spesialisasi</label>
                    <input name="specialization_name" type="text" value="{{ $doctorRole }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-white" disabled />
                    <p class="text-xs text-gray-400 mt-1">Spesialisasi dikunci (read-only).</p>
                </div>

                <div class="md:col-span-2 flex items-center justify-end gap-3 mt-2">
                <a href="{{ route('doctor.profile') }}" class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 hover:bg-gray-50">Batal</a>
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold hover:from-teal-600 hover:to-cyan-700 shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
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
