<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Wallet; // Ditambahkan untuk proses potong saldo
use App\Models\RecurringTransaction; // Ditambahkan untuk proses transaksi berulang
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
        // 2. JALANKAN TRIGGER TRANSAKSI BERULANG
        // ==========================================
        // Mengecek dan memotong saldo otomatis sebelum statistik dashboard dihitung
        $this->processRecurringTransactions($user);


        // ==========================================
        // 3. LOGIKA UNTUK DASHBOARD USER
        // ==========================================

        $reminderDebts = $user->debts()
            ->where('status', 'pending')
            ->orderBy('due_date', 'asc')
            ->get();

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

        // Mengambil data pengeluaran per kategori
        $expenses = Transaction::join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', auth()->id())
            ->where('categories.type', 'expense') // Filter berdasarkan kolom type di tabel categories
            ->select('categories.name as category_name', DB::raw('sum(transactions.amount) as total'))
            ->groupBy('categories.name')
            ->get();

        // Mengambil data pemasukan per kategori
        $incomes = Transaction::join('categories', 'transactions.category_id', '=', 'categories.id')
            ->where('transactions.user_id', auth()->id())
            ->where('categories.type', 'income') // Filter berdasarkan kolom type di tabel categories
            ->select('categories.name as category_name', DB::raw('sum(transactions.amount) as total'))
            ->groupBy('categories.name')
            ->get();

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
            'recentTransactions',
            'reminderDebts',
            'expenses',
            'incomes',
        ));
    }

    /**
     * Fungsi private untuk memproses transaksi berulang yang jatuh tempo
     */
    private function processRecurringTransactions($user)
    {
        // Ambil semua transaksi berulang milik user yang tanggalnya hari ini atau sudah terlewat
        $dueRecurringTransactions = RecurringTransaction::where('user_id', $user->id)
            ->whereDate('next_date', '<=', Carbon::today())
            ->get();

        foreach ($dueRecurringTransactions as $rt) {

            // Loop WHILE memastikan jika user tidak login berbulan-bulan, 
            // tagihannya akan terpotong sesuai jumlah bulan yang terlewat.
            while (Carbon::parse($rt->next_date)->startOfDay()->lte(Carbon::today())) {

                DB::transaction(function () use ($rt) {
                    // 1. Buat catatan riwayat transaksi riil
                    Transaction::create([
                        'user_id'     => $rt->user_id,
                        'wallet_id'   => $rt->wallet_id,
                        'category_id' => $rt->category_id,
                        'type'        => $rt->type,
                        'amount'      => $rt->amount,
                        'description' => '(Otomatis) ' . $rt->description,
                        'date'        => Carbon::parse($rt->next_date),
                    ]);

                    // 2. Sesuaikan saldo dompet
                    $wallet = Wallet::find($rt->wallet_id);
                    if ($wallet) {
                        if ($rt->type === 'income') {
                            $wallet->increment('balance', $rt->amount);
                        } else {
                            $wallet->decrement('balance', $rt->amount);
                        }
                    }

                    // 3. Kalkulasi tanggal jatuh tempo berikutnya
                    $nextDate = Carbon::parse($rt->next_date);

                    switch ($rt->frequency) {
                        case 'daily':
                            $nextDate->addDay();
                            break;
                        case 'weekly':
                            $nextDate->addWeek();
                            break;
                        case 'monthly':
                            $nextDate->addMonth();
                            break;
                        case 'yearly':
                            $nextDate->addYear();
                            break;
                        default:
                            $nextDate->addMonth(); // Fallback jika format tidak dikenali
                            break;
                    }

                    // 4. Update tanggal di tabel transaksi berulang
                    $rt->update(['next_date' => $nextDate]);
                });
            }
        }
    }
}
