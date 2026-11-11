<?php

namespace App\Exports;

use App\Models\Obat;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ObatExport implements FromArray, WithHeadings, WithStyles
{
    protected $request;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function array(): array
    {
        // Ambil data obat beserta relasi kategori dan pemasoknya
        $obats = Obat::with(['kategori', 'pemasok'])->orderBy('nama_obat', 'asc')->get();
        $result = [];

        foreach ($obats as $obat) {
            // Konversi angka ENUM satuan menjadi teks asli
            $satuanTeks = 'Box';
            if ($obat->satuan == '1') {
                $satuanTeks = 'Botol';
            } elseif ($obat->satuan == '2') {
                $satuanTeks = 'Strip';
            }

            // Masukkan data ke dalam baris array Excel
            $result[] = [
                $obat->kode_obat ?? '-',
                $obat->nama_obat ?? '-',
                $obat->kategori?->nama_kategori ?? 'Umum',
                $satuanTeks,
                $obat->harga_offline ?? 0,
                $obat->harga_online ?? 0,
                $obat->stok ?? 0,
                $obat->stok_maksimal ?? 0,
                $obat->pemasok?->nama_pemasok ?? '-'
            ];
        }

        return $result;
    }

    public function headings(): array
    {
        return [
            'merchant_sku',
            'product_name',
            'category',
            'unit',
            'base_price_offline',
            'selling_price_online',
            'stock_available',
            'max_stock_limit',
            'supplier'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '198754'] // Warna hijau sukses
                ]
            ],
        ];
    }
}
