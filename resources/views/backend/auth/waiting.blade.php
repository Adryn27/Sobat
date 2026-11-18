<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $judul }}</title>
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

      .mail-badge {
          width: 72px;
          height: 72px;
          background: rgba(37, 99, 235, 0.1);
          color: #2563eb;
          border-radius: 20px;
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: 2rem;
          margin-bottom: 1.25rem;
      }
    </style>
</head>
<body class="min-vh-100 d-flex align-items-center justify-content-center p-3 p-sm-4 p-md-5">

    <div class="container" style="max-width: 960px;">
        <div class="row g-0 rounded-4 overflow-hidden shadow-lg" style="min-height: 520px;">
            
            <!-- Sisi Kiri: Status Menunggu Verifikasi -->
            <div class="col-md-6 p-4 p-sm-5 d-flex flex-column justify-content-center glass-card bg-white">
                
                <div class="mail-badge">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>

                <h3 class="fw-bold text-dark mb-2 fs-4">Periksa Kotak Masuk Email Anda</h3>
                
                <p class="small text-muted mb-3">
                    Kami telah mengirimkan tautan aktivasi ke alamat email:
                    <br>
                    <strong class="text-dark fs-6">{{ session('email_verifikasi') ?? 'email terdaftar Anda' }}</strong>
                </p>

                <div class="alert border-0 rounded-3 p-3 mb-4" style="background-color: #f8fafc; border-left: 4px solid #2563eb !important;">
                    <div class="d-flex align-items-start gap-2.5">
                        <i class="fa-regular fa-clock text-primary mt-1"></i>
                        <div class="small text-secondary" style="font-size: 0.8rem; line-height: 1.5;">
                            Tautan verifikasi hanya berlaku <strong>1 kali</strong> dalam waktu <strong class="text-danger" id="countdownTimer">10:00</strong>. Jika melewati batas waktu tersebut, data pendaftaran akan terhapus otomatis dan Anda harus mendaftar ulang.
                        </div>
                    </div>
                </div>

                <div class="small text-muted mb-4" style="font-size: 0.78rem;">
                    <p class="mb-1 fw-semibold text-secondary">Belum menerima email?</p>
                    <ul class="ps-3 mb-0">
                        <li>Periksa folder <strong>Spam</strong> atau <strong>Promosi</strong> pada email Anda.</li>
                        <li>Pastikan alamat email yang diketikkan saat pendaftaran sudah benar.</li>
                    </ul>
                </div>

                <div class="d-flex flex-column gap-2">
                    <a href="{{ route('backend.login') }}" 
                       class="btn btn-primary w-100 fw-semibold py-2.5 rounded-3 d-flex align-items-center justify-content-center gap-2 shadow-sm"
                       style="background-color: #2563eb; border-color: #2563eb; font-size: 0.875rem;">
                        <i class="fa-solid fa-arrow-right-to-bracket" style="font-size: 0.75rem;"></i>
                        <span>Ke Halaman Login</span>
                    </a>

                    <a href="{{ route('backend.register') }}" 
                       class="btn btn-light w-100 fw-semibold py-2 rounded-3 text-secondary"
                       style="font-size: 0.8rem;">
                        Salah input email? Daftar ulang
                    </a>
                </div>

            </div>

            <!-- Sisi Kanan: Branding Sedia -->
            <div class="col-md-6 d-none d-md-flex flex-column justify-content-center bg-gradient-custom p-5 text-white position-relative overflow-hidden">
                <div class="blur-circle"></div>
                
                <div class="d-flex align-items-center gap-4 position-relative z-1 justify-content-center flex-column text-center">
                    <div class="d-flex align-items-center justify-content-center border rounded-4" 
                         style="background: rgba(255,255,255,0.2); border-color: rgba(255,255,255,0.2) !important; width: 90px; height: 90px;">
                        <img src="{{ asset('Img/logo.png') }}" alt="Sedia Logo" class="img-fluid rounded-4" style="max-width: 75%; height: auto;">
                    </div>
                    
                    <div>
                        <h1 class="fw-bold display-6 mb-1 tracking-wide">SOBAT</h1>
                        <p class="fs-5 text-white-50 mb-0">Sistem Operasional Barang Apotek Terintegrasi</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Ambil sisa detik dari controller (jika session habis, default ke 600)
        let timeLeft = {{ $sisaDetik ?? 600 }};
        const timerEl = document.getElementById('countdownTimer');

        const interval = setInterval(() => {
            timeLeft--;
            if (timeLeft <= 0) {
                clearInterval(interval);
                timerEl.textContent = "00:00 (Kedaluwarsa)";
                // Opsional: Reload halaman jika waktu habis agar sistem membersihkan data
                window.location.reload();
                return;
            }
            const minutes = String(Math.floor(timeLeft / 60)).padStart(2, '0');
            const seconds = String(timeLeft % 60).padStart(2, '0');
            timerEl.textContent = `${minutes}:${seconds}`;
        }, 1000);
    </script>
</body>
</html>