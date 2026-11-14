<?php

namespace App\Models;

use App\Traits\ToTenants;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use ToTenants;
    protected $table="penjualan"; 
    protected $guarded = ['id'];

    public function detailPenjualan()
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(user::class, 'user_id'); 
    }
}
