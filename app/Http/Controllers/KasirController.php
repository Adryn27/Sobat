<?php

namespace App\Http\Controllers;

use App\Exports\PenjualanExport;
use App\Imports\PenjualanImport;
use App\Models\DetailPenjualan;
use App\Models\Mutasi;
use App\Models\Obat;
use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class KasirController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $obat = Obat::where('stok', '>', 0)->get();
        
        $date = date('Ymd');
        $count = Penjualan::whereDate('created_at', date('Y-m-d'))->count() + 1;
        $invoice = 'INV-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        return view('backend.layout.v_sales.kasir', [
            'judul'=>'Cashier',
            'index'=>$obat,
            'invoice'=>$invoice
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
            'no_invoice'  => 'required',
            'total_harga' => 'required|numeric|min:1',
            'bayar'       => 'required|numeric',
            'obat_id'     => 'required|array',
            'jumlah'      => 'required|array',
            'harga_jual'  => 'required|array',
            'subtotal'    => 'required|array',
        ]);

        if ($request->bayar < $request->total_harga) {
            return redirect()->back()->with('error', 'Uang pembayaran tidak cukup!');
        }

        try {
            DB::transaction(function () use ($request) {
                $penjualan = Penjualan::create([
                    'user_id'     => Auth::id(),
                    'no_invoice'  => $request->no_invoice,
                    'total_harga' => $request->total_harga,
                    'bayar'       => $request->bayar,
                    'kembali'     => $request->bayar - $request->total_harga,
                ]);

                foreach ($request->obat_id as $key => $med_id) {
                    $obat = Obat::findOrFail($med_id);
                    
                    if ($obat->stok < $request->jumlah[$key]) {
                        throw new \Exception("Stok obat {$obat->nama_obat} tidak mencukupi!");
                    }

                    DetailPenjualan::create([
                        'penjualan_id'     => $penjualan->id,
                        'obat_id' => $med_id,
                        'harga_jual'  => $request->harga_jual[$key],
                        'jumlah'      => $request->jumlah[$key],
                        'subtotal'    => $request->subtotal[$key],
                    ]);
                    //Perubahan
                    $stokSebelum = $obat->stok;

                    $obat->decrement('stok', $request->jumlah[$key]);

                    //Perubahan
                    $obat->refresh();
                    $jumlah=$request->jumlah[$key];
                    Mutasi::create([
                        'obat_id'      => $med_id,
                        'user_id'      => Auth::id(),
                        'jumlah'       => $jumlah,
                        'jenis'        => 'keluar',
                        'stok_sebelum' => $stokSebelum,
                        'stok_sesudah' => $obat->stok, // Stok yang sudah ter-update
                        'catatan'      => 'Penjualan No: ' . $request->no_invoice,
                    ]);
                }
            });

            return redirect()->back()->with('success', 'Transaksi Berhasil diselesaikan!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
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
        //
    }

    public function history(Request $request)
    {
        $riwayat = Penjualan::with('detailPenjualan.obat')->latest();

        if ($request->has('filter') && !empty($request->filter)) {
            switch ($request->filter) {
                case '1_hari':
                    $riwayat->whereDate('created_at', now()->today());
                    break;
                case '1_minggu':
                    $riwayat->where('created_at', '>=', now()->subDays(7));
                    break;
                case '1_bulan':
                    $riwayat->where('created_at', '>=', now()->subMonth());
                    break;
                case '3_bulan':
                    $riwayat->where('created_at', '>=', now()->subMonths(3));
                    break;
                case '1_tahun':
                    $riwayat->where('created_at', '>=', now()->subYear());
                    break;
            }
        }
        $history= $riwayat->get();
        return view('backend.layout.v_sales.history', [
            'judul'=>'History',
            'subjudul'=>'Riwayat Transaksi Penjualan',
            'index'=>$history,
        ]);
    }

    public function import(Request $request)
    {
        try {
            $request->validate([
                'file_excel' => 'required|file|mimes:xlsx,xls,csv,txt|max:5120',
            ], [
                'file_excel.mimes' => 'Format berkas salah! Sistem hanya menerima file Excel (.xlsx, .xls) atau CSV (.csv).',
                'file_excel.required' => 'Silakan pilih file terlebih dahulu.',
            ]);
    
            Excel::import(new PenjualanImport, $request->file('file_excel'));
    
            $baru = session('import_baru', 0);
            $update = session('import_update', 0);
    
            if ($baru > 0 && $update == 0) {
                return redirect()->back()->with('success', "Berhasil meng-import {$baru} data transaksi online baru!");
            } 
            
            if ($baru > 0 && $update > 0) {
                return redirect()->back()->with('success', "Berhasil menambahkan {$baru} transaksi baru dan menyinkronkan {$update} data lama.");
            }
    
            return redirect()->back()->with('error', "Tidak ada data baru. Sistem telah memverifikasi {$update} transaksi di database sudah sinkron.");
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->with('error', $e->validator->errors()->first());
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $jenis = $request->input('jenis_penjualan', 'Semua');
            $labelJenis = $jenis == '1' ? 'Online' : ($jenis == '0' ? 'Offline' : 'Semua');
            
            $namaFile = 'Laporan_Penjualan_' . $labelJenis . '_' . date('Y-m-d_H-i-s') . '.xlsx';
            
            return Excel::download(new PenjualanExport($request), $namaFile);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengeksport data: ' . $e->getMessage());
        }
    }

    public function report(Request $request)
    {
        $query = Penjualan::query()->with('detailPenjualan.obat');

        if ($request->filled(['tgl_mulai', 'tgl_selesai'])) {
            $query->whereBetween('created_at', [
                $request->tgl_mulai . ' 00:00:00', 
                $request->tgl_selesai . ' 23:59:59'
            ]);
        }

        $laporan = $query->latest()->get();
        
        $totalPendapatan = $laporan->sum('total_harga');
        $jumlahTransaksi = $laporan->count();

        return view('backend.layout.v_report.sales', [
            'judul' => 'Sales',
            'subjudul' => 'Laporan Penjualan',
            'subjudul2' => 'Riwayat Penjualan',
            'index' => $laporan,
            'totalPendapatan' => $totalPendapatan,
            'jumlahTransaksi' => $jumlahTransaksi,
        ]);
    }

    public function exportPdf(Request $request)
    {
        $query = Penjualan::query();

        if ($request->filled(['tgl_mulai', 'tgl_selesai'])) {
            $query->whereBetween('created_at', [
                $request->tgl_mulai . ' 00:00:00', 
                $request->tgl_selesai . ' 23:59:59'
            ]);
        }

        $laporan = $query->latest()->get();
        $totalPendapatan = $laporan->sum('total_harga');

        $pdf = Pdf::loadView('backend.layout.v_report.salespdf', [
            'index' => $laporan,
            'Pendapatan' => $totalPendapatan,
        ]);

        return $pdf->download('Laporan_Penjualan_' . date('Y-m-d') . '.pdf');
    }
}
