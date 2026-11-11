@extends('backend.layout.app')
@section('content')

    <div class="app-content-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6"><h3 class="mb-0">{{ $judul }}</h3></div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
              <li class="breadcrumb-item"><a href="{{ route('backend.beranda') }}">Home</a></li>
              <li class="breadcrumb-item active" aria-current="page">Category</li>
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
            <div class="col d-flex justify-content-end">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahKategoriModal">
                Tambah
              </button>
            </div>
            
            <div class="table-responsive mt-3">
              <table id="table" class="table table-striped table-bordered text-center align-middle">
                <thead>
                  <tr>
                      <th width="10%">No</th>
                      <th>Nama Kategori Obat</th>
                      <th width="25%">Jumlah Item Obat</th>
                      <th width="25%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($index as $row)
                      <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td class="text-start"><b>{{ $row->nama_kategori }}</b></td>
                          <td><span class="badge bg-info text-dark">{{ $row->obat_count > 0 ? $row->obat_count : '0' }} Item</span></td>
                          <td>
                              <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editKategoriModal{{ $row->id }}">
                                <i class="far fa-edit"></i> Edit
                              </button>
                              
                              <form action="{{ route('backend.category.destroy', $row->id) }}" method="POST" style="display: inline-block">
                                  @method('delete') @csrf
                                  <button type="submit" class="btn btn-danger btn-sm show_confirm" data-konf-delete="{{ $row->nama_kategori }}">
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

{{-- Modal Tambah Kategori --}}
<div class="modal fade" id="tambahKategoriModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Tambah Kategori</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('backend.category.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label class="fw-bold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror" placeholder="Masukkan Kategori" required>
                        @error('nama_kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit Kategori --}}
@foreach ($index as $row)
    <div class="modal fade" id="editKategoriModal{{ $row->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><b>Edit Kategori</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('backend.category.update', $row->id) }}" method="POST">
                    @method('PUT') @csrf
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label class="fw-bold">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $row->nama_kategori) }}" class="form-control @error('nama_kategori') is-invalid @enderror" required>
                            @error('nama_kategori') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <button type="submit" class="btn btn-primary">Perbaharui</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@endsection