<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">{{ __('Transaksi Otomatis') }}</h2>
                <p class="text-sm text-gray-500 mt-1">Jadwalkan tagihan bulanan atau pendapatan rutin Anda</p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                {{-- Tombol Buat Jadwal Baru --}}
                <div x-data="{ openForm: false }" class="w-full sm:w-auto">
                    <button @click="openForm = true"
                        class="w-full sm:w-auto inline-flex justify-center items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-2 px-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Buat Jadwal Baru
                    </button>

                    {{-- Modal Form Tambah (Alpine JS) --}}
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

                                        <form action="{{ route('recurring-transactions.store') }}" method="POST">
                                            @csrf
                                            <div class="space-y-4">
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div>
                                                        <label
                                                            class="block text-xs font-bold text-gray-700 uppercase tracking-wide">Jenis</label>
                                                        <select name="type"
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
                                                    <div>
                                                        <label
                                                            class="block text-xs font-bold text-gray-700 uppercase tracking-wide">Kategori</label>
                                                        <select name="category_id"
                                                            class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                                            required>
                                                            @foreach ($categories as $cat)
                                                                <option value="{{ $cat->id }}">{{ $cat->name }}
                                                                </option>
                                                            @endforeach
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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alert Success --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" x-transition
                    class="mb-6 flex items-center justify-between p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl shadow-sm">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            {{-- Grid Cards Jadwal --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($recurrings as $trx)
                    <div
                        class="bg-white overflow-hidden shadow-sm hover:shadow-md rounded-2xl border border-gray-100 transition-all duration-300 group {{ !$trx->is_active ? 'opacity-75 grayscale-[30%]' : '' }}">
                        <div class="p-6">

                            {{-- Header Card --}}
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="p-2.5 rounded-xl {{ $trx->type == 'income' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                                        @if ($trx->type == 'income')
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        @else
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 12H4"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-800 truncate"
                                            title="{{ $trx->description }}">
                                            {{ \Illuminate\Support\Str::limit($trx->description, 20) }}</h3>
                                        <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">
                                            {{ $trx->category->name }}</p>
                                    </div>
                                </div>

                                {{-- Status Toggle Form --}}
                                <form action="{{ route('recurring-transactions.toggle', $trx->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="w-2.5 h-2.5 rounded-full outline-none focus:ring-2 focus:ring-offset-2 {{ $trx->is_active ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)] focus:ring-emerald-500' : 'bg-gray-300 focus:ring-gray-400' }}"
                                        title="{{ $trx->is_active ? 'Matikan Jadwal' : 'Aktifkan Jadwal' }}"></button>
                                </form>
                            </div>

                            {{-- Info Amount & Next Date --}}
                            <div class="mt-5 bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <p class="text-xs text-gray-500 mb-1">Total dieksekusi per <span
                                        class="font-bold text-gray-700 capitalize">
                                        @if ($trx->frequency == 'daily')
                                            Hari
                                        @elseif($trx->frequency == 'weekly')
                                            Minggu
                                        @elseif($trx->frequency == 'monthly')
                                            Bulan
                                        @else
                                            Tahun
                                        @endif
                                    </span></p>
                                <p
                                    class="text-xl font-black {{ $trx->type == 'income' ? 'text-emerald-600' : 'text-gray-900' }} tracking-tight">
                                    Rp {{ number_format($trx->amount, 0, ',', '.') }}
                                </p>

                                <div
                                    class="flex items-center gap-2 mt-3 text-xs font-medium {{ $trx->is_active ? 'text-blue-600' : 'text-gray-500' }}">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    @if ($trx->is_active)
                                        Selanjutnya: {{ \Carbon\Carbon::parse($trx->next_date)->format('d M Y') }}
                                    @else
                                        Jadwal Dinonaktifkan
                                    @endif
                                </div>
                                <div class="flex items-center gap-2 mt-1 text-xs text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                        </path>
                                    </svg>
                                    Dompet: {{ $trx->wallet->name }}
                                </div>
                            </div>

                            {{-- Tombol Aksi (Hanya muncul saat di-hover/mobile) --}}
                            <div
                                class="mt-4 pt-4 border-t border-gray-100 flex justify-end gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-200">
                                <a href="{{ route('recurring-transactions.edit', $trx->id) }}"
                                    class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-sm font-semibold transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Edit
                                </a>

                                <div x-data="{ openDelete: false }" class="inline">
                                    <button @click="openDelete = true" type="button"
                                        class="inline-flex items-center gap-1 text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg text-sm font-semibold transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Hapus
                                    </button>

                                    {{-- Modal Konfirmasi Hapus --}}
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
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-bold text-gray-900 mb-1">Hapus Jadwal?</h3>
                                            <p class="text-xs text-gray-500 mb-6 leading-relaxed">
                                                Jadwal <span
                                                    class="font-bold text-gray-700">{{ $trx->description }}</span>
                                                tidak akan dieksekusi lagi setelah dihapus. Data transaksi yang sudah
                                                tercatat sebelumnya aman.
                                            </p>
                                            <div class="flex justify-center gap-3">
                                                <button @click="openDelete = false" type="button"
                                                    class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition-colors min-w-[80px]">Batal</button>
                                                <form action="{{ route('recurring-transactions.destroy', $trx->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-md transition-all min-w-[80px]">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                @empty
                    <div
                        class="col-span-1 md:col-span-2 lg:col-span-3 bg-white py-16 px-4 rounded-2xl border border-dashed border-gray-300 text-center">
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
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Buat Jadwal Pertama
                        </button>
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
