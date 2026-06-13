<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">{{ __('Utang Piutang') }}</h2>
                <p class="text-sm text-gray-500 mt-1">Catatan pinjaman uang masuk dan keluar</p>
            </div>
            <a href="{{ route('debts.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl shadow transition text-sm">
                + Tambah Catatan
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-800 p-3 rounded-r-lg text-sm">
                    {{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Jenis</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama Kontak
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Jatuh Tempo
                            </th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Nominal</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($debts as $debt)
                            <tr
                                class="hover:bg-gray-50 {{ $debt->status == 'paid' ? 'bg-gray-50/50 opacity-60' : '' }}">
                                <td class="px-5 py-4 text-sm">
                                    <span
                                        class="px-2.5 py-0.5 rounded-md text-xs font-bold {{ $debt->type == 'debt' ? 'bg-amber-100 text-amber-800' : 'bg-indigo-100 text-indigo-800' }}">
                                        {{ $debt->type == 'debt' ? 'Utang Saya' : 'Piutang Saya' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-sm font-medium text-gray-900">
                                    {{ $debt->person_name }}
                                    <div class="text-xs text-gray-400 font-normal mt-0.5">
                                        {{ $debt->description ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ $debt->due_date ? \Carbon\Carbon::parse($debt->due_date)->format('d M Y') : 'Tidak ada' }}
                                </td>
                                <td class="px-5 py-4 text-sm text-right font-bold text-gray-900">
                                    Rp {{ number_format($debt->amount, 0, ',', '.') }}
                                    <div
                                        class="text-xs font-semibold {{ $debt->status == 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                                        {{ $debt->status == 'paid' ? 'Lunas' : 'Belum Lunas' }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center text-sm">
                                    <div class="flex justify-center gap-2">
                                        @if ($debt->status == 'pending')
                                            <form action="{{ route('debts.settle', $debt) }}" method="POST"
                                                class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="text-emerald-600 hover:text-emerald-800 font-semibold bg-emerald-50 px-2.5 py-1 rounded-md">Set
                                                    Lunas</button>
                                            </form>
                                        @endif
                                        <form action="{{ route('debts.destroy', $debt) }}" method="POST"
                                            onsubmit="return confirm('Hapus catatan ini?');" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="text-red-500 hover:text-red-700 font-semibold px-2.5 py-1">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-gray-500 text-sm">Belum ada
                                    catatan utang piutang.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4 border-t">{{ $debts->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
