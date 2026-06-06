<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->decimal('harga_per_hari_applied', 10, 2)->nullable()->after('lama_hari')->comment('Harga per hari yang diaplikasikan saat peminjaman');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn('harga_per_hari_applied');
        });
    }
};
