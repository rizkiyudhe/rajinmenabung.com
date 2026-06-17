<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">
            {{ __('Edit Jadwal Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100">

                {{-- Form dengan Alpine JS terintegrasi --}}
                <form
                    x-data='{
                    tipeTransaksi: "{{ old('type', $recurringTransaction->type) }}",
                    kategoriTerpilih: "{{ old('category_id', $recurringTransaction->category_id) }}",
                    semuaKategori: @json(
                        $categories->map(function ($c) {
                                return ['id' => $c->id, 'name' => $c->name, 'type' => strtolower($c->type ?? 'expense')];
                            })->values())
                }'
                    action="{{ route('recurring-transactions.update', $recurringTransaction->id) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <div class="space-y-5">

                        {{-- Baris 1: Jenis & Frekuensi --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide">Jenis
                                    Transaksi</label>
                                <select x-model="tipeTransaksi" @change="kategoriTerpilih = ''" name="type"
                                    class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                    required>
                                    <option value="expense">Pengeluaran</option>
                                    <option value="income">Pemasukan</option>
                                </select>
                                @error('type')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-700 uppercase tracking-wide">Frekuensi</label>
                                <select name="frequency"
                                    class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                    required>
                                    <option value="daily"
                                        {{ old('frequency', $recurringTransaction->frequency) == 'daily' ? 'selected' : '' }}>
                                        Harian
                                    </option>
                                    <option value="weekly"
                                        {{ old('frequency', $recurringTransaction->frequency) == 'weekly' ? 'selected' : '' }}>
                                        Mingguan
                                    </option>
                                    <option value="monthly"
                                        {{ old('frequency', $recurringTransaction->frequency) == 'monthly' ? 'selected' : '' }}>
                                        Bulanan
                                    </option>
                                    <option value="yearly"
                                        {{ old('frequency', $recurringTransaction->frequency) == 'yearly' ? 'selected' : '' }}>
                                        Tahunan
                                    </option>
                                </select>
                                @error('frequency')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Baris 2: Kategori & Dompet --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-700 uppercase tracking-wide">Kategori</label>
                                <select x-model="kategoriTerpilih" name="category_id"
                                    class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                    required>
                                    <option value="" disabled>-- Pilih Kategori --</option>
                                    <template x-for="cat in semuaKategori.filter(c => c.type === tipeTransaksi)"
                                        :key="cat.id">
                                        <option :value="cat.id" x-text="cat.name"
                                            :selected="cat.id == kategoriTerpilih"></option>
                                    </template>
                                </select>
                                @error('category_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label
                                    class="block text-xs font-bold text-gray-700 uppercase tracking-wide">Dompet</label>
                                <select name="wallet_id"
                                    class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm"
                                    required>
                                    @foreach ($wallets as $wallet)
                                        <option value="{{ $wallet->id }}"
                                            {{ old('wallet_id', $recurringTransaction->wallet_id) == $wallet->id ? 'selected' : '' }}>
                                            {{ $wallet->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('wallet_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Baris 3: Deskripsi --}}
                        <div>
                            <label
                                class="block text-xs font-bold text-gray-700 uppercase tracking-wide">Deskripsi</label>
                            <input type="text" name="description"
                                value="{{ old('description', $recurringTransaction->description) }}"
                                class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Baris 4: Nominal & Tanggal Mulai --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide">Nominal
                                    (Rp)</label>
                                <input type="number" name="amount"
                                    value="{{ old('amount', $recurringTransaction->amount) }}" min="1"
                                    class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-lg font-bold"
                                    required>
                                @error('amount')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- INI ADALAH INPUT YANG HILANG SEBELUMNYA --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide">Tanggal
                                    Mulai Berlaku</label>
                                <input type="date" name="start_date"
                                    value="{{ old('start_date', \Carbon\Carbon::parse($recurringTransaction->start_date)->format('Y-m-d')) }}"
                                    class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                @error('start_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex gap-3 pt-6 mt-2 border-t border-gray-100">
                            <a href="{{ route('recurring-transactions.index') }}"
                                class="w-full text-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold transition">Batal</a>
                            <button type="submit"
                                class="w-full px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-md transition">Simpan
                                Perubahan</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
