<x-filament-panels::page>

<style>
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

    .dashboard-card{
        background:rgba(148,163,184,.20);
        padding:25px;
        border-radius:18px;
        border:1px solid var(--c-gray-200);
        box-shadow:0 1px 4px rgba(0,0,0,.08);
    }

    /* Filament usually toggles dark mode via .dark class on the root */
    .dark .dashboard-card{
        background:rgba(15,23,42,.55);
        border-color:rgba(148,163,184,.25);
        box-shadow:0 1px 6px rgba(0,0,0,.30);
    }

    .table-wrapper{
        overflow-x:auto;
    }

    .stat-label{
        font-size:14px;
        color:var(--c-gray-500);
    }

    .stat-number{
        font-size:38px;
        font-weight:bold;
        margin-top:10px;
        color:var(--c-gray-900);
    }

    .dark .stat-number{
        color:rgba(226,232,240,.95);
    }

    .card-title{
        font-size:22px;
        font-weight:bold;
        margin-bottom:20px;
        color:var(--c-gray-900);
    }

    .dark .card-title{
        color:rgba(226,232,240,.95);
    }

    .row-item{
        display:flex;
        justify-content:space-between;
        align-items:center;
        padding:14px 0;
        border-bottom:1px solid var(--c-gray-200);
        gap:12px;
    }

    .dark .row-item{
        border-bottom-color:rgba(148,163,184,.22);
    }

    .row-item span:first-child{
        color:var(--c-gray-700);
    }

    .dark .row-item span:first-child{
        color:rgba(203,213,225,.95);
    }

    .visit-badge{
        font-weight:bold;
        color:#06b6d4;
        white-space:nowrap;
    }

    .revenue-badge{
        font-weight:bold;
        color:#16a34a;
        white-space:nowrap;
    }

    .table-wrapper table{
        width:100%;
        border-collapse:collapse;
    }

    .table-wrapper th{
        padding:14px;
        text-align:left;
        color:var(--c-gray-500);
    }

    .table-wrapper td{
        padding:14px;
        border-bottom:1px solid var(--c-gray-200);
        color:var(--c-gray-700);
    }

    .dark .table-wrapper td{
        border-bottom-color:rgba(148,163,184,.22);
        color:rgba(203,213,225,.95);
    }

    .status-badge{
        padding:6px 12px;
        border-radius:999px;
        background:rgba(34,197,94,.12);
        border:1px solid rgba(34,197,94,.35);
        color:#166534;
        font-size:13px;
        font-weight:600;
        white-space:nowrap;
    }

    .dark .status-badge{
        background:rgba(34,197,94,.14);
        border-color:rgba(34,197,94,.30);
        color:rgba(110,231,183,.95);
    }

    .view-all-link{
        color:#06b6d4;
        text-decoration:none;
        font-weight:600;
        font-size:15px;
    }

    .dark .view-all-link{
        color:#67e8f9;
    }

    .empty-state{
        height:230px;
        display:flex;
        justify-content:center;
        align-items:center;
        color:var(--c-gray-500);
    }

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

        <div class="stat-label">
            Total Visits
        </div>

        <div class="stat-number">
            {{ $this->totalVisits }}
        </div>

    </div>


    {{-- TOTAL REVENUE --}}
    <div class="dashboard-card">

        <div class="stat-label">
            Total Revenue
        </div>

        <div class="stat-number">
            Rp {{ number_format($this->totalRevenue, 0, ',', '.') }}
        </div>

    </div>


    {{-- NEW PATIENTS --}}
    <div class="dashboard-card">

        <div class="stat-label">
            New Patients
        </div>

        <div class="stat-number">
            {{ $this->newPatients }}
        </div>

    </div>


    {{-- APPOINTMENTS --}}
    <div class="dashboard-card">

        <div class="stat-label">
            Appointments
        </div>

        <div class="stat-number">
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

        <h2 class="card-title">
            Top Doctors by Visits
        </h2>

        @forelse($this->topDoctors as $doctor)

            <div class="row-item">

                <span>
                    {{ $doctor['name'] }}
                </span>

                <span class="visit-badge">
                    {{ $doctor['visits'] }} Visits
                </span>

            </div>

        @empty

            <div class="empty-state">
                No data available
            </div>

        @endforelse

    </div>


    {{-- HOSPITAL REVENUE --}}
    <div class="dashboard-card">

        <h2 class="card-title">
            Revenue by Hospital
        </h2>

        @forelse($this->hospitalRevenue as $hospital)

            <div class="row-item">

                <span>
                    {{ $hospital['hospital'] }}
                </span>

                <span class="revenue-badge">
                    Rp {{ number_format($hospital['revenue'], 0, ',', '.') }}
                </span>

            </div>

        @empty

            <div class="empty-state">
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

        <h2 class="card-title">
            Recent Appointments
        </h2>

        <a
            href="/admin/appointments"
            class="view-all-link"
        >
            View all visits
        </a>

    </div>


    {{-- TABLE --}}
    <div class="table-wrapper">

        <table>

            <thead>

                <tr>
                    <th>PATIENT</th>
                    <th>DOCTOR</th>
                    <th>HOSPITAL</th>
                    <th>DATE</th>
                    <th>STATUS</th>
                </tr>

            </thead>


            <tbody>

                @forelse($this->recentAppointments as $appointment)

                    <tr>

                        <td>{{ $appointment['patient'] }}</td>
                        <td>{{ $appointment['doctor'] }}</td>
                        <td>{{ $appointment['hospital'] }}</td>
                        <td>{{ $appointment['date'] }}</td>
                        <td>
                            <span class="status-badge">
                                {{ $appointment['status'] }}
                            </span>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" style="text-align:center;padding:50px;">
                            No appointments found
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</x-filament-panels::page>