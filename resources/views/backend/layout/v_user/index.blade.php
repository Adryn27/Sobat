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
              <li class="breadcrumb-item active" aria-current="page">User</li>
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
              <a href="#">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahUserModal"><i class="fas fa-plus"></i> Tambah</button>
              </a>
            </div>
            <div class="table-responsive table-hover mt-3">
              <table
                id="table"
                class="table table-striped table-bordered"
              >
                <thead>
                  <tr>
                      <th>No</th>
                      <th>Foto</th>
                      <th>Nama</th>
                      <th>Role</th>
                      <th>Email</th>
                      <th>Status</th>
                      <th style="white-space: nowrap;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($index as $row)
                      <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>
                            @if ($row->foto)
                              <img src="{{ asset('storage/img-user/' . $row->foto) }}" width="50px" height="60px" alt="Foto Pengguna">
                            @else
                              <img src="{{ asset('Img/img-default.jpg') }}" width="50px" alt="Foto Pengguna">
                            @endif
                          </td>
                          <td>{{ $row->nama }}</td>
                          <td>
                              @if ($row->role == 1)
                                  <span class="badge bg-secondary" style="color: white">Admin</span>    
                              @elseif ($row->role == 0)
                                  <span class="badge bg-primary" style="color: white">SuperAdmin</span>    
                              @elseif ($row->role == 2)
                                  <span class="badge bg-warning" style="color: white">Pemilik</span>    
                              @elseif ($row->role == 3)
                                  <span class="badge bg-success" style="color: white">Apoteker</span>    
                              @elseif ($row->role == 4)
                                  <span class="badge bg-info" style="color: white">Kepala gudang</span>    
                              @endif
                          </td>
                          <td>{{ $row->email }}</td>
                          <td>
                            @if ($row->status == 1)
                                <span class="badge bg-success" style="color: white">Aktif</span>    
                            @elseif ($row->status == 0)
                                <span class="badge bg-danger" style="color: white">Nonaktif</span>      
                            @endif
                          </td>
                          <td style="white-space: nowrap;">
                              <a href="#">
                                  <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $row->id }}">
                                    <i class="far fa-edit"></i> Edit</button>
                              </a>
                              <form action="{{ route('backend.reset', $row->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-info text-white fw-medium show_reset" data-konf-reset="{{ $row->nama }}">
                                    <i class="fas fa-paper-plane"></i> Reset Password
                                </button>
                              </form>
                              <form action="{{ route('backend.user.destroy', $row->id) }}" method="POST" style="display: inline-block">
                                  @method('delete')
                                  @csrf
    
                                  <button type="submit" class="btn btn-danger btn-sm show_confirm" data-konf-delete="{{ $row->nama }}"><i class="fas fa-trash"> Hapus</i></button>
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



{{-- Modal Tambah User--}}
<div class="modal fade" id="tambahUserModal" tabindex="-1" aria-labelledby="tambahUserModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- modal-lg untuk lebar besar -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahUserModalLabel"><b>Tambah Pengguna</b></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('backend.user.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Foto</label>
                                <img class="foto-preview mb-2" style="max-width: 100%;">
                                <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" onchange="previewFoto()">
                                @error('foto')
                                    <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Role <span class="text-danger">*</span></label>
                                <select name="role" class="form-control @error('role') is-invalid @enderror">
                                    <option value="" {{ old('role') == '' ? 'selected' : '' }}>- Pilih Role -</option>
                                    <option value="0" {{ old('role') == '0' ? 'selected' : '' }}>SuperAdmin</option>
                                    <option value="1" {{ old('role') == '1' ? 'selected' : '' }}>Admin</option>
                                    <option value="2" {{ old('role') == '2' ? 'selected' : '' }}>Pemilik</option>
                                    <option value="3" {{ old('role') == '3' ? 'selected' : '' }}>Apoteker</option>
                                    <option value="4" {{ old('role') == '4' ? 'selected' : '' }}>Kepala gudang</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Nama <span class="text-danger">*</span></label>
                                <input type="text" name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan Nama">
                                @error('nama')
                                    <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="text" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan Email">
                                @error('email')
                                    <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                @enderror
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

{{-- Modal Edit User --}}
@foreach ($index as $row)
    <div class="modal fade" id="editUserModal{{ $row->id }}" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel{{ $row->id }}"><b>Edit Pengguna</b></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    @if($row->email_verified_at == null && \Carbon\Carbon::parse($row->created_at)->addMinutes(60)->isPast())
                        <form action="{{ route('backend.verifulang', $row->id) }}" method="POST" id="formKirimUlang{{ $row->id }}">
                            @csrf
                        </form>
                    @endif
                    <form action="{{ route('backend.user.update', $row->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Foto</label>
                                    @if ($row->foto)
                                        <img src="{{ asset('storage/img-user/' . $row->foto) }}" class="foto-preview mb-2" width="100%">
                                    @else
                                        <img src="{{ asset('Img/img-default.jpg') }}" class="foto-preview mb-2" width="100%">
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Status <span class="text-danger">*</span></label>
                                
                                    <select name="status" class="form-control @error('status') is-invalid @enderror" {{ $row->email_verified_at == null ? 'disabled' : '' }}>
                                        <option value="" {{ old('status', $row->status) == '' ? 'selected' : '' }}>- Ganti Status -</option>
                                        <option value="0" {{ old('status', $row->status) == '0' ? 'selected' : '' }}>Nonaktif</option>
                                        <option value="1" {{ old('status', $row->status) == '1' ? 'selected' : '' }}>Aktif</option>
                                    </select>
                                
                                    @if($row->email_verified_at == null)
                                        <input type="hidden" name="status" value="{{ $row->status }}">
                                        
                                        <div class="d-flex align-items-center justify-content-between mt-2 p-2 border rounded bg-light">
                                            <small class="text-danger">
                                                <i class="bi bi-exclamation-circle-fill"></i> Akun belum verifikasi email
                                            </small>
                                
                                            @php
                                                $isExpired = \Carbon\Carbon::parse($row->created_at)->addMinutes(60)->isPast();
                                            @endphp
                                
                                            @if($isExpired)
                                                <button type="submit" form="formKirimUlang{{ $row->id }}" class="btn btn-sm btn-warning text-dark fw-bold px-3">
                                                    <i class="bi bi-arrow-clockwise me-1"></i> Kirim Ulang Tautan
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-sm btn-secondary text-white px-3" disabled style="cursor: not-allowed;">
                                                    <i class="bi bi-clock-history me-1"></i> Masih Aktif
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                
                                    @error('status')
                                        <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Role <span class="text-danger">*</span></label>
                                    <select name="role" class="form-control @error('role') is-invalid @enderror">
                                        <option value="" {{ old('role', $row->role) == '' ? 'selected' : '' }}>- Pilih Role -</option>
                                        <option value="0" {{ old('role', $row->role) == '0' ? 'selected' : '' }}>SuperAdmin</option>
                                        <option value="1" {{ old('role', $row->role) == '1' ? 'selected' : '' }}>Admin</option>
                                        <option value="2" {{ old('role', $row->role) == '2' ? 'selected' : '' }}>Pemilik</option>
                                        <option value="3" {{ old('role', $row->role) == '3' ? 'selected' : '' }}>Apoteker</option>
                                        <option value="4" {{ old('role', $row->role) == '4' ? 'selected' : '' }}>Kepala gudang</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label>Nama <span class="text-danger">*</span></label>
                                    <input type="text" name="nama" value="{{ old('nama', $row->nama) }}" class="form-control @error('nama') is-invalid @enderror" placeholder="Masukkan Nama">
                                    @error('nama')
                                        <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" name="email" value="{{ old('email', $row->email) }}" class="form-control @error('email') is-invalid @enderror" placeholder="Masukkan Nama" disabled>
                                    @error('email')
                                        <div class="invalid-feedback alert-danger">{{ $message }}</div>
                                    @enderror
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