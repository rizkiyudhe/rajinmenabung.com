<?php

namespace App\Http\Controllers;

use App\Models\Debt;
use Illuminate\Http\Request;

class DebtController extends Controller
{
    public function index()
    {
        $debts = auth()->user()->debts()
            ->orderBy('status', 'asc')
            ->orderBy('due_date', 'asc')
            ->paginate(10);

        return view('debts.index', compact('debts'));
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

    public function settle(Debt $debt)
    {
        if ($debt->user_id !== auth()->id()) abort(403);

        $debt->update(['status' => 'paid']);

        return redirect()->route('debts.index')->with('success', 'Status telah diperbarui menjadi Lunas!');
    }

    public function destroy(Debt $debt)
    {
        if ($debt->user_id !== auth()->id()) abort(403);

        $debt->delete();

        return redirect()->route('debts.index')->with('success', 'Catatan berhasil dihapus.');
    }
}
