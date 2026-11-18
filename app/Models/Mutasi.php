<?php

namespace App\Models;

use App\Traits\ToTenants;
use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{
    use ToTenants;
    protected $table='mutasi'; 
    protected $guarded = ['id'];

    public function obat()
    {
        return $this->belongsTo(Obat::class, 'obat_id');
    }

    public function user()
    {
        return $this->belongsTo(user::class, 'user_id'); 
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id'); // Sesuaikan foreign key
    }

    public function batchObat()
    {
        return $this->belongsTo(BatchObat::class, 'batch_obat_id');
    }
}
