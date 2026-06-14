<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('master-wallets.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Edit Master Dompet</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto px-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">

                <form action="{{ route('master-wallets.update', $masterWallet->id) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Nama
                            Dompet</label>
                        <input type="text" name="name" value="{{ old('name', $masterWallet->name) }}"
                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition text-sm font-medium"
                            required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Logo
                            Dompet</label>

                        @if ($masterWallet->logo)
                            <div class="mb-4 flex items-center gap-4 p-4 bg-gray-50 border border-gray-100 rounded-xl">
                                <div
                                    class="w-16 h-16 rounded-xl border border-gray-200 bg-white flex items-center justify-center p-2 shrink-0">
                                    <img src="{{ asset('storage/' . $masterWallet->logo) }}" alt="Logo"
                                        class="w-full h-full object-contain">
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-700">Logo Saat Ini</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Unggah file baru di bawah ini jika ingin
                                        menggantinya.</p>
                                </div>
                            </div>
                        @endif

                        <input type="file" name="logo" accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition cursor-pointer">
                        <p class="text-xs text-gray-400 mt-2">Biarkan kosong jika tidak ingin mengubah logo. Format yang
                            didukung: JPG, PNG, SVG (Maks. 2MB).</p>
                        @error('logo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <button type="submit"
                            class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-md transition transform hover:-translate-y-0.5">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
