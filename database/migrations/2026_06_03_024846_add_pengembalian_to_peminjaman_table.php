<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->date('tanggal_kembali')->nullable()->after('tanggal_pinjam');
            $table->enum('status_pinjam', ['dipinjam', 'dikembalikan'])->default('dipinjam')->after('tanggal_kembali');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['tanggal_kembali', 'status_pinjam']);
        });
    }
};
