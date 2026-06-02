<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Patient</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gradient-to-br from-slate-100 via-blue-50 to-slate-200 min-h-screen">

    <div class="flex min-h-screen">

        <!-- LEFT SIDE -->
        <div class="hidden lg:flex w-1/2 bg-blue-600 relative overflow-hidden">

            <div class="absolute inset-0 bg-black/10"></div>

            <div class="relative z-10 flex flex-col justify-center px-16 text-white">

                <h1 class="text-5xl font-bold leading-tight mb-6">
                    Hospital Patient
                    Management System
                </h1>

                <p class="text-lg text-blue-100 leading-relaxed">
                    Manage patient medical data efficiently,
                    securely, and professionally in one modern platform.
                </p>

                <div class="mt-10 flex gap-4">

                    <div class="bg-white/10 backdrop-blur-md rounded-2xl px-6 py-4">
                        <h2 class="text-3xl font-bold">24/7</h2>
                        <p class="text-blue-100 text-sm">
                            Healthcare Access
                        </p>
                    </div>

                    <div class="bg-white/10 backdrop-blur-md rounded-2xl px-6 py-4">
                        <h2 class="text-3xl font-bold">100%</h2>
                        <p class="text-blue-100 text-sm">
                            Secure Data
                        </p>
                    </div>

                </div>

            </div>

            <!-- Decorative Circle -->
            <div class="absolute -bottom-20 -right-20 w-96 h-96 bg-white/10 rounded-full"></div>
            <div class="absolute top-10 right-10 w-32 h-32 bg-white/10 rounded-full"></div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">

            <div class="w-full max-w-2xl bg-white/80 backdrop-blur-xl shadow-2xl rounded-3xl p-10 border border-white/30">

                <!-- HEADER -->
                <div class="mb-8">

                    <div class="flex items-center gap-3 mb-3">

                        <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white text-xl font-bold shadow-lg">
                            +
                        </div>

                        <div>
                            <h1 class="text-3xl font-bold text-gray-800">
                                Add New Patient
                            </h1>

                            <p class="text-gray-500 text-sm">
                                Enter patient details below
                            </p>
                        </div>

                    </div>

                </div>

                <!-- FORM -->
                <form action="/patients/store" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">
                                User ID
                            </label>

                            <input type="number"
                                   name="user_id"
                                   placeholder="Enter user ID"
                                   class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">
                                Hospital ID
                            </label>

                            <input type="number"
                                   name="hospital_id"
                                   placeholder="Enter hospital ID"
                                   class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>

                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">
                            Medical Record Number
                        </label>

                        <input type="text"
                               name="medical_record_number"
                               placeholder="MRN-00001"
                               class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">
                                Blood Type
                            </label>

                            <select name="blood_type"
                                    class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">

                                <option value="">Select Blood Type</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>

                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">
                                Insurance Provider
                            </label>

                            <input type="text"
                                   name="insurance_provider"
                                   placeholder="Insurance provider"
                                   class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>

                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">
                            Allergies
                        </label>

                        <textarea name="allergies"
                                  rows="4"
                                  placeholder="Enter allergies..."
                                  class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">
                                Emergency Contact Name
                            </label>

                            <input type="text"
                                   name="emergency_contact_name"
                                   placeholder="Contact name"
                                   class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>

                        <div>
                            <label class="text-sm font-semibold text-gray-700 block mb-2">
                                Emergency Contact Phone
                            </label>

                            <input type="text"
                                   name="emergency_contact_phone"
                                   placeholder="Phone number"
                                   class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                        </div>

                    </div>

                    <div>
                        <label class="text-sm font-semibold text-gray-700 block mb-2">
                            Insurance Policy Number
                        </label>

                        <input type="text"
                               name="insurance_policy_number"
                               placeholder="Policy number"
                               class="w-full rounded-2xl border border-gray-200 bg-gray-50 px-5 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>

                    <!-- BUTTON -->
                    <div class="flex justify-end gap-4 pt-4">

                        <a href="/patients"
                           class="px-6 py-4 rounded-2xl border border-gray-300 text-gray-700 hover:bg-gray-100 transition font-medium">
                            Cancel
                        </a>

                        <button type="submit"
                                class="px-8 py-4 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white font-semibold shadow-lg shadow-blue-300 transition duration-300">
                            Save Patient
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</body>
</html>