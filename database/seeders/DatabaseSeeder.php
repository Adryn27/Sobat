<?php

namespace Database\Seeders;

use App\Models\Kriteria;
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

        User::create([
            'nama' => 'Administrator',
            'email' => 'admin@gmail.com',
            'role' => '0',
            'status' => 1,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
        ]);
        Kriteria::create([
            'c1_volume' => 0.40,
            'c2_frekuensi' => 0.30,
            'c3_rasio' => 0.30,
        ]);
    }
}
