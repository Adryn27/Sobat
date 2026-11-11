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
            <li class="breadcrumb-item active" aria-current="page">History</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
    
    <div class="app-content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header"><h3><b>{{ $subjudul }}</b></h3></div>
          <div class="card-body">
            
            <div class="d-flex justify-content-start gap-2 mb-3">
                <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalImportExcel">
                    <i class="bi bi-box-arrow-in-up me-1"></i> Import
                </button>
                
                <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalExportExcel">
                    <i class="bi bi-box-arrow-down me-1"></i> Export
                </button>
            </div>
            <div class="row align-items-center mb-3">
                <div class="col-md-6">
                    <form action="{{ route('backend.cashier.history') }}" method="GET" class="d-flex align-items-center">
                        <select name="filter" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                            <option value="">-- Semua Periode --</option>
                            <option value="1_hari" {{ request('filter') == '1_hari' ? 'selected' : '' }}>1 Hari Terakhir</option>
                            <option value="1_minggu" {{ request('filter') == '1_minggu' ? 'selected' : '' }}>1 Minggu Terakhir</option>
                            <option value="1_bulan" {{ request('filter') == '1_bulan' ? 'selected' : '' }}>1 Bulan Terakhir</option>
                            <option value="3_bulan" {{ request('filter') == '3_bulan' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                            <option value="1_tahun" {{ request('filter') == '1_tahun' ? 'selected' : '' }}>1 Tahun Terakhir</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
              <table id="table" class="table table-bordered table-hover text-center align-middle m-0">
                <thead>
                  <tr>
                      <th>No</th>
                      <th>No Invoice</th>
                      <th>Tanggal Transaksi</th>
                      <th>Total Harga</th>
                      <th>Jenis Penjualan</th>
                      <th>Operator</th>
                      <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($index as $row)
                      <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td class="fw-bold">{{ $row->no_invoice }}</td>
                          <td>{{ date('d-m-Y H:i', strtotime($row->created_at)) }}</td>
                          <td class="text-end px-3">Rp {{ number_format($row->total_harga, 0, ',', '.') }}</td>
                          <td>
                                @if($row->jenis_penjualan == '0')
                                    <span class="badge bg-primary">Offline</span>
                                @elseif($row->jenis_penjualan == '1')
                                    <span class="badge bg-success">Online</span>
                                @endif
                          </td>
                          <td>{{ $row->user->nama ?? '-' }}</td>
                          <td>
                              <button class="btn btn-info btn-sm text-white px-3" data-bs-toggle="modal" data-bs-target="#detailHistoryModal{{ $row->id }}">
                                Detail
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

{{-- MODAL Upload Berkas & Preview File --}}
<div class="modal fade" id="modalImportExcel" tabindex="-1" aria-labelledby="modalImportExcelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0">
                <h6 class="modal-title fw-bold text-dark" id="modalImportExcelLabel">
                    <i class="bi bi-box-arrow-in-up text-success me-1"></i> Upload Berkas Transaksi 
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('backend.cashier.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="border p-4 text-center mb-2 position-relative zone-upload-excel" style="border-style: dashed !important; border-color: #cbd5e1 !important; background-color: #f8fafc;">
                        <input type="file" name="file_excel" id="excel-file-input" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" accept=".xlsx, .xls, .csv" style="cursor: pointer; z-index: 2;" required>
                        
                        <div id="preview-state-empty">
                            <i class="bi bi-cloud-arrow-up text-secondary fs-2 d-block mb-2"></i>
                            <span class="d-block fw-medium text-dark visual-clean-text">Pilih file Excel / CSV</span>
                            <small class="text-muted d-block">atau drag & drop berkas ke kotak ini</small>
                        </div>
                        
                        <div id="preview-state-active" style="display: none;">
                            <i class="bi bi-file-earmark-spreadsheet text-success fs-2 d-block mb-2"></i>
                            <span id="nama-file-preview" class="d-block fw-bold text-dark text-truncate px-2">nama_file.xlsx</span>
                            <small id="ukuran-file-preview" class="text-muted d-block mt-1">0 KB</small>
                        </div>
                    </div>

                    <div class="text-muted mb-2 lh-sm p-2 rounded alert alert-warning border border-warning-subtle small">
                        <span class="fw-bold"><i class="bi bi-info-circle-fill"></i> Susunan Header Kolom:</span><br>
                        <code>order_id | transaction_date | merchant_sku | quantity | selling_price | grand_total</code>
                    </div>
                </div>
                
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" id="btn-reset-excel" class="btn btn-sm btn-light border text-secondary px-3" style="display: none;">Reset File</button>
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-success px-3">
                        <i class="bi bi-cloud-arrow-up me-1"></i> Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL export berkas --}}
<div class="modal fade" id="modalExportExcel" tabindex="-1" aria-labelledby="modalExportExcelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0">
                <h6 class="modal-title fw-bold text-dark" id="modalExportExcelLabel">
                    <i class="bi bi-box-arrow-down text-primary me-1"></i> Export Berkas Transaksi
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('backend.cashier.export') }}" method="GET">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="jenis_penjualan" class="form-label fw-bold text-dark small">Jenis Penjualan</label>
                        <select name="jenis_penjualan" id="jenis_penjualan" class="form-select form-select-sm" required>
                            <option value="semua">-- Semua Transaksi (Online & Offline) --</option>
                            <option value="1">Online</option>
                            <option value="0">Offline</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-2">
                            <label for="tgl_mulai" class="form-label fw-bold text-dark small">Dari Tanggal</label>
                            <input type="date" name="tgl_mulai" id="tgl_mulai" class="form-control form-control-sm">
                        </div>
                        <div class="col-6 mb-2">
                            <label for="tgl_selesai" class="form-label fw-bold text-dark small">Sampai Tanggal</label>
                            <input type="date" name="tgl_selesai" id="tgl_selesai" class="form-control form-control-sm">
                        </div>
                    </div>
                    
                    <div class="text-muted mt-3 mb-0 lh-sm p-2 rounded alert alert-info border border-info-subtle small" style="font-size: 0.775rem;">
                        <span class="fw-bold"><i class="bi bi-info-circle-fill"></i> Catatan Filter:</span><br>
                        Kosongkan parameter tanggal jika Anda ingin mendownload seluruh riwayat data database.
                    </div>
                </div>
                
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary px-3">
                        <i class="bi bi-download me-1"></i> Export
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Detail Transaksi --}}
@foreach ($index as $row)
    <div class="modal fade" id="detailHistoryModal{{ $row->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold text-dark">Detail Transaksi #{{ $row->no_invoice }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-2 mb-3 bg-light p-3 mx-1 rounded border">
                        <div class="col-md-4">
                            <small class="text-muted d-block fw-semibold" style="font-size: 11px;">WAKTU TRANSAKSI</small>
                            <span class="text-dark fw-bold" style="font-size: 13px;">
                                <i class="bi bi-clock text-secondary me-1"></i> {{ date('d M Y - H:i', strtotime($row->created_at)) }} WIB
                            </span>
                        </div>
                        <div class="col-md-4 border-start ps-md-3">
                            <small class="text-muted d-block fw-semibold" style="font-size: 11px;">OPERATOR</small>
                            <span class="text-dark fw-bold" style="font-size: 13px;">
                                <i class="bi bi-person-circle text-secondary me-1"></i> {{ $row->user->nama ?? '-' }}
                            </span>
                        </div>
                        <div class="col-md-4 border-start ps-md-3">
                            <small class="text-muted d-block fw-semibold mb-1" style="font-size: 11px;">JENIS PENJUALAN</small>
                            @if($row->jenis_penjualan == '1')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 11.5px;">
                                    <i class="bi bi-globe me-1"></i> Online (E-Commerce)
                                </span>
                            @else
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="font-size: 11.5px;">
                                    <i class="bi bi-shop me-1"></i> Offline (Langsung)
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover text-center align-middle m-0" style="font-size: 13px;">
                            <thead class="table-light text-uppercase" style="font-size: 11px; letter-spacing: 0.5px;">
                                <tr>
                                    <th style="width: 5%">No</th>
                                    <th style="width: 15%">Kode</th>
                                    <th style="width: 40%">Nama Obat</th>
                                    <th style="width: 15%">Harga Satuan</th>
                                    <th style="width: 10%">Jumlah</th>
                                    <th style="width: 15%">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($row->detailPenjualan) && $row->detailPenjualan->count() > 0)
                                    @foreach($row->detailPenjualan as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-secondary fw-semibold">{{ $item->obat->kode_obat ?? '-' }}</td>
                                            <td class="text-start text-dark fw-bold">{{ $item->obat->nama_obat }}</td>
                                            <td class="text-end px-3">Rp {{ number_format($item->harga_jual, 0, ',', '.') }}</td>
                                            <td class="fw-semibold">
                                                {{ $item->jumlah }}
                                                <small class="text-muted text-lowercase">
                                                    @if(($item->obat->satuan ?? '') == '0') Box
                                                    @elseif(($item->obat->satuan ?? '') == '1') Botol
                                                    @elseif(($item->obat->satuan ?? '') == '2') Strip
                                                    @else pcs
                                                    @endif
                                                </small>
                                            </td>
                                            <td class="text-end px-3 fw-bold text-dark">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="6" class="text-muted py-3">Tidak ada detail item pada rekam data ini.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <div class="row justify-content-end mt-3 mx-0">
                        <div class="col-md-5 p-3 rounded border bg-light">
                            <table class="table table-sm table-borderless m-0" style="font-size: 13px;">
                                <tr>
                                    <td class="text-start text-secondary">Total Belanja:</td>
                                    <td class="text-end fw-bold text-dark fs-6">Rp {{ number_format($row->total_harga, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-start text-secondary">Jumlah Bayar:</td>
                                    <td class="text-end text-dark">Rp {{ number_format($row->bayar, 0, ',', '.') }}</td>
                                </tr>
                                <tr class="border-top">
                                    <td class="text-start fw-bold text-dark pt-2">Kembalian:</td>
                                    <td class="text-end fw-bold text-success pt-2 fs-6">Rp {{ number_format($row->kembali, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-transparent border-top-0 pt-0">
                    <button type="button" class="btn btn-secondary px-4 btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#excel-file-input').on('change', function(e) {
        let file = e.target.files[0];
        
        if (file) {
            let namaFile = file.name;
            let ukuranFile = (file.size / 1024).toFixed(1) + ' KB';
            
            $('#nama-file-preview').text(namaFile);
            $('#ukuran-file-preview').text(ukuranFile);
            
            $('#preview-state-empty').hide();
            $('#preview-state-active').show();
            $('#btn-reset-excel').show();
            
            $('.zone-upload-excel').css('background-color', '#f0fdf4').css('border-color', '#10b981');
        }
    });

    $('#btn-reset-excel').on('click', function(e) {
        e.preventDefault();
        $('#excel-file-input').val('');
        
        $('#preview-state-active').hide();
        $('#btn-reset-excel').hide();
        $('#preview-state-empty').show();
        
        $('.zone-upload-excel').css('background-color', '#f8fafc').css('border-color', '#cbd5e1');
    });
});
</script>

@endsection