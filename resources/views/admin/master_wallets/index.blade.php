<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Master Dompet</h2>
            <button @click="openModal = true"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-lg transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Dompet
            </button>
        </div>
    </x-slot>

    <div class="py-8" x-data="{ openModal: false, editModal: false, editData: {} }">
        <div class="max-w-5xl mx-auto px-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr class="text-gray-500 text-sm">
                            <th class="p-4">Logo</th>
                            <th class="p-4">Nama Dompet</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($masterWallets as $mw)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-4"><img src="{{ asset('storage/' . $mw->logo) }}"
                                        class="w-10 h-10 rounded-lg object-contain border"></td>
                                <td class="p-4 font-bold text-gray-800">{{ $mw->name }}</td>
                                <td class="p-4 flex justify-center gap-2">
                                    <button @click="editModal = true; editData = {{ $mw }}"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </button>
                                    <form action="{{ route('master-wallets.destroy', $mw->id) }}" method="POST"
                                        onsubmit="return confirm('Hapus?')">
                                        @csrf @method('DELETE')
                                        <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg"><svg class="w-5 h-5"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modal Tambah --}}
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
            style="display:none">
            <div class="bg-white rounded-2xl w-full max-w-sm p-6" @click.away="openModal = false">
                <h3 class="font-bold text-lg mb-4">Tambah Master Dompet</h3>
                <form action="{{ route('master-wallets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="text" name="name" class="w-full mb-3 rounded-xl border-gray-300"
                        placeholder="Nama Dompet" required>
                    <input type="file" name="logo" class="w-full mb-4">
                    <button class="w-full bg-blue-600 text-white py-2 rounded-xl font-bold">Simpan</button>
                </form>
            </div>
        </div>

        {{-- Modal Edit (Mirip tambah, gunakan action="{{ route('master-wallets.update', editData.id) }}" dan @method('PUT')) --}}
    </div>
</x-app-layout>
