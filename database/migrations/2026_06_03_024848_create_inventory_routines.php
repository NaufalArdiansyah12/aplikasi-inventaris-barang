<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        try {
            DB::unprepared('DROP PROCEDURE IF EXISTS pinjam_barang');
            DB::unprepared(<<<'SQL'
CREATE PROCEDURE pinjam_barang(
   IN p_id_user INT,
   IN p_id_barang INT,
   IN p_jumlah INT
)
BEGIN
   INSERT INTO peminjaman(id_user, id_barang, jumlah_pinjam, tanggal_pinjam)
   VALUES(p_id_user, p_id_barang, p_jumlah, CURDATE());

   UPDATE barang
   SET jumlah = jumlah - p_jumlah
   WHERE id_barang = p_id_barang;
END
SQL);
        } catch (Throwable $exception) {
            Log::warning('Procedure pinjam_barang tidak bisa dibuat.', ['message' => $exception->getMessage()]);
        }

        try {
            DB::unprepared('DROP FUNCTION IF EXISTS status_barang');
            DB::unprepared(<<<'SQL'
CREATE FUNCTION status_barang(jumlah INT)
RETURNS VARCHAR(20)
DETERMINISTIC
BEGIN
   DECLARE hasil VARCHAR(20);

   IF jumlah <= 0 THEN
       SET hasil = 'Habis';
   ELSE
       SET hasil = 'Tersedia';
   END IF;

   RETURN hasil;
END
SQL);
        } catch (Throwable $exception) {
            Log::warning('Function status_barang tidak bisa dibuat.', ['message' => $exception->getMessage()]);
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        try {
            DB::unprepared('DROP PROCEDURE IF EXISTS pinjam_barang');
            DB::unprepared('DROP FUNCTION IF EXISTS status_barang');
        } catch (Throwable $exception) {
            Log::warning('Routine inventaris tidak bisa dihapus.', ['message' => $exception->getMessage()]);
        }
    }
};
