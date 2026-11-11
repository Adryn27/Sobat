<?php

namespace App\Imports;

use App\Models\Obat;
use App\Models\Kategori;
use App\Models\Mutasi;
use App\Models\Pemasok;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;

class ObatImport implements ToModel, WithHeadingRow, WithValidation, SkipsEmptyRows
{
    public $counter = [
        'created' => 0,
        'updated' => 0,
        'skipped' => 0,
    ];

    public function model(array $row)
    {
        $kodeObat = trim($row['merchant_sku'] ?? $row['sku'] ?? $row['kode_obat_sku'] ?? '');
        if (empty($kodeObat)) { $this->counter['skipped']++; return null; }

        $namaObat = trim($row['product_name'] ?? $row['nama_obat'] ?? '');
        if (empty($namaObat)) { $this->counter['skipped']++; return null; }

        $satuanTeks = strtolower(trim($row['unit'] ?? $row['satuan'] ?? 'box'));
        $satuanEnum = ($satuanTeks === '1' || strpos($satuanTeks, 'botol') !== false) ? '1' : (($satuanTeks === '2' || strpos($satuanTeks, 'strip') !== false) ? '2' : '0');
        
        $namaKategori = trim($row['category'] ?? $row['kategori'] ?? 'Umum');
        $kategori = Kategori::firstOrCreate(['nama_kategori' => $namaKategori]);

        $pemasokInput = trim($row['manufacturer'] ?? $row['pemasok'] ?? '');
        $pemasokId = (!empty($pemasokInput) && $pemasokInput !== '-') ? Pemasok::firstOrCreate(['nama_pemasok' => $pemasokInput])->id : null;

        $hargaOffline = $row['base_price_offline'] ?? $row['base_price'] ?? $row['harga_offline'] ?? 0;
        $hargaOnline  = $row['selling_price_online'] ?? $row['selling_price'] ?? $row['harga_online'] ?? 0;
        $stokExcel    = (int)($row['stock_available'] ?? $row['stock'] ?? $row['stok_sekarang'] ?? $row['stok'] ?? 0);
        
        $inputMax = (int)($row['max_stock_limit'] ?? $row['stok_maksimal'] ?? 100);
        $stokMaxInput = ($inputMax < $stokExcel) ? $stokExcel : $inputMax;
        // $stokMaxInput = ($inputMax > 100) ? $inputMax : $stokExcel;

        $obatExisting = Obat::where('kode_obat', $kodeObat)->first();

        if ($obatExisting) {
            $stokLama = $obatExisting->stok;
            
            $stokMaxBaru = max($obatExisting->stok_maksimal, $stokMaxInput, $stokExcel);

            $obatExisting->update([
                'nama_obat'     => $namaObat,
                'satuan'        => $satuanEnum,
                'harga_offline' => $hargaOffline > 0 ? $hargaOffline : $obatExisting->harga_offline,
                'harga_online'  => $hargaOnline > 0 ? $hargaOnline : $obatExisting->harga_online,
                'stok'          => $stokExcel,
                'stok_maksimal' => $stokMaxBaru,
                'kategori_id'   => $kategori->id,
                'pemasok_id'    => $pemasokId,
            ]);

            if ($stokLama != $stokExcel) {
                Mutasi::create([
                    'obat_id'      => $obatExisting->id,
                    'user_id'      => Auth::id() ?? 1,
                    'jumlah'       => abs($stokExcel - $stokLama),
                    'jenis'        => ($stokExcel > $stokLama) ? 'masuk' : 'keluar',
                    'stok_sebelum' => $stokLama,
                    'stok_sesudah' => $stokExcel,
                    'catatan'      => 'Update Stok via Import',
                ]);
            }
            $this->counter['updated']++;
            return null;
        }

        $obatBaru = Obat::create([
            'kode_obat'     => $kodeObat,
            'nama_obat'     => $namaObat,
            'satuan'        => $satuanEnum,
            'harga_offline' => $hargaOffline,
            'harga_online'  => $hargaOnline,
            'stok'          => $stokExcel,
            'stok_maksimal' => max($stokMaxInput, $stokExcel),
            'kategori_id'   => $kategori->id,
            'pemasok_id'    => $pemasokId,
        ]);

        if ($stokExcel > 0) {
            Mutasi::create([
                'obat_id'      => $obatBaru->id,
                'user_id'      => Auth::id() ?? 1,
                'jumlah'       => $stokExcel,
                'jenis'        => 'masuk',
                'stok_sebelum' => 0,
                'stok_sesudah' => $stokExcel,
                'catatan'      => 'Stok Awal Import',
            ]);
        }

        $this->counter['created']++;
        return null;
        // $kodeObat = trim($row['merchant_sku'] ?? $row['sku'] ?? $row['kode_obat_sku'] ?? '');
        
        // if (empty($kodeObat)) {
        //     $this->counter['skipped']++;
        //     return null;
        // }

        // $namaObat = trim($row['product_name'] ?? $row['nama_obat'] ?? '');
        
        // if (empty($namaObat)) {
        //     $this->counter['skipped']++; 
        //     return null; 
        // }

        // $satuanTeks = strtolower(trim($row['unit'] ?? $row['satuan'] ?? 'box'));
        // $satuanEnum = '0'; 
        
        // if (strpos($satuanTeks, 'botol') !== false || $satuanTeks === '1') {
        //     $satuanEnum = '1';
        // } elseif (strpos($satuanTeks, 'strip') !== false || $satuanTeks === '2') {
        //     $satuanEnum = '2';
        // }

        // $namaKategori = trim($row['category'] ?? $row['kategori'] ?? 'Umum');
        // $kategori = Kategori::firstOrCreate(['nama_kategori' => $namaKategori]);

        // $pemasokInput = trim($row['manufacturer'] ?? $row['pemasok'] ?? '');
        
        // if (empty($pemasokInput) || $pemasokInput === '-') {
        //     $pemasokId = null; 
        // } else {
        //     $pemasok = Pemasok::firstOrCreate(['nama_pemasok' => $pemasokInput]);
        //     $pemasokId = $pemasok->id;
        // }

        // $hargaOffline = $row['base_price_offline'] ?? $row['base_price'] ?? $row['harga_offline'] ?? 0;
        // $hargaOnline  = $row['selling_price_online'] ?? $row['selling_price'] ?? $row['harga_online'] ?? 0;
        // $stokExcel    = $row['stock_available'] ?? $row['stock'] ?? $row['stok_sekarang'] ?? $row['stok'] ?? 0;
        // $stokMax      = $row['max_stock_limit'] ?? $row['stok_maksimal'] ?? 100;

        // $obatExisting = Obat::where('kode_obat', $kodeObat)->first();

        // if ($obatExisting) {
        //     $obatExisting->update([
        //         'nama_obat'     => $namaObat,
        //         'satuan'        => $satuanEnum,
        //         'harga_offline' => $hargaOffline > 0 ? $hargaOffline : $obatExisting->harga_offline,
        //         'harga_online'  => $hargaOnline > 0 ? $hargaOnline : $obatExisting->harga_online,
        //         'stok'          => $stokExcel,
        //         'stok_maksimal' => $stokMax,
        //         'kategori_id'   => $kategori->id,
        //         'pemasok_id'    => $pemasokId,
        //     ]);
            
        //     $this->counter['updated']++; 
        //     return null;
        // }

        // $this->counter['created']++; 
        // return new Obat([
        //     'kode_obat'     => $kodeObat,
        //     'nama_obat'     => $namaObat,
        //     'satuan'        => $satuanEnum,
        //     'harga_offline' => $hargaOffline,
        //     'harga_online'  => $hargaOnline,
        //     'stok'          => $stokExcel,
        //     'stok_maksimal' => $stokMax,
        //     'kategori_id'   => $kategori->id,
        //     'pemasok_id'    => $pemasokId,
        // ]);
    }

    public function rules(): array
    {
        return [
            'product_name' => 'nullable|string|max:255',
            'nama_obat'    => 'nullable|string|max:255',
        ];
    }
}