<?php

namespace App\Imports;

use App\Models\DetailPenjualan;
use App\Models\Mutasi;
use App\Models\Obat;
use App\Models\Penjualan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
// use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class PenjualanImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $firstRow = $collection->first();
        
        if (!$firstRow) {
            throw new \Exception("Gagal Import! File tidak memiliki baris data.");
        }

        $cleanedCollection = $collection->map(function ($row) {
            $newRow = [];
            foreach ($row as $key => $value) {
                $cleanKey = preg_replace('/_+/', '_', trim($key));
                $newRow[$cleanKey] = $value;
            }
            return $newRow;
        });

        $firstCleaned = $cleanedCollection->first();
        
        $invoiceKey = isset($firstCleaned['order_id']) ? 'order_id' : (isset($firstCleaned['no_invoice']) ? 'no_invoice' : null);
        
        if (!$invoiceKey) {
            throw new \Exception("Gagal Import! Format kolom tidak dikenali. Pastikan terdapat kolom 'order_id' atau 'no_invoice'.");
        }

        $groupedRows = $cleanedCollection->groupBy($invoiceKey);
        
        $invoiceBaru = 0;
        $invoiceDiupdate = 0;

        DB::transaction(function () use ($groupedRows, &$invoiceBaru, &$invoiceDiupdate) {
            foreach ($groupedRows as $noInvoice => $items) {
                
                if (empty($noInvoice) || strtolower(trim($noInvoice)) == 'order_id') {
                    continue;
                }

                $firstItem = $items->first();
                $rawDate = $firstItem['transaction_date'] ?? $firstItem['transactiondate'] ?? $firstItem['tanggal'] ?? $firstItem['tanggal_transaksi'] ?? null;
                //Perubahan
                if (!$rawDate) {
                    throw new \Exception("Gagal Import! Kolom tanggal transaksi tidak ditemukan atau kosong pada Invoice {$noInvoice}.");
                }
                // $tanggalTransaksi = now()->format('Y-m-d H:i:s');

                if ($rawDate) {
                    if (is_numeric($rawDate)) {
                        $tanggalTransaksi = \Carbon\Carbon::instance(
                            \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($rawDate)
                        )->format('Y-m-d H:i:s');
                    } else {
                        $timestamp = strtotime($rawDate);
                        if ($timestamp) {
                            $tanggalTransaksi = date('Y-m-d H:i:s', $timestamp);
                        }
                    }
                }

                $totalHargaTransaksi = 0;
                foreach ($items as $item) {
                    $hargaJual = $item['selling_price'] ?? $item['sellingprice'] ?? $item['harga_jual'] ?? 0;
                    $jumlah    = $item['quantity'] ?? $item['qty'] ?? $item['jumlah'] ?? 0;
                    $totalHargaTransaksi += ($hargaJual * $jumlah);
                }

                $grandTotalExcel = $items->sum(function ($item) {
                    return $item['grand_total'] ?? $item['grandtotal'] ?? $item['bayar'] ?? $item['jumlah_bayar'] ?? 0;
                });

                if ($grandTotalExcel <= 0) {
                    $grandTotalExcel = $totalHargaTransaksi;
                }

                $penjualan = Penjualan::where('no_invoice', $noInvoice)->first();

                if (!$penjualan) {
                    $penjualan = Penjualan::create([
                        'user_id'         => Auth::id(), 
                        'no_invoice'      => $noInvoice, 
                        'total_harga'     => $totalHargaTransaksi,
                        'bayar'           => $grandTotalExcel, 
                        'kembali'         => 0, 
                        'jenis_penjualan' => '1', 
                        'created_at'      => $tanggalTransaksi, 
                        'updated_at'      => $tanggalTransaksi,
                    ]);
                    $invoiceBaru++;
                } else {
                    $penjualan->update([
                        'total_harga' => $totalHargaTransaksi,
                        'bayar'       => $grandTotalExcel,
                        'created_at'  => $tanggalTransaksi, 
                    ]);
                    $invoiceDiupdate++;
                }

                foreach ($items as $item) {
                    $rawSku = $item['merchant_sku'] ?? $item['merchantsku'] ?? $item['kode_obat'] ?? null;
                    $skuCode = $rawSku !== null ? trim(strval($rawSku)) : null;

                    $hargaJual = $item['selling_price'] ?? $item['sellingprice'] ?? $item['harga_jual'] ?? 0;
                    $jumlah    = $item['quantity'] ?? $item['qty'] ?? $item['jumlah'] ?? 0;

                    if (!$skuCode) {
                        throw new \Exception("Gagal Import! Kolom kode SKU obat tidak terdeteksi pada Invoice {$noInvoice}.");
                    }

                    $obat = Obat::where('kode_obat', $skuCode)->first();

                    if (!$obat) {
                        throw new \Exception("Gagal Import! Kode SKU '{$skuCode}' pada Invoice {$noInvoice} tidak terdaftar di sistem Apotek.");
                    }

                    $cekDetail = DetailPenjualan::where('penjualan_id', $penjualan->id)
                                                 ->where('obat_id', $obat->id)
                                                 ->first();

                    if ($cekDetail) {
                        continue; 
                    }

                    if ($obat->stok < $jumlah) {
                        throw new \Exception("Gagal Import! Stok obat {$obat->nama_obat} untuk Invoice {$noInvoice} tidak mencukupi (Sisa stok sistem: {$obat->stok}).");
                    }

                    $subtotalItem = $hargaJual * $jumlah;

                    DetailPenjualan::create([
                        'penjualan_id' => $penjualan->id,
                        'obat_id'      => $obat->id,
                        'harga_jual'   => $hargaJual,
                        'jumlah'       => $jumlah,
                        'subtotal'     => $subtotalItem,
                    ]);
                    //Perubahan
                    $stokSebelum = $obat->stok;
                    $stokSesudah = $stokSebelum - $jumlah;
                    Mutasi::create([
                        'obat_id'      => $obat->id,
                        'user_id'      => Auth::id(),
                        'jumlah'       => $jumlah,
                        'jenis'        => 'keluar',
                        'stok_sebelum' => $stokSebelum,
                        'stok_sesudah' => $stokSesudah,
                        'catatan'      => 'Import Penjualan: ' . $noInvoice,
                        'created_at'   => $tanggalTransaksi,
                        'updated_at'   => $tanggalTransaksi,
                    ]);

                    $obat->decrement('stok', $jumlah);
                }
            }
        });

        session()->flash('import_baru', $invoiceBaru);
        session()->flash('import_update', $invoiceDiupdate);
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ",", 
        ];
    }
}
