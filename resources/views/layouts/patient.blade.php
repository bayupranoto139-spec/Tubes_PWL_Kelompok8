<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Patient Portal')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-cyan-50 to-slate-100">

    <div class="w-full lg:w-80 bg-white min-h-screen shadow-xl flex flex-col justify-between p-6 lg:fixed lg:left-0 lg:top-0 z-50">
        <div>
            <div class="flex items-center gap-3 px-4 py-3 mb-10 border-b border-slate-100">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-blue-600 to-cyan-500 flex items-center justify-center text-white font-black text-xl shadow-md">
                    H
                </div>
                <div>
                    <h2 class="font-black text-slate-800 text-lg leading-none">HealthMesh</h2>
                    <span class="text-xs text-slate-400 font-medium">Patient Portal</span>
                </div>
            </div>

            <nav class="space-y-2">
                <a href="{{ route('patient.dashboard') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm transition duration-200 {{ request()->routeIs('patient.dashboard') ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/20' : 'text-slate-500 hover:bg-blue-50 hover:text-blue-600' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4zM14 16a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2v-4z"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('patient.appointment') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm transition duration-200 {{ request()->routeIs('patient.appointment') ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/20' : 'text-slate-500 hover:bg-blue-50 hover:text-blue-600' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Appointment
                </a>
                <a href="{{ route('patient.medical_records') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm transition duration-200 {{ request()->routeIs('patient.medical_records') ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/20' : 'text-slate-500 hover:bg-blue-50 hover:text-blue-600' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Medical Records
                </a>
                <a href="{{ route('patient.bills') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm transition duration-200 {{ request()->routeIs('patient.bills') ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/20' : 'text-slate-500 hover:bg-blue-50 hover:text-blue-600' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Bills
                </a>
                <a href="{{ route('patient.prescriptions') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm transition duration-200 {{ request()->routeIs('patient.prescriptions') ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/20' : 'text-slate-500 hover:bg-blue-50 hover:text-blue-600' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 9.172V5L8 4z"/></svg>
                    Prescriptions
                </a>
                <a href="{{ route('patient.profile') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm transition duration-200 {{ request()->routeIs('patient.profile') ? 'bg-gradient-to-r from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-500/20' : 'text-slate-500 hover:bg-blue-50 hover:text-blue-600' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Profile
                </a>
            </nav>
        </div>

        <div class="border-t border-slate-100 pt-4">
            <a href="{{ url('/') }}" class="flex items-center gap-4 px-4 py-3.5 rounded-2xl font-bold text-sm text-red-500 hover:bg-red-50 transition duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Back to Home
            </a>
        </div>
    </div>

    <div class="flex-1 lg:ml-80 p-6 lg:p-10 min-h-screen">
        @yield('content')
    </div>

</body>
</html>