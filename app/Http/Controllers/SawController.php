<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Obat;
use Illuminate\Http\Request;
use App\Services\SawService;

class SawController extends Controller
{
    protected $sawService;

    public function __construct(SawService $sawService)
    {
        $this->sawService = $sawService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $days = $request->days ?? 30;
        $this->sawService->calculateSAW($days);

        $startDate = now()->subDays($days);

        $index = Obat::with([
            'detailPenjualan' => function ($q) use ($startDate) {
                $q->whereHas('penjualan', function ($query) use ($startDate) {
                    $query->where('created_at', '>=', $startDate);
                });
            }
        ])
        ->orderByDesc('nilai_saw')
        ->get();
        $kriteria = Kriteria::first();

        return view('backend.layout.v_analisis.index', [
            'judul' => 'Analytics',
            'subjudul' => 'Monitoring Prioritas Restock Obat Fast Moving',
            'index' => $index,
            'saw' => $kriteria,
            'days' => $days
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'c1_volume' => 'required|numeric|min:0|max:1',
            'c2_frekuensi' => 'required|numeric|min:0|max:1',
            'c3_rasio' => 'required|numeric|min:0|max:1',
        ]);
        
        $totalBobot =
            $request->c1_volume +
            $request->c2_frekuensi +
            $request->c3_rasio;
        
        $epsilon = 0.0001;
        
        if (abs($totalBobot - 1.0) > $epsilon) {
            return redirect()->back()->with('error',
                "Gagal! Total bobot harus 1.00. Saat ini: " . round($totalBobot, 4)
            );
        }
        
        Kriteria::where('id', 1)->update([
            'c1_volume' => $request->c1_volume,
            'c2_frekuensi' => $request->c2_frekuensi,
            'c3_rasio' => $request->c3_rasio,
        ]);
        
        return redirect()->back()->with('success',
            'Bobot berhasil diperbarui. Silakan jalankan ulang SAW.'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function proses()
    {
        $days = $request->days ?? 30;
        $this->sawService->calculateSAW($days);
        return redirect()->back()->with('success', 'Algoritma SAW berhasil dijalankan! Nilai prioritas inventaris obat telah disinkronkan.');
    }
}
