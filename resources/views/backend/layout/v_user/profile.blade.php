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

        <div class="row">
            <div class="col-md-4">
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
                                @if ($index->role == '0')
                                <span class="badge rounded-pill fw-bold" style="background-color: rgba(37, 99, 235, 0.1); color: #2563eb; font-size: 0.75rem;">
                                    <i class="bi bi-shield-lock-fill me-1"></i> SuperAdmin
                                </span>
                                @elseif ($index->role == '1')
                                <span class="badge rounded-pill fw-bold bg-secondary-subtle text-secondary" style="font-size: 0.75rem; padding: 6px 12px;">
                                    <i class="bi bi-person-badge-fill me-1"></i> Admin
                                </span>
                                @elseif ($index->role == '2')
                                <span class="badge rounded-pill fw-bold bg-warning-subtle text-warning-emphasis" style="font-size: 0.75rem; padding: 6px 12px;">
                                    <i class="bi bi-award-fill me-1"></i> Pemilik
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
                                <span class="small text-secondary fw-medium">Waktu Verifikasi</span>
                                <span class="small fw-semibold text-dark text-end">
                                    <i class="fa-regular fa-calendar-check me-1 text-success"></i> 
                                    {{ $index->email_verified_at ? $index->email_verified_at->format('d M Y, H:i') : 'Belum Diverifikasi' }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                    <div class="card-header bg-transparent border-bottom py-3">
                        <h5 class="card-title fw-bold fs-6 mb-0 text-dark">
                            <i class="fa-regular fa-user me-2 text-primary"></i> Detail Informasi Profil
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-12">
                                <label class="form-label small fw-semibold text-secondary mb-1">Nama Lengkap</label>
                                <div class="p-3 rounded-3 field-view-custom">
                                    {{ $index->nama }}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small fw-semibold text-secondary mb-1">Alamat Email</label>
                                <div class="p-3 rounded-3 field-view-custom">
                                    {{ $index->email }}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small fw-semibold text-secondary mb-1">Status Akun</label>
                                <div class="p-3 rounded-3 field-view-custom d-flex align-items-center gap-2">
                                    @if($index->email_verified_at)
                                        <i class="fa-solid fa-circle-check text-success"></i> Terverifikasi Aktif
                                    @else
                                        <i class="fa-solid fa-circle-exclamation text-warning"></i> Menunggu Verifikasi
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* Style komponen kustom untuk tampilan Read-Only */
    .field-view-custom {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        font-size: 0.875rem;
        color: #334155;
        font-weight: 500;
    }
</style>
@endsection