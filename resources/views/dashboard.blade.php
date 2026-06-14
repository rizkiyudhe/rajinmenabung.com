<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (auth()->user()->isAdmin())
                {{-- TAMPILAN ADMIN --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
                        <div class="p-4 bg-blue-50 rounded-xl text-blue-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total User (Keluarga)</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalUsers }}</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
                        <div class="p-4 bg-purple-50 rounded-xl text-purple-600">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Kategori Aktif</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalCategories }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 p-8 text-center">
                    <h3 class="text-xl font-bold text-gray-700">Selamat Datang, Admin!</h3>
                    <p class="text-gray-500 mt-2">Gunakan menu di atas untuk mengelola data master pengguna dan
                        kategori.</p>
                </div>
            @else
                {{-- TAMPILAN USER --}}

                {{-- Reminder Utang --}}
                @if ($reminderDebts->count() > 0)
                    <div class="mb-6 space-y-3">
                        @foreach ($reminderDebts as $debt)
                            @php
                                $isOverdue = $debt->due_date && \Carbon\Carbon::parse($debt->due_date)->isPast();
                                $dueDateFormatted = $debt->due_date
                                    ? \Carbon\Carbon::parse($debt->due_date)->format('d M Y')
                                    : null;
                            @endphp

                            <div
                                class="p-4 rounded-xl border shadow-sm flex items-center justify-between gap-4 animate-fade-in text-sm {{ $isOverdue ? 'bg-red-50 border-red-200 text-red-900' : 'bg-amber-50 border-amber-200 text-amber-900' }}">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="p-2 rounded-lg {{ $isOverdue ? 'bg-red-200 text-red-700' : 'bg-amber-200 text-amber-700' }}">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="font-bold">
                                            {{ $debt->type == 'debt' ? 'Pengingat Utang:' : 'Pengingat Piutang:' }}
                                        </span>
                                        Anda memiliki
                                        {{ $debt->type == 'debt' ? 'kewajiban membayar' : 'tagihan dana' }} kepada
                                        <span class="font-bold">{{ $debt->person_name }}</span> sebesar
                                        <span class="font-bold">Rp
                                            {{ number_format($debt->amount, 0, ',', '.') }}</span>.
                                        @if ($dueDateFormatted)
                                            <div
                                                class="text-xs mt-0.5 font-medium {{ $isOverdue ? 'text-red-600 underline' : 'text-amber-700' }}">
                                                {{ $isOverdue ? 'SUDAH JATUH TEMPO PADA ' . $dueDateFormatted : 'Jatuh tempo tanggal: ' . $dueDateFormatted }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('debts.index') }}"
                                    class="px-3 py-1.5 rounded-lg font-semibold text-xs transition shadow-sm {{ $isOverdue ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-amber-600 hover:bg-amber-700 text-white' }}">
                                    Kelola
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif

                {{-- Summary Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl p-6 shadow-lg text-white">
                        <p class="text-blue-100 text-sm font-medium mb-1">Total Saldo Tersedia</p>
                        <h3 class="text-3xl font-bold tracking-tight">Rp
                            {{ number_format($totalBalance, 0, ',', '.') }}</h3>
                        <div class="mt-4 flex items-center text-sm text-blue-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                </path>
                            </svg>
                            Dari seluruh dompet Anda
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm font-medium mb-1">Pemasukan (Bulan Ini)</p>
                                <h3 class="text-2xl font-bold text-emerald-600">+ Rp
                                    {{ number_format($incomeThisMonth, 0, ',', '.') }}</h3>
                            </div>
                            <div class="p-3 bg-emerald-50 rounded-lg text-emerald-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-gray-500 text-sm font-medium mb-1">Pengeluaran (Bulan Ini)</p>
                                <h3 class="text-2xl font-bold text-rose-600">- Rp
                                    {{ number_format($expenseThisMonth, 0, ',', '.') }}</h3>
                            </div>
                            <div class="p-3 bg-rose-50 rounded-lg text-rose-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Area Grafik --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">

                    {{-- Grafik Arus Kas Bar Chart --}}
                    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">Arus Kas Harian ({{ date('F Y') }})</h3>
                        <div class="relative h-72 w-full">
                            <canvas id="cashflowChart"></canvas>
                        </div>
                    </div>

                    {{-- Aktivitas Terakhir --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-800">Aktivitas Terakhir</h3>
                            <a href="{{ route('transactions.index') }}"
                                class="text-sm text-blue-600 hover:text-blue-800 font-medium">Lihat Semua</a>
                        </div>
                        <div class="space-y-4">
                            @forelse ($recentTransactions as $trx)
                                <div
                                    class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="p-2 rounded-full {{ $trx->type == 'income' ? 'bg-emerald-100 text-emerald-600' : 'bg-rose-100 text-rose-600' }}">
                                            @if ($trx->type == 'income')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-800">{{ $trx->category->name }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($trx->date)->format('d M') }} •
                                                {{ $trx->wallet->name }}</p>
                                        </div>
                                    </div>
                                    <div
                                        class="text-sm font-bold {{ $trx->type == 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $trx->type == 'income' ? '+' : '-' }} Rp
                                        {{ number_format($trx->amount, 0, ',', '.') }}
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-6 text-gray-500 text-sm">
                                    Belum ada transaksi.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Area Donut Chart --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-700 mb-6">Pengeluaran per Kategori</h3>
                        <div class="relative w-full h-72">
                            <canvas id="expenseChart"></canvas>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-700 mb-6">Pemasukan per Kategori</h3>
                        <div class="relative w-full h-72">
                            <canvas id="incomeChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- SCRIPT --}}
                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // 1. Bar Chart Arus Kas Harian
                        const ctx = document.getElementById('cashflowChart').getContext('2d');
                        const chartData = @json($chartData);

                        new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: chartData.labels,
                                datasets: [{
                                        label: 'Pemasukan',
                                        data: chartData.income,
                                        backgroundColor: '#10b981',
                                        borderRadius: 4,
                                        barPercentage: 0.6,
                                    },
                                    {
                                        label: 'Pengeluaran',
                                        data: chartData.expense,
                                        backgroundColor: '#f43f5e',
                                        borderRadius: 4,
                                        barPercentage: 0.6,
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: {
                                    intersect: false,
                                    mode: 'index',
                                },
                                plugins: {
                                    legend: {
                                        position: 'top',
                                        labels: {
                                            usePointStyle: true,
                                            boxWidth: 8
                                        }
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                let value = context.raw || 0;
                                                return context.dataset.label + ': Rp ' + value.toLocaleString(
                                                    'id-ID');
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        border: {
                                            display: false
                                        },
                                        grid: {
                                            color: '#f1f5f9'
                                        },
                                        ticks: {
                                            callback: function(value) {
                                                if (value >= 1000000) return 'Rp ' + (value / 1000000) + ' Jt';
                                                if (value >= 1000) return 'Rp ' + (value / 1000) + ' Rb';
                                                return 'Rp ' + value;
                                            }
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false
                                        }
                                    }
                                }
                            }
                        });

                        // 2. Data untuk Donut Chart
                        const expenseData = {!! json_encode($expenses->pluck('total')) !!};
                        const expenseLabels = {!! json_encode($expenses->pluck('category_name')) !!}; // FIXED: category_name

                        const incomeData = {!! json_encode($incomes->pluck('total')) !!};
                        const incomeLabels = {!! json_encode($incomes->pluck('category_name')) !!}; // FIXED: category_name

                        // 3. Render Donut Chart Pengeluaran
                        new Chart(document.getElementById('expenseChart'), {
                            type: 'doughnut', // FIXED: doughnut
                            data: {
                                labels: expenseLabels,
                                datasets: [{
                                    data: expenseData,
                                    backgroundColor: ['#ef4444', '#f97316', '#eab308', '#84cc16', '#06b6d4',
                                        '#d946ef', '#6366f1'
                                    ]
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '65%', // Menentukan ketebalan donat
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                let value = context.raw || 0;
                                                return context.label + ': Rp ' + value.toLocaleString('id-ID');
                                            }
                                        }
                                    }
                                }
                            }
                        });

                        // 4. Render Donut Chart Pemasukan
                        new Chart(document.getElementById('incomeChart'), {
                            type: 'doughnut', // FIXED: doughnut
                            data: {
                                labels: incomeLabels,
                                datasets: [{
                                    data: incomeData,
                                    backgroundColor: ['#22c55e', '#10b981', '#3b82f6', '#8b5cf6', '#a855f7',
                                        '#ec4899', '#14b8a6'
                                    ]
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                cutout: '65%', // Menentukan ketebalan donat
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                let value = context.raw || 0;
                                                return context.label + ': Rp ' + value.toLocaleString('id-ID');
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    });
                </script>
            @endif

        </div>
    </div>
</x-app-layout>
