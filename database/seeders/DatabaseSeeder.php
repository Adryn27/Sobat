<?php

namespace Database\Seeders;

use App\Models\Kriteria;
use App\Models\Tenants;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Akun Testing Sobat 
        Tenants::create([
            'nama_tenant' => 'Sobat Apotek',
            'slug' => 'sobat-apotek-0123',
            'alamat' => 'Jl. Raya No. 123, Bekasi',
            'telepon' => '081234567890',
            'status' => 1,
        ]);
        User::create([
            'tenant_id' => '1',
            'nama' => 'Pemilik Sobat',
            'email' => 'sobat@gmail.com',
            'role' => '2', // Pemilik
            'status' => 1,
            'email_verified_at' => now(),
            'password' => bcrypt('Sobat#1'),
        ]);
        User::create([
            'tenant_id' => '1',
            'nama' => 'Admin Sobat',
            'email' => 'admin@gmail.com',
            'role' => '1',  // Admin
            'status' => 1,
            'email_verified_at' => now(),
            'password' => bcrypt('Sobat#1'),
        ]);
        User::create([
            'tenant_id' => '1',
            'nama' => 'Apoteker Sobat',
            'email' => 'apoteker@gmail.com',
            'role' => '3', // Apoteker
            'status' => 1,
            'email_verified_at' => now(),
            'password' => bcrypt('Sobat#1'),
        ]);
        User::create([
            'tenant_id' => '1',
            'nama' => 'Gudang Sobat',
            'email' => 'gudang@gmail.com',
            'role' => '4', // Kepala Gudang
            'status' => 1,
            'email_verified_at' => now(),
            'password' => bcrypt('Sobat#1'),
        ]);
        Kriteria::create([
            'tenant_id' => '1',
            'c1_volume' => 0.40,
            'c2_frekuensi' => 0.30,
            'c3_rasio' => 0.30,
        ]);
        // Akun Tenant 2 
        Tenants::create([
            'nama_tenant' => 'Apotek Sapphire Farma',
            'slug' => 'apotek-sapphire-farma-3210',
            'alamat' => 'Jl. Raya No. 123, Bekasi',
            'telepon' => '081234567890',
            'status' => 1,
        ]);
        User::create([
            'tenant_id' => '2',
            'nama' => 'Budi Santoso',
            'email' => 'budi@gmail.com',
            'role' => '2', // Pemilik
            'status' => 1,
            'email_verified_at' => now(),
            'password' => bcrypt('Sobat#1'),
        ]);
        Kriteria::create([
            'tenant_id' => '2',
            'c1_volume' => 0.40,
            'c2_frekuensi' => 0.30,
            'c3_rasio' => 0.30,
        ]);
    }
}
