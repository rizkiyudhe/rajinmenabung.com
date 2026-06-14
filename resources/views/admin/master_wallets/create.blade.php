<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('master-wallets.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
            </a>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Tambah Master Dompet</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto px-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">

                <form action="{{ route('master-wallets.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Nama
                            Dompet</label>
                        <input type="text" name="name"
                            class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm transition text-sm font-medium"
                            placeholder="Misal: BCA, Mandiri, OVO" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Logo
                            Dompet</label>
                        <input type="file" name="logo" accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition cursor-pointer">
                        <p class="text-xs text-gray-400 mt-2">Format yang didukung: JPG, PNG, SVG (Maks. 2MB).</p>
                        @error('logo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <button type="submit"
                            class="w-full px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm shadow-md transition transform hover:-translate-y-0.5">
                            Simpan Data
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
