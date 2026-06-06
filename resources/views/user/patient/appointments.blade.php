@extends('layouts.patient')

@section('title', 'My Appointments - HealthMesh')
@section('page_title', 'My Appointments')

@section('content')
    <!-- Header Page Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-gray-400">View and coordinate your clinic checkups and history</p>
        </div>

        <!-- Trigger Modal Button -->
        <button onclick="toggleModal(true)"
            class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 text-white rounded-2xl text-sm font-semibold shadow-lg shadow-teal-500/10 hover:shadow-teal-500/20 hover:-trangray-y-0.5 transition-all cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2".5 d="M12 4v16m8-8H4"></path>
            </svg>
            Book Appointment
        </button>
    </div>

    <!-- Filter Tabs Menu -->
    <div class="flex items-center gap-2 overflow-x-auto pb-2 -mx-6 px-6 sm:mx-0 sm:px-0">
        <a href="{{ route('patient.appointments') }}"
            class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-xl transition-colors {{ empty($status) ? 'bg-teal-500 text-white shadow-md shadow-teal-500/10' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            All Statuses
        </a>
        <a href="{{ route('patient.appointments', ['status' => 'scheduled']) }}"
            class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-xl transition-colors {{ $status === 'scheduled' ? 'bg-blue-500 text-white shadow-md shadow-blue-500/15' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            Scheduled ⏳
        </a>
        <a href="{{ route('patient.appointments', ['status' => 'confirmed']) }}"
            class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-xl transition-colors {{ $status === 'confirmed' ? 'bg-teal-500 text-white shadow-md shadow-teal-500/10' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            Confirmed ✓
        </a>
        <a href="{{ route('patient.appointments', ['status' => 'completed']) }}"
            class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-xl transition-colors {{ $status === 'completed' ? 'bg-green-500 text-white shadow-md shadow-green-500/15' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            Completed 🩺
        </a>
        <a href="{{ route('patient.appointments', ['status' => 'cancelled']) }}"
            class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-xl transition-colors {{ $status === 'cancelled' ? 'bg-red-500 text-white shadow-md shadow-red-500/15' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            Cancelled ✗
        </a>
    </div>

    <!-- Appointments List View -->
    <div class="space-y-4">
        @forelse ($appointments as $apt)
            <div
                class="bg-white rounded-3xl border border-gray-200 p-6 shadow-sm hover:shadow-md hover:-trangray-y-0.5 transition-all duration-300 flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                <div class="flex items-start gap-4">
                    <!-- Stethoscope Stencil Icon -->
                    <div class="p-3.5 bg-teal-50 text-teal-600 rounded-2xl flex-shrink-0 mt-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>

                    <div class="space-y-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h3 class="text-base font-bold text-gray-800">{{ $apt->doctor->name }}</h3>
                            <span
                                class="px-2.5 py-0.5 bg-gray-100 text-gray-600 text-[10px] font-bold uppercase rounded-md">{{ $apt->doctor->specialization->name }}</span>
                        </div>
                        <p class="text-xs text-gray-400 font-medium">{{ $apt->patientEnrollment->hospital->name }} &bull;
                            MRN: <span
                                class="font-bold text-gray-600">{{ $apt->patientEnrollment->medical_record_number }}</span>
                        </p>

                        <!-- Scheduled Date -->
                        <div class="flex flex-wrap items-center gap-4 text-xs font-semibold text-gray-500 mt-2">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                {{ $apt->scheduled_at->format('l, d M Y \a\t H:i') }}
                            </span>

                            @if ($apt->schedule)
                                <span class="flex items-center gap-1 text-[11px] text-gray-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Slot: {{ substr($apt->schedule->start_time, 0, 5) }} -
                                    {{ substr($apt->schedule->end_time, 0, 5) }}
                                </span>
                            @endif
                        </div>

                        <!-- Complaint description -->
                        <p
                            class="text-xs text-gray-500 mt-3 pt-2.5 border-t border-gray-100 leading-relaxed italic bg-gray-50/50 p-2.5 rounded-xl">
                            "{{ $apt->complaint }}"
                        </p>
                    </div>
                </div>

                <!-- Status Badge and Cancellations -->
                <div
                    class="flex items-center justify-between md:flex-col md:items-end gap-3 self-stretch md:self-center pt-4 md:pt-0 border-t md:border-t-0 border-gray-100">
                    <span
                        class="px-3 py-1 text-xs font-bold uppercase tracking-wider rounded-full {{ $apt->status === 'confirmed'
                            ? 'bg-teal-50 text-teal-700'
                            : ($apt->status === 'completed'
                                ? 'bg-green-50 text-green-700'
                                : ($apt->status === 'cancelled'
                                    ? 'bg-red-50 text-red-700'
                                    : 'bg-blue-50 text-blue-700')) }}">
                        {{ $apt->status }}
                    </span>

                    @if ($apt->status === 'scheduled')
                        <form action="{{ route('patient.appointments.cancel', $apt->id) }}" method="POST" class="m-0"
                            onsubmit="return confirm('Apakah Anda yakin ingin membatalkan jadwal konsultasi ini?')">
                            @csrf
                            <button type="submit"
                                class="px-4 py-2 text-xs font-semibold text-red-600 hover:bg-red-50 rounded-xl border border-red-200 transition-all cursor-pointer">
                                Cancel Schedule
                            </button>
                        </form>
                    @endif
                </div>

            </div>
        @empty
            <!-- Empty State Illustration -->
            <div class="bg-white rounded-3xl border border-gray-200 p-12 text-center space-y-4 shadow-sm">
                <div class="inline-flex p-5 bg-teal-50 text-teal-600 rounded-full">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <div class="space-y-1 max-w-sm mx-auto">
                    <h3 class="text-lg font-bold text-gray-800">No appointments found</h3>
                    <p class="text-sm text-gray-400 leading-normal">We couldn't find any scheduled slots matching that
                        filter. Set up your checkup details now.</p>
                </div>
                <button onclick="toggleModal(true)"
                    class="inline-flex items-center gap-1.5 px-5 py-3 bg-teal-500 text-white hover:bg-teal-600 rounded-2xl text-xs font-semibold shadow-md shadow-teal-500/10 hover:shadow-teal-500/20 transition-all cursor-pointer">
                    Schedule Checkup
                </button>
            </div>
        @endforelse

        <!-- Laravel Pagination -->
        <div class="pt-4">
            {{ $appointments->appends(['status' => $status])->links() }}
        </div>
    </div>

    <!-- ================= BOOKING MODAL ================= -->
    <div id="booking-modal" class="fixed inset-0 z-50 overflow-y-auto hidden">
        <!-- Backdrop Blur -->
        <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity"></div>

        <!-- Modal Card Shell -->
        <div class="flex min-h-full items-center justify-center p-4 sm:p-6">
            <div
                class="relative w-full max-w-lg transform overflow-hidden rounded-3xl bg-white border border-gray-200 p-6 md:p-8 shadow-2xl transition-all space-y-6">

                <!-- Modal Header -->
                <div class="flex items-center justify-between pb-4 border-b border-gray-100">
                    <div class="space-y-1">
                        <h3 class="text-lg font-extrabold text-gray-800">New Consultation Schedule</h3>
                        <p class="text-xs text-gray-400">Fill in details to book an appointment</p>
                    </div>
                    <button onclick="toggleModal(false)"
                        class="p-2 text-gray-400 hover:text-gray-600 rounded-xl hover:bg-gray-50 transition-colors cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Booking Form -->
                <form action="{{ route('patient.appointments.store') }}" method="POST" class="space-y-4 m-0">
                    @csrf

                    <!-- Hospital Select -->
                    <div class="space-y-1.5">
                        <label for="hospital_id" class="text-xs font-bold text-gray-500 uppercase tracking-wider">Select
                            Hospital</label>
                        <select name="hospital_id" id="hospital_id" required onchange="filterDoctors()"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-transparent text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all outline-none">
                            <option value="">Choose a Hospital</option>
                            @foreach ($hospitals as $hosp)
                                <option value="{{ $hosp->id }}">{{ $hosp->name }} ({{ $hosp->city }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Doctor Select -->
                    <div class="space-y-1.5">
                        <label for="doctor_id" class="text-xs font-bold text-gray-500 uppercase tracking-wider">Select
                            Medical Professional</label>
                        <select name="doctor_id" id="doctor_id" required disabled
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-transparent text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all outline-none">
                            <option value="">Choose a Professional</option>
                            @foreach ($doctors as $doc)
                                <option value="{{ $doc->id }}" data-hospital-id="{{ $doc->user->hospital_id }}">
                                    {{ $doc->name }} ({{ $doc->specialization->name }}) - Rp
                                    {{ number_format($doc->consultation_fee, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        <p id="doctor-hint" class="text-[10px] text-gray-400">Please choose a hospital first</p>
                    </div>

                    <!-- Date select -->
                    <div class="space-y-1.5">
                        <label for="appointment_date"
                            class="text-xs font-bold text-gray-500 uppercase tracking-wider">Appointment Date</label>
                        <select name="appointment_date" id="appointment_date" required disabled onchange="renderSlots()"
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-transparent text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all outline-none">
                            <option value="">Choose a date</option>
                        </select>
                    </div>

                    <!-- Slot select -->
                    <div class="space-y-1.5">
                        <label for="appointment_slot"
                            class="text-xs font-bold text-gray-500 uppercase tracking-wider">Appointment Time</label>
                        <select name="appointment_slot" id="appointment_slot" required disabled
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-transparent text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all outline-none">
                            <option value="">Choose a time</option>
                        </select>
                        <p id="schedule-hint" class="text-[10px] text-gray-400">Please choose doctor first</p>
                    </div>

                    <!-- Complaint -->
                    <div class="space-y-1.5">
                        <label for="complaint" class="text-xs font-bold text-gray-500 uppercase tracking-wider">Chief
                            Complaint / Reasons</label>
                        <textarea name="complaint" id="complaint" rows="3" required
                            placeholder="Describe your symptoms or purposes for consultation..."
                            class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-transparent text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all outline-none resize-none"></textarea>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" onclick="toggleModal(false)"
                            class="px-5 py-3 text-sm font-semibold text-gray-500 hover:bg-gray-50 rounded-2xl border border-gray-200 transition-all cursor-pointer">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-3 text-sm font-semibold bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 text-white rounded-2xl shadow-lg shadow-teal-500/10 hover:shadow-teal-500/20 hover:-trangray-y-0.5 transition-all cursor-pointer">
                            Confirm Booking
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Modal toggle visibility
        function toggleModal(show) {
            const modal = document.getElementById('booking-modal');
            if (show) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            } else {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        // Preloaded scheduleByDoctorAndDate from backend (doctorId -> date(Y-m-d) -> slots[])
        const scheduleByDoctorAndDate = @json($scheduleByDoctorAndDate ?? []);

        function resetScheduleUI() {
            const dateSelect = document.getElementById('appointment_date');
            const slotSelect = document.getElementById('appointment_slot');

            dateSelect.value = '';
            slotSelect.value = '';

            dateSelect.innerHTML = '<option value="">Choose a date</option>';
            slotSelect.innerHTML = '<option value="">Choose a time</option>';

            dateSelect.disabled = true;
            slotSelect.disabled = true;
        }

        function renderDates() {
            const doctorSelect = document.getElementById('doctor_id');
            const dateSelect = document.getElementById('appointment_date');
            const slotSelect = document.getElementById('appointment_slot');
            const hint = document.getElementById('schedule-hint');

            const doctorId = doctorSelect.value;
            if (!doctorId) {
                resetScheduleUI();
                if (hint) hint.textContent = 'Please choose doctor first';
                return;
            }

            // Fill available dates only (those that exist in scheduleByDoctorAndDate)
            dateSelect.disabled = false;
            slotSelect.disabled = true;
            slotSelect.innerHTML = '<option value="">Choose a time</option>';
            slotSelect.value = '';

            const doctorData = scheduleByDoctorAndDate[doctorId] || {};
            const dates = Object.keys(doctorData).sort();

            dateSelect.innerHTML = '<option value="">Choose a date</option>';
            dates.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d;
                opt.textContent = d;
                dateSelect.appendChild(opt);
            });

            if (dates.length === 0) {
                if (hint) hint.textContent = 'No available schedules for this doctor in the next 14 days';
                slotSelect.disabled = true;
            } else {
                if (hint) hint.textContent = 'Pilih tanggal untuk melihat slot';
            }
        }

        function renderSlots() {
            const doctorSelect = document.getElementById('doctor_id');
            const dateSelect = document.getElementById('appointment_date');
            const slotSelect = document.getElementById('appointment_slot');

            const doctorId = doctorSelect.value;
            const date = dateSelect.value;

            slotSelect.innerHTML = '<option value="">Choose a time</option>';
            slotSelect.value = '';

            if (!doctorId || !date) {
                slotSelect.disabled = true;
                return;
            }

            const slots = (scheduleByDoctorAndDate?.[doctorId]?.[date]) || [];
            slotSelect.disabled = false;

            slots.forEach(t => {
                const opt = document.createElement('option');
                opt.value = t;
                opt.textContent = t;
                slotSelect.appendChild(opt);
            });

            if (slots.length === 0) {
                slotSelect.disabled = true;
            }
        }

        // Dynamic Doctor filtering based on Hospital ID selection
        function filterDoctors() {
            const hospitalSelect = document.getElementById('hospital_id');
            const doctorSelect = document.getElementById('doctor_id');
            const hint = document.getElementById('doctor-hint');

            const selectedHospitalId = hospitalSelect.value;

            // Reset doctor selection
            doctorSelect.value = "";
            resetScheduleUI();

            if (!selectedHospitalId) {
                doctorSelect.disabled = true;
                hint.textContent = "Please choose a hospital first";
                return;
            }

            doctorSelect.disabled = false;
            hint.textContent = "Only medical professionals at the chosen hospital are displayed";

            const options = doctorSelect.options;
            let countVisible = 0;

            for (let i = 0; i < options.length; i++) {
                const option = options[i];
                const hospId = option.getAttribute('data-hospital-id');

                if (!option.value) {
                    // Keep the placeholder visible
                    option.style.display = "block";
                    continue;
                }

                if (hospId === selectedHospitalId) {
                    option.style.display = "block";
                    countVisible++;
                } else {
                    option.style.display = "none";
                }
            }

            if (countVisible === 0) {
                hint.textContent = "No medical professionals registered at this hospital yet";
            }
        }

        // Trigger date rendering when doctor changes
        document.addEventListener('DOMContentLoaded', () => {
            const doctorSelect = document.getElementById('doctor_id');
            if (doctorSelect) {
                doctorSelect.addEventListener('change', () => {
                    renderDates();
                });
            }
            resetScheduleUI();
        });
    </script>
@endsection
