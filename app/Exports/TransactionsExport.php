<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TransactionsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $filters;

    public function __withFilters($filters)
    {
        $this->filters = $filters;
        return $this;
    }

    public function query()
    {
        $query = Transaction::where('user_id', auth()->id())->with(['wallet', 'category'])->latest();

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('date', '>=', $this->filters['start_date']);
        }
        if (!empty($this->filters['end_date'])) {
            $query->whereDate('date', '<=', $this->filters['end_date']);
        }
        if (!empty($this->filters['wallet_id'])) {
            $query->where('wallet_id', $this->filters['wallet_id']);
        }
        if (!empty($this->filters['category_id'])) {
            $query->where('category_id', $this->filters['category_id']);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Deskripsi',
            'Kategori',
            'Dompet',
            'Tipe',
            'Nominal (Rp)',
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->date->format('d/m/Y'),
            $transaction->description,
            $transaction->category->name,
            $transaction->wallet->name,
            $transaction->type == 'income' ? 'Pemasukan' : 'Pengeluaran',
            $transaction->amount,
        ];
    }
}
