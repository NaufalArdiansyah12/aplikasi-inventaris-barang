<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['id_user', 'id_barang', 'jumlah_pinjam', 'lama_hari', 'tanggal_pinjam', 'tanggal_kembali', 'status_pinjam', 'denda', 'kondisi_pengembalian'])]
class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $primaryKey = 'id_pinjam';

    protected function casts(): array
    {
        return [
            'tanggal_pinjam' => 'date',
            'tanggal_kembali' => 'date',
            'denda' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    /**
     * Hitung denda keterlambatan berdasarkan tanggal pengembalian aktual
     * 
     * @param string|\DateTime $tanggalKembaliAktual Tanggal pengembalian aktual
     * @return float Total denda (0 jika tidak terlambat)
     */
    public function hitungDendaKeterlambatan($tanggalKembaliAktual = null): float
    {
        // Jika tidak ada parameter, gunakan tanggal sekarang
        if ($tanggalKembaliAktual === null) {
            $tanggalKembaliAktual = now()->toDate();
        }

        $tanggalKembaliAktual = \Carbon\Carbon::parse($tanggalKembaliAktual);
        $tanggalJatuhTempo = \Carbon\Carbon::parse($this->tanggal_kembali);

        // Hitung selisih hari (jika positif berarti terlambat)
        $hariTerlambat = $tanggalKembaliAktual->diffInDays($tanggalJatuhTempo);

        // Jika tidak terlambat, denda = 0
        if ($hariTerlambat <= 0) {
            return 0;
        }

        // Total denda = hari terlambat × denda per hari × jumlah barang
        return $hariTerlambat * $this->barang->denda_per_hari * $this->jumlah_pinjam;
    }

    /**
     * Dapatkan status keterlambatan
     * 
     * @return array
     */
    public function getStatusKeterlambatan(): array
    {
        $tanggalSekarang = \Carbon\Carbon::now();
        $tanggalJatuhTempo = \Carbon\Carbon::parse($this->tanggal_kembali);
        $hariTerlambat = $tanggalSekarang->diffInDays($tanggalJatuhTempo);

        return [
            'terlambat' => $hariTerlambat > 0,
            'hari_terlambat' => $hariTerlambat > 0 ? $hariTerlambat : 0,
            'denda_estimasi' => $hariTerlambat > 0 ? $this->hitungDendaKeterlambatan($tanggalSekarang->toDateString()) : 0,
        ];
    }
}
