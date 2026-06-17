<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\RecurringTransaction;
use App\Models\Wallet;
use Illuminate\Http\Request;

class RecurringTransactionController extends Controller
{
    public function index()
    {
        // Ambil data transaksi berulang, kategori, dan dompet milik user yang login
        $recurrings = RecurringTransaction::where('user_id', auth()->id())->latest()->get();

        // Sesuaikan query kategori dengan struktur Anda (misal jika ada filter jenis kategori)
        $categories = Category::all(); // Atau Category::where('user_id', auth()->id())->get();

        $wallets = Wallet::where('user_id', auth()->id())->get();

        return view('recurring_transactions.index', compact('recurrings', 'categories', 'wallets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric|min:1',
            'frequency' => 'required|in:daily,weekly,monthly,yearly',
            'start_date' => 'required|date',
        ]);

        RecurringTransaction::create([
            'user_id' => auth()->id(),
            'wallet_id' => $request->wallet_id,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'type' => $request->type,
            'amount' => $request->amount,
            'frequency' => $request->frequency,
            'start_date' => $request->start_date,
            'next_date' => $request->start_date, // Pertama kali dieksekusi sesuai tanggal mulai
            'is_active' => true,
        ]);

        return redirect()->route('recurring-transactions.index')
            ->with('success', 'Otomatisasi transaksi berhasil ditambahkan.');
    }

    public function toggle(RecurringTransaction $recurringTransaction)
    {
        // Pastikan keamanan: hanya pemilik yang bisa mengubah
        if ($recurringTransaction->user_id !== auth()->id()) abort(403);

        $recurringTransaction->update([
            'is_active' => !$recurringTransaction->is_active
        ]);

        return back()->with('success', 'Status transaksi berulang berhasil diubah.');
    }

    public function destroy(RecurringTransaction $recurringTransaction)
    {
        // Pastikan keamanan
        if ($recurringTransaction->user_id !== auth()->id()) abort(403);

        $recurringTransaction->delete();

        return back()->with('success', 'Otomatisasi transaksi berhasil dihapus.');
    }

    public function edit(RecurringTransaction $recurringTransaction)
    {
        if ($recurringTransaction->user_id !== auth()->id()) abort(403);

        $categories = Category::all();
        $wallets = Wallet::where('user_id', auth()->id())->get();

        return view('recurring_transactions.edit', compact('recurringTransaction', 'categories', 'wallets'));
    }

    public function update(Request $request, RecurringTransaction $recurringTransaction)
    {
        if ($recurringTransaction->user_id !== auth()->id()) abort(403);

        $request->validate([
            'description' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:categories,id',
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric|min:1',
            'frequency' => 'required|in:daily,weekly,monthly,yearly',
            'start_date' => 'required|date',
        ]);

        // 1. Ambil data spesifik saja (Lebih aman dari $request->all())
        $data = $request->only([
            'description',
            'type',
            'category_id',
            'wallet_id',
            'amount',
            'frequency',
            'start_date'
        ]);


        if ($request->start_date !== $recurringTransaction->start_date || $request->frequency !== $recurringTransaction->frequency) {
            $data['next_date'] = $request->start_date;
        }

        $recurringTransaction->update($data);

        return redirect()->route('recurring-transactions.index')
            ->with('success', 'Jadwal transaksi berhasil diperbarui.');
    }
}
