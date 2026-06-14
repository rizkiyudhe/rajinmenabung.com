<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">{{ __('Transaksi Otomatis') }}</h2>
                <p class="text-sm text-gray-500 mt-1">Jadwalkan tagihan bulanan atau pendapatan rutin Anda</p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">

                {{-- Tombol Buat Jadwal Baru & Wrapper Alpine JS --}}
                <div x-data="{ openForm: false }" class="w-full sm:w-auto">
                    <button @click="openForm = true"
                        class="w-full sm:w-auto inline-flex justify-center items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-2 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Buat Jadwal Baru
                    </button>

                    {{-- Modal Form Tambah --}}
                    <div x-show="openForm" style="display: none;" class="relative z-50" aria-labelledby="modal-title"
                        role="dialog" aria-modal="true">
                        <div x-show="openForm" x-transition.opacity
                            class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
                        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                            <div
                                class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                <div x-show="openForm" x-transition:enter="ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave="ease-in duration-200"
                                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                    @click.away="openForm = false"
                                    class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">

                                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                        <div class="flex justify-between items-center mb-5">
                                            <h3 class="text-xl font-bold text-gray-900" id="modal-title">Tambah Jadwal
                                                Baru</h3>
                                            <button @click="openForm = false" class="text-gray-400 hover:text-gray-500">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>

                                        {{-- SOLUSI: Pembungkus x-data wajib menggunakan KUTIP SATU (') --}}
                                        <form
                                            x-data='{
                                            tipeTransaksi: "expense",
                                            kategoriTerpilih: "",
                                            semuaKategori: @json(
                                                $categories->map(function ($c) {
                                                        return ['id' => $c->id, 'name' => $c->name, 'type' => strtolower($c->type ?? 'expense')];
                                                    })->values())
                                        }'
                                            action="{{ route('recurring-transactions.store') }}" method="POST">
                                            @csrf
                                            <div class="space-y-4">
                                                <div class="grid grid-cols-2 gap-4">

                                                    {{-- Dropdown Type --}}
                                                    <div>
                                                        <label
                                                            class="block text-xs font-bold text-gray-700 uppercase tracking-wide">Jenis</label>
                                                        <select x-model="tipeTransaksi" @change="kategoriTerpilih = ''"
                                                            name="type"
                                                            class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                                            required>
                                                            <option value="expense">Pengeluaran</option>
                                                            <option value="income">Pemasukan</option>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="block text-xs font-bold text-gray-700 uppercase tracking-wide">Frekuensi</label>
                                                        <select name="frequency"
                                                            class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                                            required>
                                                            <option value="daily">Harian</option>
                                                            <option value="weekly">Mingguan</option>
                                                            <option value="monthly" selected>Bulanan</option>
                                                            <option value="yearly">Tahunan</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-2 gap-4">
                                                    {{-- Dropdown Category (Dinamic via Alpine.js) --}}
                                                    <div>
                                                        <label
                                                            class="block text-xs font-bold text-gray-700 uppercase tracking-wide">Kategori</label>
                                                        <select x-model="kategoriTerpilih" name="category_id"
                                                            class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                                            required>
                                                            <option value="" disabled>-- Pilih Kategori --
                                                            </option>
                                                            <template
                                                                x-for="cat in semuaKategori.filter(c => c.type === tipeTransaksi)"
                                                                :key="cat.id">
                                                                <option :value="cat.id" x-text="cat.name">
                                                                </option>
                                                            </template>
                                                        </select>
                                                    </div>
                                                    <div>
                                                        <label
                                                            class="block text-xs font-bold text-gray-700 uppercase tracking-wide">Dompet</label>
                                                        <select name="wallet_id"
                                                            class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                                            required>
                                                            @foreach ($wallets as $wallet)
                                                                <option value="{{ $wallet->id }}">{{ $wallet->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div>
                                                    <label
                                                        class="block text-xs font-bold text-gray-700 uppercase tracking-wide">Deskripsi
                                                        (Contoh: Tagihan WiFi)</label>
                                                    <input type="text" name="description"
                                                        class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                        required>
                                                </div>

                                                <div>
                                                    <label
                                                        class="block text-xs font-bold text-gray-700 uppercase tracking-wide">Nominal
                                                        (Rp)</label>
                                                    <input type="number" name="amount" min="1"
                                                        class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-lg font-bold"
                                                        required>
                                                </div>

                                                <div>
                                                    <label
                                                        class="block text-xs font-bold text-gray-700 uppercase tracking-wide">Mulai
                                                        Dieksekusi Tanggal</label>
                                                    <input type="date" name="start_date"
                                                        class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                                        required value="{{ date('Y-m-d') }}">
                                                </div>
                                            </div>

                                            <div class="mt-6 flex gap-3">
                                                <button type="button" @click="openForm = false"
                                                    class="w-full justify-center rounded-xl bg-white px-3 py-2.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Batal</button>
                                                <button type="submit"
                                                    class="w-full justify-center rounded-xl bg-blue-600 px-3 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">Simpan
                                                    Jadwal</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            {{-- Layout List Memanjang ke Bawah --}}
            <div class="space-y-4">
                @forelse ($recurrings as $trx)
                    <div
                        class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 transition-all hover:shadow-md flex flex-col md:flex-row gap-6 md:items-center justify-between {{ !$trx->is_active ? 'opacity-60 bg-gray-50' : '' }}">

                        {{-- Sisi Kiri: Ikon & Informasi --}}
                        <div class="flex items-center gap-4">
                            <div
                                class="shrink-0 p-3.5 rounded-xl {{ $trx->type == 'income' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                                @if ($trx->type == 'income')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 12H4"></path>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">{{ $trx->description }}</h3>
                                <p class="text-sm font-medium text-gray-500 mt-0.5">{{ $trx->category->name }} &bull;
                                    {{ $trx->wallet->name }}</p>
                                <div class="flex items-center gap-2 mt-1.5 text-xs text-gray-500 font-medium">
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                            </path>
                                        </svg>
                                        Siklus:
                                        @if ($trx->frequency == 'daily')
                                            Harian
                                        @elseif($trx->frequency == 'weekly')
                                            Mingguan
                                        @elseif($trx->frequency == 'monthly')
                                            Bulanan
                                        @else
                                            Tahunan
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Sisi Kanan: Nominal, Status Aktif (Toggle), dan Tombol Aksi --}}
                        <div
                            class="flex flex-wrap md:flex-nowrap items-center justify-between md:justify-end gap-5 md:gap-8 w-full md:w-auto mt-2 md:mt-0 pt-4 md:pt-0 border-t border-gray-100 md:border-none">

                            {{-- Nominal --}}
                            <div class="text-left md:text-right">
                                <p
                                    class="text-xl font-black tracking-tight {{ $trx->type == 'income' ? 'text-emerald-600' : 'text-gray-900' }}">
                                    {{ $trx->type == 'income' ? '+' : '-' }} Rp
                                    {{ number_format($trx->amount, 0, ',', '.') }}
                                </p>
                                <p
                                    class="text-xs font-semibold {{ $trx->is_active ? 'text-blue-600' : 'text-gray-400' }} mt-1">
                                    {{ $trx->is_active ? 'Selanjutnya: ' . \Carbon\Carbon::parse($trx->next_date)->format('d M Y') : 'Dinonaktifkan' }}
                                </p>
                            </div>

                            <div class="flex items-center gap-4">
                                {{-- Tombol Toggle Switch Modern --}}
                                <form action="{{ route('recurring-transactions.toggle', $trx->id) }}" method="POST"
                                    class="shrink-0 flex items-center">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="relative inline-flex h-7 w-12 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 {{ $trx->is_active ? 'bg-emerald-500' : 'bg-gray-300' }}"
                                        title="{{ $trx->is_active ? 'Matikan Jadwal' : 'Aktifkan Jadwal' }}">
                                        <span
                                            class="pointer-events-none inline-block h-6 w-6 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $trx->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                                    </button>
                                </form>

                                {{-- Garis Pembatas --}}
                                <div class="hidden sm:block h-8 w-px bg-gray-200"></div>

                                {{-- Aksi (Edit & Hapus) --}}
                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="{{ route('recurring-transactions.edit', $trx->id) }}"
                                        class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-2.5 py-1.5 rounded-md transition-all duration-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Edit
                                    </a>

                                    <div x-data="{ openDelete: false }">
                                        <button @click="openDelete = true" type="button"
                                            class="inline-flex items-center gap-1 text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-2.5 py-1.5 rounded-md transition-all duration-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            Hapus
                                        </button>

                                        {{-- Modal Hapus List --}}
                                        <div x-show="openDelete"
                                            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 text-left"
                                            style="display: none;">
                                            <div x-show="openDelete" x-transition.opacity @click="openDelete = false"
                                                class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity">
                                            </div>
                                            <div x-show="openDelete" x-transition
                                                class="relative bg-white rounded-2xl max-w-sm w-full p-6 text-center shadow-2xl border border-gray-100 z-10 transform transition-all">
                                                <div
                                                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-50 text-red-600 mb-4">
                                                    <svg class="h-6 w-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                    </svg>
                                                </div>
                                                <h3 class="text-lg font-bold text-gray-900 mb-1">Hapus Jadwal?</h3>
                                                <p class="text-xs text-gray-500 mb-6">Jadwal <span
                                                        class="font-bold">{{ $trx->description }}</span> akan dihapus
                                                    permanen. Transaksi sebelumnya tetap aman.</p>
                                                <div class="flex justify-center gap-3">
                                                    <button @click="openDelete = false" type="button"
                                                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-xl transition">Batal</button>
                                                    <form
                                                        action="{{ route('recurring-transactions.destroy', $trx->id) }}"
                                                        method="POST">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl shadow-md transition">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white py-16 px-4 rounded-2xl border border-dashed border-gray-300 text-center">
                        <div class="mx-auto w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mb-4">
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-700 mb-2">Belum Ada Transaksi Otomatis</h3>
                        <p class="text-sm text-gray-500 mb-6 max-w-sm mx-auto">Hemat waktu Anda dengan menjadwalkan
                            tagihan atau pendapatan rutin seperti gaji, langganan internet, atau cicilan.</p>
                        <button onclick="document.querySelector('[x-data]').__x.$data.openForm = true"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl shadow-md transition-colors">
                            Buat Jadwal Pertama
                        </button>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
