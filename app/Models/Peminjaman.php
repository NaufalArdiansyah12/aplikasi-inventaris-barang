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
}
