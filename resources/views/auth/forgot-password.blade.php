<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Rajin Menabung') }} - Lupa Password</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-900 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 p-8 sm:p-10 animate-fade-in">

        <div class="text-center mb-6">
            <h1
                class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 tracking-wider">
                Rajin Menabung
            </h1>
            <h2 class="text-lg font-bold text-gray-800 mt-2">Lupa Password?</h2>
        </div>

        <div class="mb-6 text-sm text-gray-500 text-center leading-relaxed">
            {{ __('Tidak masalah. Cukup masukkan alamat email Anda, dan kami akan mengirimkan tautan untuk membuat password baru.') }}
        </div>

        <x-auth-session-status
            class="mb-6 text-sm font-medium text-green-700 bg-green-50 border border-green-200 p-3 rounded-xl text-center"
            :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-8">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email
                    Terdaftar</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    placeholder="contoh@email.com"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-500 font-medium" />
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                Kirim Link Reset Password
            </button>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 text-sm font-semibold text-gray-500 hover:text-blue-600 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke halaman Login
                </a>
            </div>
        </form>
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>

</body>

</html>
