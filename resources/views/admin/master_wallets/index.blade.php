<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Master Dompet (Admin)</h2>
            {{-- Tombol Tambah diarahkan ke halaman Create --}}
            <a href="{{ route('master-wallets.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg transition flex items-center gap-2 transform hover:-translate-y-0.5 duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Dompet
            </a>
        </div>
    </x-slot>

    {{-- State hanya untuk Modal Hapus --}}
    <div class="py-8" x-data="{ openDelete: false, nameDelete: '', deleteAction: '' }">


        <div class="max-w-5xl mx-auto px-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr class="text-gray-500 text-xs font-bold uppercase tracking-wider">
                            <th class="p-4 pl-6">Logo</th>
                            <th class="p-4">Nama Dompet</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach ($masterWallets as $mw)
                            <tr class="hover:bg-gray-50/80 transition">
                                <td class="p-4 pl-6">
                                    @if ($mw->logo)
                                        <img src="{{ asset('storage/' . $mw->logo) }}" alt="{{ $mw->name }}"
                                            class="w-10 h-10 rounded-xl object-contain border border-gray-100 bg-white p-1 shadow-sm">
                                    @else
                                        <div
                                            class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-gray-400 border border-gray-100 shadow-sm">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                                <td class="p-4 font-bold text-gray-800">{{ $mw->name }}</td>
                                <td class="p-4 flex justify-center gap-2">
                                    {{-- AKSI EDIT (Diarahkan ke halaman Edit) --}}
                                    <a href="{{ route('master-wallets.edit', $mw->id) }}"
                                        class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-xl font-semibold text-xs transition duration-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                        Edit
                                    </a>

                                    {{-- AKSI HAPUS (Memicu Modal Validasi Hapus) --}}
                                    <button
                                        @click="openDelete = true; deleteAction = '{{ route('master-wallets.destroy', $mw->id) }}'; nameDelete = '{{ addslashes($mw->name) }}'"
                                        class="inline-flex items-center gap-1 text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-xl font-semibold text-xs transition duration-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MODAL HAPUS DATA --}}
        <div x-show="openDelete" style="display: none;"
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/40 backdrop-blur-sm"
            x-transition.opacity.duration.200ms>
            <div class="relative bg-white rounded-2xl max-w-sm w-full p-6 text-center shadow-2xl border border-gray-100"
                @click.away="openDelete = false">
                <div
                    class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-50 text-red-600 mb-4">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 mb-1">Hapus Master Dompet?</h3>
                <p class="text-xs text-gray-500 mb-6 leading-relaxed">
                    Menghapus master <span class="font-bold text-gray-800" x-text="nameDelete"></span> dapat
                    memengaruhi pilihan opsi jenis rekening pada sistem pengguna yang akan datang.
                </p>
                <div class="flex justify-center gap-3">
                    <button @click="openDelete = false" type="button"
                        class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition-colors min-w-[100px]">
                        Batal
                    </button>
                    <form :action="deleteAction" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-xl shadow-md transition-all min-w-[100px]">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
