<x-filament-panels::page>

    {{-- CARD STATS --}}
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;">

        <div style="background:white;padding:25px;border-radius:18px;box-shadow:0 1px 4px rgba(0,0,0,.08);">
            <div style="font-size:14px;color:gray;">Total Visits</div>

            <div style="font-size:38px;font-weight:bold;margin-top:10px;">
                {{ $this->totalVisits }}
            </div>
        </div>

        <div style="background:white;padding:25px;border-radius:18px;box-shadow:0 1px 4px rgba(0,0,0,.08);">
            <div style="font-size:14px;color:gray;">Total Revenue</div>

            <div style="font-size:38px;font-weight:bold;margin-top:10px;">
                Rp {{ number_format($this->totalRevenue, 0, ',', '.') }}
            </div>
        </div>

        <div style="background:white;padding:25px;border-radius:18px;box-shadow:0 1px 4px rgba(0,0,0,.08);">
            <div style="font-size:14px;color:gray;">New Patients</div>

            <div style="font-size:38px;font-weight:bold;margin-top:10px;">
                {{ $this->newPatients }}
            </div>
        </div>

        <div style="background:white;padding:25px;border-radius:18px;box-shadow:0 1px 4px rgba(0,0,0,.08);">
            <div style="font-size:14px;color:gray;">Appointments</div>

            <div style="font-size:38px;font-weight:bold;margin-top:10px;">
                {{ $this->appointments }}
            </div>
        </div>

    </div>


    {{-- TOP DOCTOR & REVENUE --}}
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:25px;">

        {{-- TOP DOCTORS --}}
        <div style="background:white;padding:25px;border-radius:18px;height:320px;box-shadow:0 1px 4px rgba(0,0,0,.08);">

            <h2 style="font-size:22px;font-weight:bold;margin-bottom:20px;">
                Top Doctors by Visits
            </h2>

            @forelse($this->topDoctors as $doctor)

                <div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid #eee;">

                    <span>
                        {{ $doctor['name'] }}
                    </span>

                    <span style="font-weight:bold;color:#06b6d4;">
                        {{ $doctor['visits'] }} Visits
                    </span>

                </div>

            @empty

                <div style="height:230px;display:flex;justify-content:center;align-items:center;color:gray;">
                    No data available
                </div>

            @endforelse

        </div>


        {{-- HOSPITAL REVENUE --}}
        <div style="background:white;padding:25px;border-radius:18px;height:320px;box-shadow:0 1px 4px rgba(0,0,0,.08);">

            <h2 style="font-size:22px;font-weight:bold;margin-bottom:20px;">
                Revenue by Hospital
            </h2>

            @forelse($this->hospitalRevenue as $hospital)

                <div style="display:flex;justify-content:space-between;padding:12px 0;border-bottom:1px solid #eee;">

                    <span>
                        {{ $hospital['hospital'] }}
                    </span>

                    <span style="font-weight:bold;color:green;">
                        Rp {{ number_format($hospital['revenue'], 0, ',', '.') }}
                    </span>

                </div>

            @empty

                <div style="height:230px;display:flex;justify-content:center;align-items:center;color:gray;">
                    No data available
                </div>

            @endforelse

        </div>

    </div>


    {{-- RECENT APPOINTMENTS --}}
    <div style="background:white;padding:25px;border-radius:18px;margin-top:25px;box-shadow:0 1px 4px rgba(0,0,0,.08);">

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">

            <h2 style="font-size:22px;font-weight:bold;">
                Recent Appointments
            </h2>

            {{-- BUTTON VIEW ALL --}}
            <a
                href="/admin/appointments"
                style="
                    color:#06b6d4;
                    text-decoration:none;
                    font-weight:600;
                    font-size:15px;
                "
            >
                View all visits →
            </a>

        </div>

        <table style="width:100%;border-collapse:collapse;">

            <thead>
                <tr style="border-bottom:1px solid #ddd;">

                    <th style="padding:14px;text-align:left;">
                        PATIENT
                    </th>

                    <th style="padding:14px;text-align:left;">
                        DOCTOR
                    </th>

                    <th style="padding:14px;text-align:left;">
                        HOSPITAL
                    </th>

                    <th style="padding:14px;text-align:left;">
                        DATE
                    </th>

                    <th style="padding:14px;text-align:left;">
                        STATUS
                    </th>

                </tr>
            </thead>

            <tbody>

                @forelse($this->recentAppointments as $appointment)

                    <tr style="border-bottom:1px solid #eee;">

                        <td style="padding:14px;">
                            {{ $appointment['patient'] }}
                        </td>

                        <td style="padding:14px;">
                            {{ $appointment['doctor'] }}
                        </td>

                        <td style="padding:14px;">
                            {{ $appointment['hospital'] }}
                        </td>

                        <td style="padding:14px;">
                            {{ $appointment['date'] }}
                        </td>

                        <td style="padding:14px;">

                            <span style="
                                padding:6px 12px;
                                border-radius:999px;
                                background:#dcfce7;
                                color:#166534;
                                font-size:13px;
                                font-weight:600;
                            ">
                                {{ $appointment['status'] }}
                            </span>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5"
                            style="
                                text-align:center;
                                padding:50px;
                                color:gray;
                            ">

                            No appointments found

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</x-filament-panels::page>