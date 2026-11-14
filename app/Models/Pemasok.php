<?php

namespace App\Models;

use App\Traits\ToTenants;
use Illuminate\Database\Eloquent\Model;

class Pemasok extends Model
{
    use ToTenants;
    protected $table="pemasok"; 
    protected $guarded = ['id'];

    public function obat()
    {
        return $this->hasMany(Obat::class, 'obat_id');
    }
}
