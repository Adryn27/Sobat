<?php

namespace App\Services;

use App\Models\Kriteria;
use App\Models\Obat;
// use Illuminate\Support\Facades\DB;

class SawService
{
    public function calculateSAW($days = 30)
    {
        $startDate = now()->subDays($days);

        // $obats = Obat::with(['detailPenjualan' => function ($q) use ($startDate) {
        //     $q->where('created_at', '>=', $startDate);
        // }])->get();
        $obats = Obat::with([
            'detailPenjualan' => function ($q) use ($startDate) {
                $q->whereHas('penjualan', function ($query) use ($startDate) {
                    $query->where('created_at', '>=', $startDate);
                });
            }
        ])->get();

        $bobot = Kriteria::first();

        $matrix = [];

        $max = [
            'c1' => 0,
            'c2' => 0,
        ];

        $minC3 = PHP_INT_MAX;

        foreach ($obats as $obat) {

            // C1 Volume Penjualan
            $c1 = $obat->detailPenjualan->sum('jumlah');

            // C2 Frekuensi
            $c2 = $obat->detailPenjualan->count();

            // C3 Rasio Stok (Cost)
            $stokMin = $obat->stok_maksimal * 0.5;
            $c3 = $stokMin > 0 ? ($obat->stok / $stokMin) : 0;


            $matrix[$obat->id] = compact('c1','c2','c3');

            $max['c1'] = max($max['c1'], $c1);
            $max['c2'] = max($max['c2'], $c2);

            $minC3 = min($minC3, $c3);
        }

        $max['c1'] = $max['c1'] ?: 1;
        $max['c2'] = $max['c2'] ?: 1;
        $minC3 = $minC3 ?: 1;

        foreach ($obats as $obat) {

            $m = $matrix[$obat->id];

            // NORMALISASI SAW
            $r1 = $m['c1'] / $max['c1'];
            $r2 = $m['c2'] / $max['c2'];

            // COST
            $r3 = $m['c3'] > 0 ? ($minC3 / $m['c3']) : 1;

            // NILAI AKHIR
            $saw =
                ($r1 * $bobot->c1_volume) +
                ($r2 * $bobot->c2_frekuensi) +
                ($r3 * $bobot->c3_rasio);

            $obat->update([
                'nilai_saw' => $saw
            ]);
        }
        return $obat;
    }
}