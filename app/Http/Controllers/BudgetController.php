<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter bulan/tahun dari URL, jika kosong gunakan bulan saat ini
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $budgets = auth()->user()->budgets()
            ->with('category')
            ->where('month', $month)
            ->where('year', $year)
            ->get();

        // Hitung pengeluaran real-time untuk setiap anggaran
        foreach ($budgets as $budget) {
            $spent = auth()->user()->transactions()
                ->where('category_id', $budget->category_id)
                ->where('type', 'expense')
                ->whereMonth('date', $month)
                ->whereYear('date', $year)
                ->sum('amount');

            $budget->spent = $spent;
            $percentage = $budget->amount > 0 ? ($spent / $budget->amount) * 100 : 0;
            $budget->percentage = min(100, $percentage); // Maksimal bar 100%
            $budget->is_over_budget = $spent > $budget->amount;
        }

        return view('budgets.index', compact('budgets', 'month', 'year'));
    }

    public function create()
    {
        // Hanya tampilkan kategori yang tipe-nya "Pengeluaran (expense)"
        $categories = Category::where('type', 'expense')->orderBy('name')->get();
        return view('budgets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:1',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2000|max:2100',
        ]);

        // Gunakan updateOrCreate agar tidak ada anggaran ganda per kategori di bulan yang sama
        auth()->user()->budgets()->updateOrCreate(
            [
                'category_id' => $request->category_id,
                'month' => $request->month,
                'year' => $request->year,
            ],
            [
                'amount' => $request->amount
            ]
        );

        return redirect()->route('budgets.index', ['month' => $request->month, 'year' => $request->year])
            ->with('success', 'Anggaran berhasil ditetapkan!');
    }

    public function destroy(Budget $budget)
    {
        if ($budget->user_id !== auth()->id()) abort(403);
        $month = $budget->month;
        $year = $budget->year;

        $budget->delete();

        return redirect()->route('budgets.index', ['month' => $month, 'year' => $year])
            ->with('success', 'Anggaran dihapus.');
    }
}
