@extends('layouts.patient')

@section('title', 'My Appointments - HealthMesh')
@section('page_title', 'My Appointments')

@section('content')
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
        <p class="text-sm text-gray-400">View and coordinate your clinic checkups and history</p>
        <button onclick="toggleModal(true)"
            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-[#14b8a6] hover:bg-[#0d9488] text-white rounded-2xl text-sm font-semibold shadow-lg shadow-teal-500/10 transition-all w-full sm:w-auto">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Book Appointment
        </button>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-2 mb-3 scrollbar-none -mx-3 px-3 sm:mx-0 sm:px-0">
        @php
            $tabs = [
                null          => 'All',
                'scheduled'   => '⏳ Scheduled',
                'confirmed'   => '✓ Confirmed',
                'completed'   => '🩺 Completed',
                'cancelled'   => '✗ Cancelled',
            ];
            $tabColors = [
                null          => 'bg-teal-500 text-white',
                'scheduled'   => 'bg-blue-500 text-white',
                'confirmed'   => 'bg-teal-500 text-white',
                'completed'   => 'bg-green-500 text-white',
                'cancelled'   => 'bg-red-500 text-white',
            ];
        @endphp
        @foreach($tabs as $val => $label)
            <a href="{{ route('patient.appointments', $val ? ['status' => $val] : []) }}"
               class="shrink-0 px-3 py-1.5 text-xs font-semibold rounded-xl transition-colors whitespace-nowrap
                      {{ ($status ?? null) === $val
                            ? ($tabColors[$val] ?? 'bg-teal-500 text-white')
                            : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Appointment Cards --}}
    <div class="space-y-3">
        @forelse ($appointments as $apt)
            @php
                $statusColor = match($apt->status) {
                    'confirmed' => 'bg-teal-50 text-teal-700 border-teal-200',
                    'completed' => 'bg-green-50 text-green-700 border-green-200',
                    'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                    default     => 'bg-blue-50 text-blue-700 border-blue-200',
                };
            @endphp
            <div class="bg-white rounded-2xl border border-gray-200 p-3 sm:p-4 shadow-sm">

                {{-- Top row: icon + name + status badge --}}
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-start gap-3 min-w-0">
                        <div class="p-2.5 bg-teal-50 text-teal-600 rounded-xl shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="font-bold text-gray-800 text-sm truncate">{{ $apt->doctor->name }}</p>
                            <p class="text-[11px] text-gray-400 mt-0.5">{{ $apt->doctor->specialization->name }}</p>
                        </div>
                    </div>
                    <span class="shrink-0 px-2.5 py-1 text-[11px] font-bold uppercase rounded-full border {{ $statusColor }}">
                        {{ $apt->status }}
                    </span>
                </div>

                {{-- Details grid --}}
                <div class="mt-2 grid grid-cols-2 gap-x-3 gap-y-1.5 text-xs">
                    <div>
                        <p class="text-gray-400 font-medium">Rumah Sakit</p>
                        <p class="text-gray-700 font-semibold truncate">{{ $apt->patientEnrollment->hospital->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 font-medium">No. RM</p>
                        <p class="text-gray-700 font-semibold">{{ $apt->patientEnrollment->medical_record_number }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 font-medium">Tanggal</p>
                        <p class="text-gray-700 font-semibold">{{ $apt->scheduled_at->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 font-medium">Jam</p>
                        <p class="text-gray-700 font-semibold">
                            {{ $apt->scheduled_at->format('H:i') }}
                            @if($apt->schedule)
                                <span class="text-gray-400">({{ substr($apt->schedule->start_time,0,5) }}–{{ substr($apt->schedule->end_time,0,5) }})</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Complaint --}}
                <p class="mt-2 text-xs text-gray-500 italic bg-gray-50 rounded-xl px-2.5 py-1.5 leading-relaxed line-clamp-2">
                    "{{ $apt->complaint }}"
                </p>

                {{-- Cancel button --}}
                @if ($apt->status === 'scheduled')
                    <div class="mt-2 flex justify-end">
                        <form action="{{ route('patient.appointments.cancel', $apt->id) }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit"
                                class="px-4 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50 rounded-xl border border-red-200 transition-all">
                                Cancel Schedule
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-200 p-10 text-center shadow-sm">
                <div class="inline-flex p-4 bg-teal-50 text-teal-500 rounded-full mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-base font-bold text-gray-800">No appointments found</h3>
                <p class="text-sm text-gray-400 mt-1 mb-4">No scheduled slots matching that filter.</p>
                <button onclick="toggleModal(true)"
                    class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-teal-500 text-white hover:bg-teal-600 rounded-2xl text-xs font-semibold transition-all">
                    Schedule Checkup
                </button>
            </div>
        @endforelse

        <div class="pt-2">{{ $appointments->appends(['status' => $status])->links() }}</div>
    </div>

    {{-- ===== BOOKING MODAL ===== --}}
    <div id="booking-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
        <div class="flex min-h-full items-end sm:items-center justify-center p-0 sm:p-4">
            <div class="relative w-full sm:max-w-lg bg-white sm:rounded-3xl rounded-t-3xl border border-gray-200 shadow-2xl overflow-hidden">

                {{-- Handle bar (mobile) --}}
                <div class="flex justify-center pt-3 pb-1 sm:hidden">
                    <div class="w-10 h-1 bg-gray-300 rounded-full"></div>
                </div>

                {{-- Modal content: scrollable --}}
                <div class="overflow-y-auto max-h-[85vh] p-5 sm:p-7 space-y-5">

                    {{-- Header --}}
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="text-base font-extrabold text-gray-800">New Consultation Schedule</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Fill in details to book an appointment</p>
                        </div>
                        <button onclick="toggleModal(false)"
                            class="p-2 text-gray-400 hover:text-gray-600 rounded-xl hover:bg-gray-100 transition-colors shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Form --}}
                    <form action="{{ route('patient.appointments.store') }}" method="POST" class="space-y-4 m-0">
                        @csrf

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Hospital</label>
                            <select name="hospital_id" id="hospital_id" required onchange="filterDoctors()"
                                class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none">
                                <option value="">Choose a Hospital</option>
                                @foreach ($hospitals as $hosp)
                                    <option value="{{ $hosp->id }}">{{ $hosp->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Doctor</label>
                            <select name="doctor_id" id="doctor_id" required disabled
                                class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none disabled:opacity-60">
                                <option value="">Choose a Doctor</option>
                                @foreach ($doctors as $doc)
                                    <option value="{{ $doc->id }}" data-hospital-id="{{ $doc->user->hospital_id }}">
                                        {{ $doc->name }} ({{ $doc->specialization->name }}) — Rp {{ number_format($doc->consultation_fee, 0, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            <p id="doctor-hint" class="text-[10px] text-gray-400">Please choose a hospital first</p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Date</label>
                            <select name="appointment_date" id="appointment_date" required disabled onchange="renderSlots()"
                                class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none disabled:opacity-60">
                                <option value="">Choose a date</option>
                            </select>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Time Slot</label>
                            <select name="appointment_slot" id="appointment_slot" required disabled
                                class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none disabled:opacity-60">
                                <option value="">Choose a time</option>
                            </select>
                            <p id="schedule-hint" class="text-[10px] text-gray-400">Please choose doctor first</p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-bold text-gray-500 uppercase tracking-wider">Complaint</label>
                            <textarea name="complaint" id="complaint" rows="3" required
                                placeholder="Describe your symptoms..."
                                class="w-full px-3 py-2.5 rounded-xl border border-gray-200 text-sm focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 outline-none resize-none"></textarea>
                        </div>

                        <div class="flex gap-2 pt-2">
                            <button type="button" onclick="toggleModal(false)"
                                class="flex-1 py-2.5 text-sm font-semibold text-gray-500 bg-gray-100 hover:bg-gray-200 rounded-2xl transition-all">
                                Cancel
                            </button>
                            <button type="submit"
                                class="flex-1 py-2.5 bg-[#14b8a6] hover:bg-[#0d9488] text-white rounded-2xl text-sm font-semibold transition-all">
                                Confirm Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    function toggleModal(show) {
        const modal = document.getElementById('booking-modal');
        modal.classList.toggle('hidden', !show);
        document.body.classList.toggle('overflow-hidden', show);
    }

    const scheduleByDoctorAndDate = @json($scheduleByDoctorAndDate ?? []);

    function resetScheduleUI() {
        ['appointment_date','appointment_slot'].forEach(id => {
            const el = document.getElementById(id);
            el.value = '';
            el.innerHTML = `<option value="">Choose a ${id === 'appointment_date' ? 'date' : 'time'}</option>`;
            el.disabled = true;
        });
    }

    function renderDates() {
        const doctorId = document.getElementById('doctor_id').value;
        const dateEl   = document.getElementById('appointment_date');
        const hint     = document.getElementById('schedule-hint');
        resetScheduleUI();
        if (!doctorId) return;
        const dates = Object.keys(scheduleByDoctorAndDate[doctorId] || {}).sort();
        dateEl.disabled = false;
        dates.forEach(d => {
            const o = document.createElement('option');
            o.value = o.textContent = d;
            dateEl.appendChild(o);
        });
        hint.textContent = dates.length ? 'Pilih tanggal untuk melihat slot' : 'No available schedules in the next 14 days';
    }

    function renderSlots() {
        const doctorId = document.getElementById('doctor_id').value;
        const date     = document.getElementById('appointment_date').value;
        const slotEl   = document.getElementById('appointment_slot');
        slotEl.innerHTML = '<option value="">Choose a time</option>';
        slotEl.disabled = true;
        if (!doctorId || !date) return;
        const slots = scheduleByDoctorAndDate?.[doctorId]?.[date] || [];
        slotEl.disabled = slots.length === 0;
        slots.forEach(t => {
            const o = document.createElement('option');
            o.value = o.textContent = t;
            slotEl.appendChild(o);
        });
    }

    function filterDoctors() {
        const hospId    = document.getElementById('hospital_id').value;
        const doctorEl  = document.getElementById('doctor_id');
        const hint      = document.getElementById('doctor-hint');
        doctorEl.value  = '';
        resetScheduleUI();
        if (!hospId) { doctorEl.disabled = true; hint.textContent = 'Please choose a hospital first'; return; }
        doctorEl.disabled = false;
        let count = 0;
        [...doctorEl.options].forEach(o => {
            if (!o.value) return;
            const show = o.dataset.hospitalId === hospId;
            o.hidden = !show;
            if (show) count++;
        });
        hint.textContent = count ? 'Doctors at selected hospital' : 'No doctors registered at this hospital';
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('doctor_id')?.addEventListener('change', renderDates);
        resetScheduleUI();
    });
</script>
@endsection