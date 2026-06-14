<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">{{ __('Anggaran Bulanan') }}</h2>
                <p class="text-sm text-gray-500 mt-1">Pantau batas pengeluaran Anda</p>
            </div>
            <a href="{{ route('budgets.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl shadow transition text-sm">
                + Set Anggaran Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div
                class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6 flex justify-between items-center">
                <form action="{{ route('budgets.index') }}" method="GET"
                    class="flex items-center gap-3 w-full max-w-md">
                    <select name="month"
                        class="rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500"
                        onchange="this.form.submit()">
                        @foreach (range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $m, 10)) }}
                            </option>
                        @endforeach
                    </select>
                    <select name="year"
                        class="rounded-lg border-gray-200 text-sm focus:ring-blue-500 focus:border-blue-500"
                        onchange="this.form.submit()">
                        @foreach (range(date('Y') - 2, date('Y') + 2) as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($budgets as $budget)
                    @php
                        // Logika warna progress bar
                        $barColor = 'bg-emerald-500';
                        $textColor = 'text-emerald-600';
                        if ($budget->percentage >= 75) {
                            $barColor = 'bg-amber-400';
                            $textColor = 'text-amber-600';
                        }
                        if ($budget->percentage >= 90) {
                            $barColor = 'bg-red-500';
                            $textColor = 'text-red-600';
                        }
                    @endphp

                    <div
                        class="bg-white rounded-2xl p-6 shadow-sm border {{ $budget->is_over_budget ? 'border-red-200 bg-red-50/30' : 'border-gray-100' }} relative group">

                        <div x-data="{ openDelete: false }">

                            <button @click="openDelete = true" type="button"
                                class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity text-gray-400 hover:text-red-500 focus:outline-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </button>

                            <div x-show="openDelete"
                                class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4"
                                style="display: none;">

                                <div x-show="openDelete" x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0" @click="openDelete = false"
                                    class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity">
                                </div>

                                <div x-show="openDelete" x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave="ease-in duration-200"
                                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    class="relative bg-white rounded-2xl max-w-sm w-full p-6 text-center shadow-2xl border border-gray-100 z-10 transform transition-all">

                                    <div
                                        class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-50 text-red-600 mb-4">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>

                                    <h3 class="text-lg font-bold text-gray-900 mb-1">Hapus Anggaran?</h3>
                                    <p class="text-xs text-gray-500 mb-6 leading-relaxed">
                                        Anggaran untuk kategori <span
                                            class="font-semibold text-gray-700">{{ $budget->category->name }}</span>
                                        bulan ini akan dihapus. Pengeluaran tetap tercatat.
                                    </p>

                                    <div class="flex justify-center gap-3">
                                        <button @click="openDelete = false" type="button"
                                            class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition-colors min-w-[80px]">
                                            Batal
                                        </button>
                                        <form action="{{ route('budgets.destroy', $budget) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-md hover:shadow-lg transition-all min-w-[80px]">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="font-bold text-gray-800 mb-4">{{ $budget->category->name }}</div>

                        <div class="flex justify-between items-end mb-2">
                            <div>
                                <p class="text-xs text-gray-500 font-medium">Terpakai</p>
                                <p class="text-lg font-black {{ $textColor }}">Rp
                                    {{ number_format($budget->spent, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 font-medium">Dari Anggaran</p>
                                <p class="text-sm font-bold text-gray-600">Rp
                                    {{ number_format($budget->amount, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="w-full bg-gray-200 rounded-full h-2.5 mb-2 overflow-hidden">
                            <div class="{{ $barColor }} h-2.5 rounded-full transition-all duration-500"
                                style="width: {{ $budget->percentage }}%"></div>
                        </div>

                        <div class="text-xs font-semibold flex justify-between">
                            <span class="{{ $textColor }}">{{ round($budget->percentage) }}%</span>
                            @if ($budget->is_over_budget)
                                <span class="text-red-500 animate-pulse">Over Budget!</span>
                            @else
                                <span class="text-gray-500">Sisa: Rp
                                    {{ number_format($budget->amount - $budget->spent, 0, ',', '.') }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-full py-12 text-center text-gray-500 bg-white rounded-2xl border border-dashed border-gray-300">
                        Belum ada anggaran yang diset untuk bulan ini.<br>
                        <a href="{{ route('budgets.create') }}"
                            class="text-blue-600 font-semibold hover:underline mt-2 inline-block">
                            Set Anggaran Sekarang
                        </a>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
