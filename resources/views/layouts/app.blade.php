<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Dompetku') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50">

    @if (session('success') || session('error') || $errors->any())
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            x-transition:enter="transform ease-out duration-300 transition"
            x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
            x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0 flex translate-x-4"
            class="fixed top-5 right-5 z-50 max-w-sm w-full bg-white/95 backdrop-blur-md shadow-2xl rounded-2xl pointer-events-auto overflow-hidden border-l-4 shadow-blue-500/5 {{ session('success') ? 'border-emerald-500' : 'border-rose-500' }}"
            style="display: none;">
            <div class="p-4 sm:p-5">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        @if (session('success'))
                            <div class="p-1 bg-emerald-100 text-emerald-600 rounded-lg">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        @else
                            <div class="p-1 bg-rose-100 text-rose-600 rounded-lg">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1 pt-0.5">
                        <p class="text-sm font-bold text-gray-900">
                            {{ session('success') ? 'Aksi Berhasil!' : 'Oops, Ada Masalah!' }}
                        </p>
                        <p class="mt-1 text-xs font-medium text-gray-500 leading-relaxed">
                            @if (session('success'))
                                {{ session('success') }}
                            @elseif(session('error'))
                                {{ session('error') }}
                            @else
                                Ada beberapa kesalahan pada inputan formulir Anda.
                            @endif
                        </p>
                    </div>

                    <div class="flex-shrink-0 flex">
                        <button @click="show = false"
                            class="text-gray-400 hover:text-gray-600 focus:outline-none p-1 rounded-lg hover:bg-gray-100 transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @include('layouts.navigation')

    <div class="pt-16 md:pt-0 md:pl-64 min-h-screen flex flex-col transition-all duration-300">

        @isset($header)
            <header class="bg-white border-b border-gray-100">
                <div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <main class="flex-1">
            {{ $slot }}
        </main>

    </div>

</body>

</html>
