@extends('layouts.patient')

@section('title', 'Manage Sessions - MedVerse')
@section('page_title', 'Manage Sessions')

@section('content')
<div class="space-y-6">
    <div class="bg-white dark:bg-gray-900 border border-slate-200/60 dark:border-gray-800/60 rounded-3xl p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">Active Device Sessions</h2>
                <p class="text-sm text-slate-500 dark:text-gray-400 mt-1">Review and revoke other login sessions for this account.</p>
            </div>
            <div class="text-sm text-slate-500 dark:text-gray-400">Signed in as <span class="font-semibold text-slate-800 dark:text-white">{{ $user->email }}</span></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-900 border border-slate-200/60 dark:border-gray-800/60 rounded-3xl p-6 shadow-sm space-y-4">
            <h3 class="text-base font-bold text-slate-800 dark:text-white">Security Control</h3>
            <p class="text-sm text-slate-500 dark:text-gray-400">If you suspect your account is logged in elsewhere, use the form below to log out all other sessions.</p>

            @if ($errors->any())
                <div class="rounded-3xl border border-red-100 bg-red-50 p-4 text-sm text-red-700">
                    <strong class="font-semibold">Validation error:</strong>
                    <ul class="mt-2 list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('patient.profile.sessions.logout-other') }}" method="POST" class="space-y-4">
                @csrf
                <div class="space-y-1.5">
                    <label for="current_password" class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Current Password</label>
                    <input id="current_password" name="current_password" type="password" required class="w-full rounded-xl border border-slate-200 dark:border-gray-700 bg-transparent px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 outline-none">
                </div>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/10 hover:bg-slate-800 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout Other Sessions
                </button>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-slate-200/60 dark:border-gray-800/60 rounded-3xl p-6 shadow-sm">
            <h3 class="text-base font-bold text-slate-800 dark:text-white">Current Session</h3>
            <div class="mt-4 space-y-3 text-sm text-slate-500 dark:text-gray-400">
                <div class="rounded-3xl border border-slate-100 dark:border-gray-800 p-4 bg-slate-50 dark:bg-gray-950/60">
                    <p><span class="font-semibold text-slate-700 dark:text-white">Device:</span> Browser session</p>
                    <p><span class="font-semibold text-slate-700 dark:text-white">User:</span> {{ $user->name }}</p>
                    <p><span class="font-semibold text-slate-700 dark:text-white">Email:</span> {{ $user->email }}</p>
                </div>
                <p class="leading-relaxed">This page allows you to revoke access from other devices. If you are not sure which sessions are active, logging out all other sessions is the safest option.</p>
            </div>
        </div>
    </div>
</div>
@endsection