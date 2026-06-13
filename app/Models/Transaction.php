<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'wallet_id',
        'category_id',
        'type',
        'amount',
        'description',
        'date'
    ];

    // Transaksi ini dilakukan oleh satu User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Transaksi ini memotong/menambah dari satu Dompet
    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    // Transaksi ini masuk ke dalam satu Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
