@extends('backend.layout.app')
@section('content')

    <div class="app-content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6"><h3 class="mb-0">{{ $judul }}</h3></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
              <li class="breadcrumb-item"><a href="{{ route('backend.beranda') }}">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Product</li>
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
            <div class="col d-flex justify-content-between align-items-center">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalImportExcel">
                        <i class="bi bi-box-arrow-in-up me-1"></i> Import
                    </button>
                    
                    <a href="{{ route('backend.product.export') }}" class="btn btn-primary shadow-sm">
                        <i class="bi bi-box-arrow-down me-1"></i> Export
                    </a>
                </div>
            
                <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahObatModal">
                    <i class="fas fa-plus me-1"></i> Tambah
                </button>
            </div>
            <div class="table-responsive table-hover mt-3">
              <table id="table" class="table table-striped table-bordered text-center align-middle">
                <thead>
                  <tr>
                      <th>No</th>
                      <th>SKU/Kode</th>
                      <th>Nama Obat</th>
                      <th>Kategori</th>
                      <th>Satuan</th>
                      <th>Harga Offline</th>
                      <th>Harga Online</th>
                      <th>Stok Tersedia</th>
                      <th>Stok Maks</th>
                      <th>Pemasok</th>
                      <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($index as $row)
                      <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td><span class="badge bg-dark">{{ $row->kode_obat }}</span></td>
                          <td class="text-start"><b>{{ $row->nama_obat }}</b></td>
                          <td >{{ $row->kategori->nama_kategori}}</td>
                          <td>
                            @if($row->satuan == '0')
                                Box
                            @elseif($row->satuan == '1')
                                Botol
                            @elseif($row->satuan == '2')
                                Strip
                            @endif
                          </td>
                          <td>Rp {{ number_format($row->harga_offline, 0, ',', '.') }}</td>
                          <td>Rp {{ number_format($row->harga_online, 0, ',', '.') }}</td>
                          <td><b>{{ $row->stok }}</b></td>
                          <td>{{ $row->stok_maksimal }}</td>
                          <td>{{ $row->pemasok->nama_pemasok ?? '-'}}</td>
                          <td style="white-space: nowrap;">
                              <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editObatModal{{ $row->id }}">
                                <i class="far fa-edit"></i> Edit
                              </button>
                              <form action="{{ route('backend.product.destroy', $row->id) }}" method="POST" style="display: inline-block">
                                  @method('delete') @csrf
                                  <button type="submit" class="btn btn-danger btn-sm show_confirm" data-konf-delete="{{ $row->nama_obat }}">
                                    <i class="fas fa-trash"></i> Hapus
                                  </button>
                              </form>
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

{{-- Modal Tambah Obat --}}
<div class="modal fade" id="tambahObatModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Tambah Data Obat</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('backend.product.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label class="fw-bold">Kode Obat / SKU <span class="text-danger">*</span></label>
                                <input type="text" name="kode_obat" class="form-control @error('kode_obat') is-invalid @enderror" value="{{ old('kode_obat') }}" placeholder="Masukkan Kode Obat/SKU" required>
                                @error('kode_obat')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-2">
                                <label class="fw-bold">Nama Obat & Varian <span class="text-danger">*</span></label>
                                <input type="text" name="nama_obat" class="form-control @error('nama_obat') is-invalid @enderror" value="{{ old('nama_obat') }}" placeholder="Masukkan Nama Obat & Varian" required>
                                @error('nama_obat')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-2">
                                <label class="fw-bold">Kategori <span class="text-danger">*</span></label>
                                <select name="kategori_id" class="form-control @error('kategori_id') is-invalid @enderror" required>
                                    <option value="">- Pilih Kategori -</option>
                                    @foreach($kategori as $kat)
                                        <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-2">
                                <label class="fw-bold">Satuan Jual <span class="text-danger">*</span></label>
                                <select name="satuan" class="form-control @error('satuan') is-invalid @enderror" required>
                                    <option value="">- Pilih Satuan -</option>
                                    <option value="0" {{ old('satuan') == 'Box' ? 'selected' : '' }}>Box</option>
                                    <option value="1" {{ old('satuan') == 'Botol' ? 'selected' : '' }}>Botol</option>
                                    <option value="2" {{ old('satuan') == 'Strip' ? 'selected' : '' }}>Strip</option>
                                </select>
                                @error('satuan')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label class="fw-bold">Harga Jual Offline (Apotek) <span class="text-danger">*</span></label>
                                <input type="number" name="harga_offline" class="form-control @error('harga_offline') is-invalid @enderror" value="{{ old('harga_offline') }}" placeholder="Masukkan Harga Jual Offline" required>
                                @error('harga_offline')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-2">
                                <label class="fw-bold">Harga Jual Online (Marketplace) <span class="text-danger">*</span></label>
                                <input type="number" name="harga_online" class="form-control @error('harga_online') is-invalid @enderror" value="{{ old('harga_online') }}" placeholder="Masukkan Harga Jual Online" required>
                                @error('harga_online')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-2">
                                <label class="fw-bold">Stok Awal <span class="text-danger">*</span></label>
                                <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok', 0) }}" required>
                                @error('stok')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group mb-2">
                                <label class="fw-bold">Kapasitas Stok Maksimal <span class="text-danger">*</span></label>
                                <input type="number" name="stok_maksimal" class="form-control @error('stok_maksimal') is-invalid @enderror" value="{{ old('stok_maksimal', 100) }}" required>
                                @error('stok_maksimal')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <div class="form-group mb-2">
                                <label class="fw-bold">Pemasok <span class="text-danger">*</span></label>
                                <select name="pemasok_id" class="form-control @error('pemasok_id') is-invalid @enderror" required>
                                    <option value="">- Pilih Pemasok -</option>
                                    @foreach($pemasok as $sup)
                                        <option value="{{ $sup->id }}" {{ old('pemasok_id') == $sup->id ? 'selected' : '' }}>{{ $sup->nama_pemasok }}</option>
                                    @endforeach
                                </select>
                                @error('pemasok_id')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Obat</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit Obat --}}
@foreach ($index as $row)
    <div class="modal fade" id="editObatModal{{ $row->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Edit Data Obat</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('backend.product.update', $row->id) }}" method="POST">
                        @method('PUT') @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label class="fw-bold text-muted">Kode Obat / SKU</label>
                                    <input type="text" class="form-control bg-light" value="{{ $row->kode_obat }}" disabled>
                                </div>
                                <div class="form-group mb-2">
                                    <label class="fw-bold">Nama Obat & Varian <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_obat" value="{{ old('nama_obat', $row->nama_obat) }}" class="form-control @error('nama_obat') is-invalid @enderror" required>
                                    @error('nama_obat')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-2">
                                    <label class="fw-bold">Kategori <span class="text-danger">*</span></label>
                                    <select name="kategori_id" class="form-control @error('kategori_id') is-invalid @enderror" required>
                                        @foreach($kategori as $kat)
                                            <option value="{{ $kat->id }}" {{ old('kategori_id', $row->kategori_id) == $kat->id ? 'selected' : '' }}>
                                                {{ $kat->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kategori_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-2">
                                    <label class="fw-bold">Satuan Jual <span class="text-danger">*</span></label>
                                    <select name="satuan" class="form-control @error('satuan') is-invalid @enderror" required>
                                        <option value="" {{ old('satuan', $row->satuan) == '' ? 'selected' : '' }}>- Pilih Satuan -</option>
                                        <option value="0" {{ old('satuan', $row->satuan) == '0' ? 'selected' : '' }}>Box</option>
                                        <option value="1" {{ old('satuan', $row->satuan) == '1' ? 'selected' : '' }}>Botol</option>
                                        <option value="2" {{ old('satuan', $row->satuan) == '2' ? 'selected' : '' }}>Strip</option>
                                    </select>
                                    @error('satuan')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label class="fw-bold">Harga Jual Offline (Apotek) <span class="text-danger">*</span></label>
                                    <input type="number" name="harga_offline" value="{{ old('harga_offline', $row->harga_offline) }}" class="form-control @error('harga_offline') is-invalid @enderror" required>
                                    @error('harga_offline')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group mb-2">
                                    <label class="fw-bold">Harga Jual Online (Marketplace) <span class="text-danger">*</span></label>
                                    <input type="number" name="harga_online" value="{{ old('harga_online', $row->harga_online) }}" class="form-control @error('harga_online') is-invalid @enderror" required>
                                    @error('harga_online')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="alert alert-secondary py-2 mb-2" style="font-size: 13px;">
                                    <i class="bi bi-lock-fill"></i> Stok (<b>Saat ini: {{ $row->stok }}</b>)
                                </div>
                                <div class="form-group mb-2">
                                    <label class="fw-bold">Kapasitas Stok Maksimal <span class="text-danger">*</span></label>
                                    <input type="number" name="stok_maksimal" value="{{ old('stok_maksimal', $row->stok_maksimal) }}" class="form-control @error('stok_maksimal') is-invalid @enderror" required>
                                    @error('stok_maksimal')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="form-group mb-2">
                                    <label class="fw-bold">Pemasok <span class="text-danger">*</span></label>
                                    <select name="pemasok_id" class="form-control @error('pemasok_id') is-invalid @enderror" required>
                                        @foreach($pemasok as $sup)
                                            <option value="" {{ old('pemasok_id', $row->pemasok_id) == '' ? 'selected' : '' }}>- Pilih Pemasok -</option>
                                            <option value="{{ $sup->id }}" {{ old('pemasok_id', $row->pemasok_id) == $sup->id ? 'selected' : '' }}>{{ $sup->nama_pemasok }}</option>
                                        @endforeach
                                    </select>
                                    @error('pemasok_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary">Perbaharui</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

{{-- Modal Import Obat --}}
<div class="modal fade" id="modalImportExcel" tabindex="-1" aria-labelledby="modalImportExcel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0">
                <h6 class="modal-title fw-bold text-dark" id="modalImportExcel">
                    <i class="bi bi-box-arrow-in-up text-success me-1"></i> Upload Berkas Data Obat
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="{{ route('backend.product.import') }}" method="POST" enctype="multipart/form-data">
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
                        <code>merchant_sku</code> | <code>product_name</code> | <code>category</code> | <code>unit</code> | <code>base_price</code> | <code>selling_price</code> | <code>stock_available</code>
                    </div>
                </div>
                
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" id="btn-reset-excel" class="btn btn-sm btn-light border text-secondary px-3" style="display: none;">Reset File</button>
                    <button type="button" class="btn btn-sm btn-secondary px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-success px-3">
                        <i class="bi bi-cloud-arrow-up me-1"></i> Mulai Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputStok = document.querySelector('input[name="stok"]');
        const inputMax = document.querySelector('input[name="stok_maksimal"]');
    
        inputStok.addEventListener('input', function() {
            let stok = parseInt(this.value) || 0;
            let currentMax = parseInt(inputMax.value) || 100;
    
            if (stok > currentMax) {
                inputMax.value = stok;
            }
        });
    });
</script>
@endsection