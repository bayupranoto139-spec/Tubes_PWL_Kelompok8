<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - HealthMesh</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white rounded-3xl shadow-xl p-8 w-full max-w-md text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-teal-100 flex items-center justify-center">
            ✉️
        </div>

        <h1 class="text-2xl font-bold text-gray-800 mb-2">
            Verify Your Email
        </h1>

        <p class="text-gray-500 mb-6">
            Kami telah mengirim link verifikasi ke email Anda.
            Silakan cek inbox atau folder spam.
        </p>

        @if (session('status'))
            <div class="mb-4 p-3 rounded-lg bg-green-50 text-green-700 text-sm">
                Verification email sent successfully.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button
                type="submit"
                class="w-full bg-teal-500 hover:bg-teal-600 text-white font-semibold py-3 rounded-xl transition">
                Resend Verification Email
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf

            <button
                type="submit"
                class="w-full border border-gray-200 text-gray-600 py-3 rounded-xl hover:bg-gray-50 transition">
                Logout
            </button>
        </form>
    </div>

</body>
</html>