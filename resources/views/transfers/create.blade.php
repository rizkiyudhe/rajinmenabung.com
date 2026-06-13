<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('transfers.index') }}" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">{{ __('Transfer Saldo') }}</h2>
                <p class="text-sm text-gray-500 mt-1">Pindahkan dana antar dompet internal Anda</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 sm:p-8">

                <form action="{{ route('transfers.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Dari Dompet (Sumber)</label>
                            <select name="from_wallet_id"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-200 outline-none"
                                required>
                                @foreach ($wallets as $wallet)
                                    <option value="{{ $wallet->id }}"
                                        {{ old('from_wallet_id') == $wallet->id ? 'selected' : '' }}>
                                        {{ $wallet->name }} (Saldo: Rp
                                        {{ number_format($wallet->balance, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Ke Dompet (Tujuan)</label>
                            <select name="to_wallet_id"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-200 outline-none"
                                required>
                                @foreach ($wallets as $wallet)
                                    <option value="{{ $wallet->id }}"
                                        {{ old('to_wallet_id') == $wallet->id ? 'selected' : '' }}>
                                        {{ $wallet->name }} (Saldo: Rp
                                        {{ number_format($wallet->balance, 0, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Transfer</label>
                            <div class="relative">
                                <span
                                    class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                <input type="number" name="amount" min="1" value="{{ old('amount') }}"
                                    placeholder="0"
                                    class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-200 outline-none font-semibold @error('amount') border-red-400 @enderror"
                                    required>
                            </div>
                            @error('amount')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mutasi</label>
                            <input type="date" name="date" value="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-200 outline-none"
                                required>
                        </div>
                    </div>

                    <div class="mb-8">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Keterangan <span
                                class="text-gray-400 font-normal">(Opsional)</span></label>
                        <input type="text" name="description" value="{{ old('description') }}"
                            placeholder="Contoh: Tarik tunai ATM / Setor tunai"
                            class="w-full px-4 py-2.5 rounded-xl border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-400 focus:ring-2 focus:ring-blue-200 outline-none">
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-100">
                        <a href="{{ route('transfers.index') }}"
                            class="text-sm font-medium text-gray-500 hover:text-gray-700 px-4 py-2">Batal</a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-6 rounded-xl shadow-md transition-all">Proses
                            Transfer</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
