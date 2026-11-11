<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    protected $table="detail_penjualan"; 
    protected $guarded = ['id'];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id', 'id');
    }

    /**
     * Relasi ke model Medicine (Mengetahui obat apa yang dibeli)
     */
    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id', 'id');
    }
}
