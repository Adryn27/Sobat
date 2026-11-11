<?php

namespace App\Exports;

use App\Models\Penjualan;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PenjualanExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $request;
    public function __construct($request)
    {
        $this->request = $request;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {
        $query = Penjualan::with(['detailPenjualan.obat', 'user']);

        if ($this->request->filled('jenis_penjualan') && $this->request->jenis_penjualan !== 'semua') {
            $query->where('jenis_penjualan', $this->request->jenis_penjualan);
        }

        if ($this->request->filled('tgl_mulai')) {
            $query->whereDate('created_at', '>=', $this->request->tgl_mulai);
        }

        if ($this->request->filled('tgl_selesai')) {
            $query->whereDate('created_at', '<=', $this->request->tgl_selesai);
        }

        $penjualans = $query->orderBy('created_at', 'desc')->get();
        $result = [];

        foreach ($penjualans as $penjualan) {
            
            $jenisText = $penjualan->jenis_penjualan == '1' ? 'Online' : 'Offline';
            
            $tanggalClean = $penjualan->created_at ? \Carbon\Carbon::parse($penjualan->created_at)->format('Y-m-d H:i:s') : '-';

            if ($penjualan->detailPenjualan->count() > 0) {
                foreach ($penjualan->detailPenjualan as $detail) {
                    $result[] = [
                        $penjualan->no_invoice ?? '-',
                        $tanggalClean,
                        $jenisText,
                        $penjualan->user->nama ?? '-',
                        $detail->obat->kode_obat ?? '-',
                        $detail->obat->nama_obat ?? '-',
                        $detail->harga_jual ?? 0,
                        $detail->jumlah ?? 0,
                        $detail->subtotal ?? 0,
                        $penjualan->total_harga ?? 0,
                        $penjualan->bayar ?? 0
                    ];
                }
            } else {
                $result[] = [
                    $penjualan->no_invoice ?? '-',
                    $tanggalClean,
                    $jenisText,
                    $penjualan->user->name ?? '-',
                    '-',
                    '-',
                    0,
                    0,
                    0,
                    $penjualan->total_harga ?? 0,
                    $penjualan->bayar ?? 0
                ];
            }
        }

        return $result;
    }

    public function headings(): array
    {
        return [
            'No Invoice',
            'Tanggal Transaksi',
            'Jenis Transaksi',
            'Operator',
            'Kode Obat',
            'Nama Obat',
            'Harga Jual',
            'Quantity',
            'Subtotal Item',
            'Total Belanja',
            'Jumlah Bayar'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '198754'] 
                ]
            ],
        ];
    }
}
