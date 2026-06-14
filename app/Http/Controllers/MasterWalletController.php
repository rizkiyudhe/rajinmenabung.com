<?php

namespace App\Http\Controllers;

use App\Models\MasterWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MasterWalletController extends Controller
{
    public function index()
    {
        // Pastikan hanya admin yang bisa akses (opsional, ganti sesuai logika admin Anda)
        if (!auth()->user()->isAdmin()) abort(403);

        $masterWallets = MasterWallet::latest()->get();
        return view('admin.master_wallets.index', compact('masterWallets'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAdmin()) abort(403);

        $request->validate([
            'name' => 'required|string|max:255|unique:master_wallets,name',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $data = $request->only('name');

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('master-wallets', 'public');
        }

        MasterWallet::create($data);

        return back()->with('success', 'Master Wallet berhasil ditambahkan.');
    }

    public function destroy(MasterWallet $masterWallet)
    {
        if (!auth()->user()->isAdmin()) abort(403);

        if ($masterWallet->logo) {
            Storage::disk('public')->delete($masterWallet->logo);
        }

        $masterWallet->delete();
        return back()->with('success', 'Master Wallet berhasil dihapus.');
    }

    public function update(Request $request, MasterWallet $masterWallet)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $data = $request->only('name');
        if ($request->hasFile('logo')) {
            if ($masterWallet->logo) Storage::disk('public')->delete($masterWallet->logo);
            $data['logo'] = $request->file('logo')->store('master-wallets', 'public');
        }

        $masterWallet->update($data);
        return back()->with('success', 'Dompet berhasil diupdate.');
    }
}
