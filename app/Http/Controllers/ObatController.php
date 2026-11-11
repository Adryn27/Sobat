<?php

namespace App\Http\Controllers;

use App\Exports\ObatExport;
use App\Imports\ObatImport;
use App\Models\Kategori;
use App\Models\Mutasi;
use App\Models\Obat;
use App\Models\Pemasok;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ObatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $obat=Obat::orderby('updated_at','desc')->get();
        $kategori =Kategori::orderBy('created_at', 'asc')->get();
        $pemasok=Pemasok::orderby('created_at','asc')->get();
        return view('backend.layout.v_data.obat', [
            'judul'=>'Product',
            'subjudul'=>'Daftar Obat',
            'index'=>$obat,
            'kategori'=>$kategori,
            'pemasok'=>$pemasok,
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
        $request->validate([
            'kode_obat'     => 'required|string|unique:obat,kode_obat|max:50',
            'nama_obat'     => 'required|string|max:255',
            'kategori_id'   => 'required|exists:kategori,id',
            'satuan'        => 'required|in:0,1,2',
            'harga_offline' => 'required|numeric|min:0',
            'harga_online'  => 'required|numeric|min:0',
            'stok'          => 'required|integer|min:0',
            'stok_maksimal' => 'required|integer|min:1',
            'pemasok_id'   => 'required|exists:pemasok,id',
        ]);

        $stokAwal = $request->stok;
        $stokMax = $request->stok_maksimal;
        $finalStokMax = ($stokAwal > $stokMax) ? $stokAwal : $stokMax;

        DB::transaction(function () use ($request, $finalStokMax) {
            $obat = Obat::create([
                'kode_obat'     => trim($request->kode_obat),
                'nama_obat'     => ucwords(strtolower(trim($request->nama_obat))),
                'kategori_id'   => $request->kategori_id,
                'satuan'        => $request->satuan,
                'harga_offline' => $request->harga_offline,
                'harga_online'  => $request->harga_online,
                'stok'          => $request->stok,
                'stok_maksimal' => $finalStokMax, // Simpan nilai yang sudah di-adjust
                'pemasok_id'    => $request->pemasok_id,
            ]);

            if ($request->stok > 0) {
                Mutasi::create([
                    'obat_id'      => $obat->id,
                    'user_id'      => Auth::id(),
                    'jumlah'       => $request->stok,
                    'jenis'        => 'masuk',
                    'stok_sebelum' => 0,
                    'stok_sesudah' => $request->stok,
                    'catatan'      => 'Stok Awal Penambahan Obat',
                ]);
            }
        });
        //Perubahan (Variabel)
        // $obat=Obat::create([
        //     'kode_obat'     => trim($request->kode_obat),
        //     'nama_obat'     => ucwords(strtolower(trim($request->nama_obat))),
        //     'kategori_id'   => $request->kategori_id,
        //     'satuan'        => $request->satuan,
        //     'harga_offline' => $request->harga_offline,
        //     'harga_online'  => $request->harga_online,
        //     'stok'          => $request->stok,
        //     'stok_maksimal' => $request->stok_maksimal,
        //     'pemasok_id'   => $request->pemasok_id,
        // ]);

        // //Perubahan
        // if ($request->stok > 0) {
        //     Mutasi::create([
        //         'obat_id'      => $obat->id,
        //         'user_id'      => Auth::id(),
        //         'jumlah'       => $request->stok,
        //         'jenis'        => 'masuk',
        //         'stok_sebelum' => 0,
        //         'stok_sesudah' => $request->stok,
        //         'catatan'      => 'Stok Awal Penambahan Obat',
        //         'created_at'   => now(),
        //         'updated_at'   => now(),
        //     ]);
        // }

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
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
            'nama_obat'     => 'required|string|max:255',
            'kategori_id'   => 'required|exists:kategori,id',
            'satuan'        => 'required|string',
            'harga_offline' => 'required|numeric|min:0',
            'harga_online'  => 'required|numeric|min:0',
            'stok_maksimal' => 'required|integer|min:1',
            'pemasok_id'   => 'required|exists:pemasok,id',
        ]);

        $obat = Obat::findOrFail($id);
        
        $obat->update([
            'nama_obat'     => ucwords(strtolower(trim($request->nama_obat))),
            'kategori_id'   => $request->kategori_id,
            'satuan'        => $request->satuan,
            'harga_offline' => $request->harga_offline,
            'harga_online'  => $request->harga_online,
            'stok_maksimal' => $request->stok_maksimal,
            'pemasok_id'   => $request->pemasok_id,
        ]);

        return redirect()->back()->with('success', 'Data berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $obat = Obat::findOrFail($id);
            $obat->delete();
            return back()->with('success', 'Obat berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Obat tidak dapat dihapus karena masih terkait dengan data transaksi atau mutasi stok.');
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv|max:4096'
        ]);
    
        try {
            $import = new ObatImport;
    
            Excel::import($import, $request->file('file_excel'));
            
            $created = $import->counter['created'];
            $updated = $import->counter['updated'];
            $skipped = $import->counter['skipped'];
    
            $message = "Proses sinkronisasi selesai. ";
            $message .= "{$created} Obat Baru ditambahkan, ";
            $message .= "{$updated} Data Obat diperbarui.";
            
            if ($skipped > 0) {
                $message .= " ({$skipped} baris dilewati karena data tidak lengkap/tanpa nama obat).";
            }
            
            return redirect()->back()->with('success', $message);
    
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            return redirect()->back()->with('error', 'Format penulisan data pada berkas excel ada yang keliru.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses file master obat: ' . $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $namaFile = 'data_obat_' . date('Ymd_His') . '.xlsx';
            return Excel::download(new ObatExport($request), $namaFile);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengeksport data: ' . $e->getMessage());
        }
    }
}
