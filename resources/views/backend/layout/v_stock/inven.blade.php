@extends('backend.layout.app')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">{{ $judul }}</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('backend.beranda') }}">Home</a></li>
                    <li class="breadcrumb-item active">Inventory</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-4">
                <a href="{{ route('backend.stock.inven') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); color: white;">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-box-seam" style="font-size: 3rem; opacity: 0.7;"></i>
                            <div class="ms-3">
                                <h5 class="mb-0 text-white-50">Total Jenis Obat</h5>
                                <h2 class="mb-0 fw-bold">{{ $jumlahObat }} Jenis</h2>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        
            <div class="col-md-4">
                <a href="{{ route('backend.stock.inven', ['filter' => 'kritis']) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #f6c23e 0%, #d4a017 100%); color: white; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle" style="font-size: 3rem; opacity: 0.7;"></i>
                            <div class="ms-3">
                                <h5 class="mb-0 text-white-50">Stok Kritis</h5>
                                <h2 class="mb-0 fw-bold">{{ $jumlahStokKritis }} Item</h2>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        
            <div class="col-md-4">
                <a href="{{ route('backend.stock.inven', ['filter' => 'kosong']) }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%); color: white; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-x-circle" style="font-size: 3rem; opacity: 0.7;"></i>
                            <div class="ms-3">
                                <h5 class="mb-0 text-white-50">Stok Kosong</h5>
                                <h2 class="mb-0 fw-bold">{{ $jumlahStokKosong }} Item</h2>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        @php
            use Carbon\Carbon;
            $batasWaktuAman = Carbon::now()->addMonths(3);
            $shortExpiryBatches = \App\Models\BatchObat::with('obat')
                ->where('stok', '>', 0)
                ->where('expired_date', '<=', $batasWaktuAman)
                ->orderBy('expired_date', 'asc')
                ->get();
        @endphp

        <div class="card mb-4 border-warning shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-dark">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Peringatan Kadaluarsa
                </h5>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover text-center align-middle mb-0">
                        <thead class="table-warning">
                            <tr>
                                <th width="5%">No</th>
                                <th class="text-start">Nama Obat</th>
                                <th>Nomor Batch</th>
                                <th>Tanggal Expired</th>
                                <th>Sisa Waktu</th>
                                <th>Sisa Stok Batch</th>
                                <th>Status</th>
                                <th width="12%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($shortExpiryBatches as $b)
                                @php
                                    $tglExp = Carbon::parse($b->expired_date)->startOfDay();
                                    $sekarang = Carbon::now()->startOfDay();
                                    $selisihHari = $sekarang->diffInDays($tglExp, false);
                                    $isExpired = $selisihHari < 0;
                                @endphp
                                <tr class="{{ $isExpired ? 'table-danger' : '' }}">
                                    <td class="fw-semibold">{{ $loop->iteration }}</td>
                                    <td class="text-start">
                                        <span class="fw-bold text-dark">{{ $b->obat->nama_obat ?? '-' }}</span>
                                        @if(!empty($b->obat->kode_obat))
                                            <br><small class="text-muted">{{ $b->obat->kode_obat }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border font-monospace px-2 py-1">
                                            {{ $b->nomor_batch ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold">
                                        {{ $tglExp->format('d-m-Y') }}
                                    </td>
                                    <td>
                                        @if ($isExpired)
                                            <span class="text-danger fw-bold">
                                                <i class="bi bi-x-circle-fill me-1"></i>Lewat {{ abs(floor($selisihHari)) }} Hari
                                            </span>
                                        @elseif ($selisihHari == 0)
                                            <span class="text-danger fw-bold">
                                                <i class="bi bi-exclamation-circle-fill me-1"></i>Hari Ini!
                                            </span>
                                        @else
                                            <span class="fw-bold {{ $selisihHari <= 30 ? 'text-danger' : 'text-dark' }}">
                                                <i class="bi bi-clock me-1"></i>{{ floor($selisihHari) }} Hari lagi
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="fw-bold fs-6">{{ $b->stok }}</span> <small class="text-muted">pcs</small>
                                    </td>
                                    <td>
                                        @if ($isExpired)
                                            <span class="badge rounded-pill bg-danger px-3 py-1">Kadaluarsa</span>
                                        @else
                                            <span class="badge rounded-pill bg-warning text-dark px-3 py-1">Mendekati Kadaluarsa</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('backend.stock.index', [
                                            'obat_id'  => $b->obat_id,
                                            'batch_id' => $b->id,
                                            'jumlah'   => $b->stok,
                                        ]) }}" 
                                        class="btn btn-sm {{ $isExpired ? 'btn-danger' : 'btn-primary' }} rounded-pill px-3 shadow-sm">
                                            Pilih <i class="bi bi-arrow-right-short ms-1"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-muted py-4">
                                        <i class="bi bi-check-circle-fill text-success me-1"></i>
                                        Tidak ada obat yang mendekati masa kedaluwarsa. Semua stok aman!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- <div class="card mb-4 border-warning">
            <div class="card-header text-dark d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><b><i class="fas fa-exclamation-triangle"></i> Peringatan Kadaluarsa</b></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-center align-middle">
                        <thead>
                            <tr class="table-warning">
                                <th>No</th>
                                <th>Nama Obat</th>
                                <th>Nomor Batch</th>
                                <th>Tanggal Expired</th>
                                <th>Sisa Waktu</th>
                                <th>Sisa Stok Batch</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                use Carbon\Carbon;
                                $batasWaktuAman = Carbon::now()->addMonths(3);
                                $shortExpiryBatches = \App\Models\BatchObat::with('obat')
                                    ->where('stok', '>', 0)
                                    ->where('expired_date', '<=', $batasWaktuAman)
                                    ->orderBy('expired_date', 'asc')
                                    ->get();
                            @endphp
                            
                            @forelse ($shortExpiryBatches as $b)
                                @php
                                    $tglExp = Carbon::parse($b->expired_date)->startOfDay();
                                    $sekarang = Carbon::now()->startOfDay();
                                    $selisihHari = $sekarang->diffInDays($tglExp, false);
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-start"><b>{{ $b->obat->nama_obat }}</b></td>
                                    <td>{{ $b->nomor_batch ?? '-' }}</td>
                                    <td>{{ date('d-m-Y', strtotime($b->expired_date)) }}</td>
                                    <td>
                                        @if ($selisihHari < 0)
                                            <span class="text-danger fw-bold">Sudah Lewat (Expired)</span>
                                        @elseif ($selisihHari == 0)
                                            <span class="text-danger fw-bold">Hari Ini!</span>
                                        @else
                                            <span class="fw-bold {{ $selisihHari <= 30 ? 'text-warning text-dark' : '' }}">{{ floor($selisihHari) }} Hari lagi</span>
                                        @endif
                                    </td>
                                    <td>{{ $b->stok }} pcs</td>
                                    <td>
                                        @if ($selisihHari < 0)
                                            <span class="badge bg-danger">Kadaluarsa</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Mendekati Kadaluarsa</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-muted py-3">Tidak ada obat yang mendekati masa kedaluwarsa. Semua stok aman!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div> --}}

        <div class="card">
            <div class="card-header">
                <h3><b>{{ $subjudul }}</b></h3>
            </div>
            <div class="card-body">
                <div class="d-flex gap-2 mb-3">
                    <a href="{{ route('backend.stock.pdf') }}" class="btn btn-secondary btn-sm" target="_blank">
                        <i class="bi bi-printer me-1"></i> Print Form Stock Opname
                    </a>
                </div>

                <div class="table-responsive">
                    <table id="table" class="table table-bordered table-hover text-center align-middle m-0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Obat</th>
                                <th>Stok Terkini</th>
                                <th>Status</th>
                                <th>Riwayat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($index as $row)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-start">{{ $row->nama_obat }}</td>
                                <td class="fw-bold">{{ $row->stok }}</td>
                                <td>
                                    @if($row->stok == 0)
                                        <span class="badge bg-dark">Kosong</span>
                                    @elseif ($row->stok < ($row->stok_maksimal * 0.5))
                                        <span class="badge bg-danger">Kritis</span>
                                    @else
                                        <span class="badge bg-success">Aman</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#modal{{ $row->id }}">
                                        <i class="bi bi-clock-history"></i> Detail
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal --}}
@foreach ($index as $row)
<div class="modal fade" id="modal{{ $row->id }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header"><h5>Riwayat: {{ $row->kode_obat }} - {{ $row->nama_obat }} 
                @if ($row->satuan == '0')
                    <span class="badge bg-light text-dark border" >Box</span>
                @elseif ($row->satuan == '1')
                    <span class="badge bg-light text-dark border" >Botol</span>
                @else
                    <span class="badge bg-light text-dark border" >Strip</span>
                @endif
            </h5></div>
            <div class="modal-body">
                <table class="table table-bordered table-sm">
                    <thead class="table-primary">
                        <tr><th>TGL</th><th>KETERANGAN</th><th>MASUK</th><th>KELUAR</th><th>SISA</th></tr>
                    </thead>
                    <tbody>
                        @foreach($row->mutasi as $m)
                            <tr class="{{ $m->jenis == 'opname' ? 'table-warning' : '' }}">
                                <td>{{ $m->created_at ? $m->created_at->format('d/m/Y H:i') : '-' }}</td>
                                
                                <td class="text-start">
                                    @if($m->jenis)
                                        <strong>{{ ucfirst($m->jenis) }}</strong> - 
                                    @endif
                                    
                                    {{-- Tampilkan catatan aslinya --}}
                                    {{ $m->catatan }}
                                    
                                    
                                </td>
                                
                                <td>{{ ($m->jenis == 'masuk') ? $m->jumlah : '-' }}</td>
                                <td>{{ ($m->jenis == 'keluar') ? $m->jumlah : '-' }}</td>
                                
                                <td class="fw-bold">{{ $m->stok_sesudah }}</td>
                            </tr>
                        @endforeach
                        {{-- @foreach($row->mutasi as $m)
                            <tr class="{{ $m->jenis == 'opname' ? 'table-warning' : '' }}">
                                <td>{{ $m->created_at ? $m->created_at->format('d/m H:i') : '-' }}</td>
                                
                                <td><strong>{{ ucfirst($m->jenis) }}</strong> - {{ $m->catatan }}</td>
                                
                                <td>{{ ($m->jenis == 'masuk') ? $m->jumlah : '-' }}</td>
                                <td>{{ ($m->jenis == 'keluar') ? $m->jumlah : '-' }}</td>
                                
                                <td class="font-weight-bold">{{ $m->stok_sesudah }}</td>
                            </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection