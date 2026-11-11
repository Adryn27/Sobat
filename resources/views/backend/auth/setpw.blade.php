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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
      /* 1. Pengaturan Dasar & Font */
      body { 
          font-family: 'Plus Jakarta Sans', sans-serif; 
          background-color: #eff6ff;
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
  
      /* 4. Menghilangkan Fitur Mata Bawaan Browser */
      input::-ms-reveal,
      input::-ms-clear {
          display: none;
      }
  
      /* 5. Kustomisasi Input & Ikon Sisi Kiri */
      .input-icon-left {
          position: absolute;
          left: 16px;
          top: 50%;
          transform: translateY(-50%);
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

      /* Kustomisasi khusus tampilan input yang di-disable agar tetap clean */
      .input-field-custom:disabled {
          background-color: #f1f5f9 !important;
          color: #64748b;
          border-style: dashed;
          cursor: not-allowed;
      }
  
      /* 6. Atur Posisi Ikon Tanda Seru (.is-invalid) */
      .input-field-custom.is-invalid {
          background-position: right 12px center !important;
          background-size: 16px 16px;
      }
  
      /* 7. Pengaturan Tombol Toggle Mata */
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
  
      /* Geser mata jika ada error agar tidak bertabrakan dengan tanda seru */
      .input-field-custom.is-invalid ~ .btn-toggle-password {
          right: 38px !important;
      }
  
      /* 8. Teks Error Laravel */
      .invalid-feedback {
          display: block;
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
            
            <div class="col-md-6 p-4 p-sm-5 d-flex flex-column justify-content-center glass-card bg-white rounded-end-4">
                
                <div class="mb-4">
                    <div class="d-md-none d-flex align-items-center gap-2 mb-3">
                        <img src="{{ asset('Img/logo.png') }}" alt="Logo" class="rounded-3" style="width: 32px; height: 32px;">
                        <span class="fw-bold fs-5 text-dark">Sapphire Farma</span>
                    </div>
                    <h3 class="fw-bold text-dark mb-1 fs-4">Atur Kata Sandi</h3>
                    <p class="small text-muted mb-0">Buat kata sandi baru untuk mengaktifkan akun staf Anda.</p>
                </div>
                <form action="{{ route('set.password',$user->verification_token) }}" method="POST">
                  @csrf

                  <div class="mb-3">
                      <label class="form-label small fw-semibold text-secondary mb-1.5">Alamat Email Anda</label>
                      
                      <div class="position-relative">
                          <div class="input-icon-left">
                              <i class="fa-regular fa-envelope"></i>
                          </div>
                          
                          <input type="email" 
                              class="form-control input-field-custom" 
                              value="{{ $user->email }}" 
                              disabled 
                              readonly>
                      </div>
                      <div class="form-text text-muted ps-1" style="font-size: 0.7rem;">Email terverifikasi sistem dan tidak dapat diubah.</div>
                  </div>

                  <div class="mb-3">
                      <label for="password" class="form-label small fw-semibold text-secondary mb-1.5">Kata Sandi Baru</label>
                      
                      <div class="position-relative">
                          <div class="input-icon-left">
                              <i class="fa-solid fa-lock"></i>
                          </div>
                          
                          <input type="password" id="password" name="password" required
                              class="form-control input-field-custom @error('password') is-invalid @enderror" 
                              style="padding-right: 65px !important;" 
                              placeholder="Minimal 8 karakter">
                          
                          <button type="button" id="togglePassword" class="btn-toggle-password">
                              <i class="fa-regular fa-eye" id="eyeIcon"></i>
                          </button>
                      </div>

                        <div class="form-text text-muted ps-1 mb-1" style="font-size: 0.7rem; lh-base">
                            Wajib menggunakan kombinasi <strong>huruf besar [A-Z]</strong>, <strong>huruf kecil [a-z]</strong>, <strong>angka [0-9]</strong>, dan <strong>simbol [#, @, dll]</strong>.
                        </div>
              
                      @error('password')
                          <span class="invalid-feedback ps-1" role="alert">
                              {{ $message }}
                          </span>
                      @enderror
                  </div>

                  <div class="mb-4">
                      <label for="password_confirmation" class="form-label small fw-semibold text-secondary mb-1.5">Konfirmasi Kata Sandi Baru</label>
                      
                      <div class="position-relative">
                          <div class="input-icon-left">
                              <i class="fa-solid fa-key"></i>
                          </div>
                          
                          <input type="password" id="password_confirmation" name="password_confirmation" required
                              class="form-control input-field-custom @error('password') is-invalid @enderror" 
                              style="padding-right: 65px !important;" 
                              placeholder="Ulangi kata sandi baru">
                          
                          <button type="button" id="togglePasswordConfirm" class="btn-toggle-password">
                              <i class="fa-regular fa-eye" id="eyeIconConfirm"></i>
                          </button>
                      </div>
                  </div>
              
                  <button type="submit" 
                      class="btn btn-primary w-100 fw-semibold py-2.5 rounded-3 d-flex align-items-center justify-content-center gap-2 shadow-sm"
                      style="background-color: #2563eb; border-color: #2563eb; font-size: 0.875rem;">
                      <i class="fa-solid fa-floppy-disk" style="font-size: 0.75rem;"></i>
                      <span>Simpan</span>
                  </button>
                </form>

                <div class="mt-4 pt-4 border-top border-light text-center">
                    <p class="small text-muted mb-0" style="font-size: 0.75rem;">Ada Kendala? <a href="#" class="text-primary fw-semibold text-decoration-none">Hubungi Pemilik</a></p>
                </div>

            </div>
            <div class="col-md-6 d-none d-md-flex flex-column justify-content-center bg-gradient-custom p-5 text-white relative overflow-hidden position-relative">
                <div class="blur-circle"></div>
                
                <div class="d-flex align-items-center gap-4 position-relative z-1 justify-content-center flex-column text-center">
                    <div class="d-flex align-items-center justify-content-center border border-white-20 rounded-4" 
                         style="background: rgba(255,255,255,0.2); border-color: rgba(255,255,255,0.2) !important; width: 90px; height: 90px;">
                        <img src="{{ asset('Img/logo.png') }}" alt="Sapphire Farma Logo" class="img-fluid rounded-4" style="max-width: 75%; height: auto;">
                    </div>
                    
                    <div>
                        <h1 class="fw-bold display-6 mb-1 tracking-wide">Apotek Sapphire Farma</h1>
                        <p class="fs-5 text-white-50 mb-0">Sistem Inventaris Apotek</p>
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