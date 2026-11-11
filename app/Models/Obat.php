<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    protected $table="obat"; 
    protected $guarded = ['id'];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class, 'pemasok_id');
    }

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'obat_id');
    }

    public function mutasi()
    {
        return $this->hasMany(Mutasi::class, 'obat_id', 'id');
    }
}
