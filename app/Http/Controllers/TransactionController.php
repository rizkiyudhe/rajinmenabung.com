<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        // Gunakan paginate() agar performa cepat
        $transactions = auth()->user()->transactions()
            ->with(['wallet', 'category'])
            ->orderBy('date', 'desc')
            ->latest()
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $wallets = auth()->user()->wallets;

        // Cegah masuk jika belum punya dompet
        if ($wallets->isEmpty()) {
            return redirect()->route('wallets.index')->with('success', 'Silakan buat dompet terlebih dahulu sebelum mencatat transaksi.');
        }

        $categories = Category::orderBy('name')->get();

        return view('transactions.create', compact('wallets', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'category_id' => 'required|exists:categories,id',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        $wallet = Wallet::where('id', $request->wallet_id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Proses simpan & update saldo sekaligus
        DB::transaction(function () use ($request, $wallet) {
            auth()->user()->transactions()->create($request->all());

            if ($request->type === 'income') {
                $wallet->increment('balance', $request->amount);
            } else {
                $wallet->decrement('balance', $request->amount);
            }
        });

        return redirect()->route('transactions.index')->with('success', 'Transaksi dicatat & saldo diperbarui!');
    }

    public function destroy(Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) abort(403);

        DB::transaction(function () use ($transaction) {
            $wallet = $transaction->wallet;

            // Kembalikan saldo sebelum transaksi dihapus
            if ($transaction->type === 'income') {
                $wallet->decrement('balance', $transaction->amount);
            } else {
                $wallet->increment('balance', $transaction->amount);
            }

            $transaction->delete();
        });

        return redirect()->route('transactions.index')->with('success', 'Transaksi dihapus & saldo dikembalikan.');
    }
}
