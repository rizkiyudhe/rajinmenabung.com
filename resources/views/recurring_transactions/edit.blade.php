<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">
            {{ __('Edit Jadwal Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <form action="{{ route('recurring-transactions.update', $recurringTransaction->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jenis Transaksi</label>
                            <select name="type"
                                class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                                <option value="expense"
                                    {{ $recurringTransaction->type == 'expense' ? 'selected' : '' }}>Pengeluaran
                                </option>
                                <option value="income" {{ $recurringTransaction->type == 'income' ? 'selected' : '' }}>
                                    Pemasukan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kategori</label>
                            <select name="category_id"
                                class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ $recurringTransaction->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Dompet</label>
                            <select name="wallet_id"
                                class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                                @foreach ($wallets as $wallet)
                                    <option value="{{ $wallet->id }}"
                                        {{ $recurringTransaction->wallet_id == $wallet->id ? 'selected' : '' }}>
                                        {{ $wallet->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <input type="text" name="description" value="{{ $recurringTransaction->description }}"
                                class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nominal (Rp)</label>
                            <input type="number" name="amount" value="{{ $recurringTransaction->amount }}"
                                class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Frekuensi</label>
                            <select name="frequency"
                                class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                                <option value="daily"
                                    {{ $recurringTransaction->frequency == 'daily' ? 'selected' : '' }}>Harian</option>
                                <option value="weekly"
                                    {{ $recurringTransaction->frequency == 'weekly' ? 'selected' : '' }}>Mingguan
                                </option>
                                <option value="monthly"
                                    {{ $recurringTransaction->frequency == 'monthly' ? 'selected' : '' }}>Bulanan
                                </option>
                                <option value="yearly"
                                    {{ $recurringTransaction->frequency == 'yearly' ? 'selected' : '' }}>Tahunan
                                </option>
                            </select>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <a href="{{ route('recurring-transactions.index') }}"
                                class="w-full text-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-bold transition">Batal</a>
                            <button type="submit"
                                class="w-full px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold transition">Simpan
                                Perubahan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
