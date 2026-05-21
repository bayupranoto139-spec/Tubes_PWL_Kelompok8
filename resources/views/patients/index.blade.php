<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>Patient Dashboard</title>
</head>

<body class="min-h-screen bg-gradient-to-br from-blue-100 via-cyan-100 to-slate-100">

    <div class="max-w-7xl mx-auto p-10">

        <!-- HEADER -->
        <div class="flex items-center justify-between mb-10">

            <div>

                <h1 class="text-6xl font-black text-slate-800">
                    Patient Dashboard
                </h1>

                <p class="mt-3 text-slate-500 text-lg">
                    Hospital management system
                </p>

            </div>

            <button class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white px-6 py-4 rounded-2xl shadow-xl hover:scale-105 transition">

                + Add Patient

            </button>

        </div>

        <!-- STATS -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

            <div class="bg-white p-7 rounded-3xl shadow-xl">

                <p class="text-slate-400">
                    Total Patients
                </p>

                <h2 class="text-5xl font-black text-blue-600 mt-3">
                    {{ count($patients) }}
                </h2>

            </div>

            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 p-7 rounded-3xl shadow-xl text-white">

                <p class="opacity-80">
                    Hospital Status
                </p>

                <h2 class="text-4xl font-black mt-3">
                    Active
                </h2>

            </div>

            <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-7 rounded-3xl shadow-xl text-white">

                <p class="opacity-80">
                    System
                </p>

                <h2 class="text-4xl font-black mt-3">
                    Online
                </h2>

            </div>

        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-[30px] shadow-2xl overflow-hidden">

            <div class="p-7 border-b">

                <h2 class="text-3xl font-bold text-slate-700">
                    Patient Records
                </h2>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-gradient-to-r from-blue-600 to-cyan-500 text-white">

                        <tr>

                            <th class="px-8 py-5 text-left">
                                ID
                            </th>

                            <th class="px-8 py-5 text-left">
                                Patient Name
                            </th>

                            <th class="px-8 py-5 text-left">
                                Gender
                            </th>

                            <th class="px-8 py-5 text-left">
                                Phone
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($patients as $patient)

                        <tr class="border-b hover:bg-blue-50 transition duration-200">

                            <td class="px-8 py-6 font-bold text-slate-700">

                                #{{ $patient->id }}

                            </td>

                            <td class="px-8 py-6">

                                <div class="flex items-center gap-4">

                                    <div class="w-14 h-14 rounded-full bg-gradient-to-r from-blue-500 to-cyan-500 flex items-center justify-center text-white font-black text-xl shadow-lg">

                                        {{ strtoupper(substr($patient->name,0,1)) }}

                                    </div>

                                    <div>

                                        <h3 class="font-bold text-lg text-slate-800">
                                            {{ $patient->name }}
                                        </h3>

                                        <p class="text-slate-400 text-sm">
                                            Patient ID: {{ $patient->id }}
                                        </p>

                                    </div>

                                </div>

                            </td>

                            <td class="px-8 py-6">

                                <span class="bg-blue-100 text-blue-700 px-4 py-2 rounded-full text-sm font-bold">

                                    {{ $patient->gender }}

                                </span>

                            </td>

                            <td class="px-8 py-6 text-slate-600 font-medium">

                                {{ $patient->phone }}

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="4" class="py-16 text-center text-slate-400 text-xl">

                                No patient data found

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</body>
</html>