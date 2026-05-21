<div class="min-h-screen bg-gradient-to-br from-slate-100 to-green-100 p-8">

    <div class="max-w-7xl mx-auto">

        <div class="mb-8">

            <h1 class="text-5xl font-bold text-slate-800">
                Payment Dashboard
            </h1>

            <p class="text-slate-500 mt-2">
                Manage patient payment transactions
            </p>

        </div>

        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

            <table class="w-full">

                <thead class="bg-gradient-to-r from-green-600 to-emerald-500 text-white">

                    <tr>

                        <th class="px-6 py-5 text-left">
                            Patient
                        </th>

                        <th class="px-6 py-5 text-left">
                            Amount
                        </th>

                        <th class="px-6 py-5 text-left">
                            Status
                        </th>

                        <th class="px-6 py-5 text-center">
                            Action
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100 bg-white">

                    @forelse($bills as $bill)

                    <tr class="hover:bg-green-50 transition">

                        <td class="px-6 py-5 font-semibold">

                            {{ $bill->patient->name ?? '-' }}

                        </td>

                        <td class="px-6 py-5 font-bold text-slate-700">

                            Rp {{ number_format($bill->amount,0,',','.') }}

                        </td>

                        <td class="px-6 py-5">

                            @if($bill->status == 'paid')

                            <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-bold">

                                Paid

                            </span>

                            @else

                            <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-bold">

                                Unpaid

                            </span>

                            @endif

                        </td>

                        <td class="px-6 py-5 text-center">

                            @if($bill->status != 'paid')

                            <form action="/payments/pay/{{ $bill->id }}" method="POST">

                                @csrf

                                <button
                                    class="bg-green-500 hover:bg-green-600 text-white px-5 py-3 rounded-xl shadow-lg transition"
                                >
                                    Pay Now
                                </button>

                            </form>

                            @else

                            <span class="text-green-600 font-bold">
                                Completed
                            </span>

                            @endif

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="4" class="py-10 text-center text-slate-400">

                            No payment data found

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>