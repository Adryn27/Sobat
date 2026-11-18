<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOBAT - Sistem Prioritas Inventaris Obat Fast Moving</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sobat-navy: #0b2d48;
            --sobat-navy-hover: #071e31;
            --sobat-teal: #149c96;
            --sobat-bg-light: #f4f7fa;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #334155;
            overflow-x: hidden;
        }

        .navbar {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }

        .btn-sobat-primary {
            background-color: var(--sobat-navy);
            border-color: var(--sobat-navy);
            color: #ffffff;
            font-weight: 600;
            transition: all 0.25s ease;
        }
        .btn-sobat-primary:hover, .btn-sobat-primary:focus {
            background-color: var(--sobat-navy-hover);
            border-color: var(--sobat-navy-hover);
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(11, 45, 72, 0.28);
        }

        .btn-sobat-outline {
            border: 2px solid var(--sobat-navy);
            color: var(--sobat-navy);
            background-color: transparent;
            font-weight: 600;
            transition: all 0.25s ease;
        }
        .btn-sobat-outline:hover {
            background-color: var(--sobat-navy);
            border-color: var(--sobat-navy);
            color: #ffffff;
            transform: translateY(-2px);
        }

        .btn-sobat-light {
            background-color: #ffffff;
            border-color: #ffffff;
            color: var(--sobat-navy);
            font-weight: 700;
            transition: all 0.25s ease;
        }
        .btn-sobat-light:hover {
            background-color: #e2e8f0;
            border-color: #e2e8f0;
            color: var(--sobat-navy);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.2);
        }

        .hero-section {
            background: linear-gradient(135deg, #eef4f8 0%, #ffffff 60%, #e4eef5 100%);
            padding-top: 110px;
            padding-bottom: 80px;
        }
        .hero-title {
            color: var(--sobat-navy);
            font-weight: 800;
            line-height: 1.2;
        }
        .text-navy {
            color: var(--sobat-navy);
        }
        .text-teal {
            color: var(--sobat-teal);
        }

        .feature-card {
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            transition: all 0.3s ease;
            background: #ffffff;
        }
        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 24px rgba(11, 45, 72, 0.1);
            border-color: var(--sobat-navy);
        }
        .icon-box {
            width: 54px;
            height: 54px;
            border-radius: 12px;
            background-color: rgba(11, 45, 72, 0.08);
            color: var(--sobat-navy);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .mockup-window {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #cbd5e1;
            box-shadow: 0 20px 40px rgba(11, 45, 72, 0.14);
            overflow: hidden;
        }
        .mockup-header {
            background: #f1f5f9;
            padding: 10px 16px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }

        .cta-section {
            background: linear-gradient(135deg, #0b2d48 0%, #13466e 100%);
            color: #ffffff;
            border-radius: 24px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top border-bottom py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 text-decoration-none" href="#">
                <img src="{{ asset('Img/logo Sobat.png') }}" alt="Logo Sobat" style="width: 38px; height: 38px; object-fit: contain;">
                <div style="width: 2px; height: 32px; background-color: #149c96; border-radius: 2px;"></div>
                <div>
                    <span class="fw-bold fs-4 d-block lh-1" style="color: #0b2d48;">SOBAT</span>
                    <small class="text-muted d-block lh-1 mt-1" style="font-size: 0.65rem;">
                        Sistem Operasional Barang Apotek Terintegrasi
                    </small>
                </div>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto me-lg-3 fw-medium gap-lg-3">
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="#kriteria-saw">Kriteria</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-dark" href="#fitur">Fitur</a>
                    </li>
                </ul>
            
                <div class="d-flex align-items-center gap-2 mt-3 mt-lg-0">
                    @auth
                        <a href="{{ route('backend.beranda') }}" class="btn btn-sobat-primary rounded-pill px-4">
                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('backend.login') }}" class="btn btn-sobat-primary rounded-pill px-4">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- 2. HERO SECTION -->
    <section id="beranda" class="hero-section">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="badge rounded-pill px-3 py-2 mb-3 fw-semibold" style="background-color: rgba(11, 45, 72, 0.1); color: #0b2d48;">
                        <i class="bi bi-graph-up-arrow me-1"></i> Metode Simple Additive Weighting (SAW)
                    </span>
                    <h1 class="display-5 hero-title mb-3">
                        Monitoring Prioritas Inventaris <span class="text-teal">Obat Fast Moving</span> Secara Terukur.
                    </h1>
                    <p class="lead text-secondary mb-4" style="font-size: 1.05rem;">
                        <strong>SOBAT</strong> membantu apotek menentukan prioritas pengadaan kembali (<em>restock</em>) obat <em>fast moving</em> berdasarkan analisis volume penjualan, frekuensi transaksi, dan rasio stok terkini secara objektif.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('backend.login') }}" class="btn btn-sobat-primary btn-lg rounded-pill px-4 fs-6">
                            Lihat Ranking Prioritas <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="#kriteria-saw" class="btn btn-sobat-outline btn-lg rounded-pill px-4 fs-6">
                            Pelajari Metode SAW
                        </a>
                    </div>

                    <div class="row mt-5 pt-3 border-top g-3">
                        <div class="col-4">
                            <h5 class="fw-bold mb-0 text-navy">SPK SAW</h5>
                            <small class="text-muted">Perankingan Otomatis</small>
                        </div>
                        <div class="col-4">
                            <h5 class="fw-bold mb-0 text-navy">Fast Moving</h5>
                            <small class="text-muted">Cegah Kekosongan Stok</small>
                        </div>
                        <div class="col-4">
                            <h5 class="fw-bold mb-0 text-navy">FEFO</h5>
                            <small class="text-muted">Kontrol Batch & Expired</small>
                        </div>
                    </div>
                </div>

                <!-- Visual Mockup Preview: Ranking SAW -->
                <div class="col-lg-6">
                    <div class="mockup-window">
                        <div class="mockup-header">
                            <span class="dot bg-danger"></span>
                            <span class="dot bg-warning"></span>
                            <span class="dot bg-success"></span>
                            <small class="text-muted ms-2 font-monospace">sobat.id</small>
                        </div>
                        <div class="p-4 bg-white">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-bold text-navy"><i class="bi bi-trophy-fill text-warning me-1"></i> Ranking SAW (Prioritas Restock)</span>
                                <span class="badge rounded-pill px-3" style="background-color: #0b2d48; color: #ffffff;">Real-Time</span>
                            </div>

                            <!-- Simulasi Tabel Ranking SAW -->
                            <div class="table-responsive">
                                <table class="table table-hover align-middle text-center mb-3" style="font-size: 0.82rem;">
                                    <thead class="table-light border-bottom">
                                        <tr>
                                            <th>Rank</th>
                                            <th class="text-start">Obat</th>
                                            <th>Vol</th>
                                            <th>Frek</th>
                                            <th>Stok</th>
                                            <th>Score</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="table-secondary bg-opacity-25">
                                            <td class="fw-bold">#1</td>
                                            <td class="text-start">
                                                <strong class="text-dark d-block">Paracetamol 500mg</strong>
                                                <small class="text-muted">OBT01</small>
                                            </td>
                                            <td>145</td>
                                            <td>48</td>
                                            <td>12 / 150</td>
                                            <td class="fw-bold text-primary">0.9420</td>
                                            <td><span class="badge bg-danger rounded-pill">Prioritas Tinggi</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">#2</td>
                                            <td class="text-start">
                                                <strong class="text-dark d-block">Amoxicillin 500mg</strong>
                                                <small class="text-muted">OBT02</small>
                                            </td>
                                            <td>112</td>
                                            <td>36</td>
                                            <td>18 / 120</td>
                                            <td class="fw-bold text-primary">0.8150</td>
                                            <td><span class="badge bg-danger rounded-pill">Prioritas Tinggi</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">#3</td>
                                            <td class="text-start">
                                                <strong class="text-dark d-block">Antasida Doen Tab</strong>
                                                <small class="text-muted">OBT03</small>
                                            </td>
                                            <td>64</td>
                                            <td>22</td>
                                            <td>65 / 100</td>
                                            <td class="fw-bold text-primary">0.5410</td>
                                            <td><span class="badge bg-warning text-dark rounded-pill">Prioritas Sedang</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">#4</td>
                                            <td class="text-start">
                                                <strong class="text-dark d-block">Vitamin C 500mg</strong>
                                                <small class="text-muted">OBT04</small>
                                            </td>
                                            <td>30</td>
                                            <td>12</td>
                                            <td>115 / 120</td>
                                            <td class="fw-bold text-primary">0.2680</td>
                                            <td><span class="badge bg-success rounded-pill">Prioritas Rendah</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Simulasi Ringkasan Kriteria -->
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="p-2 px-3 rounded-3 border bg-light">
                                        <small class="text-muted d-block" style="font-size: 0.72rem;">Kriteria Benefit</small>
                                        <span class="fw-bold text-navy small"><i class="bi bi-arrow-up-right-circle-fill text-teal me-1"></i> Volume & Frekuensi</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 px-3 rounded-3 border bg-light">
                                        <small class="text-muted d-block" style="font-size: 0.72rem;">Kriteria Cost</small>
                                        <span class="fw-bold text-navy small"><i class="bi bi-arrow-down-right-circle-fill text-danger me-1"></i> Rasio Stok</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. KRITERIA PENILAIAN SAW -->
    <section id="kriteria-saw" class="py-5 my-4">
        <div class="container">
            <div class="text-center mx-auto mb-5" style="max-width: 680px;">
                <span class="text-navy fw-bold text-uppercase small">Kriteria Penilaian</span>
                <h2 class="fw-bold mt-1 text-navy">Tiga Kriteria Utama Penentu Prioritas Obat</h2>
                <p class="text-muted">Metode <em>Simple Additive Weighting</em> (SAW) menghitung nilai preferensi setiap item obat secara otomatis berdasarkan data transaksi aktual apotek.</p>
            </div>

            <div class="row g-4">
                <!-- Kriteria 1 -->
                <div class="col-md-4">
                    <div class="feature-card p-4 h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="icon-box">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <span class="badge bg-success-subtle text-success border border-success rounded-pill">Kriteria Benefit</span>
                        </div>
                        <h5 class="fw-bold text-navy">1. Volume Penjualan</h5>
                        <p class="text-muted small mb-0">
                            Mengukur total kuantitas unit obat yang terjual dalam periode berjalan. Semakin tinggi jumlah unit keluar, semakin tinggi tingkat perputaran obat tersebut.
                        </p>
                    </div>
                </div>

                <!-- Kriteria 2 -->
                <div class="col-md-4">
                    <div class="feature-card p-4 h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="icon-box">
                                <i class="bi bi-repeat"></i>
                            </div>
                            <span class="badge bg-success-subtle text-success border border-success rounded-pill">Kriteria Benefit</span>
                        </div>
                        <h5 class="fw-bold text-navy">2. Frekuensi Transaksi</h5>
                        <p class="text-muted small mb-0">
                            Menghitung seberapa sering obat muncul dalam struk pembelian pelanggan untuk membedakan permintaan rutin harian dari pembelian partai besar sesaat.
                        </p>
                    </div>
                </div>

                <!-- Kriteria 3 -->
                <div class="col-md-4">
                    <div class="feature-card p-4 h-100">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="icon-box">
                                <i class="bi bi-layers-half"></i>
                            </div>
                            <span class="badge bg-danger-subtle text-danger border border-danger rounded-pill">Kriteria Cost</span>
                        </div>
                        <h5 class="fw-bold text-navy">3. Rasio Stok</h5>
                        <p class="text-muted small mb-0">
                            Membandingkan sisa stok aktif terhadap stok maksimal. Obat dengan penjualan tinggi namun sisa stok menipis akan langsung menempati peringkat <strong>Prioritas Tinggi</strong>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. FITUR OPERASIONAL TERINTEGRASI -->
    <section id="fitur" class="py-5" style="background-color: var(--sobat-bg-light);">
        <div class="container">
            <div class="text-center mx-auto mb-5" style="max-width: 650px;">
                <span class="text-navy fw-bold text-uppercase small">Ekosistem Operasional</span>
                <h2 class="fw-bold mt-1 text-navy">Terhubung Langsung dengan Kasir & Kontrol Batch</h2>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="bg-white p-4 rounded-4 shadow-sm h-100 border">
                        <i class="bi bi-cart-check-fill text-navy fs-3 mb-2 d-block"></i>
                        <h5 class="fw-bold text-navy">Kasir POS & Algoritma FEFO</h5>
                        <p class="text-muted small mb-0">Setiap transaksi di kasir otomatis memperbarui data volume & frekuensi SAW sekaligus memotong stok batch dengan tanggal kedaluwarsa terdekat.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white p-4 rounded-4 shadow-sm h-100 border">
                        <i class="bi bi-exclamation-triangle-fill text-warning fs-3 mb-2 d-block"></i>
                        <h5 class="fw-bold text-navy">Peringatan Dini Kadaluarsa</h5>
                        <p class="text-muted small mb-0">Batch obat yang kedaluwarsa otomatis diblokir dari penjualan kasir dan ditandai untuk segera diproses melalui mutasi retur atau pemusnahan.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white p-4 rounded-4 shadow-sm h-100 border">
                        <i class="bi bi-building-check text-teal fs-3 mb-2 d-block"></i>
                        <h5 class="fw-bold text-navy">Multi-Tenant & Manajemen Role</h5>
                        <p class="text-muted small mb-0">Mendukung pemantauan mandiri oleh masing-masing apotek dengan pembagian akses terstruktur untuk SuperAdmin, Owner, dan Admin.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. CALL TO ACTION (CTA) -->
    <section class="py-5 my-3">
        <div class="container">
            <div class="cta-section p-5 text-center shadow">
                <h2 class="fw-bold mb-3">Optimalkan Perencanaan Stok Obat Fast Moving Anda</h2>
                <p class="text-white-50 mx-auto mb-4" style="max-width: 580px;">
                    Masuk ke SOBAT untuk memantau peringkat prioritas restock obat secara real-time dan menjaga ketersediaan stok apotek tetap aman.
                </p>
                <a href="{{ route('backend.login') }}" class="btn btn-sobat-light btn-lg rounded-pill px-5">
                    <i class="bi bi-box-arrow-in-right me-2"></i> Masuk SOBAT
                </a>
            </div>
        </div>
    </section>

    <!-- 6. FOOTER -->
    <footer class="bg-white border-top py-4 mt-4">
        <div class="container">
            <div class="row align-items-center gy-3">
                <div class="col-md-6 text-center text-md-start">
                    <div class="d-inline-flex align-items-center gap-2">
                        <img src="{{ asset('Img/logo Sobat.png') }}" alt="Logo Sobat" height="24">
                        <span class="fw-bold text-navy">SOBAT</span>
                        <span class="text-muted small">— Sistem Operasional Barang Apotek Terintegrasi</span>
                    </div>
                </div>
                <div class="col-md-6 text-center text-md-end text-muted small">
                    Copyright &copy; 2026 <a href="https://github.com/Adryn27/Sobat" class="text-decoration-none fw-semibold text-navy">Sobat Apotek</a>. All rights reserved.
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>