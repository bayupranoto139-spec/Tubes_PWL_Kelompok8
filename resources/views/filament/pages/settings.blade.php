<x-filament-panels::page>

<style>
    .settings-container{
        width:100%;
        max-width:1200px;
        margin:auto;
    }

    .settings-subtitle{
        color:var(--c-gray-500);
        font-size:15px;
        margin-bottom:28px;
    }

    .settings-card{
        background:#e2e8f0;
        padding:25px;
        border-radius:18px;
        box-shadow:0 1px 4px rgba(0,0,0,.08);
        margin-bottom:24px;
        color: var(--c-gray-600);
    }

.dark .settings-card{
        background:rgba(15,23,42,.55);
        border:1px solid rgba(148,163,184,.25);
        box-shadow:0 1px 6px rgba(0,0,0,.30);
    }

    .settings-card{
        background:rgba(148,163,184,.20);
        border:1px solid var(--c-gray-200);
    }

    .settings-card-header{
        display:flex;
        align-items:flex-start;
        gap:14px;
        margin-bottom:24px;
    }

    .settings-icon{
        width:48px;
        height:48px;
        border-radius:14px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:20px;
    }

    .settings-card-title{
        font-size:22px;
        font-weight:700;
        color: var(--c-gray-700);
    }

    .settings-card-desc{
        color:var(--c-gray-500);
        font-size:14px;
        margin-top:4px;
    }

    .settings-label{
        font-weight:600;
        font-size:14px;
        color: var(--c-gray-600);
    }

.settings-input{
        width:100%;
        margin-top:10px;
        padding:14px;
        border:1px solid var(--c-gray-300);
        border-radius:14px;
        font-size:15px;
        outline:none;
        background:rgba(148,163,184,.10);
        color: var(--c-gray-600);
    }

    .dark .settings-input{
        background:rgba(2,6,23,.35);
        border-color:rgba(148,163,184,.22);
        color:rgba(226,232,240,.95);
    }

    .settings-hint{
        margin-top:7px;
        color:var(--c-gray-500);
        font-size:13px;
    }

    .settings-toggle-row{
        display:flex;
        justify-content:space-between;
        align-items:center;
        padding:22px;
        background:var(--c-gray-100);
        border-radius:18px;
        margin-bottom:16px;
        cursor:pointer;
        transition:.2s;
        color: var(--c-gray-600);
    }

    .settings-toggle-row:hover{
        background: var(--c-gray-200);
    }

    .toggle-title{
        font-weight:700;
        font-size:17px;
        color: var(--c-gray-700);
    }

    .toggle-desc{
        font-size:14px;
        color:var(--c-gray-500);
        margin-top:6px;
    }

    .toggle-checkbox{
        width:24px;
        height:24px;
        accent-color:#14b8a6;
        cursor:pointer;
    }

    .settings-save-btn{
        width:100%;
        padding:16px;
        background:#14b8a6;
        border:none;
        border-radius:16px;
        color:white;
        font-weight:700;
        cursor:pointer;
        font-size:15px;
        box-shadow:0 4px 10px rgba(20,184,166,.25);
        transition:.2s;
    }

    .settings-save-btn:hover{
        transform:translateY(-2px);
    }
</style>


<div class="settings-container">

    {{-- SUBTITLE --}}
    <div>
        <p class="settings-subtitle">
            Configure global system parameters
        </p>
    </div>


    {{-- APPOINTMENT SETTINGS --}}
    <div class="settings-card">

        <div class="settings-card-header">

            <div class="settings-icon" style="background:#ccfbf1;">
                📅
            </div>

            <div>
                <div class="settings-card-title">
                    Appointment Settings
                </div>

                <div class="settings-card-desc">
                    Configure appointment rules
                </div>
            </div>

        </div>


        <div style="margin-bottom:22px;">

            <label class="settings-label">
                Priority Time Limit (minutes)
            </label>

            <input
                type="number"
                value="30"
                class="settings-input"
            >

            <div class="settings-hint">
                Minutes before appointment when patient loses priority if not yet checked in.
            </div>

        </div>


        <div>

            <label class="settings-label">
                Max Advance Booking Days
            </label>

            <input
                type="number"
                value="30"
                class="settings-input"
            >

            <div class="settings-hint">
                How many days in advance patients can book appointments.
            </div>

        </div>

    </div>


    {{-- QUEUE SETTINGS --}}
    <div class="settings-card">

        <div class="settings-card-header">

            <div class="settings-icon" style="background:#dbeafe;">
                📋
            </div>

            <div>
                <div class="settings-card-title">
                    Queue Settings
                </div>

                <div class="settings-card-desc">
                    Configure queue management
                </div>
            </div>

        </div>


        <label class="settings-label">
            Queue Reset Time
        </label>

        <input
            type="time"
            value="00:00"
            class="settings-input"
        >

        <div class="settings-hint">
            Time of day when the queue counter resets to 1.
        </div>

    </div>


    {{-- NOTIFICATIONS --}}
    <div class="settings-card">

        <div class="settings-card-header">

            <div class="settings-icon" style="background:#f3e8ff;">
                🔔
            </div>

            <div>
                <div class="settings-card-title">
                    Notifications
                </div>

                <div class="settings-card-desc">
                    Configure system notifications
                </div>
            </div>

        </div>


        {{-- NEW APPOINTMENT --}}
        <div class="settings-toggle-row">

            <div>
                <div class="toggle-title">
                    Notify on new appointment
                </div>

                <div class="toggle-desc">
                    Send email to doctor when a new appointment is booked
                </div>
            </div>

            <input
                type="checkbox"
                checked
                class="toggle-checkbox"
            >

        </div>


        {{-- CANCELLATION --}}
        <div class="settings-toggle-row">

            <div>
                <div class="toggle-title">
                    Notify on cancellation
                </div>

                <div class="toggle-desc">
                    Send email when appointment is cancelled
                </div>
            </div>

            <input
                type="checkbox"
                checked
                class="toggle-checkbox"
            >

        </div>


        {{-- PAYMENT --}}
        <div class="settings-toggle-row">

            <div>
                <div class="toggle-title">
                    Notify on payment
                </div>

                <div class="toggle-desc">
                    Send receipt after payment
                </div>
            </div>

            <input
                type="checkbox"
                checked
                class="toggle-checkbox"
            >

        </div>

    </div>


    {{-- SAVE BUTTON --}}
    <button
        type="button"
        onclick="alert('Settings saved successfully!')"
        class="settings-save-btn"
    >
        💾 Save Settings
    </button>

</div>

</x-filament-panels::page>