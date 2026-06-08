<x-filament-panels::page>

<style>
    .walkin-card {
        background: rgba(255,255,255,0.995);
        border: 1px solid var(--c-gray-200);
        border-radius: 18px;
        box-shadow: 0 1px 4px rgba(0,0,0,.08);
        padding: 32px;
        max-width: 600px;
    }
    .dark .walkin-card {
        background: rgba(15,23,42,.55);
        border-color: rgba(148,163,184,.25);
    }

    .walkin-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--c-gray-900);
        margin-bottom: 4px;
    }
    .dark .walkin-title { color: rgba(226,232,240,.95); }

    .walkin-desc {
        font-size: 14px;
        color: var(--c-gray-500);
        margin-bottom: 28px;
    }

    .walkin-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: var(--c-gray-700);
        margin-bottom: 6px;
    }
    .dark .walkin-label { color: var(--c-gray-300); }

    .walkin-input,
    .walkin-select,
    .walkin-textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid var(--c-gray-300);
        border-radius: 10px;
        font-size: 14px;
        color: var(--c-gray-800);
        background: #fff;
        outline: none;
        box-sizing: border-box;
        transition: border-color .15s;
    }
    .walkin-input:focus,
    .walkin-select:focus,
    .walkin-textarea:focus {
        border-color: #14b8a6;
        box-shadow: 0 0 0 2px rgba(20,184,166,.15);
    }
    .dark .walkin-input,
    .dark .walkin-select,
    .dark .walkin-textarea {
        background: rgba(2,6,23,.4);
        border-color: rgba(148,163,184,.25);
        color: rgba(226,232,240,.95);
    }

    .walkin-field { margin-bottom: 20px; }

    .walkin-error {
        margin-top: 4px;
        font-size: 12px;
        color: #dc2626;
    }

    .walkin-hint {
        margin-top: 4px;
        font-size: 12px;
        color: #d97706;
    }

    .walkin-actions {
        display: flex;
        gap: 12px;
        margin-top: 28px;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: #f59e0b;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: background .15s;
    }
    .btn-submit:hover { background: #d97706; }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        background: transparent;
        border: 1px solid var(--c-gray-300);
        color: var(--c-gray-600);
        font-size: 14px;
        font-weight: 600;
        border-radius: 10px;
        text-decoration: none;
        transition: background .15s;
    }
    .btn-cancel:hover { background: var(--c-gray-100); }

    .alert-success {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        padding: 12px 16px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        font-size: 14px;
        color: #15803d;
    }

    .alert-error {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        padding: 12px 16px;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 10px;
        font-size: 14px;
        color: #dc2626;
    }
</style>

{{-- Flash --}}
@if (session('success'))
    <div class="alert-success">✓ {{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert-error">✕ {{ session('error') }}</div>
@endif

<div class="walkin-card">

    <div class="walkin-title">Form Pendaftaran Walk-in</div>
    <div class="walkin-desc">
        Daftarkan pasien yang datang langsung tanpa appointment.
        Pasien akan masuk antrian dengan prioritas walk-in (setelah pasien appointment).
    </div>

    <form method="POST" action="{{ route('admin.walk-in.store') }}">
        @csrf

        {{-- PASIEN --}}
        <div class="walkin-field">
            <label class="walkin-label">Pasien <span style="color:#dc2626">*</span></label>
            <select name="patient_enrollment_id" required class="walkin-select">
                <option value="">-- Pilih Pasien --</option>
                @foreach ($patients as $id => $label)
                    <option value="{{ $id }}" {{ old('patient_enrollment_id') == $id ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('patient_enrollment_id')
                <div class="walkin-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- DOKTER --}}
        <div class="walkin-field">
            <label class="walkin-label">Dokter (Jadwal Hari Ini) <span style="color:#dc2626">*</span></label>
            <select name="doctor_id" required class="walkin-select">
                <option value="">-- Pilih Dokter --</option>
                @foreach ($doctors as $doctor)
                    <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                        dr. {{ $doctor->user->name }}{{ $doctor->specialization ? ' — ' . $doctor->specialization->name : '' }}
                    </option>
                @endforeach
            </select>
            @error('doctor_id')
                <div class="walkin-error">{{ $message }}</div>
            @enderror
            @if ($doctors->isEmpty())
                <div class="walkin-hint">⚠ Tidak ada dokter yang memiliki jadwal hari ini.</div>
            @endif
        </div>

        {{-- KELUHAN --}}
        <div class="walkin-field">
            <label class="walkin-label">Keluhan <span style="color:#dc2626">*</span></label>
            <textarea
                name="complaint"
                rows="3"
                required
                placeholder="Tuliskan keluhan pasien..."
                class="walkin-textarea"
            >{{ old('complaint') }}</textarea>
            @error('complaint')
                <div class="walkin-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- ACTIONS --}}
        <div class="walkin-actions">
            <button type="submit" class="btn-submit">
                ＋ Daftarkan ke Antrian
            </button>
            <a href="{{ route('filament.admin.resources.queues.index') }}" class="btn-cancel">
                Batal
            </a>
        </div>

    </form>

</div>

</x-filament-panels::page>