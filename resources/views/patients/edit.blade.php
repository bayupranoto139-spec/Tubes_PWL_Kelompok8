@extends('layouts.app')

@section('title', 'Edit Patient')

@section('content')
    <div class="bg-white rounded-[30px] shadow-2xl overflow-hidden">
        <div class="p-7 border-b">
            <h1 class="text-3xl font-bold text-slate-800">Edit Patient</h1>
            <p class="mt-2 text-slate-500">Update the patient details below.</p>
        </div>

        <div class="p-7">
            @if($errors->any())
                <div class="mb-6 rounded-3xl bg-red-50 border border-red-200 p-5 text-red-700">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('patients.update', $patient->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Name</label>
                        <input type="text" name="name" value="{{ old('name', $patient->user->name) }}" placeholder="Full name" class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $patient->user->email) }}" placeholder="Email address" class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Phone</label>
                        <input type="text" name="phone" value="{{ old('phone', $patient->user->phone) }}" placeholder="Phone number" class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Gender</label>
                        <select name="gender" class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">Select gender</option>
                            <option value="L" {{ old('gender', $patient->user->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender', $patient->user->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Blood Type</label>
                        <select name="blood_type" class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">Select blood type</option>
                            <option value="A" {{ old('blood_type', $patient->blood_type) == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ old('blood_type', $patient->blood_type) == 'B' ? 'selected' : '' }}>B</option>
                            <option value="AB" {{ old('blood_type', $patient->blood_type) == 'AB' ? 'selected' : '' }}>AB</option>
                            <option value="O" {{ old('blood_type', $patient->blood_type) == 'O' ? 'selected' : '' }}>O</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Insurance Provider</label>
                        <input type="text" name="insurance_provider" value="{{ old('insurance_provider', $patient->insurance_provider) }}" placeholder="Insurance provider" class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Allergies</label>
                    <textarea name="allergies" rows="4" class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">{{ old('allergies', $patient->allergies) }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Emergency Contact Name</label>
                        <input type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}" placeholder="Contact name" class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">Emergency Contact Phone</label>
                        <input type="text" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}" placeholder="Phone number" class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Insurance Policy Number</label>
                    <input type="text" name="insurance_policy_number" value="{{ old('insurance_policy_number', $patient->insurance_policy_number) }}" placeholder="Policy number" class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <div class="flex flex-col gap-3 pt-4 sm:flex-row sm:justify-end">
                    <a href="{{ route('patients.index') }}" class="px-6 py-4 rounded-2xl border border-gray-300 text-gray-700 hover:bg-gray-100 transition font-medium">Cancel</a>
                    <button type="submit" class="px-8 py-4 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-lg shadow-blue-300 transition duration-300">Update Patient</button>
                </div>
            </form>
        </div>
    </div>
@endsection
