<?php

namespace App\Http\Controllers;

use App\Exports\TransactionsExport;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $wallets = Wallet::where('user_id', auth()->id())->get();
        $categories = Category::all();

        // Ambil data filter (default: bulan ini)
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());
        $walletId = $request->get('wallet_id');
        $categoryId = $request->get('category_id');

        // Query transaksi dasar
        $query = Transaction::where('user_id', auth()->id())
            ->whereBetween('date', [$startDate, $endDate]);

        if ($walletId) {
            $query->where('wallet_id', $walletId);
        }
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $transactions = $query->with(['wallet', 'category'])->latest()->get();

        // Hitung total ringkasan keuangan
        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        return view('reports.index', compact(
            'transactions',
            'wallets',
            'categories',
            'startDate',
            'endDate',
            'walletId',
            'categoryId',
            'totalIncome',
            'totalExpense',
            'netBalance'
        ));
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->only(['start_date', 'end_date', 'wallet_id', 'category_id']);
        $export = (new TransactionsExport)->__withFilters($filters);

        return Excel::download($export, 'laporan_keuangan_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', now()->endOfMonth()->toDateString());

        $query = Transaction::where('user_id', auth()->id())->whereBetween('date', [$startDate, $endDate]);

        if ($request->filled('wallet_id')) $query->where('wallet_id', $request->wallet_id);
        if ($request->filled('category_id')) $query->where('category_id', $request->category_id);

        $transactions = $query->with(['wallet', 'category'])->latest()->get();

        $totalIncome = $transactions->where('type', 'income')->sum('amount');
        $totalExpense = $transactions->where('type', 'expense')->sum('amount');

        // Render view khusus PDF
        $pdf = Pdf::loadView('reports.pdf', compact('transactions', 'startDate', 'endDate', 'totalIncome', 'totalExpense'));

        return $pdf->download('laporan_keuangan_' . now()->format('Ymd_His') . '.pdf');
    }
}
