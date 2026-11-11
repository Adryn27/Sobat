<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $judul }}</title>
    <link
        rel="icon"
        type="image/jpg"
        sizes="32x32"
        href="{{ asset('Img/LogoB.jpg') }}"
    />
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
      /* 1. Pengaturan Dasar & Font */
      body { 
          font-family: 'Plus Jakarta Sans', sans-serif; 
          background-color: #eff6ff; /* bg-blue-50 alternatif */
      }
      
      /* 2. Sisi Kiri: Ornamen Gradasi & Efek Blur (Desktop) */
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
  
      /* 3. Sisi Kanan: Efek Glassmorphism Form */
      .glass-card {
          background: rgba(255, 255, 255, 0.8) !important;
          backdrop-filter: blur(16px);
          -webkit-backdrop-filter: blur(16px);
          box-shadow: 0 15px 60px -15px rgba(37, 99, 235, 0.2);
      }
  
      /* 4. Menghilangkan Fitur Mata Bawaan Browser (MS Edge / IE) */
      input::-ms-reveal,
      input::-ms-clear {
          display: none;
      }
  
      /* 5. Kustomisasi Input & Ikon Sisi Kiri */
      .input-group-custom {
          position: relative;
      }
      
      .input-icon-left {
          position: absolute;
          left: 16px;
          top: 50%;
          transform: translateY(-50%);
          color: #94a3b8;
          z-index: 5;
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
  
      /* 6. Atur Posisi Ikon Tanda Seru (.is-invalid) Bawaan Bootstrap agar Rapi */
      .input-field-custom.is-invalid {
          background-position: right 12px center !important;
          background-size: 16px 16px;
      }
  
      /* 7. Pengaturan Tombol Toggle Mata (Password) */
      .btn-toggle-password {
          position: absolute;
          right: 16px; /* Posisi normal saat tidak ada eror */
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
  
      /* Solusi Kunci: Geser tombol mata ke kiri jika input password error, 
         agar tidak bertabrakan dengan ikon tanda seru Bootstrap */
      .input-field-custom.is-invalid ~ .btn-toggle-password {
          right: 38px !important;
      }
  
      /* 8. Perbaikan Pesan Eror Teks Masukan Laravel */
      .invalid-feedback {
          display: block; /* Memaksa teks eror muncul di bawah input-group */
          color: #dc3545;
          font-size: 0.75rem;
          margin-top: 0.35rem;
          font-weight: 500;
      }
  </style>
</head>
<body class="min-vh-screen d-flex align-items-center justify-content-center p-3 p-sm-4 p-md-5">

    <div class="container" style="max-width: 960px;">
        <div class="row g-0 rounded-4 overflow-hidden" style="min-height: 550px;">
            
            <div class="col-md-6 d-none d-md-flex flex-column justify-content-between bg-gradient-custom p-5 text-white relative overflow-hidden position-relative">
                <div class="blur-circle"></div>
                
                <div class="d-flex align-items-center gap-3 position-relative z-1">
                    <div class="d-flex align-items-center justify-content-center border border-white-20 rounded-circle" style="background: rgba(255,255,255,0.2); border-color: rgba(255,255,255,0.2) !important; width: 50px; height: 50px;">
                      <img src="{{ asset('Img/logo.png') }}" alt="Sapphire Farma Logo" class="img-fluid rounded-circle" style="max-width: 70%; height: auto;">
                    </div>
                    <div>
                        <h1 class="fw-bold fs-5 mb-0 tracking-wide">Apotek Sapphire Farma</h1>
                        <p class="small text-white-50 mb-0" style="font-size: 0.75rem;">Sistem Invetaris Apotek</p>
                    </div>
                </div>

                <div class="my-auto py-5 position-relative z-1">
                    <i class="fa-solid fa-boxes-stacked display-4 mb-3 d-block" style="color: rgba(191, 219, 254, 0.4);"></i>
                    <h2 class="fw-bold fs-3 mb-2">Kelola Stok Obat Jadi Lebih Mudah & Akurat</h2>
                    <p class="small text-white-50 lh-base mb-0">Pantau ketersediaan obat dan transaksi apotek dalam satu dashboard terintegrasi.</p>
                </div>

                <p class="small text-white-50 mb-0 position-relative z-1" style="font-size: 0.75rem;">&copy; 2026 Sapphire Farma. All rights reserved.</p>
            </div>

            <div class="col-md-6 p-4 p-sm-5 d-flex flex-column justify-content-center glass-card bg-white rounded-end-4">
                
                <div class="mb-4">
                    <div class="d-md-none d-flex align-items-center gap-2 mb-3">
                        <i class="fa-solid fa-mortar-pestle fs-4 text-primary"></i>
                        <span class="fw-bold fs-5 text-dark">Sapphire Farma</span>
                    </div>
                    <h3 class="fw-bold text-dark mb-1 fs-4">Selamat Datang Kembali</h3>
                    <p class="small text-muted mb-0">Silakan masuk untuk mengelola inventaris apotek.</p>
                </div>
                
                <form action="{{ route('backend.login') }}" method="POST">
                  @csrf
              
                  <div class="mb-3">
                      <label for="email" class="form-label small fw-semibold text-secondary mb-1.5">Alamat Email</label>
                      
                      <div class="position-relative">
                          <div class="input-icon-left">
                              <i class="fa-regular fa-envelope"></i>
                          </div>
                          
                          <input type="email" id="email" name="email" required
                              class="form-control input-field-custom @error('email') is-invalid @enderror"
                              style="padding-right: 40px !important;" 
                              placeholder="nama@sapphirefarma.com"
                              value="{{ old('email') }}">
                      </div>
              
                      @error('email')
                          <span class="invalid-feedback ps-1" role="alert">
                              {{ $message }}
                          </span>
                      @enderror
                  </div>
              
                  <div class="mb-3">
                      <div class="d-flex justify-content-between align-items-center mb-1.5">
                          <label for="password" class="form-label small fw-semibold text-secondary mb-0">Kata Sandi</label>
                      </div>
                      
                      <div class="position-relative">
                          <div class="input-icon-left">
                              <i class="fa-solid fa-lock"></i>
                          </div>
                          
                          <input type="password" id="password" name="password" required
                              class="form-control input-field-custom @error('password') is-invalid @enderror" 
                              style="padding-right: 65px !important;" 
                              placeholder="••••••••">
                          
                          <button type="button" id="togglePassword" class="btn-toggle-password">
                              <i class="fa-regular fa-eye" id="eyeIcon"></i>
                          </button>
                      </div>
              
                      @error('password')
                          <span class="invalid-feedback ps-1" role="alert">
                              {{ $message }}
                          </span>
                      @enderror
                  </div>
              
                  <button type="submit" 
                      class="btn btn-primary w-100 fw-semibold py-2.5 rounded-3 d-flex align-items-center justify-content-center gap-2 shadow-sm"
                      style="background-color: #2563eb; border-color: #2563eb; font-size: 0.875rem;">
                      <i class="fa-solid fa-right-to-bracket" style="font-size: 0.75rem;"></i>
                      <span>Masuk</span>
                  </button>
                </form>

                <div class="mt-4 pt-4 border-top border-light text-center">
                    <p class="small text-muted mb-0" style="font-size: 0.75rem;">Kendala akses atau akun staf baru? <a href="#" class="text-primary fw-semibold text-decoration-none">Hubungi Pemilik</a></p>
                </div>

            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript Fungsionalitas Mata -->
    <script>
        const passwordInput = document.getElementById('password');
        const togglePasswordButton = document.getElementById('togglePassword');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePasswordButton.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            
            if (isPassword) {
                eyeIcon.classList.remove('fa-regular', 'fa-eye');
                eyeIcon.classList.add('fa-solid', 'fa-eye-slash');
            } else {
                eyeIcon.classList.remove('fa-solid', 'fa-eye-slash');
                eyeIcon.classList.add('fa-regular', 'fa-eye');
            }
        });
    </script>
    @if (session('error'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Tautan Kedaluwarsa!',
            text: "{{ session('error') }}",
            confirmButtonColor: '#d33',
        });
    </script>
@endif
</body>
</html>