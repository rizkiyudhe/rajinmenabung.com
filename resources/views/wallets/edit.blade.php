<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Dompet: ') }} <span class="text-blue-600">{{ $wallet->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('wallets.update', $wallet) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Dompet / Rekening</label>
                            <select name="name"
                                class="mt-1 w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                required>
                                <option value="">-- Pilih Jenis Dompet --</option>
                                @foreach ($masterWallets as $mw)
                                    {{-- PERBAIKAN DI SINI --}}
                                    <option value="{{ $mw->name }}"
                                        {{ $wallet->name == $mw->name ? 'selected' : '' }}>
                                        {{ $mw->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="balance" class="block text-sm font-medium text-gray-700 mb-1">
                                Penyesuaian Saldo (Rp)
                            </label>
                            <input type="number" name="balance" id="balance"
                                value="{{ old('balance', round($wallet->balance)) }}"
                                class="mt-1 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                                min="0" required>
                            <p class="text-gray-400 text-xs mt-1">Ubah manual hanya jika terjadi selisih pencatatan.</p>
                            @error('balance')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end space-x-4 border-t pt-4 mt-6">
                            <a href="{{ route('wallets.index') }}"
                                class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                                Batal
                            </a>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
