@include('doctor.includes.header')
@include('doctor.includes.sidebar')

<div class="max-w-3xl mx-auto space-y-6">

    {{-- Page header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-400 mb-1">
                <a href="{{ route('doctor.today') }}" class="hover:text-teal-600 transition-colors">Jadwal Hari Ini</a>
                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                <span class="text-gray-600">Buat Rekam Medis</span>
            </div>
            <h1 class="text-2xl font-extrabold text-gray-800">Rekam Medis Pasien</h1>
            <p class="text-sm text-gray-400 mt-1">Isi hasil pemeriksaan untuk appointment ini.</p>
        </div>
        <a href="{{ route('doctor.today') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-700 text-sm font-semibold hover:bg-gray-50 shadow-sm transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i>
            Kembali
        </a>
    </div>

    {{-- Patient info card --}}
    @php
        $patient  = optional(optional($appointment->patientEnrollment)->user);
        $hospital = optional(optional($appointment->patientEnrollment)->hospital);
        $rm       = optional($appointment->patientEnrollment)->medical_record_number;
    @endphp

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100"
            style="background: linear-gradient(90deg, rgba(20,184,166,.07), rgba(6,182,212,.07))">
            <p class="text-xs font-bold text-teal-600 uppercase tracking-wide mb-1">Data Pasien</p>
            <div class="flex flex-wrap gap-6 mt-2">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-sm flex-shrink-0">
                        {{ strtoupper(substr($patient->name ?? '?', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900 text-sm">{{ $patient->name ?? '-' }}</p>
                        <p class="text-xs text-gray-400">No. RM: {{ $rm ?? '-' }}</p>
                    </div>
                </div>
                <div class="text-sm text-gray-600 space-y-0.5 my-auto">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-hospital text-teal-400 w-4"></i>
                        <span>{{ $hospital->name ?? '-' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-clock text-teal-400 w-4"></i>
                        <span>{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('d M Y, H:i') }} WIB</span>
                    </div>
                </div>
                <div class="text-sm text-gray-600 my-auto">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-comment-medical text-teal-400 w-4"></i>
                        <span class="italic">"{{ $appointment->complaint }}"</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-2xl px-5 py-4 text-sm text-red-700 space-y-1">
            <p class="font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i> Terdapat kesalahan input:
            </p>
            <ul class="list-disc list-inside space-y-0.5 text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form --}}
    <form method="POST"
        action="{{ route('doctor.medical-records.store', $appointment->id) }}"
        class="space-y-5">
        @csrf

        {{-- Diagnosis --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 space-y-5">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-teal-50 flex items-center justify-center">
                    <i class="fa-solid fa-stethoscope text-teal-500 text-xs"></i>
                </span>
                Hasil Pemeriksaan
            </h3>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Diagnosis <span class="text-red-500">*</span>
                </label>
                <textarea name="diagnosis" rows="3" required
                    placeholder="Contoh: Hipertensi grade I, ISPA ringan, Diabetes Mellitus Tipe 2..."
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500/30 focus:border-teal-400 resize-none transition">{{ old('diagnosis') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Rencana Pengobatan <span class="text-red-500">*</span>
                </label>
                <textarea name="treatment_plan" rows="4" required
                    placeholder="Contoh: Istirahat cukup, minum obat sesuai resep, kontrol 1 minggu lagi..."
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500/30 focus:border-teal-400 resize-none transition">{{ old('treatment_plan') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Catatan Tambahan
                    <span class="text-xs text-gray-400 font-normal ml-1">(opsional)</span>
                </label>
                <textarea name="notes" rows="3"
                    placeholder="Catatan khusus, pantangan, saran gaya hidup, dll..."
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-white text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-teal-500/30 focus:border-teal-400 resize-none transition">{{ old('notes') }}</textarea>
            </div>
        </div>

        {{-- Status Kasus --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <h3 class="font-bold text-gray-800 flex items-center gap-2 mb-4">
                <span class="w-7 h-7 rounded-lg bg-violet-50 flex items-center justify-center">
                    <i class="fa-solid fa-tag text-violet-500 text-xs"></i>
                </span>
                Status Kasus
            </h3>

            <div class="grid grid-cols-2 gap-3">
                {{-- Opsi: Active --}}
                <label class="relative flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all
                    {{ old('case_status', 'active') === 'active' ? 'border-amber-400 bg-amber-50' : 'border-gray-200 bg-white hover:border-amber-200 hover:bg-amber-50/40' }}"
                    id="label-active">
                    <input type="radio" name="case_status" value="active"
                        {{ old('case_status', 'active') === 'active' ? 'checked' : '' }}
                        class="mt-0.5 accent-amber-500"
                        onchange="updateStatusStyle(this)">
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">Masih Aktif</p>
                        <p class="text-xs text-gray-400 mt-0.5">Pasien perlu kontrol lanjutan atau masih dalam pengobatan.</p>
                    </div>
                </label>

                {{-- Opsi: Healed --}}
                <label class="relative flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all
                    {{ old('case_status') === 'healed' ? 'border-emerald-400 bg-emerald-50' : 'border-gray-200 bg-white hover:border-emerald-200 hover:bg-emerald-50/40' }}"
                    id="label-healed">
                    <input type="radio" name="case_status" value="healed"
                        {{ old('case_status') === 'healed' ? 'checked' : '' }}
                        class="mt-0.5 accent-emerald-500"
                        onchange="updateStatusStyle(this)">
                    <div>
                        <p class="font-semibold text-gray-800 text-sm">Sembuh</p>
                        <p class="text-xs text-gray-400 mt-0.5">Pasien dinyatakan sembuh dan tidak perlu kontrol lagi.</p>
                    </div>
                </label>
            </div>
        </div>

        {{-- Info resep --}}
        <div class="bg-blue-50 border border-blue-100 rounded-2xl px-5 py-4 text-xs text-blue-700">
            <p class="font-semibold mb-1">
                <i class="fa-solid fa-circle-info mr-1"></i> Resep Obat
            </p>
            <p class="text-blue-600">
                Setelah rekam medis disimpan, kamu bisa menambahkan resep obat melalui halaman
                <a href="{{ route('doctor.prescription') }}" class="underline font-semibold hover:text-blue-800">Daftar Resep</a>
                (opsional). Jika tidak diperlukan, langsung selesaikan appointment dari halaman
                <a href="{{ route('doctor.today') }}" class="underline font-semibold hover:text-blue-800">Jadwal Hari Ini</a>.
            </p>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3 pb-6">
            <a href="{{ route('doctor.today') }}"
                class="px-5 py-2.5 rounded-xl border border-gray-200 bg-white text-gray-700 text-sm font-semibold hover:bg-gray-50 shadow-sm transition-colors">
                Batal
            </a>
            <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-gradient-to-r from-teal-500 to-cyan-600 text-white text-sm font-semibold hover:from-teal-600 hover:to-cyan-700 shadow-sm transition-all active:scale-95">
                <i class="fa-solid fa-floppy-disk"></i>
                Simpan Rekam Medis
            </button>
        </div>
    </form>
</div>

<script>
    function updateStatusStyle(radio) {
        const labelActive = document.getElementById('label-active');
        const labelHealed = document.getElementById('label-healed');

        // Reset both
        labelActive.className = labelActive.className
            .replace('border-amber-400 bg-amber-50', 'border-gray-200 bg-white hover:border-amber-200 hover:bg-amber-50/40');
        labelHealed.className = labelHealed.className
            .replace('border-emerald-400 bg-emerald-50', 'border-gray-200 bg-white hover:border-emerald-200 hover:bg-emerald-50/40');

        if (radio.value === 'active') {
            labelActive.className = labelActive.className
                .replace('border-gray-200 bg-white hover:border-amber-200 hover:bg-amber-50/40', 'border-amber-400 bg-amber-50');
        } else {
            labelHealed.className = labelHealed.className
                .replace('border-gray-200 bg-white hover:border-emerald-200 hover:bg-emerald-50/40', 'border-emerald-400 bg-emerald-50');
        }
    }
</script>

</main>
</div>
</div>
</body>
</html>