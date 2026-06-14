<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\MasterWallet; // Tambahkan import ini
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        // Ambil semua dompet khusus untuk user yang sedang login
        $wallets = auth()->user()->wallets()->latest()->get();
        return view('wallets.index', compact('wallets'));
    }

    public function create()
    {
        // Ambil data master wallet untuk ditampilkan di dropdown
        $masterWallets = MasterWallet::orderBy('name', 'asc')->get();
        return view('wallets.create', compact('masterWallets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'required|numeric|min:0',
        ]);

        // Simpan dompet dan otomatis kaitkan dengan user_id yang sedang login
        auth()->user()->wallets()->create($request->only('name', 'balance'));

        return redirect()->route('wallets.index')->with('success', 'Dompet berhasil ditambahkan!');
    }

    public function edit(Wallet $wallet)
    {
        // Pastikan user tidak mengedit dompet orang lain
        if ($wallet->user_id !== auth()->id()) abort(403);

        // Ambil data master wallet untuk ditampilkan di dropdown
        $masterWallets = MasterWallet::orderBy('name', 'asc')->get();

        return view('wallets.edit', compact('wallet', 'masterWallets'));
    }

    public function update(Request $request, Wallet $wallet)
    {
        if ($wallet->user_id !== auth()->id()) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'balance' => 'required|numeric|min:0',
        ]);

        $wallet->update($request->only('name', 'balance'));

        return redirect()->route('wallets.index')->with('success', 'Dompet berhasil diperbarui!');
    }

    public function destroy(Wallet $wallet)
    {
        if ($wallet->user_id !== auth()->id()) abort(403);

        $wallet->delete();

        return redirect()->route('wallets.index')->with('success', 'Dompet berhasil dihapus!');
    }
}
