<?php

namespace App\Models;

use App\Traits\ToTenants;
use Illuminate\Database\Eloquent\Model;

class BatchObat extends Model
{
    use ToTenants;
    protected $table="batch_obat"; 
    protected $guarded = ['id'];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }
}
