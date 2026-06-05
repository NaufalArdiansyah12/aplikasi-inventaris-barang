<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table): void {
            $table->decimal('denda', 10, 2)->nullable()->after('status_pinjam');
            $table->string('kondisi_pengembalian')->nullable()->after('denda');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table): void {
            $table->dropColumn(['denda', 'kondisi_pengembalian']);
        });
    }
};
