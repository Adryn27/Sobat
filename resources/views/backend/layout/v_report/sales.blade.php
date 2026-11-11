@extends('backend.layout.app')
@section('content')

<style>
    input[type="date"]::-webkit-calendar-picker-indicator {
        display: block !important;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236c757d' class='bi bi-calendar' viewBox='0 0 16 16'%3E%3Cpath d='M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 2 0 0 0 1 1h12a1 2 0 0 0 1-1V4H1z'/%3E%3C/svg%3E");
        cursor: pointer;
        opacity: 1 !important;
    }
</style>
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6"><h3 class="mb-0">{{ $judul }}</h3></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
              <li class="breadcrumb-item"><a href="{{ route('backend.beranda') }}">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Sales</li>
            </ol>
          </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="row mb-4">
            <!-- Card Pendapatan -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%); color: white;">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-cash-stack" style="font-size: 3rem; opacity: 0.7;"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0 text-white-50">Total Pendapatan</h5>
                            <h2 class="mb-0 fw-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        
            <!-- Card Transaksi -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm overflow-hidden" style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%); color: white;">
                    <div class="card-body d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="bi bi-cart-check" style="font-size: 3rem; opacity: 0.7;"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0 text-white-50">Total Transaksi</h5>
                            <h2 class="mb-0 fw-bold">{{ $jumlahTransaksi }} Transaksi</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3><b>{{ $subjudul2 }}</b></h3>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-start gap-2 mb-3">
                    <a href="{{ route('backend.cashier.pdf', ['tgl_mulai' => request('tgl_mulai'), 
                        'tgl_selesai' => request('tgl_selesai')]) }}" class="btn btn-danger btn-sm">
                        <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
                    </a>
                </div>
                <form action="{{ route('backend.cashier.report') }}" method="GET" class="mb-4">
                    <div class="d-flex align-items-end gap-2">
                        
                        <div class="input-group input-group-sm" style="width: auto;">
                            <span class="input-group-text bg-light">Periode</span>
                            <input type="date" name="tgl_mulai" value="{{ request('tgl_mulai') }}" class="form-control" style="width: 140px;">
                            <span class="input-group-text">s/d</span>
                            <input type="date" name="tgl_selesai" value="{{ request('tgl_selesai') }}" class="form-control" style="width: 140px;">
                        </div>
                
                        <div class="d-flex gap-1">
                            <button type="submit" class="btn btn-primary btn-sm px-3">
                                <i class="bi bi-filter"></i>
                            </button>
                            <a href="{{ route('backend.cashier.report') }}" class="btn btn-outline-secondary btn-sm px-3">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table id="table" class="table table-bordered table-hover text-center align-middle m-0">
                      <thead>
                        <tr>
                            <th>No</th>
                            <th>No Invoice</th>
                            <th>Tanggal Transaksi</th>
                            <th>Total Harga</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($index as $row)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $row->no_invoice }}</td>
                            <td>{{ $row->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end">Rp {{ number_format($row->total_harga, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection