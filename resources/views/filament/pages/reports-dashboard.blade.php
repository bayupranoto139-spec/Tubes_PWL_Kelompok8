<x-filament-panels::page>

<style>

    /*
    |--------------------------------------------------------------------------
    | GRID LAYOUT
    |--------------------------------------------------------------------------
    */

    .stats-grid{
        display:grid;
        grid-template-columns:repeat(4,1fr);
        gap:20px;
    }

    .two-grid{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:20px;
        margin-top:25px;
    }

    /*
    |--------------------------------------------------------------------------
    | CARD
    |--------------------------------------------------------------------------
    */

    .dashboard-card{
        background:white;
        padding:25px;
        border-radius:18px;
        box-shadow:0 1px 4px rgba(0,0,0,.08);
    }

    /*
    |--------------------------------------------------------------------------
    | TABLE
    |--------------------------------------------------------------------------
    */

    .table-wrapper{
        overflow-x:auto;
    }

    /*
    |--------------------------------------------------------------------------
    | RESPONSIVE
    |--------------------------------------------------------------------------
    */

    @media(max-width:1024px){

        .stats-grid{
            grid-template-columns:repeat(2,1fr);
        }

    }

    @media(max-width:768px){

        .stats-grid{
            grid-template-columns:1fr;
        }

        .two-grid{
            grid-template-columns:1fr;
        }

        .stat-number{
            font-size:28px !important;
        }

        .card-title{
            font-size:18px !important;
        }

        table{
            min-width:700px;
        }

    }

</style>


{{-- ========================================================= --}}
{{-- CARD STATS --}}
{{-- ========================================================= --}}

<div class="stats-grid">

    {{-- TOTAL VISITS --}}
    <div class="dashboard-card">

        <div style="
            font-size:14px;
            color:#6b7280;
        ">
            Total Visits
        </div>

        <div class="stat-number"
             style="
                font-size:38px;
                font-weight:bold;
                margin-top:10px;
             ">

            {{ $this->totalVisits }}

        </div>

    </div>


    {{-- TOTAL REVENUE --}}
    <div class="dashboard-card">

        <div style="
            font-size:14px;
            color:#6b7280;
        ">
            Total Revenue
        </div>

        <div class="stat-number"
             style="
                font-size:38px;
                font-weight:bold;
                margin-top:10px;
             ">

            Rp {{ number_format($this->totalRevenue, 0, ',', '.') }}

        </div>

    </div>


    {{-- NEW PATIENTS --}}
    <div class="dashboard-card">

        <div style="
            font-size:14px;
            color:#6b7280;
        ">
            New Patients
        </div>

        <div class="stat-number"
             style="
                font-size:38px;
                font-weight:bold;
                margin-top:10px;
             ">

            {{ $this->newPatients }}

        </div>

    </div>


    {{-- APPOINTMENTS --}}
    <div class="dashboard-card">

        <div style="
            font-size:14px;
            color:#6b7280;
        ">
            Appointments
        </div>

        <div class="stat-number"
             style="
                font-size:38px;
                font-weight:bold;
                margin-top:10px;
             ">

            {{ $this->appointments }}

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- TOP DOCTORS & REVENUE --}}
{{-- ========================================================= --}}

<div class="two-grid">

    {{-- TOP DOCTORS --}}
    <div class="dashboard-card">

        <h2 class="card-title"
            style="
                font-size:22px;
                font-weight:bold;
                margin-bottom:20px;
            ">

            Top Doctors by Visits

        </h2>

        @forelse($this->topDoctors as $doctor)

            <div style="
                display:flex;
                justify-content:space-between;
                align-items:center;
                padding:14px 0;
                border-bottom:1px solid #eee;
                gap:12px;
            ">

                <span>
                    {{ $doctor['name'] }}
                </span>

                <span style="
                    font-weight:bold;
                    color:#06b6d4;
                    white-space:nowrap;
                ">
                    {{ $doctor['visits'] }} Visits
                </span>

            </div>

        @empty

            <div style="
                height:230px;
                display:flex;
                justify-content:center;
                align-items:center;
                color:gray;
            ">

                No data available

            </div>

        @endforelse

    </div>


    {{-- HOSPITAL REVENUE --}}
    <div class="dashboard-card">

        <h2 class="card-title"
            style="
                font-size:22px;
                font-weight:bold;
                margin-bottom:20px;
            ">

            Revenue by Hospital

        </h2>

        @forelse($this->hospitalRevenue as $hospital)

            <div style="
                display:flex;
                justify-content:space-between;
                align-items:center;
                padding:14px 0;
                border-bottom:1px solid #eee;
                gap:12px;
            ">

                <span>
                    {{ $hospital['hospital'] }}
                </span>

                <span style="
                    font-weight:bold;
                    color:#16a34a;
                    white-space:nowrap;
                ">

                    Rp {{ number_format($hospital['revenue'], 0, ',', '.') }}

                </span>

            </div>

        @empty

            <div style="
                height:230px;
                display:flex;
                justify-content:center;
                align-items:center;
                color:gray;
            ">

                No data available

            </div>

        @endforelse

    </div>

</div>


{{-- ========================================================= --}}
{{-- RECENT APPOINTMENTS --}}
{{-- ========================================================= --}}

<div class="dashboard-card" style="margin-top:25px;">

    {{-- HEADER --}}
    <div style="
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:20px;
        flex-wrap:wrap;
        gap:10px;
    ">

        <h2 class="card-title"
            style="
                font-size:22px;
                font-weight:bold;
            ">

            Recent Appointments

        </h2>

        {{-- VIEW ALL --}}
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


    {{-- TABLE --}}
    <div class="table-wrapper">

        <table style="
            width:100%;
            border-collapse:collapse;
        ">

            {{-- TABLE HEAD --}}
            <thead>

                <tr style="
                    border-bottom:1px solid #ddd;
                ">

                    <th style="
                        padding:14px;
                        text-align:left;
                    ">
                        PATIENT
                    </th>

                    <th style="
                        padding:14px;
                        text-align:left;
                    ">
                        DOCTOR
                    </th>

                    <th style="
                        padding:14px;
                        text-align:left;
                    ">
                        HOSPITAL
                    </th>

                    <th style="
                        padding:14px;
                        text-align:left;
                    ">
                        DATE
                    </th>

                    <th style="
                        padding:14px;
                        text-align:left;
                    ">
                        STATUS
                    </th>

                </tr>

            </thead>


            {{-- TABLE BODY --}}
            <tbody>

                @forelse($this->recentAppointments as $appointment)

                    <tr style="
                        border-bottom:1px solid #eee;
                    ">

                        {{-- PATIENT --}}
                        <td style="padding:14px;">

                            {{ $appointment['patient'] }}

                        </td>

                        {{-- DOCTOR --}}
                        <td style="padding:14px;">

                            {{ $appointment['doctor'] }}

                        </td>

                        {{-- HOSPITAL --}}
                        <td style="padding:14px;">

                            {{ $appointment['hospital'] }}

                        </td>

                        {{-- DATE --}}
                        <td style="padding:14px;">

                            {{ $appointment['date'] }}

                        </td>

                        {{-- STATUS --}}
                        <td style="padding:14px;">

                            <span style="
                                padding:6px 12px;
                                border-radius:999px;
                                background:#dcfce7;
                                color:#166534;
                                font-size:13px;
                                font-weight:600;
                                white-space:nowrap;
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

</div>

</x-filament-panels::page>