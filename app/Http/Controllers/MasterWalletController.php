<?php

namespace App\Http\Controllers;

use App\Models\MasterWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MasterWalletController extends Controller
{
    public function index()
    {
        // Catatan: Pengecekan isAdmin() dihapus dari sini karena 
        // sudah ditangani secara otomatis oleh route middleware ['admin']
        $masterWallets = MasterWallet::latest()->get();
        return view('admin.master_wallets.index', compact('masterWallets'));
    }

    public function create()
    {
        return view('admin.master_wallets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:master_wallets,name',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $data = $request->only('name');

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('master-wallets', 'public');
        }

        MasterWallet::create($data);

        // Setelah berhasil tambah, pindah ke halaman index
        return redirect()->route('master-wallets.index')->with('success', 'Master Wallet berhasil ditambahkan.');
    }

    public function edit(MasterWallet $masterWallet)
    {
        // Fungsi ini hanya bertugas menampilkan view form edit
        return view('admin.master_wallets.edit', compact('masterWallet'));
    }

    public function update(Request $request, MasterWallet $masterWallet)
    {
        // Pengecekan unique name dikecualikan untuk ID dompet ini sendiri
        $request->validate([
            'name' => 'required|string|max:255|unique:master_wallets,name,' . $masterWallet->id,
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $data = $request->only('name');

        if ($request->hasFile('logo')) {
            // Hapus gambar lama jika ada
            if ($masterWallet->logo) {
                Storage::disk('public')->delete($masterWallet->logo);
            }
            // Simpan gambar baru
            $data['logo'] = $request->file('logo')->store('master-wallets', 'public');
        }

        $masterWallet->update($data);

        // Setelah berhasil edit, pindah ke halaman index
        return redirect()->route('master-wallets.index')->with('success', 'Master Wallet berhasil diperbarui.');
    }

    public function destroy(MasterWallet $masterWallet)
    {
        if ($masterWallet->logo) {
            Storage::disk('public')->delete($masterWallet->logo);
        }

        $masterWallet->delete();

        // Tetap gunakan back() untuk hapus agar user tidak terlempar ke mana-mana
        return back()->with('success', 'Master Wallet berhasil dihapus.');
    }
}
