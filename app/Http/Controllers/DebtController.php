<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Debt;
use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DebtController extends Controller
{
    public function index()
    {
        $wallets = Wallet::where('user_id', auth()->id())->get();
        $categories = Category::all();
        $debts = auth()->user()->debts()
            ->orderBy('status', 'asc')
            ->orderBy('due_date', 'asc')
            ->paginate(10);

        // FIX: Tambahkan 'wallets' ke dalam compact agar bisa dibaca oleh file Blade
        return view('debts.index', compact('debts', 'wallets', 'categories'));
    }

    public function create()
    {
        return view('debts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:debt,receivable',
            'person_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'due_date' => 'nullable|date',
            'description' => 'nullable|string|max:255',
        ]);

        auth()->user()->debts()->create($request->all());

        return redirect()->route('debts.index')->with('success', 'Catatan utang/piutang berhasil disimpan!');
    }

    // FIX: Tambahkan Request $request untuk menangkap pilihan dompet dari form
    public function settle(Request $request, Debt $debt)
    {
        // Pastikan keamanan: hanya pemilik catatan yang bisa mengubah
        if ($debt->user_id !== auth()->id()) abort(403);

        // Validasi wajib pilih dompet
        $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Gunakan DB Transaction agar proses potong saldo dan ubah status berjalan serentak
        DB::transaction(function () use ($debt, $request) {

            // 1. Ubah status menjadi Lunas
            $debt->update(['status' => 'paid']);

            // 2. Tentukan Jenis Transaksi
            // Jika 'debt' (Kita punya utang ke orang) -> Saat dilunasi, uang kita keluar (expense)
            // Jika 'receivable' (Orang punya utang ke kita) -> Saat dilunasi, uang kita masuk (income)
            $transactionType = $debt->type === 'receivable' ? 'income' : 'expense';
            $transactionText = $debt->type === 'receivable' ? 'Terima pelunasan piutang dari ' : 'Bayar utang ke ';

            // 3. Buat Catatan Transaksi Riil
            Transaction::create([
                'user_id'     => auth()->id(),
                'wallet_id'   => $request->wallet_id,
                'category_id' => $request->category_id, // Biarkan null, atau isi dengan ID kategori khusus jika ada
                'type'        => $transactionType,
                'amount'      => $debt->amount,
                'description' => $transactionText . $debt->person_name,
                'date'        => Carbon::today(),
            ]);

            // 4. Perbarui Saldo Dompet
            $wallet = Wallet::find($request->wallet_id);

            if ($transactionType === 'income') {
                $wallet->increment('balance', $debt->amount); // Tambah saldo
            } else {
                $wallet->decrement('balance', $debt->amount); // Potong saldo
            }
        });

        return redirect()->route('debts.index')->with('success', 'Catatan telah dilunasi dan saldo dompet berhasil disesuaikan!');
    }

    public function destroy(Debt $debt)
    {
        if ($debt->user_id !== auth()->id()) abort(403);

        $debt->delete();

        return redirect()->route('debts.index')->with('success', 'Catatan berhasil dihapus.');
    }
}
