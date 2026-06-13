<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('transactions.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">{{ __('Catat Transaksi') }}</h2>
                <p class="text-sm text-gray-500 mt-1">Masukkan data pemasukan atau pengeluaran baru</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden p-6 sm:p-8">

                <form action="{{ route('transactions.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Transaksi</label>
                            <select name="type" id="type"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                                required>
                                <option value="expense">Pengeluaran</option>
                                <option value="income">Pemasukan</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal</label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                                required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Dompet</label>
                            <select name="wallet_id"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                                required>
                                @foreach ($wallets as $wallet)
                                    <option value="{{ $wallet->id }}">{{ $wallet->name }} (Rp
                                        {{ number_format($wallet->balance, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                            <select name="category_id" id="category_id"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-200 outline-none transition-all"
                                required>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal (Rp)</label>
                        <div class="relative">
                            <span
                                class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                            <input type="number" name="amount" min="1" placeholder="0"
                                class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-200 outline-none transition-all text-lg font-semibold"
                                required>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan <span
                                class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="description" placeholder="Contoh: Makan siang bareng teman"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-200 outline-none transition-all">
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('transactions.index') }}"
                            class="text-sm font-medium text-gray-500 hover:text-gray-700 px-4 py-2">Batal</a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-xl shadow-md transition-all">Simpan
                            Transaksi</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const categorySelect = document.getElementById('category_id');

            // Konversi data array PHP (Laravel) menjadi array JavaScript
            const categories = @json($categories);

            function updateCategoryDropdown() {
                const selectedType = typeSelect.value;

                // Kosongkan opsi dropdown kategori setiap kali jenis transaksi diubah
                categorySelect.innerHTML = '';

                // Saring kategori yang tipenya sama dengan jenis transaksi (income / expense)
                const filteredCategories = categories.filter(cat => cat.type === selectedType);

                if (filteredCategories.length > 0) {
                    // Masukkan kategori yang lolos filter ke dalam dropdown
                    filteredCategories.forEach(cat => {
                        const option = document.createElement('option');
                        option.value = cat.id;
                        option.textContent = cat.name;
                        categorySelect.appendChild(option);
                    });
                } else {
                    // Tampilkan pesan default jika belum ada kategori untuk tipe tersebut
                    const option = document.createElement('option');
                    option.value = "";
                    option.textContent = "-- Belum ada kategori --";
                    option.disabled = true;
                    option.selected = true;
                    categorySelect.appendChild(option);
                }
            }

            // Jalankan sekali saat halaman pertama dimuat
            updateCategoryDropdown();

            // Jalankan fungsi setiap kali user mengganti jenis transaksi
            typeSelect.addEventListener('change', updateCategoryDropdown);
        });
    </script>
</x-app-layout>
