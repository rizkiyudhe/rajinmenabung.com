<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Rajin Menabugn') }} - Login</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-900 flex items-center justify-center min-h-screen p-4">

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 p-8 sm:p-10 animate-fade-in">

        <div class="text-center mb-8">
            <h1
                class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 tracking-wider">
                Rajin Menabung
            </h1>
        </div>

        <x-auth-session-status class="mb-4 text-sm text-green-600 bg-green-50 p-3 rounded-lg" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-5">
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    autocomplete="username" placeholder="contoh@email.com"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-500 font-medium" />
            </div>

            <div class="mb-5">
                <div class="flex justify-between items-center mb-2">
                    <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>

                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-500 font-medium" />
            </div>

            <div class="mb-8 flex items-center">
                <input id="remember_me" type="checkbox" name="remember"
                    class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 transition-colors cursor-pointer">
                <label for="remember_me" class="ms-2 text-sm font-medium text-gray-600 cursor-pointer select-none">
                    Ingat saya di perangkat ini
                </label>
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-bold py-3 px-4 rounded-xl shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                Masuk Sekarang
            </button>


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
