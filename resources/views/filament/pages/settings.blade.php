<x-filament-panels::page>

<div style="
    width:100%;
    max-width:1200px;
    margin:auto;
">

    {{-- SUBTITLE --}}
    <div style="margin-bottom:28px;">

        <p style="
            color:#6b7280;
            font-size:15px;
        ">
            Configure global system parameters
        </p>

    </div>


    {{-- APPOINTMENT SETTINGS --}}
    <div style="
        background:white;
        padding:32px;
        border-radius:24px;
        box-shadow:0 2px 10px rgba(0,0,0,.05);
        margin-bottom:24px;
    ">

        <div style="
            display:flex;
            align-items:flex-start;
            gap:14px;
            margin-bottom:24px;
        ">

            <div style="
                width:48px;
                height:48px;
                border-radius:14px;
                background:#ccfbf1;
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:20px;
            ">
                📅
            </div>

            <div>
                <div style="
                    font-size:22px;
                    font-weight:700;
                ">
                    Appointment Settings
                </div>

                <div style="
                    color:#6b7280;
                    font-size:14px;
                    margin-top:4px;
                ">
                    Configure appointment rules
                </div>
            </div>

        </div>


        <div style="margin-bottom:22px;">

            <label style="
                font-weight:600;
                font-size:14px;
            ">
                Priority Time Limit (minutes)
            </label>

            <input
                type="number"
                value="30"
                style="
                    width:100%;
                    margin-top:10px;
                    padding:14px;
                    border:1px solid #d1d5db;
                    border-radius:14px;
                    font-size:15px;
                    outline:none;
                "
            >

            <div style="
                margin-top:7px;
                color:#9ca3af;
                font-size:13px;
            ">
                Minutes before appointment when patient loses priority if not yet checked in.
            </div>

        </div>


        <div>

            <label style="
                font-weight:600;
                font-size:14px;
            ">
                Max Advance Booking Days
            </label>

            <input
                type="number"
                value="30"
                style="
                    width:100%;
                    margin-top:10px;
                    padding:14px;
                    border:1px solid #d1d5db;
                    border-radius:14px;
                    font-size:15px;
                    outline:none;
                "
            >

            <div style="
                margin-top:7px;
                color:#9ca3af;
                font-size:13px;
            ">
                How many days in advance patients can book appointments.
            </div>

        </div>

    </div>


    {{-- QUEUE SETTINGS --}}
    <div style="
        background:white;
        padding:32px;
        border-radius:24px;
        box-shadow:0 2px 10px rgba(0,0,0,.05);
        margin-bottom:24px;
    ">

        <div style="
            display:flex;
            align-items:flex-start;
            gap:14px;
            margin-bottom:24px;
        ">

            <div style="
                width:48px;
                height:48px;
                border-radius:14px;
                background:#dbeafe;
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:20px;
            ">
                📋
            </div>

            <div>
                <div style="
                    font-size:22px;
                    font-weight:700;
                ">
                    Queue Settings
                </div>

                <div style="
                    color:#6b7280;
                    font-size:14px;
                    margin-top:4px;
                ">
                    Configure queue management
                </div>
            </div>

        </div>


        <label style="
            font-weight:600;
            font-size:14px;
        ">
            Queue Reset Time
        </label>

        <input
            type="time"
            value="00:00"
            style="
                width:100%;
                margin-top:10px;
                padding:14px;
                border:1px solid #d1d5db;
                border-radius:14px;
                font-size:15px;
                outline:none;
            "
        >

        <div style="
            margin-top:7px;
            color:#9ca3af;
            font-size:13px;
        ">
            Time of day when the queue counter resets to 1.
        </div>

    </div>


    {{-- NOTIFICATIONS --}}
    <div style="
        background:white;
        padding:32px;
        border-radius:24px;
        box-shadow:0 2px 10px rgba(0,0,0,.05);
        margin-bottom:24px;
    ">

        <div style="
            display:flex;
            align-items:flex-start;
            gap:14px;
            margin-bottom:24px;
        ">

            <div style="
                width:48px;
                height:48px;
                border-radius:14px;
                background:#f3e8ff;
                display:flex;
                align-items:center;
                justify-content:center;
                font-size:20px;
            ">
                🔔
            </div>

            <div>
                <div style="
                    font-size:22px;
                    font-weight:700;
                ">
                    Notifications
                </div>

                <div style="
                    color:#6b7280;
                    font-size:14px;
                    margin-top:4px;
                ">
                    Configure system notifications
                </div>
            </div>

        </div>


        {{-- NEW APPOINTMENT --}}
        <label style="
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:22px;
            background:#f9fafb;
            border-radius:18px;
            margin-bottom:16px;
            cursor:pointer;
            transition:.2s;
        "
        onmouseover="this.style.background='#f3f4f6'"
        onmouseout="this.style.background='#f9fafb'"
        >

            <div>
                <div style="
                    font-weight:700;
                    font-size:17px;
                ">
                    Notify on new appointment
                </div>

                <div style="
                    font-size:14px;
                    color:#9ca3af;
                    margin-top:6px;
                ">
                    Send email to doctor when a new appointment is booked
                </div>
            </div>

            <input
                type="checkbox"
                checked
                style="
                    width:24px;
                    height:24px;
                    accent-color:#14b8a6;
                    cursor:pointer;
                "
            >

        </label>


        {{-- CANCELLATION --}}
        <label style="
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:22px;
            background:#f9fafb;
            border-radius:18px;
            margin-bottom:16px;
            cursor:pointer;
            transition:.2s;
        "
        onmouseover="this.style.background='#f3f4f6'"
        onmouseout="this.style.background='#f9fafb'"
        >

            <div>
                <div style="
                    font-weight:700;
                    font-size:17px;
                ">
                    Notify on cancellation
                </div>

                <div style="
                    font-size:14px;
                    color:#9ca3af;
                    margin-top:6px;
                ">
                    Send email when appointment is cancelled
                </div>
            </div>

            <input
                type="checkbox"
                checked
                style="
                    width:24px;
                    height:24px;
                    accent-color:#14b8a6;
                    cursor:pointer;
                "
            >

        </label>


        {{-- PAYMENT --}}
        <label style="
            display:flex;
            justify-content:space-between;
            align-items:center;
            padding:22px;
            background:#f9fafb;
            border-radius:18px;
            cursor:pointer;
            transition:.2s;
        "
        onmouseover="this.style.background='#f3f4f6'"
        onmouseout="this.style.background='#f9fafb'"
        >

            <div>
                <div style="
                    font-weight:700;
                    font-size:17px;
                ">
                    Notify on payment
                </div>

                <div style="
                    font-size:14px;
                    color:#9ca3af;
                    margin-top:6px;
                ">
                    Send receipt after payment
                </div>
            </div>

            <input
                type="checkbox"
                checked
                style="
                    width:24px;
                    height:24px;
                    accent-color:#14b8a6;
                    cursor:pointer;
                "
            >

        </label>

    </div>


    {{-- SAVE BUTTON --}}
    <button
        type="button"
        onclick="alert('Settings saved successfully!')"
        style="
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
        "
        onmouseover="this.style.transform='translateY(-2px)'"
        onmouseout="this.style.transform='translateY(0px)'"
    >
        💾 Save Settings
    </button>

</div>

</x-filament-panels::page>