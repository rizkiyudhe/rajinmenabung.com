<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">{{ __('Utang Piutang') }}</h2>
                <p class="text-sm text-gray-500 mt-1">Catatan pinjaman uang masuk dan keluar</p>
            </div>
            <a href="{{ route('debts.create') }}"
                class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-2 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Catatan
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">



            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Jenis</th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Nama Kontak</th>
                                <th
                                    class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Jatuh Tempo</th>
                                <th
                                    class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Nominal</th>
                                <th
                                    class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($debts as $debt)
                                <tr
                                    class="hover:bg-gray-50 transition-colors duration-150 {{ $debt->status == 'paid' ? 'bg-gray-50/50 opacity-60' : '' }}">
                                    <td class="px-5 py-4 whitespace-nowrap text-sm">
                                        <span
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md text-xs font-bold {{ $debt->type == 'debt' ? 'bg-amber-100 text-amber-800' : 'bg-indigo-100 text-indigo-800' }}">
                                            @if ($debt->type == 'debt')
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                                </svg>
                                                Utang Saya
                                            @else
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                                </svg>
                                                Piutang Saya
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $debt->person_name }}
                                        <div class="text-xs text-gray-400 font-normal mt-0.5 truncate max-w-[200px]">
                                            {{ $debt->description ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $debt->due_date ? \Carbon\Carbon::parse($debt->due_date)->format('d M Y') : 'Tidak ada' }}
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-sm text-right font-bold text-gray-900">
                                        Rp {{ number_format($debt->amount, 0, ',', '.') }}
                                        <div
                                            class="text-xs font-semibold mt-0.5 {{ $debt->status == 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                                            {{ $debt->status == 'paid' ? 'Lunas' : 'Belum Lunas' }}
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex justify-center items-center gap-2">

                                            @if ($debt->status == 'pending')
                                                {{-- MODAL PELUNASAN MENGGUNAKAN ALPINE JS --}}
                                                <div x-data="{ openSettle: false }" class="inline">
                                                    <button @click="openSettle = true" type="button"
                                                        class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 px-2.5 py-1.5 rounded-md transition-all duration-200"
                                                        title="Tandai sebagai Lunas">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        </svg>
                                                        Lunas
                                                    </button>

                                                    {{-- Modal Pilih Dompet & Kategori Pelunasan --}}
                                                    <div x-show="openSettle" style="display: none;"
                                                        class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 text-left">
                                                        <div x-show="openSettle" x-transition.opacity
                                                            @click="openSettle = false"
                                                            class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity">
                                                        </div>
                                                        <div x-show="openSettle" x-transition
                                                            class="relative bg-white rounded-2xl max-w-sm w-full p-6 text-left shadow-2xl border border-gray-100 z-10 transform transition-all">

                                                            <h3 class="text-lg font-bold text-gray-900 mb-2">Pilih
                                                                Dompet & Kategori</h3>
                                                            <p class="text-xs text-gray-500 mb-4">
                                                                Pilih sumber dana dan kategori untuk pelunasan
                                                                <b>{{ $debt->person_name }}</b> sebesar <b>Rp
                                                                    {{ number_format($debt->amount, 0, ',', '.') }}</b>.
                                                            </p>

                                                            <form action="{{ route('debts.settle', $debt) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('POST')

                                                                {{-- Dropdown Dompet --}}
                                                                <div class="mb-4">
                                                                    <label
                                                                        class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">Dompet</label>
                                                                    <select name="wallet_id"
                                                                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm"
                                                                        required>
                                                                        <option value="" disabled selected>--
                                                                            Pilih Dompet --</option>
                                                                        @foreach ($wallets as $wallet)
                                                                            <option value="{{ $wallet->id }}">
                                                                                {{ $wallet->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                {{-- TAMBAHAN: Dropdown Kategori --}}
                                                                <div class="mb-6">
                                                                    <label
                                                                        class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">Kategori
                                                                        Transaksi</label>
                                                                    <select name="category_id"
                                                                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 text-sm"
                                                                        required>
                                                                        <option value="" disabled selected>--
                                                                            Pilih Kategori --</option>
                                                                        @php
                                                                            $typeFilter =
                                                                                $debt->type == 'receivable'
                                                                                    ? 'income'
                                                                                    : 'expense';
                                                                            $filteredCategories = $categories->where(
                                                                                'type',
                                                                                $typeFilter,
                                                                            );
                                                                        @endphp
                                                                        @foreach ($filteredCategories as $cat)
                                                                            <option value="{{ $cat->id }}">
                                                                                {{ $cat->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="flex justify-end gap-3">
                                                                    <button @click="openSettle = false" type="button"
                                                                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-xl transition">Batal</button>
                                                                    <button type="submit"
                                                                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-md transition">Konfirmasi
                                                                        Lunas</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            {{-- MODAL HAPUS --}}
                                            <div x-data="{ openDelete: false }" class="inline">
                                                <button @click="openDelete = true" type="button"
                                                    class="inline-flex items-center gap-1 text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-2.5 py-1.5 rounded-md transition-all duration-200">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                    Hapus
                                                </button>

                                                <div x-show="openDelete"
                                                    class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 text-left"
                                                    style="display: none;">
                                                    <div x-show="openDelete" x-transition.opacity
                                                        @click="openDelete = false"
                                                        class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity">
                                                    </div>
                                                    <div x-show="openDelete" x-transition
                                                        class="relative bg-white rounded-2xl max-w-sm w-full p-6 text-center shadow-2xl border border-gray-100 z-10 transform transition-all">
                                                        <div
                                                            class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-50 text-red-600 mb-4">
                                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                                stroke-width="2" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                            </svg>
                                                        </div>
                                                        <h3 class="text-lg font-bold text-gray-900 mb-1">Yakin Ingin
                                                            Hapus?</h3>
                                                        <p
                                                            class="text-xs text-gray-500 mb-6 leading-relaxed whitespace-normal">
                                                            Catatan tagihan/utang dengan <span
                                                                class="font-semibold text-gray-700">{{ $debt->person_name }}</span>
                                                            akan dihapus secara permanen.
                                                        </p>
                                                        <div class="flex justify-center gap-3">
                                                            <button @click="openDelete = false" type="button"
                                                                class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition-colors min-w-[80px]">Batal</button>
                                                            <form action="{{ route('debts.destroy', $debt) }}"
                                                                method="POST" class="inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-md hover:shadow-lg transition-all min-w-[80px]">Ya,
                                                                    Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-10 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            <span class="text-gray-500 font-medium text-sm">Belum ada catatan utang
                                                piutang.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if (method_exists($debts, 'links') && $debts->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-white">
                        {{ $debts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        /* Custom scrollbar untuk tabel */
        .overflow-x-auto::-webkit-scrollbar {
            height: 6px;
        }

        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 8px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }

        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</x-app-layout>
