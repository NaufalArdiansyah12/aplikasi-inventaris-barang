<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama_barang', 'jumlah', 'kondisi_barang', 'denda_per_hari', 'foto'])]
class Barang extends Model
{
    protected $table = 'barang';

    protected $primaryKey = 'id_barang';

    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'id_barang', 'id_barang');
    }

    public function getStatusAttribute(): string
    {
        return $this->jumlah <= 0 ? 'Habis' : 'Tersedia';
    }

    protected $casts = [
        'denda_per_hari' => 'decimal:2',
    ];

    protected $appends = [];
}
