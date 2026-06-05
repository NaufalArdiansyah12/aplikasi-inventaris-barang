<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['username' => 'admin'],
            ['nama' => 'Administrator', 'password' => 'admin123', 'role' => 'admin']
        );

        User::query()->updateOrCreate(
            ['username' => 'user'],
            ['nama' => 'Peminjam', 'password' => 'user123', 'role' => 'user']
        );

        Barang::query()->updateOrCreate(
            ['nama_barang' => 'Laptop Lab'],
            ['jumlah' => 10, 'kondisi_barang' => 'baik']
        );

        Barang::query()->updateOrCreate(
            ['nama_barang' => 'Proyektor'],
            ['jumlah' => 4, 'kondisi_barang' => 'baik']
        );

        Barang::query()->updateOrCreate(
            ['nama_barang' => 'Kabel HDMI'],
            ['jumlah' => 15, 'kondisi_barang' => 'baik']
        );
    }
}
