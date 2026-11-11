<?php

namespace App\Http\Controllers;

use App\Models\Mutasi;
use App\Models\Obat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MutasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $mutasi= Mutasi::with('obat')->latest();
        if ($request->has('filter')) {
            switch ($request->filter) {
                case '1_hari':
                    $mutasi->whereDate('created_at', now()->today());
                    break;
                case '1_minggu':
                    $mutasi->where('created_at', '>=', now()->subDays(7));
                    break;
                case '1_bulan':
                    $mutasi->where('created_at', '>=', now()->subMonth());
                    break;
                case '3_bulan':
                    $mutasi->where('created_at', '>=', now()->subMonths(3));
                    break;
                case '1_tahun':
                    $mutasi->where('created_at', '>=', now()->subYear());
                    break;
            }
        }
        $stok= $mutasi->get();
        $obat = Obat::all(); 
        return view('backend.layout.v_stock.index', [
            'judul' => 'Stock Adjustment',
            'subjudul' => 'Mutasi Stok',
            'index' => $stok,
            'obat' => $obat,
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
        $request->validate(['obat_id' => 'required', 'jumlah' => 'required|numeric', 'jenis' => 'required']);

        DB::transaction(function () use ($request) {
            $obat = Obat::findOrFail($request->obat_id);
            $stokSebelum = $obat->stok;
            
            if ($request->jenis == 'masuk') {
                $stokSesudah = $stokSebelum + $request->jumlah;
            } elseif ($request->jenis == 'keluar') {
                if ($stokSebelum < $request->jumlah) throw new \Exception("Stok tidak cukup!");
                $stokSesudah = $stokSebelum - $request->jumlah;
            } else { 
                $stokSesudah = $request->jumlah;
                $request->merge(['jumlah' => abs($stokSesudah - $stokSebelum)]);
            }

            $dataUpdate = ['stok' => $stokSesudah];
            if ($stokSesudah > $obat->stok_maksimal) {
                $dataUpdate['stok_maksimal'] = $stokSesudah;
            }
            // Simpan ke Mutasi
            Mutasi::create([
                'user_id' => Auth::id(),
                'obat_id' => $obat->id,
                'jumlah' => $request->jumlah,
                'jenis' => $request->jenis,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'catatan' => $request->catatan
            ]);

            $obat->update($dataUpdate);
        });

        return back()->with('success', 'Data berhasil ditambahkan');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $mutasi = Mutasi::findOrFail($id);
        
        // $isLatest = Mutasi::where('obat_id', $mutasi->obat_id)->where('id', '>', $id)->exists();
        // if ($isLatest) return back()->with('error', 'Hapus transaksi terbaru terlebih dahulu!');

        // DB::transaction(function () use ($mutasi) {
        //     $obat = Obat::findOrFail($mutasi->obat_id);
        //     $obat->update(['stok' => $mutasi->stok_sebelum]);
        //     $mutasi->delete();
        // });

        // return back()->with('success', 'Transaksi dihapus dan stok dikembalikan.');
    }

    public function inven(Request $request)
    {
        $obt = Obat::with(['mutasi' => function($q) {
            $q->orderBy('created_at', 'ASC'); 
        }]);

        if ($request->has('filter')) {
            if ($request->filter == 'kosong') {
                $obt->where('stok', 0);
            } elseif ($request->filter == 'kritis') {
                $obt->where('stok', '>', 0)
                      ->whereRaw('stok < (stok_maksimal * 0.5)');
            }
        }
    
        $obat = $obt->orderBy('stok', 'asc')->get();
    
        foreach ($obat as $o) {
            $runningBalance = 0;
            $o->mutasi->each(function($m) use (&$runningBalance) {
                if ($m->jenis == 'masuk') $runningBalance += $m->jumlah;
                elseif ($m->jenis == 'keluar') $runningBalance -= ($m->jumlah ?? 0);
                elseif ($m->jenis == 'opname') $runningBalance = $m->stok_sesudah;
                $m->calculated_sisa = $runningBalance;
            });
        
            $stoks = $o->mutasi->sortByDesc('created_at')->take(10)->sortBy('created_at');
            
            $stokAwalAsli = $o->mutasi->whereIn('catatan', ['Stok Awal Import', 'Stok Awal Penambahan Obat'])->first();
        
            if ($stoks->isEmpty()) {
                $saldoBawaan = $o->stok; 
                $label = $stokAwalAsli ? $stokAwalAsli->catatan : 'Stok Awal (Input Data)';
            } else {
                $transaksiTertua = $stoks->first();
                $sebelumnya = $o->mutasi->where('created_at', '<', $transaksiTertua->created_at)->last();
                
                $saldoBawaan = $sebelumnya ? $sebelumnya->calculated_sisa : 0;
                
                if ($transaksiTertua->id == ($stokAwalAsli->id ?? null)) {
                    $label = $stokAwalAsli->catatan;
                } else {
                    $label = 'Stock Awal (Sisa sebelum ' . $transaksiTertua->created_at->format('d M Y H:i') . ')';
                }
            }
        
            $barisSaldoBawaan = new Mutasi([
                'catatan' => $label,
                'jenis'   => null,
                'jumlah'  => 0,
                'stok_sesudah' => $saldoBawaan,
                'created_at' => ($stoks->isNotEmpty()) ? $stoks->first()->created_at->subSecond() : now()
            ]);
        
            if ($stoks->isNotEmpty() && $stoks->first()->id == ($stokAwalAsli->id ?? null)) {
                $tampilan = $stoks;
            } else {
                $tampilan = collect([$barisSaldoBawaan])->merge($stoks);
            }
            
            $o->setRelation('mutasi', $tampilan);
        }

        $jumlahObat = Obat::count();
        $jumlahStokKritis = Obat::where('stok', '>', 0)
                        ->whereRaw('stok < (stok_maksimal * 0.5)')
                        ->count();
        $jumlahStokKosong = Obat::where('stok', 0)->count();
        // $jumlahStokKritis = $obat->filter(fn($item) => $item->stok > 0 && $item->stok < ($item->stok_maksimal * 0.5))->count();
        // $jumlahStokKosong = $obat->filter(fn($item) => $item->stok == 0)->count();
    
        return view('backend.layout.v_stock.inven', [
            'judul' => 'Inventory',
            'subjudul' => 'Daftar Obat',
            'index' => $obat,
            'jumlahObat' => $jumlahObat,
            'jumlahStokKritis' => $jumlahStokKritis,
            'jumlahStokKosong' => $jumlahStokKosong,
        ]);
    }

    public function print()
    {
        $obat = Obat::join('kategori', 'obat.kategori_id', '=', 'kategori.id')
            ->orderBy('kategori.nama_kategori', 'asc')
            ->orderBy('obat.nama_obat', 'asc')
            ->select('obat.*')
            ->get();
        
        $pdf = Pdf::loadView('backend.layout.v_stock.stokpdf', [
            'judul' => 'Formulir Stock Opname',
            'index' => $obat,
        ])->setPaper('a4', 'landscape');
        return $pdf->download('Form_Stock_Opname_'.date('Y-m-d').'.pdf');
    }
}
