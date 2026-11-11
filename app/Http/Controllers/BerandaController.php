<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\Obat;
use App\Models\Penjualan;
use Illuminate\Http\Request;

class BerandaController extends Controller
{
    public function berandaBackend(Request $request)
    {
        return view('backend.layout.v_dashboard.index', [
            'judul' => 'Dashboard'
        ]);
    }

    public function getData(Request $request)
    {
        $days = (int) ($request->days ?? 30);
        $startDate = now()->subDays($days - 1)->startOfDay();
        $endDate = now()->endOfDay();

        $bobot = Kriteria::first();

        // 1. STATISTIK CARD (Update filter agar sinkron dengan periode)
        $totalObat = Obat::count();
        $fastMoving = Obat::where('nilai_saw', '>=', 0.75)->count();
        $stokKritis = Obat::whereRaw('stok <= (stok_maksimal * 0.50)')->count();
        
        // PERBAIKAN: Gunakan whereBetween
        $totalSales = Penjualan::join('detail_penjualan', 'penjualan.id', '=', 'detail_penjualan.penjualan_id')
            ->whereBetween('penjualan.created_at', [$startDate, $endDate])
            ->sum('detail_penjualan.jumlah');

        // 2. TOP SAW & RESTOCK
        $sawService = new \App\Services\SawService();
        $sawService->calculateSAW($days); // Ini akan update kolom nilai_saw di DB

        // 2. Sekarang baru ambil data dari database (sudah terupdate)
        $topRestock = Obat::orderByDesc('nilai_saw')->take(10)->get()->map(function ($o) {
            $score = $o->nilai_saw;
            $o->status = $score >= 0.75 ? 'Prioritas Tinggi' : ($score >= 0.5 ? 'Prioritas Sedang' : 'Prioritas Rendah');
            $o->color = $score >= 0.75 ? 'danger' : ($score >= 0.5 ? 'warning text-dark' : 'success');
            return $o;
        });
        // $topRestock = Obat::orderByDesc('nilai_saw')->take(10)->get()->map(function ($o) {
        //     $score = $o->nilai_saw;
        //     $o->status = $score >= 0.75 ? 'Prioritas Tinggi' : ($score >= 0.5 ? 'Prioritas Sedang' : 'Prioritas Rendah');
        //     $o->color = $score >= 0.75 ? 'danger' : ($score >= 0.5 ? 'warning text-dark' : 'success');
        //     return $o;
        // });

        // 4. ALERT STOK
        $alertStok = Obat::whereRaw('stok <= (stok_maksimal * 0.50)')
            ->orderBy('stok')
            ->take(10)
            ->get(['nama_obat', 'stok']);

        // 5. TREN PENJUALAN
        $sales = Penjualan::join('detail_penjualan', 'penjualan.id', '=', 'detail_penjualan.penjualan_id')
            ->selectRaw('DATE(penjualan.created_at) as tanggal, SUM(detail_penjualan.jumlah) as total')
            ->whereBetween('penjualan.created_at', [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();
        

        // 6. TOP C4 (INTENSITAS)
        $intensitas = Obat::all()->map(function ($obat) use ($startDate, $endDate) {
            $volume = $obat->detailPenjualan()
                ->whereHas('penjualan', fn($q) => $q->whereBetween('created_at', [$startDate, $endDate]))
                ->sum('jumlah');
            
            $c4 = $obat->stok > 0 ? ($volume / $obat->stok) : 0;

            if ($c4 >= 2) {
                $label = 'Sangat Tinggi';
                $color = '#e74a3b'; // Merah
            } elseif ($c4 > 1) {
                $label = 'Tinggi';
                $color = '#fd7e14'; // Oranye
            } elseif ($c4 >= 0.5) {
                $label = 'Sedang';
                $color = '#f6c23e'; // Kuning
            } else {
                $label = 'Rendah';
                $color = '#1cc88a'; // Hijau
            }

            return [
                'nama_obat' => $obat->nama_obat,
                'c4' => round($c4, 2),
                'label' => $label,
                'color' => $color
            ];
        })->sortByDesc('c4')->take(10);

        return response()->json([
            'total_obat'    => $totalObat,
            'fast_moving'   => $fastMoving,
            'stok_kritis'   => $stokKritis,
            'total_sales'   => number_format($totalSales, 0, ',', '.') . ' Pcs',
            'top_labels'    => $topRestock->pluck('nama_obat'),
            'top_scores'    => $topRestock->pluck('nilai_saw'),
            'sales_labels'  => $sales->pluck('tanggal'),
            'sales_values'  => $sales->pluck('total'),
            'c4_labels' => $intensitas->pluck('nama_obat'),
            'c4_values' => $intensitas->pluck('c4'),
            'c4_colors' => $intensitas->pluck('color'),
            'c4_categories' => $intensitas->pluck('label'),
            'top_restock'   => $topRestock,
            'alert_stok'    => $alertStok,
            'bobot'         => $bobot,
            'start_date'    => $startDate->format('d M Y'),
            'end_date'      => $endDate->format('d M Y')
        ]);
    }
}
