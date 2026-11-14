<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $judul}}</title>
    <link rel="icon" type="image/jpg" sizes="32x32" href="{{ asset('Img/LogoB.jpg') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
      body { 
          font-family: 'Plus Jakarta Sans', sans-serif; 
          background-color: #eff6ff;
      }
      
      .bg-gradient-custom {
          background: linear-gradient(135deg, #2563eb, #4338ca);
      }
  
      .blur-circle {
          position: absolute;
          top: 0;
          right: 0;
          width: 160px;
          height: 160px;
          background: rgba(255, 255, 255, 0.1);
          border-radius: 50%;
          filter: blur(40px);
          margin-right: -64px;
          margin-top: -64px;
      }
  
      .glass-card {
          background: rgba(255, 255, 255, 0.85) !important;
          backdrop-filter: blur(16px);
          -webkit-backdrop-filter: blur(16px);
          box-shadow: 0 15px 60px -15px rgba(37, 99, 235, 0.2);
      }
  
      input::-ms-reveal,
      input::-ms-clear {
          display: none;
      }
  
      .input-icon-left {
          position: absolute;
          left: 16px;
          top: 50%;
          transform: translateY(-50%);
          color: #94a3b8;
          z-index: 5;
          pointer-events: none;
      }

      .input-icon-textarea {
          position: absolute;
          left: 16px;
          top: 14px;
          color: #94a3b8;
          z-index: 5;
          pointer-events: none;
      }
  
      .input-field-custom {
          padding-left: 42px !important;
          border-radius: 12px !important;
          background-color: #f8fafc;
          border: 1px solid #e2e8f0;
          font-size: 0.875rem;
          transition: all 0.2s ease;
      }
  
      .input-field-custom:focus {
          background-color: #fff;
          border-color: #2563eb;
          box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
      }
  
      .input-field-custom.is-invalid {
          background-position: right 12px center !important;
          background-size: 16px 16px;
      }
  
      .btn-toggle-password {
          position: absolute;
          right: 16px;
          top: 50%;
          transform: translateY(-50%);
          background: none;
          border: none;
          color: #94a3b8;
          z-index: 5;
          padding: 0;
          cursor: pointer;
          transition: right 0.2s ease, color 0.2s ease;
      }
  
      .btn-toggle-password:hover {
          color: #475569;
      }
  
      .input-field-custom.is-invalid ~ .btn-toggle-password {
          right: 38px !important;
      }
  
      .invalid-feedback {
          display: block;
          color: #dc3545;
          font-size: 0.75rem;
          margin-top: 0.35rem;
          font-weight: 500;
      }

      .section-divider {
          font-size: 0.72rem;
          text-transform: uppercase;
          letter-spacing: 0.06em;
          color: #2563eb;
          font-weight: 700;
          margin-bottom: 12px;
          display: flex;
          align-items: center;
          gap: 8px;
      }
      .section-divider::after {
          content: "";
          flex: 1;
          height: 1px;
          background-color: #e2e8f0;
      }
    </style>
</head>
<body class="min-vh-100 d-flex align-items-center justify-content-center p-3 p-sm-4 p-md-5">

    <div class="container" style="max-width: 1040px;">
        <div class="row g-0 rounded-4 overflow-hidden shadow-lg" style="min-height: 620px;">
            
            <!-- Sisi Kiri: Form Pendaftaran -->
            <div class="col-md-7 p-4 p-sm-5 d-flex flex-column justify-content-center glass-card bg-white">
                
                <div class="mb-4">
                    <div class="d-md-none d-flex align-items-center gap-2 mb-3">
                        <img src="{{ asset('Img/logo.png') }}" alt="Logo" class="rounded-3" style="width: 32px; height: 32px;">
                        <span class="fw-bold fs-5 text-dark">Sedia</span>
                    </div>
                    <h3 class="fw-bold text-dark mb-1 fs-4">Daftarkan Apotek Anda</h3>
                    <p class="small text-muted mb-0">Lengkapi profil apotek dan akun pemilik untuk mulai mengelola inventaris.</p>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger border-0 rounded-3 small py-2.5 px-3 mb-3 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <form action="{{ route('backend.register.auth') }}" method="POST">
                    @csrf

                    <!-- Bagian 1: Identitas Apotek -->
                    <div class="section-divider">
                        <i class="fa-solid fa-store"></i> Informasi Apotek
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-7">
                            <label for="nama_tenant" class="form-label small fw-semibold text-secondary mb-1">Nama Apotek</label>
                            <div class="position-relative">
                                <div class="input-icon-left">
                                    <i class="fa-solid fa-clinic-medical"></i>
                                </div>
                                <input type="text" id="nama_tenant" name="nama_tenant" value="{{ old('nama_tenant') }}" required
                                    class="form-control input-field-custom @error('nama_tenant') is-invalid @enderror"
                                    placeholder="Contoh: Apotek Sapphire Farma">
                            </div>
                            @error('nama_tenant')
                                <span class="invalid-feedback ps-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-5">
                            <label for="telepon" class="form-label small fw-semibold text-secondary mb-1">No. Telepon / WA</label>
                            <div class="position-relative">
                                <div class="input-icon-left">
                                    <i class="fa-solid fa-phone"></i>
                                </div>
                                <input type="text" id="telepon" name="telepon" value="{{ old('telepon') }}" required
                                    class="form-control input-field-custom @error('telepon') is-invalid @enderror"
                                    placeholder="0812xxxxxxxx">
                            </div>
                            @error('telepon')
                                <span class="invalid-feedback ps-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="alamat" class="form-label small fw-semibold text-secondary mb-1">Alamat Lengkap Apotek</label>
                            <div class="position-relative">
                                <div class="input-icon-textarea">
                                    <i class="fa-solid fa-location-dot"></i>
                                </div>
                                <textarea id="alamat" name="alamat" rows="2" required
                                    class="form-control input-field-custom @error('alamat') is-invalid @enderror"
                                    placeholder="Masukkan alamat lengkap apotek">{{ old('alamat') }}</textarea>
                            </div>
                            @error('alamat')
                                <span class="invalid-feedback ps-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Bagian 2: Akun Pemilik -->
                    <div class="section-divider mt-4">
                        <i class="fa-solid fa-user-shield"></i> Akun Pemilik (Owner)
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label for="nama" class="form-label small fw-semibold text-secondary mb-1">Nama Lengkap Pemilik</label>
                            <div class="position-relative">
                                <div class="input-icon-left">
                                    <i class="fa-regular fa-user"></i>
                                </div>
                                <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                                    class="form-control input-field-custom @error('nama') is-invalid @enderror"
                                    placeholder="Nama pemilik">
                            </div>
                            @error('nama')
                                <span class="invalid-feedback ps-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="email" class="form-label small fw-semibold text-secondary mb-1">Alamat Email</label>
                            <div class="position-relative">
                                <div class="input-icon-left">
                                    <i class="fa-regular fa-envelope"></i>
                                </div>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                    class="form-control input-field-custom @error('email') is-invalid @enderror"
                                    placeholder="owner@apotek.com">
                            </div>
                            @error('email')
                                <span class="invalid-feedback ps-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="password" class="form-label small fw-semibold text-secondary mb-1">Kata Sandi</label>
                            <div class="position-relative">
                                <div class="input-icon-left">
                                    <i class="fa-solid fa-lock"></i>
                                </div>
                                <input type="password" id="password" name="password" required
                                    class="form-control input-field-custom @error('password') is-invalid @enderror"
                                    style="padding-right: 45px !important;"
                                    placeholder="Minimal 8 karakter">
                                <button type="button" id="togglePassword" class="btn-toggle-password">
                                    <i class="fa-regular fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <span class="invalid-feedback ps-1">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="password_confirmation" class="form-label small fw-semibold text-secondary mb-1">Konfirmasi Kata Sandi</label>
                            <div class="position-relative">
                                <div class="input-icon-left">
                                    <i class="fa-solid fa-key"></i>
                                </div>
                                <input type="password" id="password_confirmation" name="password_confirmation" required
                                    class="form-control input-field-custom @error('password') is-invalid @enderror"
                                    style="padding-right: 45px !important;"
                                    placeholder="Ulangi kata sandi">
                                <button type="button" id="togglePasswordConfirm" class="btn-toggle-password">
                                    <i class="fa-regular fa-eye" id="eyeIconConfirm"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-text text-muted ps-1 mb-4" style="font-size: 0.7rem;">
                        Kata sandi wajib kombinasi <strong>huruf besar [A-Z]</strong>, <strong>huruf kecil [a-z]</strong>, <strong>angka [0-9]</strong>, dan <strong>simbol [#, @, dll]</strong>.
                    </div>
              
                    <button type="submit" 
                        class="btn btn-primary w-100 fw-semibold py-2.5 rounded-3 d-flex align-items-center justify-content-center gap-2 shadow-sm"
                        style="background-color: #2563eb; border-color: #2563eb; font-size: 0.875rem;">
                        <i class="fa-solid fa-paper-plane" style="font-size: 0.75rem;"></i>
                        <span>Daftar</span>
                    </button>
                </form>

                <div class="mt-4 pt-3 border-top border-light text-center">
                    <p class="small text-muted mb-0" style="font-size: 0.8rem;">
                        Sudah memiliki akun apotek? 
                        <a href="{{ route('backend.login') }}" class="text-primary fw-semibold text-decoration-none">Login</a>
                    </p>
                </div>

            </div>

            <!-- Sisi Kanan: Branding Sedia -->
            <div class="col-md-5 d-none d-md-flex flex-column justify-content-center bg-gradient-custom p-5 text-white position-relative overflow-hidden">
                <div class="blur-circle"></div>
                
                <div class="d-flex align-items-center gap-4 position-relative z-1 justify-content-center flex-column text-center">
                    <div class="d-flex align-items-center justify-content-center border rounded-4" 
                         style="background: rgba(255,255,255,0.2); border-color: rgba(255,255,255,0.2) !important; width: 90px; height: 90px;">
                        <img src="{{ asset('Img/logo.png') }}" alt="Sedia Logo" class="img-fluid rounded-4" style="max-width: 75%; height: auto;">
                    </div>
                    
                    <div>
                        <h1 class="fw-bold display-6 mb-2 tracking-wide">Sobat</h1>
                        <p class="fs-6 text-white-50 mb-4">Platform Manajemen Inventaris &amp; Operasional Apotek Terintegrasi</p>
                    </div>

                    <div class="text-start bg-white bg-opacity-10 rounded-4 p-3 w-100 border border-white border-opacity-10">
                        <div class="d-flex align-items-center gap-2 mb-2 small">
                            <i class="fa-solid fa-circle-check text-info"></i>
                            <span>Isolasi data apotek mandiri &amp; aman</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 mb-2 small">
                            <i class="fa-solid fa-circle-check text-info"></i>
                            <span>Kelola staf apoteker &amp; kasir dalam 1 klik</span>
                        </div>
                        <div class="d-flex align-items-center gap-2 small">
                            <i class="fa-solid fa-circle-check text-info"></i>
                            <span>Pantau stok obat fast moving</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function setupPasswordToggle(inputId, buttonId, iconId) {
            const input = document.getElementById(inputId);
            const button = document.getElementById(buttonId);
            const icon = document.getElementById(iconId);

            button.addEventListener('click', function () {
                const isPassword = input.getAttribute('type') === 'password';
                input.setAttribute('type', isPassword ? 'text' : 'password');
                
                if (isPassword) {
                    icon.classList.remove('fa-regular', 'fa-eye');
                    icon.classList.add('fa-solid', 'fa-eye-slash');
                } else {
                    icon.classList.remove('fa-solid', 'fa-eye-slash');
                    icon.classList.add('fa-regular', 'fa-eye');
                }
            });
        }

        setupPasswordToggle('password', 'togglePassword', 'eyeIcon');
        setupPasswordToggle('password_confirmation', 'togglePasswordConfirm', 'eyeIconConfirm');
    </script>
    
</body>
</html>