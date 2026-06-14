<div x-data="{ sidebarOpen: false }">
    <div
        class="md:hidden flex items-center justify-between bg-white border-b border-gray-200 px-4 py-3 fixed w-full top-0 z-30 shadow-sm">
        <a href="{{ route('dashboard') }}"
            class="text-xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 tracking-wider">
            Rajin Menabung
        </a>
        <button @click="sidebarOpen = true"
            class="text-gray-500 hover:text-gray-700 focus:outline-none p-2 rounded-md hover:bg-gray-100 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16">
                </path>
            </svg>
        </button>
    </div>

    <div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 md:hidden"
        @click="sidebarOpen = false" style="display: none;"></div>

    <nav :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out md:translate-x-0 h-screen flex flex-col shadow-2xl md:shadow-none">

        <div class="flex items-center justify-center h-16 border-b border-gray-100 px-4 shrink-0 bg-white">
            <a href="{{ route('dashboard') }}"
                class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600 tracking-wider">
                Rajin Menabung
            </a>
        </div>

        <div class="flex-1 overflow-y-auto py-6 px-4 space-y-2">

            <a href="{{ route('dashboard') }}"
                class="{{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-colors text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                    </path>
                </svg>
                Dashboard
            </a>

            @if (auth()->user()->isAdmin())
                <div class="pt-4 pb-2 text-xs font-bold text-gray-400 uppercase tracking-wider pl-4">Menu Admin</div>
                <a href="{{ route('users.index') }}"
                    class="{{ request()->routeIs('users.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-colors text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    Master User
                </a>
                <a href="{{ route('categories.index') }}"
                    class="{{ request()->routeIs('categories.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-colors text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                    Master Kategori
                </a>
                <a href="{{ route('master-wallets.index') }}"
                    class="{{ request()->routeIs('master-wallets.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-colors text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                        </path>
                    </svg>
                    Master Dompet
                </a>
            @else
                <div class="pt-4 pb-2 text-xs font-bold text-gray-400 uppercase tracking-wider pl-4">Keuangan Pribadi
                </div>
                <a href="{{ route('wallets.index') }}"
                    class="{{ request()->routeIs('wallets.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-colors text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                        </path>
                    </svg>
                    Dompet
                </a>
                <a href="{{ route('transactions.index') }}"
                    class="{{ request()->routeIs('transactions.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-colors text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                        </path>
                    </svg>
                    Transaksi
                </a>
                <a href="{{ route('budgets.index') }}"
                    class="{{ request()->routeIs('budgets.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-colors text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                    </svg>
                    Anggaran
                </a>
                <a href="{{ route('transfers.index') }}"
                    class="{{ request()->routeIs('transfers.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-colors text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                    Mutasi Saldo
                </a>
                <a href="{{ route('debts.index') }}"
                    class="{{ request()->routeIs('debts.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-colors text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Utang Piutang
                </a>
                <a href="{{ route('recurring-transactions.index') }}"
                    class="{{ request()->routeIs('recurring-transactions.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-colors text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                        </path>
                    </svg>
                    Transaksi Berulang
                </a>

                {{-- MENU BARU: LAPORAN KEUANGAN --}}
                <a href="{{ route('reports.index') }}"
                    class="{{ request()->routeIs('reports.*') ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 font-medium' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-colors text-sm mt-2 border-t border-dashed border-gray-200 pt-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                    Laporan Keuangan
                </a>
            @endif
        </div>

        <div class="border-t border-gray-100 p-4 shrink-0 bg-gray-50">
            <div class="mb-3 px-3">
                <p class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
            </div>
            <div class="space-y-1">
                <a href="{{ route('profile.edit') }}"
                    class="flex items-center gap-2 px-3 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-200 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profil Saya
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2 px-3 py-2.5 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </nav>
</div>
