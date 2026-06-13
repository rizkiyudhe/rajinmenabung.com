<?php

namespace App\Http\Controllers;

use App\Models\Transfer;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    public function index()
    {
        $transfers = auth()->user()->transfers()
            ->with(['fromWallet', 'toWallet'])
            ->orderBy('date', 'desc')
            ->latest()
            ->paginate(10);

        return view('transfers.index', compact('transfers'));
    }

    public function create()
    {
        $wallets = auth()->user()->wallets;

        // Butuh minimal 2 dompet untuk melakukan transfer
        if ($wallets->count() < 2) {
            return redirect()->route('wallets.index')->with('success', 'Anda memerlukan minimal 2 dompet untuk melakukan fitur mutasi saldo.');
        }

        return view('transfers.create', compact('wallets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_wallet_id' => 'required|exists:wallets,id',
            'to_wallet_id' => 'required|exists:wallets,id|different:from_wallet_id',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        // Ambil data kedua dompet dan pastikan milik user yang login
        $fromWallet = Wallet::where('id', $request->from_wallet_id)->where('user_id', auth()->id())->firstOrFail();
        $toWallet = Wallet::where('id', $request->to_wallet_id)->where('user_id', auth()->id())->firstOrFail();

        // Validasi apakah saldo dompet asal mencukupi
        if ($fromWallet->balance < $request->amount) {
            return back()->withErrors(['amount' => 'Saldo di ' . $fromWallet->name . ' tidak mencukupi untuk melakukan mutasi ini.'])->withInput();
        }

        // Eksekusi mutasi saldo secara aman
        DB::transaction(function () use ($request, $fromWallet, $toWallet) {
            // 1. Catat histori transfer
            auth()->user()->transfers()->create($request->all());

            // 2. Potong saldo pengirim, tambah saldo penerima
            $fromWallet->decrement('balance', $request->amount);
            $toWallet->increment('balance', $request->amount);
        });

        return redirect()->route('transfers.index')->with('success', 'Mutasi saldo berhasil dilakukan!');
    }

    public function destroy(Transfer $transfer)
    {
        if ($transfer->user_id !== auth()->id()) abort(403);

        DB::transaction(function () use ($transfer) {
            // Kembalikan saldo sebelum histori dihapus
            $transfer->fromWallet()->increment('balance', $transfer->amount);
            $transfer->toWallet()->decrement('balance', $transfer->amount);

            $transfer->delete();
        });

        return redirect()->route('transfers.index')->with('success', 'Riwayat mutasi dihapus & saldo dikembalikan ke dompet asal.');
    }

    public function edit(Transfer $transfer)
    {
        // Pastikan hanya pemilik yang bisa mengedit
        if ($transfer->user_id !== auth()->id()) abort(403);

        $wallets = auth()->user()->wallets;
        return view('transfers.edit', compact('transfer', 'wallets'));
    }

    public function update(Request $request, Transfer $transfer)
    {
        if ($transfer->user_id !== auth()->id()) abort(403);

        $request->validate([
            'from_wallet_id' => 'required|exists:wallets,id',
            'to_wallet_id' => 'required|exists:wallets,id|different:from_wallet_id',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $transfer) {
            // 1. REVERSE (KEMBALIKAN) SALDO LAMA DULU
            $oldFromWallet = Wallet::find($transfer->from_wallet_id);
            $oldToWallet = Wallet::find($transfer->to_wallet_id);
            $oldFromWallet->increment('balance', $transfer->amount);
            $oldToWallet->decrement('balance', $transfer->amount);

            // 2. AMBIL DATA DOMPET BARU & REFRESH SALDO TERKINI
            $newFromWallet = Wallet::find($request->from_wallet_id);
            $newToWallet = Wallet::find($request->to_wallet_id);

            // Cek apakah saldo pengirim cukup untuk nominal yang baru diupdate
            if ($newFromWallet->balance < $request->amount) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'amount' => 'Saldo dompet sumber tidak mencukupi untuk update mutasi ini.'
                ]);
            }

            // 3. TERAPKAN POTONGAN SALDO BARU
            $newFromWallet->decrement('balance', $request->amount);
            $newToWallet->increment('balance', $request->amount);

            // 4. UPDATE DATA HISTORI MUTASI
            $transfer->update($request->all());
        });

        return redirect()->route('transfers.index')->with('success', 'Mutasi saldo berhasil diperbarui!');
    }
}
