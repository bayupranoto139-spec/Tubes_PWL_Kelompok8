@extends('layouts.patient')

@section('title', 'Patient Profile')
@section('page_title', 'Profile Settings')

@section('content')
    <div class="rounded-[30px] bg-white p-10 shadow-2xl">
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-slate-800">Profile</h1>
                    <p class="text-sm text-slate-500">Manage your personal information and account settings.</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-8">
                    <h2 class="text-xl font-semibold text-slate-800 mb-4">Personal details</h2>
                    <div class="space-y-3 text-sm text-slate-600">
                        <div><span class="font-semibold text-slate-800">Name:</span> Jane Doe</div>
                        <div><span class="font-semibold text-slate-800">Email:</span> jane.doe@example.com</div>
                        <div><span class="font-semibold text-slate-800">Phone:</span> +62 812 3456 7890</div>
                        <div><span class="font-semibold text-slate-800">Gender:</span> Female</div>
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-slate-50 p-8">
                    <h2 class="text-xl font-semibold text-slate-800 mb-4">Account information</h2>
                    <div class="space-y-3 text-sm text-slate-600">
                        <div><span class="font-semibold text-slate-800">Member since:</span> March 2025</div>
                        <div><span class="font-semibold text-slate-800">Insurance:</span> BNI Life</div>
                        <div><span class="font-semibold text-slate-800">Emergency contact:</span> +62 811 9876 5432</div>
                        <div><span class="font-semibold text-slate-800">Blood type:</span> O</div>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-8">
                <h2 class="text-xl font-semibold text-slate-800 mb-4">Security</h2>
                <p class="text-sm text-slate-500">Update your password, manage device access, and keep your account secure.</p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <button class="rounded-2xl bg-blue-600 px-6 py-3 text-white font-semibold hover:bg-blue-700 transition">Change password</button>
                    <button class="rounded-2xl border border-slate-200 px-6 py-3 text-slate-700 hover:bg-slate-100 transition">Manage sessions</button>
                </div>
            </div>
        </div>
    </div>
@endsection