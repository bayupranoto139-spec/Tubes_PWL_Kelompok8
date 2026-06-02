@extends('layouts.patient')

@section('title', 'My Profile - MedVerse')
@section('page_title', 'My Profile')

@section('content')
<div class="space-y-6">
    
    <!-- Profile Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Side: Avatar Card & Joined Info -->
        <div class="space-y-6">
            
            <!-- Avatar Card -->
            <div class="bg-white dark:bg-gray-900 border border-slate-200/60 dark:border-gray-800/60 rounded-3xl p-6 shadow-sm text-center space-y-4">
                <div class="inline-flex w-24 h-24 rounded-3xl bg-gradient-to-tr from-teal-500 to-cyan-500 text-white font-extrabold text-3xl items-center justify-center uppercase shadow-lg shadow-teal-500/20">
                    {{ substr($user->name, 0, 2) }}
                </div>
                <div class="space-y-1">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white">{{ $user->name }}</h3>
                    <span class="inline-flex items-center gap-1.5 px-3 py-0.5 bg-teal-50 dark:bg-teal-950/20 text-teal-600 dark:text-teal-400 text-[10px] font-bold uppercase rounded-full tracking-wider">
                        Patient Account
                    </span>
                    <p class="text-xs text-slate-400 mt-1 dark:text-gray-400">{{ $user->email }}</p>
                </div>
                
                <div class="pt-4 border-t border-slate-100 dark:border-gray-800/60 grid grid-cols-2 gap-4 text-xs font-semibold text-slate-500 dark:text-gray-400">
                    <div class="space-y-0.5 border-r border-slate-100 dark:border-gray-800/60">
                        <span class="block text-[9px] text-slate-400 uppercase tracking-wider font-bold">Registered</span>
                        <span class="text-slate-800 dark:text-gray-200">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="space-y-0.5">
                        <span class="block text-[9px] text-slate-400 uppercase tracking-wider font-bold">Hospitals</span>
                        <span class="text-slate-800 dark:text-gray-200">{{ $user->patientEnrollments->count() }} enrolled</span>
                    </div>
                </div>
            </div>

            <!-- Medical Summary info (Read-only quick showcase) -->
            <div class="bg-white dark:bg-gray-900 border border-slate-200/60 dark:border-gray-800/60 rounded-3xl p-6 shadow-sm space-y-4">
                <h4 class="text-xs font-bold text-slate-400 dark:text-gray-400 uppercase tracking-widest pl-1">Quick Medical Card</h4>
                
                <div class="space-y-3.5 divide-y divide-slate-50 dark:divide-gray-800/80 text-xs font-semibold">
                    <div class="flex items-center justify-between py-2 first:pt-0">
                        <span class="text-slate-400">Blood Type</span>
                        <span class="px-2.5 py-0.5 bg-teal-50 dark:bg-teal-950/20 text-teal-600 dark:text-teal-400 rounded text-xs font-black uppercase">{{ $medicalInfo->blood_type ?? '-' }}</span>
                    </div>
                    <div class="flex items-start justify-between py-2.5">
                        <span class="text-slate-400 mt-0.5">Allergies</span>
                        <span class="text-right text-slate-800 dark:text-white max-w-[140px] truncate" title="{{ $medicalInfo->allergies }}">{{ $medicalInfo->allergies ?? 'None' }}</span>
                    </div>
                    <div class="flex items-start justify-between py-2.5">
                        <span class="text-slate-400 mt-0.5">Emergency Contact</span>
                        <div class="text-right space-y-0.5">
                            <span class="block text-slate-800 dark:text-white">{{ $medicalInfo->emergency_contact_name }}</span>
                            <span class="block text-[10px] text-slate-400">{{ $medicalInfo->emergency_contact_phone }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between py-2.5 last:pb-0">
                        <span class="text-slate-400">Insurance Provider</span>
                        <span class="text-slate-800 dark:text-white truncate max-w-[140px]">{{ $medicalInfo->insurance_provider ?? 'Self Paid' }}</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Side: Edit Form Fields -->
        <div class="bg-white dark:bg-gray-900 border border-slate-200/60 dark:border-gray-800/60 rounded-3xl p-6 md:p-8 shadow-sm lg:col-span-2 space-y-6">
            
            <form action="{{ route('patient.profile.update') }}" method="POST" class="space-y-6 m-0">
                @csrf

                @if ($errors->any())
                    <div class="rounded-3xl border border-red-100 bg-red-50 p-4 text-sm text-red-700">
                        <strong class="font-semibold">There were problems with your submission:</strong>
                        <ul class="mt-2 list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <!-- Section 1: Personal Details -->
                <div class="space-y-4">
                    <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2 pb-2.5 border-b border-slate-100 dark:border-gray-800">
                        <span class="w-1.5 h-4 bg-teal-500 rounded-full"></span>
                        Personal Information
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Full name -->
                        <div class="space-y-1.5">
                            <label for="name" class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Full Name</label>
                            <input type="text" name="name" id="name" required value="{{ old('name', $user->name) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-transparent text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 dark:text-white transition-all outline-none">
                        </div>

                        <!-- Email address -->
                        <div class="space-y-1.5">
                            <label for="email" class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Email Address</label>
                            <input type="email" name="email" id="email" required value="{{ old('email', $user->email) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-transparent text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 dark:text-white transition-all outline-none">
                        </div>

                        <!-- Phone number -->
                        <div class="space-y-1.5">
                            <label for="phone" class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Phone Number</label>
                            <input type="text" name="phone" id="phone" required value="{{ old('phone', $user->phone) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-transparent text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 dark:text-white transition-all outline-none">
                        </div>

                        <!-- Gender (dropdown) -->
                        <div class="space-y-1.5">
                            <label for="gender" class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Gender</label>
                            <select name="gender" id="gender" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-transparent text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 dark:text-white transition-all outline-none">
                                <option value="L" {{ old('gender', $user->gender) === 'L' ? 'selected' : '' }} class="dark:bg-gray-900">Laki-laki (Male)</option>
                                <option value="P" {{ old('gender', $user->gender) === 'P' ? 'selected' : '' }} class="dark:bg-gray-900">Perempuan (Female)</option>
                            </select>
                        </div>

                        <!-- Date of Birth -->
                        <div class="space-y-1.5 sm:col-span-2">
                            <label for="date_of_birth" class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Date of Birth</label>
                            <input type="date" name="date_of_birth" id="date_of_birth" required value="{{ old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d') ?? '') }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-transparent text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 dark:text-white transition-all outline-none">
                        </div>

                        <!-- Address -->
                        <div class="space-y-1.5 sm:col-span-2">
                            <label for="address" class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Address Details</label>
                            <textarea name="address" id="address" rows="2" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-transparent text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 dark:text-white transition-all outline-none resize-none">{{ old('address', $user->address) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Clinical Medical details -->
                <div class="space-y-4 pt-2">
                    <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2 pb-2.5 border-b border-slate-100 dark:border-gray-800">
                        <span class="w-1.5 h-4 bg-teal-500 rounded-full"></span>
                        Medical Information
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Blood type selection -->
                        <div class="space-y-1.5">
                            <label for="blood_type" class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Blood Type</label>
                            <select name="blood_type" id="blood_type" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-transparent text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 dark:text-white transition-all outline-none">
                                <option value="" {{ is_null(old('blood_type', $medicalInfo->blood_type)) ? 'selected' : '' }} class="dark:bg-gray-900">- Select Type -</option>
                                <option value="A" {{ old('blood_type', $medicalInfo->blood_type) === 'A' ? 'selected' : '' }} class="dark:bg-gray-900">A</option>
                                <option value="B" {{ old('blood_type', $medicalInfo->blood_type) === 'B' ? 'selected' : '' }} class="dark:bg-gray-900">B</option>
                                <option value="AB" {{ old('blood_type', $medicalInfo->blood_type) === 'AB' ? 'selected' : '' }} class="dark:bg-gray-900">AB</option>
                                <option value="O" {{ old('blood_type', $medicalInfo->blood_type) === 'O' ? 'selected' : '' }} class="dark:bg-gray-900">O</option>
                            </select>
                        </div>

                        <!-- Insurance provider -->
                        <div class="space-y-1.5">
                            <label for="insurance_provider" class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Insurance Provider</label>
                            <input type="text" name="insurance_provider" id="insurance_provider" placeholder="BPJS / Mandiri / None" value="{{ old('insurance_provider', $medicalInfo->insurance_provider) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-transparent text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 dark:text-white transition-all outline-none">
                        </div>

                        <!-- Policy number -->
                        <div class="space-y-1.5">
                            <label for="insurance_policy_number" class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Insurance Policy Number</label>
                            <input type="text" name="insurance_policy_number" id="insurance_policy_number" placeholder="Enter Policy #" value="{{ old('insurance_policy_number', $medicalInfo->insurance_policy_number) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-transparent text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 dark:text-white transition-all outline-none">
                        </div>

                        <!-- Emergency Name -->
                        <div class="space-y-1.5">
                            <label for="emergency_contact_name" class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Emergency Contact Name</label>
                            <input type="text" name="emergency_contact_name" id="emergency_contact_name" required value="{{ old('emergency_contact_name', $medicalInfo->emergency_contact_name) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-transparent text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 dark:text-white transition-all outline-none">
                        </div>

                        <!-- Emergency Phone -->
                        <div class="space-y-1.5">
                            <label for="emergency_contact_phone" class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Emergency Contact Phone</label>
                            <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" required value="{{ old('emergency_contact_phone', $medicalInfo->emergency_contact_phone) }}" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-transparent text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 dark:text-white transition-all outline-none">
                        </div>

                        <!-- Allergies description -->
                        <div class="space-y-1.5 sm:col-span-2">
                            <label for="allergies" class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Clinical Allergies</label>
                            <textarea name="allergies" id="allergies" rows="2" placeholder="List details of food, drugs or general allergies..." class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-transparent text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 dark:text-white transition-all outline-none resize-none">{{ old('allergies', $medicalInfo->allergies) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Password Changes (Optional) -->
                <div class="space-y-4 pt-2">
                    <h3 class="text-base font-bold text-slate-800 dark:text-white flex items-center gap-2 pb-2.5 border-b border-slate-100 dark:border-gray-800">
                        <span class="w-1.5 h-4 bg-teal-500 rounded-full"></span>
                        Security Settings
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- Current Password -->
                        <div class="space-y-1.5">
                            <label for="current_password" class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Current Password</label>
                            <input type="password" name="current_password" id="current_password" placeholder="••••••••" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-transparent text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 dark:text-white transition-all outline-none">
                        </div>

                        <!-- New Password -->
                        <div class="space-y-1.5">
                            <label for="new_password" class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">New Password</label>
                            <input type="password" name="new_password" id="new_password" placeholder="••••••••" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-transparent text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 dark:text-white transition-all outline-none">
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-1.5">
                            <label for="new_password_confirmation" class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" placeholder="••••••••" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-gray-700 bg-transparent text-sm focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 dark:text-white transition-all outline-none">
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-3 justify-between rounded-3xl border border-slate-200 dark:border-gray-800 p-4 bg-slate-50 dark:bg-gray-950/70">
                        <button type="submit" name="action" value="change_password" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 text-white rounded-2xl text-sm font-semibold shadow-lg shadow-blue-500/10 hover:bg-blue-700 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0-1.1.9-2 2-2h4a2 2 0 012 2v7a2 2 0 01-2 2h-4a2 2 0 01-2-2v-7zm-4 0V8a4 4 0 118 0v3M8 15h.01"></path></svg>
                            Change Password
                        </button>

                        <a href="{{ route('patient.profile.sessions') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-slate-900 text-white rounded-2xl text-sm font-semibold shadow-lg shadow-slate-900/10 hover:bg-slate-800 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Manage Sessions
                        </a>
                    </div>
                </div>

                <!-- Submit Form button -->
                <div class="pt-4 border-t border-slate-100 dark:border-gray-800 flex justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3.5 bg-gradient-to-r from-teal-500 to-cyan-500 hover:from-teal-600 hover:to-cyan-600 text-white rounded-2xl text-sm font-semibold shadow-lg shadow-teal-500/10 hover:shadow-teal-500/25 hover:-translate-y-0.5 transition-all cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Save Profiles Configuration
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>
@endsection
