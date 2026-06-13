<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin (Kepala Keluarga)
        User::create([
            'name' => 'Admin@rajinmenabung.com',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin'), // Password standarnya: password
            'role' => 'admin',
        ]);

        // 2. Buat Akun User (Anggota Keluarga)
        User::create([
            'name' => 'emylia nadira',
            'email' => 'emil@rajinmenabung.com',
            'password' => Hash::make('password'), // Password standarnya: password
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Rizki Yudhe Pratama',
            'email' => 'rizki@rajinmenabung.com',
            'password' => Hash::make('password'), // Password standarnya: password
            'role' => 'user',
        ]);

        // 3. Data Default Kategori Pemasukan
        $incomes = ['Gaji Bulanan', 'Bonus', 'Hasil Usaha', 'Pemberian'];
        foreach ($incomes as $income) {
            Category::create([
                'name' => $income,
                'type' => 'income',
            ]);
        }

        // 4. Data Default Kategori Pengeluaran
        $expenses = [
            'Makanan & Minuman',
            'Transportasi',
            'Tagihan Listrik & Air',
            'Pendidikan',
            'Kesehatan',
            'Belanja Dapur',
            'Hiburan'
        ];

        foreach ($expenses as $expense) {
            Category::create([
                'name' => $expense,
                'type' => 'expense',
            ]);
        }
    }
}
