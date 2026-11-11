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
      body { 
          font-family: 'Plus Jakarta Sans', sans-serif; 
          background-color: #eff6ff;
      }
  
      .glass-card {
          background: rgba(255, 255, 255, 0.95) !important;
          box-shadow: 0 20px 40px -15px rgba(37, 99, 235, 0.15);
          border: 1px solid #e2e8f0;
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

      .input-field-custom:disabled {
          background-color: #f1f5f9 !important;
          color: #64748b;
          border-style: dashed;
          cursor: not-allowed;
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
    </style>
</head>
<body class="min-vh-screen d-flex align-items-center justify-content-center p-3 p-sm-4">

    <div class="w-100" style="max-width: 480px;">
        
        <div class="card glass-card rounded-4 border-0 p-4 p-sm-5">
            
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center border border-light rounded-3 p-2 bg-light mb-3" style="width: 64px; height: 64px;">
                    <img src="{{ asset('Img/LogoB.jpg') }}" alt="Sapphire Farma Logo" class="img-fluid rounded-3">
                </div>
                <h4 class="fw-bold text-dark mb-1">Atur Ulang Kata Sandi</h4>
                <p class="small text-muted mb-0">Masukkan kata sandi baru untuk akun staf Anda.</p>
            </div>

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary mb-1.5">Alamat Email Anda</label>
                    <div class="position-relative">
                        <div class="input-icon-left">
                            <i class="fa-regular fa-envelope"></i>
                        </div>
                        <input type="email" 
                               class="form-control input-field-custom" 
                               value="{{ $email }}" 
                               disabled 
                               readonly>
                        <input type="hidden" name="email" value="{{ $email }}">
                    </div>
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

                    <div class="form-text text-muted ps-1 mb-1" style="font-size: 0.7rem; line-height: 1.4;">
                        Kombinasikan <strong>huruf besar</strong>, <strong>huruf kecil</strong>, <strong>angka</strong>, dan <strong>simbol</strong>.
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
                    <i class="fa-solid fa-key" style="font-size: 0.75rem;"></i>
                    <span>Perbarui Kata Sandi</span>
                </button>
            </form>

            <div class="mt-4 pt-3 border-top border-light text-center">
                <p class="small text-muted mb-0" style="font-size: 0.75rem;">
                    Apotek Sapphire Farma &copy; {{ date('Y') }}
                </p>
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