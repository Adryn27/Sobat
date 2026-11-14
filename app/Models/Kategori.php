<?php

namespace App\Models;

use App\Traits\ToTenants;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    use ToTenants;
    protected $table='kategori'; 
    protected $guarded = ['id'];

    public function obat()
    {
        return $this->hasMany(Obat::class, 'kategori_id');
    }
}

