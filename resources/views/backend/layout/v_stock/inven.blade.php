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
                                <td>{{ $m->created_at ? $m->created_at->format('d/m H:i') : '-' }}</td>
                                
                                <td><strong>{{ ucfirst($m->jenis) }}</strong> - {{ $m->catatan }}</td>
                                
                                <td>{{ ($m->jenis == 'masuk') ? $m->jumlah : '-' }}</td>
                                <td>{{ ($m->jenis == 'keluar') ? $m->jumlah : '-' }}</td>
                                
                                <td class="font-weight-bold">{{ $m->stok_sesudah }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection