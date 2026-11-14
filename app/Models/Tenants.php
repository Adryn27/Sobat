<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Tenants extends Model
{
    protected $table="tenants"; 
    protected $guarded = ['id'];

    protected static function booted()
    {
        static::created(function ($tenant) {
            DB::table('kriteria')->insert([ 
                'tenant_id'    => $tenant->id,
                'c1_volume'    => 0.40,
                'c2_frekuensi' => 0.30,
                'c3_rasio'     => 0.30,
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        });
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
