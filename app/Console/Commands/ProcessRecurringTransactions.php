<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\RecurringTransaction;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ProcessRecurringTransactions extends Command
{
    // Nama perintah yang akan dipanggil
    protected $signature = 'transactions:process-recurring';

    // Deskripsi perintah
    protected $description = 'Memproses transaksi berulang dan mencatatnya ke tabel transaksi';

    public function handle()
    {
        // Cari semua transaksi berulang yang aktif dan tanggal eksekusinya hari ini atau kelewat
        $recurrings = RecurringTransaction::where('is_active', true)
            ->where('next_date', '<=', Carbon::today())
            ->get();

        $count = 0;

        foreach ($recurrings as $recurring) {
            DB::transaction(function () use ($recurring, &$count) {
                // 1. Masukkan ke tabel transaksi utama
                Transaction::create([
                    'user_id' => $recurring->user_id,
                    'wallet_id' => $recurring->wallet_id,
                    'category_id' => $recurring->category_id,
                    'type' => $recurring->type,
                    'amount' => $recurring->amount,
                    'description' => $recurring->description . ' (Otomatis)',
                    'date' => Carbon::today(),
                ]);

                // 2. Update saldo dompet (Wallet)
                $wallet = $recurring->wallet;
                if ($recurring->type == 'income') {
                    $wallet->balance += $recurring->amount;
                } else {
                    $wallet->balance -= $recurring->amount;
                }
                $wallet->save();

                // 3. Hitung tanggal eksekusi berikutnya (next_date)
                $nextDate = Carbon::parse($recurring->next_date);
                if ($recurring->frequency == 'daily') {
                    $nextDate->addDay();
                } elseif ($recurring->frequency == 'weekly') {
                    $nextDate->addWeek();
                } elseif ($recurring->frequency == 'monthly') {
                    $nextDate->addMonth();
                } elseif ($recurring->frequency == 'yearly') {
                    $nextDate->addYear();
                }

                // 4. Simpan tanggal berikutnya ke database
                $recurring->update([
                    'next_date' => $nextDate
                ]);

                $count++;
            });
        }

        $this->info("Berhasil memproses {$count} transaksi berulang.");
    }
}
