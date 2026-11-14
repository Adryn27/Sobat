@extends('backend.layout.app')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold" style="color: #1e293b;">{{ $judul }}</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('backend.beranda') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profile</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">

        <div class="row g-4">
            <!-- Kolom Kiri: Foto & Ringkasan Profil Pengguna -->
            <div class="col-lg-4">
                <div class="card card-primary card-outline shadow-sm border-0 mb-4" style="border-top-width: 4px; border-radius: 12px;">
                    <div class="card-body pt-4">
                        <div class="text-center mb-3">
                            @if ($index->foto)
                                <img src="{{ asset('storage/img-user/' . $index->foto) }}" class="img-fluid rounded-4 shadow-sm" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #f1f5f9; padding: 4px;">
                            @else
                                <img src="{{ asset('Img/img-default.jpg') }}" class="img-fluid rounded-4 shadow-sm" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #f1f5f9; padding: 4px;">
                            @endif
                        </div>

                        <h4 class="profile-username text-center fw-bold fs-5 mb-1" style="color: #1e293b;">{{ $index->nama }}</h4>
                        <p class="text-muted text-center small mb-4">{{ $index->email }}</p>

                        <ul class="list-group list-group-flush mb-0">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent py-2.5">
                                <span class="small text-secondary fw-medium">Role Pengguna</span>
                                @if ($index->role == '2')
                                <span class="badge rounded-pill fw-bold bg-warning-subtle text-warning-emphasis" style="font-size: 0.75rem; padding: 6px 12px;">
                                    <i class="bi bi-award-fill me-1"></i> Pemilik
                                </span>
                                @elseif ($index->role == '1')
                                <span class="badge rounded-pill fw-bold bg-secondary-subtle text-secondary" style="font-size: 0.75rem; padding: 6px 12px;">
                                    <i class="bi bi-person-badge-fill me-1"></i> Admin
                                </span>
                                @elseif ($index->role == '3')
                                <span class="badge rounded-pill fw-bold bg-warning-subtle text-warning-emphasis" style="font-size: 0.75rem; padding: 6px 12px;">
                                    <i class="bi bi-capsule-pill me-1"></i> Apoteker
                                </span>
                                @elseif ($index->role == '4')
                                <span class="badge rounded-pill fw-bold bg-warning-subtle text-warning-emphasis" style="font-size: 0.75rem; padding: 6px 12px;">
                                    <i class="bi bi-box-seam-fill me-1"></i> Kepala Gudang
                                </span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent py-2.5">
                                <span class="small text-secondary fw-medium">Status Akun</span>
                                <span class="small fw-semibold text-dark text-end">
                                    @if($index->email_verified_at)
                                        <span class="text-success"><i class="fa-solid fa-circle-check me-1"></i> Aktif</span>
                                    @else
                                        <span class="text-warning"><i class="fa-solid fa-circle-exclamation me-1"></i> Menunggu</span>
                                    @endif
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0 bg-transparent py-2.5">
                                <span class="small text-secondary fw-medium">Waktu Verifikasi</span>
                                <span class="small fw-semibold text-dark text-end" style="font-size: 0.8rem;">
                                    {{ $index->email_verified_at ? $index->email_verified_at->format('d M Y, H:i') : '-' }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Profil Apotek & Zona Bahaya (Jika Pemilik) -->
            <div class="col-lg-8">
                
                <!-- Card Profil Apotek (Dilihat oleh Pemilik maupun Staf yang terikat tenant) -->
                @if($index->tenants)
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h5 class="card-title fw-bold fs-6 mb-0 text-dark">
                            <i class="fa-solid fa-store me-2 text-primary"></i> Informasi Detail Apotek
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(Auth::user()->role == '2')
                        <!-- JIKA PEMILIK: TAMPILKAN FORM INTERAKTIF DENGAN TOMBOL UPDATE -->
                        <form action="{{ route('backend.tenant.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row g-4">
                                <div class="col-md-12">
                                    <label for="nama_tenant" class="form-label small fw-semibold text-secondary mb-1">Nama Apotek</label>
                                    <input type="text" class="form-control form-control-sm field-input-custom fw-bold" id="nama_tenant" name="nama_tenant" value="{{ old('nama_tenant', $index->tenants->nama_tenant) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="telepon" class="form-label small fw-semibold text-secondary mb-1">No. Telepon Apotek</label>
                                    <input type="text" class="form-control form-control-sm field-input-custom" id="telepon" name="telepon" value="{{ old('telepon', $index->tenants->telepon) }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="status" class="form-label small fw-semibold text-secondary mb-1">Status Apotek</label>
                                    <select class="form-select form-select-sm field-input-custom" id="status" name="status" required>
                                        <option value="1" {{ $index->tenants->status == 1 ? 'selected' : '' }}>Aktif</option>
                                        <option value="0" {{ $index->tenants->status == 0 ? 'selected' : '' }}>Nonaktif</option>
                                    </select>
                                    <div class="form-text text-muted" style="font-size: 0.72rem;">
                                        Jika diubah ke Nonaktif, seluruh akun staf akan ikut nonaktif otomatis.
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label for="alamat" class="form-label small fw-semibold text-secondary mb-1">Alamat Lengkap Apotek</label>
                                    <textarea class="form-control form-control-sm field-input-custom" id="alamat" name="alamat" rows="3" required>{{ old('alamat', $index->tenants->alamat) }}</textarea>
                                </div>

                                <div class="col-12 text-start pt-2">
                                    <button type="submit" class="btn btn-warning btn-sm fw-semibold px-4 shadow-sm">
                                        <i class="fa-solid fa-floppy-disk me-1"></i> Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <!-- JIKA STAF: TAMPILKAN TEXT BIASA (READ-ONLY) -->
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label small fw-semibold text-secondary mb-1">Nama Apotek</label>
                                <div class="p-3 rounded-3 field-view-custom fw-bold">
                                    {{ $index->tenants->nama_tenant }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary mb-1">No. Telepon Apotek</label>
                                <div class="p-3 rounded-3 field-view-custom">
                                    {{ $index->tenants->telepon ?? '-' }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-secondary mb-1">Status Apotek</label>
                                <div class="p-3 rounded-3 field-view-custom">
                                    @if($index->tenants->status)
                                        <span class="text-success fw-semibold"><i class="fa-solid fa-circle-check me-1"></i> Aktif</span>
                                    @else
                                        <span class="text-danger fw-semibold"><i class="fa-solid fa-circle-xmark me-1"></i> Nonaktif</span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small fw-semibold text-secondary mb-1">Alamat Lengkap Apotek</label>
                                <div class="p-3 rounded-3 field-view-custom">
                                    {{ $index->tenants->alamat ?? '-' }}
                                </div>
                            </div>
                        </div>
                    @endif
                    </div>
                </div>
                @endif

                <!-- ZONA BAHAYA: HANYA MUNCUL JIKA ROLE == '2' (PEMILIK) -->
                @if(Auth::user()->role == '2')
                <div class="card border-danger shadow-sm mb-4" style="border-radius: 12px;">
                    <div class="card-body">
                        <h5 class="text-danger fw-bold fs-6 mb-2">Zona Bahaya</h5>
                        <p class="small text-muted mb-3">Menghapus akun apotek akan menghapus seluruh data inventaris, obat, transaksi, dan akun seluruh staf secara permanen. Tindakan ini tidak dapat dibatalkan.</p>
                        
                        <button type="button" class="btn btn-danger btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#deleteTenantModal">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> Hapus Akun Apotek Ini Permanen
                        </button>
                    </div>
                </div>
                @endif

            </div>
        </div>

    </div>
</div>

<!-- Modal Konfirmasi Hapus Tenant (Hanya Pemilik) -->
@if(Auth::user()->role == '2')
<div class="modal fade" id="deleteTenantModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px; overflow: hidden;">
            <form action="{{ route('backend.tenant.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title fs-6 fw-bold">
                        <i class="fa-solid fa-triangle-exclamation me-1"></i> Konfirmasi Hapus Akun Apotek
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <p class="small text-danger fw-semibold mb-3">
                        Peringatan: Seluruh data apotek, obat masuk, transaksi, dan data staf akan musnah selamanya!
                    </p>
                    
                    <div class="mb-0">
                        <label for="password_konfirmasi" class="form-label small fw-semibold text-secondary">Masukkan Kata Sandi Anda untuk Konfirmasi</label>
                        <input type="password" class="form-control form-control-sm" id="password_konfirmasi" name="password_konfirmasi" required placeholder="Kata sandi akun Anda">
                    </div>
                </div>
                
                <div class="modal-footer bg-light px-4 py-3">
                    <button type="button" class="btn btn-secondary btn-sm px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm fw-semibold px-3">Ya, Hapus Permanen</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<style>
    .field-view-custom {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        font-size: 0.875rem;
        color: #334155;
        font-weight: 500;
    }
</style>
@endsection