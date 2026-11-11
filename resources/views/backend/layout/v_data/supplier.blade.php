@extends('backend.layout.app')
@section('content')

    <div class="app-content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <h3 class="mb-0">{{ $judul }}</h3>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
              <li class="breadcrumb-item"><a href="{{ route('backend.beranda') }}">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Supplier</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <div class="app-content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header">
            <h3><b>{{ $subjudul }}</b></h3>
          </div>
          <div class="card-body">
            <div class="col d-flex justify-content-end">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahSupplierModal">
                Tambah
              </button>
            </div>
            <div class="table-responsive table-hover mt-3">
              <table id="table" class="table table-striped table-bordered">
                <thead>
                  <tr>
                      <th>No</th>
                      <th>Nama Pemasok</th>
                      <th>Nama Sales</th>
                      <th>No. Telepon</th>
                      <th>Email</th>
                      <th>Alamat</th>
                      <th>No. Rekening</th>
                      <th style="white-space: nowrap;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($index as $row)
                      <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td><b>{{ $row->nama_pemasok }}</b></td>
                          <td>{{ $row->nama_sales ?? '-' }}</td>
                          <td>{{ $row->no_telp }}</td>
                          <td>{{ $row->email ?? '-' }}</td>
                          <td>{{ $row->alamat ?? '-' }}</td>
                          <td>{{ $row->no_rekening ?? '-' }}</td>
                          <td style="white-space: nowrap;">
                              <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editSupplierModal{{ $row->id }}">
                                <i class="far fa-edit"></i> Edit
                              </button>
                              
                              <form action="{{ route('backend.supplier.destroy', $row->id) }}" method="POST" style="display: inline-block">
                                  @method('delete')
                                  @csrf
                                  <button type="submit" class="btn btn-danger btn-sm show_confirm" data-konf-delete="{{ $row->nama_supplier }}">
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

{{-- Modal Tambah Supplier --}}
<div class="modal fade" id="tambahSupplierModal" tabindex="-1" aria-labelledby="tambahSupplierModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahSupplierModalLabel"><b>Tambah Pemasok</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('backend.supplier.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Nama Supplier/Perusahaan <span class="text-danger">*</span></label>
                                <input type="text" name="nama_pemasok" value="{{ old('nama_pemasok') }}" class="form-control @error('nama_pemasok') is-invalid @enderror" placeholder="Contoh: Apotek Sapphire Farma" required>
                                @error('nama_pemasok') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label class="fw-bold">Nama Sales Agen</label>
                                <input type="text" name="nama_sales" value="{{ old('nama_sales') }}" class="form-control" placeholder="Masukkan nama">
                            </div>
                            <div class="form-group mb-3">
                                <label class="fw-bold">No. Telepon <span class="text-danger">*</span></label>
                                <input type="tel" name="no_telp" value="{{ old('no_telp') }}" class="form-control @error('no_telp') is-invalid @enderror" placeholder="Contoh: 081234567xx" pattern="[0-9]*" inputmode="numeric" required>
                                @error('no_telp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="fw-bold">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="contoh@supplier.com">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label class="fw-bold">No. Rekening Bank</label>
                                <input type="text" name="no_rekening" value="{{ old('no_rekening') }}" class="form-control" placeholder="Contoh: BCA - 12345678 a/n PT. X">
                            </div>
                            <div class="form-group mb-3">
                                <label class="fw-bold">Alamat Kantor</label>
                                <textarea name="alamat" class="form-control" rows="2" placeholder="Alamat lengkap"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit Supplier --}}
@foreach ($index as $row)
    <div class="modal fade" id="editSupplierModal{{ $row->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Edit Pemasok</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('backend.supplier.update', $row->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-bold">Nama Supplier/Perusahaan <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_pemasok" value="{{ old('nama_pemasok', $row->nama_pemasok) }}" class="form-control @error('nama_pemasok') is-invalid @enderror" required>
                                    @error('nama_pemasok') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label class="fw-bold">Nama Sales Agen</label>
                                    <input type="text" name="nama_sales" value="{{ old('nama_sales', $row->nama_sales) }}" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="fw-bold">No. Telepon <span class="text-danger">*</span></label>
                                    <input type="tel" name="no_telp" value="{{ old('no_telp', $row->no_telp) }}" class="form-control @error('no_telp') is-invalid @enderror" pattern="[0-9]*" inputmode="numeric" required>
                                    @error('no_telp') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="fw-bold">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $row->email) }}" class="form-control @error('email') is-invalid @enderror">
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label class="fw-bold">No. Rekening Bank</label>
                                    <input type="text" name="no_rekening" value="{{ old('no_rekening', $row->no_rekening) }}" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label class="fw-bold">Alamat Kantor</label>
                                    <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $row->alamat) }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Perbaharui</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection