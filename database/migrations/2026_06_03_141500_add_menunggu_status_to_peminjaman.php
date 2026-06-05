<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah opsi enum 'menunggu_konfirmasi' ke kolom status_pinjam
        // Gunakan raw SQL karena Blueprint tidak punya helper untuk menambah nilai enum secara langsung
        DB::statement("ALTER TABLE `peminjaman` MODIFY `status_pinjam` ENUM('dipinjam','menunggu_konfirmasi','dikembalikan') NOT NULL DEFAULT 'dipinjam'");
    }

    public function down(): void
    {
        // Sebelum mengembalikan enum ke keadaan semula, pastikan tidak ada nilai 'menunggu_konfirmasi'
        DB::table('peminjaman')->where('status_pinjam', 'menunggu_konfirmasi')->update(['status_pinjam' => 'dipinjam']);
        DB::statement("ALTER TABLE `peminjaman` MODIFY `status_pinjam` ENUM('dipinjam','dikembalikan') NOT NULL DEFAULT 'dipinjam'");
    }
};
