<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            // Ubah harga_per_hari menjadi harga untuk durasi 1-7 hari
            $table->renameColumn('harga_per_hari', 'harga_1_7_hari');
            
            // Tambah kolom harga untuk durasi lainnya
            $table->decimal('harga_8_14_hari', 10, 2)->nullable()->after('harga_1_7_hari');
            $table->decimal('harga_15_plus_hari', 10, 2)->nullable()->after('harga_8_14_hari');
        });
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn('harga_8_14_hari');
            $table->dropColumn('harga_15_plus_hari');
            $table->renameColumn('harga_1_7_hari', 'harga_per_hari');
        });
    }
};
