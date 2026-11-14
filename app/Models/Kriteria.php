<?php

namespace App\Models;

use App\Traits\ToTenants;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use ToTenants;
    protected $table='kriteria'; 
    protected $guarded = ['id'];
}
