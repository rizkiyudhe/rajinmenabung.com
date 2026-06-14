<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">{{ __('Laporan Keuangan') }}</h2>
                <p class="text-sm text-gray-500 mt-1">Analisis riwayat arus kas dan unduh dokumen Anda</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Form Filter & Tombol Download --}}
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <form action="{{ route('reports.index') }}" method="GET"
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-600">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ $startDate }}"
                            class="mt-1 w-full rounded-xl border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-600">Tanggal Selesai</label>
                        <input type="date" name="end_date" value="{{ $endDate }}"
                            class="mt-1 w-full rounded-xl border-gray-300 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-600">Dompet</label>
                        <select name="wallet_id" class="mt-1 w-full rounded-xl border-gray-300 text-sm">
                            <option value="">Semua Dompet</option>
                            @foreach ($wallets as $w)
                                <option value="{{ $w->id }}" {{ $walletId == $w->id ? 'selected' : '' }}>
                                    {{ $w->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit"
                            class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-sm text-sm transition">
                            Filter
                        </button>
                        <a href="{{ route('reports.index') }}"
                            class="w-full py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition text-center">
                            Reset
                        </a>
                    </div>
                </form>

                {{-- Opsi Ekspor Dokumen --}}
                <div class="mt-4 pt-4 border-t border-gray-100 flex flex-wrap gap-3 justify-end">
                    <a href="{{ route('reports.excel', request()->all()) }}"
                        class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 px-4 py-2 rounded-xl text-xs font-bold transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Unduh Excel (.xlsx)
                    </a>
                    <a href="{{ route('reports.pdf', request()->all()) }}"
                        class="inline-flex items-center gap-2 bg-rose-50 text-rose-700 hover:bg-rose-100 px-4 py-2 rounded-xl text-xs font-bold transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                        Unduh PDF (.pdf)
                    </a>
                </div>
            </div>

            {{-- Ringkasan Angka Akumulasi --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Total Pemasukan</p>
                    <p class="text-2xl font-black text-emerald-600 mt-2">Rp
                        {{ number_format($totalIncome, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Total Pengeluaran</p>
                    <p class="text-2xl font-black text-rose-600 mt-2">Rp
                        {{ number_format($totalExpense, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Selisih (Net Cashflow)</p>
                    <p class="text-2xl font-black mt-2 {{ $netBalance >= 0 ? 'text-blue-600' : 'text-rose-700' }}">
                        Rp {{ number_format($netBalance, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Daftar List Baris Transaksi --}}
            <div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="font-bold text-gray-700">Rincian Transaksi Terfilter</h3>
                </div>
                <ul class="divide-y divide-gray-100">
                    @forelse($transactions as $trx)
                        <li class="p-4 sm:p-5 flex items-center justify-between gap-4 hover:bg-gray-50/50 transition">
                            <div class="flex items-center gap-4">
                                <div
                                    class="p-3 rounded-xl {{ $trx->type == 'income' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                    @if ($trx->type == 'income')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 12H4"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">{{ $trx->description }}</h4>
                                    <p class="text-xs font-medium text-gray-500 mt-0.5">{{ $trx->category->name }}
                                        &bull; {{ $trx->wallet->name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p
                                    class="font-black {{ $trx->type == 'income' ? 'text-emerald-600' : 'text-gray-900' }}">
                                    {{ $trx->type == 'income' ? '+' : '-' }} Rp
                                    {{ number_format($trx->amount, 0, ',', '.') }}
                                </p>
                                <p class="text-xs font-medium text-gray-400 mt-0.5">
                                    {{ \Carbon\Carbon::parse($trx->date)->format('d M Y') }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="p-12 text-center text-gray-400">Tidak ada data transaksi yang cocok pada periode ini.
                        </li>
                    @endforelse
                </ul>
            </div>

        </div>
    </div>
</x-app-layout>
