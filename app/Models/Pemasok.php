<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemasok extends Model
{
    protected $table="pemasok"; 
    protected $guarded = ['id'];

    public function obat()
    {
        return $this->hasMany(Obat::class, 'obat_id');
    }
}
