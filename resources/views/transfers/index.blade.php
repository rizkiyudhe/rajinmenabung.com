<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">{{ __('Mutasi Saldo') }}</h2>
                <p class="text-sm text-gray-500 mt-1">Riwayat perpindahan dana antar dompet</p>
            </div>
            <a href="{{ route('transfers.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl shadow transition">
                + Transfer Saldo
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div
                    class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-800 p-3 rounded-r-lg shadow-sm text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Dari Dompet
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Ke Dompet</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Nominal</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($transfers as $tf)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($tf->date)->format('d M Y') }}</td>
                                <td class="px-5 py-4 text-sm font-medium text-rose-600">
                                    {{ $tf->fromWallet->name }}
                                    <div class="text-xs text-gray-400 font-normal mt-0.5">
                                        {{ $tf->description ?? 'Transfer dana' }}</div>
                                </td>
                                <td class="px-5 py-4 text-sm font-medium text-emerald-600">{{ $tf->toWallet->name }}
                                </td>
                                <td class="px-5 py-4 text-sm text-right font-bold text-gray-900">
                                    Rp {{ number_format($tf->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('transfers.edit', $tf) }}"
                                            class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-2.5 py-1.5 rounded-md transition-all duration-200">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                            Edit
                                        </a>

                                        <form action="{{ route('transfers.destroy', $tf) }}" method="POST"
                                            onsubmit="return confirm('Yakin menghapus mutasi ini? Saldo kedua dompet akan dikembalikan seperti semula.');"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1 text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-2.5 py-1.5 rounded-md transition-all duration-200">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-gray-500 text-sm">Belum ada
                                    riwayat mutasi saldo.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if ($transfers->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $transfers->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
