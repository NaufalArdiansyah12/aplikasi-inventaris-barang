<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            // Hapus field pricing yang bertingkat
            $table->dropColumn('harga_1_7_hari');
            $table->dropColumn('harga_8_14_hari');
            $table->dropColumn('harga_15_plus_hari');
            
            // Tambah kolom denda_per_hari untuk denda keterlambatan pengembalian
            $table->decimal('denda_per_hari', 10, 2)->default(0)->comment('Denda per hari untuk keterlambatan pengembalian');
        });

        Schema::table('peminjaman', function (Blueprint $table) {
            // Hapus kolom harga_per_hari_applied
            $table->dropColumn('harga_per_hari_applied');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->decimal('harga_per_hari_applied', 10, 2)->nullable();
        });

        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn('denda_per_hari');
            
            $table->decimal('harga_1_7_hari', 10, 2)->default(0)->after('kondisi_barang');
            $table->decimal('harga_8_14_hari', 10, 2)->nullable()->after('harga_1_7_hari');
            $table->decimal('harga_15_plus_hari', 10, 2)->nullable()->after('harga_8_14_hari');
        });
    }
};
