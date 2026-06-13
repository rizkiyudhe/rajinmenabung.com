<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">{{ __('Daftar Transaksi') }}</h2>
                <p class="text-sm text-gray-500 mt-1">Pantau arus kas Anda</p>
            </div>
            <a href="{{ route('transactions.create') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl shadow transition">
                + Catat Transaksi
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div
                    class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-800 p-3 rounded-r-lg shadow-sm text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Dompet</th>
                            <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Nominal</th>
                            <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($transactions as $trx)
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($trx->date)->format('d M Y') }}</td>
                                <td class="px-5 py-4 text-sm font-medium text-gray-900">
                                    {{ $trx->category->name }}
                                    <div class="text-xs text-gray-400 font-normal mt-0.5">{{ $trx->description ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-sm text-gray-500">{{ $trx->wallet->name }}</td>
                                <td
                                    class="px-5 py-4 text-sm text-right font-bold {{ $trx->type == 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $trx->type == 'income' ? '+' : '-' }} Rp
                                    {{ number_format($trx->amount, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <form action="{{ route('transactions.destroy', $trx) }}" method="POST"
                                        onsubmit="return confirm('Hapus transaksi ini? Saldo dompet akan disesuaikan otomatis.');"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-500 hover:text-red-700 text-sm font-medium">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-gray-500 text-sm">Belum ada
                                    transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                @if (method_exists($transactions, 'links') && $transactions->hasPages())
                    <div class="p-4 border-t border-gray-100">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
