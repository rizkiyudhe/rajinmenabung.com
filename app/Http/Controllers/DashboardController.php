<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ==========================================
        // 1. LOGIKA UNTUK DASHBOARD ADMIN
        // ==========================================
        if ($user->isAdmin()) {
            $totalUsers = User::where('role', 'user')->count();
            $totalCategories = Category::count();

            return view('dashboard', compact('totalUsers', 'totalCategories'));
        }

        // ==========================================
        // 2. LOGIKA UNTUK DASHBOARD USER
        // ==========================================

        // A. Kartu Statistik (Bulan Ini)
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $totalBalance = $user->wallets()->sum('balance');

        $incomeThisMonth = $user->transactions()
            ->where('type', 'income')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        $expenseThisMonth = $user->transactions()
            ->where('type', 'expense')
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->sum('amount');

        // B. Data Grafik (Pemasukan vs Pengeluaran Harian Bulan Ini)
        $daysInMonth = Carbon::now()->daysInMonth;
        $labels = [];
        $incomeData = [];
        $expenseData = [];

        // Siapkan array kosong untuk setiap hari dalam bulan ini
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $labels[] = $i;
            $incomeData[$i] = 0;
            $expenseData[$i] = 0;
        }

        // Ambil rekap transaksi per hari langsung dari database
        $monthlyTransactions = $user->transactions()
            ->select(DB::raw('DAY(date) as day'), 'type', DB::raw('SUM(amount) as total'))
            ->whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->groupBy('day', 'type')
            ->get();

        // Masukkan total ke array sesuai tanggal
        foreach ($monthlyTransactions as $trx) {
            if ($trx->type == 'income') {
                $incomeData[$trx->day] = (float) $trx->total;
            } else {
                $expenseData[$trx->day] = (float) $trx->total;
            }
        }

        $chartData = [
            'labels' => $labels,
            'income' => array_values($incomeData),
            'expense' => array_values($expenseData),
        ];

        // C. 5 Transaksi Terakhir
        $recentTransactions = $user->transactions()
            ->with(['category', 'wallet'])
            ->orderBy('date', 'desc')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalBalance',
            'incomeThisMonth',
            'expenseThisMonth',
            'chartData',
            'recentTransactions'
        ));
    }
}
