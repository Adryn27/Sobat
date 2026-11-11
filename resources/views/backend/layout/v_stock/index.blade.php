@extends('backend.layout.app')
@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">{{ $judul }}</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('backend.beranda') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Stock Adjustment</li>
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

                <div class="row align-items-center mb-3">
                    <div class="col-md-9">
                        <form action="{{ route('backend.stock.index') }}" method="GET" class="d-flex align-items-center">
                            <select name="filter" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                                <option value="">-- Semua Periode --</option>
                                <option value="1_hari" {{ request('filter') == '1_hari' ? 'selected' : '' }}>Hari Ini</option>
                                <option value="1_minggu" {{ request('filter') == '1_minggu' ? 'selected' : '' }}>1 Minggu Terakhir</option>
                                <option value="1_bulan" {{ request('filter') == '1_bulan' ? 'selected' : '' }}>1 Bulan Terakhir</option>
                                <option value="3_bulan" {{ request('filter') == '3_bulan' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                                <option value="1_tahun" {{ request('filter') == '1_tahun' ? 'selected' : '' }}>1 Tahun Terakhir</option>
                            </select>
                        </form>
                    </div>
                    <div class="col-md-3 text-end">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahMutasiModal">
                            <i class="fas fa-plus"></i> Tambah Mutasi
                        </button>
                    </div>
                </div>

                <div class="table-responsive mt-3">
                    <table id="table" class="table table-striped table-bordered text-center align-middle">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Tanggal Mutasi</th>
                                <th>Nama Obat</th>
                                <th>Jenis</th>
                                <th>Stok Awal</th>
                                <th>Perubahan</th>
                                <th>Stok Akhir</th>
                                <th>Catatan</th>
                                <th>Operator</th>
                                {{-- <th width="15%">Aksi</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($index as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ date('d-m-Y H:i', strtotime($row->created_at)) }}</td>
                                    <td class="text-start"><b>{{ $row->obat->nama_obat ?? 'Data Dihapus' }}</b></td>
                                    <td>
                                        <span class="badge {{ $row->jenis == 'masuk' ? 'bg-success' : ($row->jenis == 'keluar' ? 'bg-warning text-dark' : 'bg-info text-dark') }}">
                                            {{ ucfirst($row->jenis) }}
                                        </span>
                                    </td>
                                    <td>{{ $row->stok_sebelum }}</td>
                                    <td>{{ $row->jumlah }}</td>
                                    <td>{{ $row->stok_sesudah }}</td>
                                    <td class="text-start">{{ $row->catatan }}</td>
                                    <td>{{ $row->user->nama ?? '-' }}</td>
                                    {{-- <td>
                                        <form action="{{ route('backend.stock.destroy', $row->id) }}" method="POST" style="display: inline-block">
                                            @method('delete') @csrf
                                            <button type="submit" class="btn btn-danger btn-sm show_confirm" data-konf-delete="mutasi ini">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td> --}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Mutasi --}}
<div class="modal fade" id="tambahMutasiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Tambah Mutasi Stok</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('backend.stock.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="fw-bold">Pilih Obat <span class="text-danger">*</span></label>
                        <select id="obatSelect" name="obat_id" class="form-select @error('obat_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Obat --</option>
                            @foreach ($obat as $o)
                                <option value="{{ $o->id }}">{{ $o->nama_obat }} (Stok: {{ $o->stok }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold">Jenis Mutasi <span class="text-danger">*</span></label>
                        <select name="jenis" class="form-select" required>
                            <option value="masuk">Masuk (Restock)</option>
                            <option value="keluar">Keluar (Rusak/Jual/Kadaluarsa)</option>
                            <option value="opname">Opname (Koreksi Fisik)</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold">Jumlah / Stok Fisik Akhir <span class="text-danger">*</span></label>
                        <input type="number" name="jumlah" class="form-control" placeholder="Masukkan jumlah..." required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="fw-bold">Catatan</label>
                        <textarea name="catatan" class="form-control" placeholder="Contoh: Barang rusak, salah hitung, dll"></textarea>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Mutasi</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#obatSelect').select2({
            placeholder: "-- Cari atau Pilih Obat --",
            allowClear: true,
            width: '100%' // Agar lebar select mengikuti lebar container
        });
    });
</script>
@endsection