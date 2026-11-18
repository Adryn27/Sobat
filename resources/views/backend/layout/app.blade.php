<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ $judul }}</title>

    <link
        rel="icon"
        type="image/jpg"
        sizes="32x32"
        href="{{ asset('Img/logo Sobat.png') }}"
    />

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->

    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="{{ asset('Base/dist/css/adminlte.css') }}" as="style" />
    <!--end::Accessibility Features-->

    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
      media="print"
      onload="this.media = 'all'"
    />
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{ asset('Base/dist/css/adminlte.css') }}" />
    <!--end::Required Plugin(AdminLTE)-->

    <!-- DataTables Bootstrap 5 Styling -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <style>
      .dropdown-menu-notifications {
        max-height: 400px;
        overflow-y: auto;  
        scrollbar-width: thin; 
      }
    </style>
    <script>

      document.addEventListener('DOMContentLoaded', function(){
      
          const logoutBtn =
              document.querySelector('.show_confirm_logout');
      
          logoutBtn.addEventListener('click', function(e){     
              e.preventDefault();      
              Swal.fire({      
                  title: 'Konfirmasi Logout?',      
                  text: 'Anda akan keluar dari sistem.',      
                  icon: 'warning',      
                  showCancelButton: true,     
                  confirmButtonColor: '#0d6efd',      
                  cancelButtonColor: '#d33',      
                  confirmButtonText: 'Ya, Logout',      
                  cancelButtonText: 'Batal',      
              }).then((result)=>{      
                  if(result.isConfirmed){      
                      document
                          .getElementById('logout-form')
                          .submit();
                  }
              });
          });
      });
      
    </script>
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg sidebar-mini bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
          </ul>
          <!--end::Start Navbar Links-->

          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">

            <!--begin::Notifications Dropdown Menu-->
            <li class="nav-item dropdown">
              <a class="nav-link" data-bs-toggle="dropdown" href="#">
                  <i class="bi bi-bell-fill"></i>
                  {{-- Badge hanya muncul jika totalNotif > 0 --}}
                  @if($totalNotif > 0)
                      <span class="navbar-badge badge text-bg-warning">{{ $totalNotif }}</span>
                  @endif
              </a>
              <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end dropdown-menu-notifications">
                  <span class="dropdown-item dropdown-header">{{ $totalNotif }} Notifikasi</span>
          
                  @forelse($stokKritis as $obat)
                      <div class="dropdown-divider"></div>
                      <a href="{{ route('backend.stock.index') }}" class="dropdown-item text-danger text-wrap">
                          <i class="bi bi-exclamation-triangle me-2"></i>
                          <strong>{{ $obat->nama_obat }}</strong><br>
                          <small>Stok kritis: sisa {{ $obat->stok }} (di bawah 50% dari maks {{ $obat->stok_maksimal }})</small>
                      </a>
                  @empty
                      {{-- Tidak ada stok kritis --}}
                  @endforelse
          
                  @forelse($prioritasTinggi as $obat)
                      <div class="dropdown-divider"></div>
                      <a href="{{ route('backend.analysis.index') }}" class="dropdown-item text-primary text-wrap">
                          <i class="bi bi-award me-2"></i>
                          <strong>{{ $obat->nama_obat }}</strong><br>
                          <small>Prioritas Restock: Nilai SAW tinggi berdasarkan performa Volume, Frekuensi, Rasio Stok & Intensitas.</small>
                      </a>
                  @empty
                      {{-- Tidak ada prioritas tinggi --}}
                  @endforelse

                  @forelse($batchExpired as $batch)
                      <div class="dropdown-divider"></div>
                      <a href="{{ route('backend.stock.inven') }}" class="dropdown-item text-danger text-wrap">
                          <i class="bi bi-calendar-x"></i>
                          <strong>{{ $batch->obat->nama_obat ?? 'Obat' }}</strong><br>
                          <small>
                              Batch: <b>{{ $batch->nomor_batch }}</b> sudah Expired pada {{ date('d-m-Y', strtotime($batch->expired_date)) }} (Sisa: {{ $batch->stok }})
                          </small>
                      </a>
                  @empty
                      {{-- Obat Kadaluarsa --}}
                  @endforelse
          
                  {{-- Jika tidak ada notifikasi sama sekali --}}
                  @if($totalNotif == 0)
                      <div class="dropdown-divider"></div>
                      <span class="dropdown-item text-muted text-center">
                          <i class="bi bi-check-circle me-1"></i> Tidak ada notifikasi baru
                      </span>
                  @endif
              </div>
          </li>
            <!--end::Notifications Dropdown Menu-->

            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
              <a class="nav-link" href="#" data-lte-toggle="fullscreen">
                <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                <i data-lte-icon="minimize" class="bi bi-fullscreen-exit" style="display: none"></i>
              </a>
            </li>
            <!--end::Fullscreen Toggle-->

            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                @if (Auth::user()->foto)
                  <img
                    src="{{ asset('storage/img-user/' . Auth::user()->foto) }}"
                    alt="user"
                    class="user-image rounded-circle shadow"
                    width="31"
                  />
                @else
                  <img
                    src="{{ asset('Img/img-default.jpg') }}"
                    alt="user"
                    class="user-image rounded-circle shadow"
                    width="31"
                  />
                @endif
                <span class="d-none d-md-inline">Welcome, {{ Auth::user()->nama }}</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end shadow border-0 rounded-3 overflow-hidden" style="min-width: 280px;">
    
                <li class="px-3 py-2 bg-light border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center text-truncate pe-2">
                        <i class="bi bi-shop text-primary me-2 fs-5"></i>
                        <span class="fw-bold text-dark text-truncate" style="font-size: 0.9rem;">
                            {{ Auth::user()->tenants->nama_tenant}}
                        </span>
                    </div>
                    
                    @php
                        $isApotekAktif = (Auth::user()->tenants->status) == 1; 
                    @endphp
            
                    <span class="d-inline-flex align-items-center badge rounded-pill {{ $isApotekAktif ? 'bg-success-subtle text-success border border-success' : 'bg-danger-subtle text-danger border border-danger' }}" 
                          title="{{ $isApotekAktif ? 'Apotek Aktif' : 'Apotek Nonaktif' }}">
                        <span class="rounded-circle me-1 {{ $isApotekAktif ? 'bg-success' : 'bg-danger' }}" 
                              style="width: 8px; height: 8px; display: inline-block;"></span>
                        <span style="font-size: 0.7rem;">{{ $isApotekAktif ? 'Aktif' : 'Nonaktif' }}</span>
                    </span>
                </li>
            
                <li>
                    <a href="{{ route('backend.profile') }}" class="dropdown-item p-3 d-flex align-items-center justify-content-between border-bottom">
                        <div class="d-flex align-items-center overflow-hidden">
                            <!-- Foto Profil -->
                            @if (Auth::user()->foto)
                                <img
                                    src="{{ asset('storage/img-user/' . Auth::user()->foto) }}"
                                    alt="user"
                                    class="rounded-circle shadow-sm me-3 flex-shrink-0"
                                    style="width: 48px; height: 48px; object-fit: cover;"
                                />
                            @else
                                <img
                                    src="{{ asset('Img/img-default.jpg') }}"
                                    alt="user"
                                    class="rounded-circle shadow-sm me-3 flex-shrink-0"
                                    style="width: 48px; height: 48px; object-fit: cover;"
                                />
                            @endif
            
                            <!-- Nama, Role & Email -->
                            <div class="text-truncate">
                                <div class="fw-bold text-dark text-truncate mb-0">
                                    {{ Auth::user()->nama }}
                                </div>
                                <div class="small text-muted text-truncate">
                                    {{ Auth::user()->email }}
                                </div>
                                @if (Auth::user()->role == '2')
                                <span class="badge rounded-pill fw-bold bg-warning-subtle text-warning-emphasis mt-1" style="font-size: 0.75rem; padding: 6px 12px;">
                                    <i class="bi bi-award-fill me-1"></i> Pemilik
                                </span>
                                @elseif (Auth::user()->role == '1')
                                <span class="badge rounded-pill fw-bold bg-secondary-subtle text-secondary mt-1" style="font-size: 0.75rem; padding: 6px 12px;">
                                    <i class="bi bi-person-badge-fill me-1"></i> Admin
                                </span>
                                @elseif (Auth::user()->role == '3')
                                <span class="badge rounded-pill fw-bold bg-primary-subtle text-primary-emphasis mt-1" style="font-size: 0.75rem; padding: 6px 12px;">
                                    <i class="bi bi-capsule-pill me-1"></i> Apoteker
                                </span>
                                @elseif (Auth::user()->role == '4')
                                <span class="badge rounded-pill fw-bold bg-danger-subtle text-danger-emphasis mt-1" style="font-size: 0.75rem; padding: 6px 12px;">
                                    <i class="bi bi-box-seam-fill me-1"></i> Kepala Gudang
                                </span>
                                @endif
                                {{-- <span class="badge bg-primary-subtle text-primary mt-1" style="font-size: 0.7rem;">
                                    @if (Auth::user()->role == '3')
                                        Apoteker
                                    @elseif (Auth::user()->role == '1')
                                        Admin
                                    @elseif (Auth::user()->role == '2')
                                        Pemilik
                                    @elseif (Auth::user()->role == '4')
                                        Kepala Gudang
                                    @endif
                                </span> --}}
                            </div>
                        </div>
            
                        <!-- Tanda Panah (>) -->
                        <i class="bi bi-chevron-right text-muted ms-2"></i>
                    </a>
                </li>
            
                <!-- 3. TOMBOL SIGN OUT -->
                <li class="p-2 bg-white">
                    <a href="#" class="btn btn-outline-danger btn-sm w-100 d-flex align-items-center justify-content-center show_confirm_logout">
                        <i class="bi bi-box-arrow-right me-2"></i> Sign out
                    </a>
                    
                    <form
                        id="logout-form"
                        action="{{ route('backend.logout') }}"
                        method="POST"
                        class="d-none"
                    >
                        @csrf
                    </form>
                </li>
            </ul>
              {{-- <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <!--begin::User Image-->
                <li class="user-header text-bg-primary">
                  @if (Auth::user()->foto)
                  <img
                    src="{{ asset('storage/img-user/' . Auth::user()->foto) }}"
                    alt="user"
                    class="rounded-circle shadow"
                  />
                @else
                  <img
                    src="{{ asset('Img/img-default.jpg') }}"
                    alt="user"
                    class="rounded-circle shadow"
                  />
                @endif
                  <p>
                    {{ Auth::user()->nama }} - 
                    @if (Auth::user()->role == '0')
                      SuperAdmin
                    @elseif (Auth::user()->role == '1')
                      Admin
                    @elseif (Auth::user()->role == '2')
                      Owner
                    @endif
                    <small>
                      {{ Auth::user()->email }}
                    </small>
                  </p>
                </li>
                <!--end::User Image-->
                <!--begin::Menu Footer-->
                <li class="user-footer">
                  <a href="{{ route('backend.profile') }}" class="btn btn-outline-secondary">Profile</a>
                  <a href="#" class="btn btn-outline-danger float-end show_confirm_logout">
                    Sign out               
                  </a>
                  
                  <form
                      id="logout-form"
                      action="{{ route('backend.logout') }}"
                      method="POST"
                      class="d-none"
                  >
                  
                      @csrf
                  
                  </form>
                </li>
                <!--end::Menu Footer-->
              </ul> --}}
            </li>
            <!--end::User Menu Dropdown-->
          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->
      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-primary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="{{ route('backend.beranda') }}" class="brand-link">
            <!--begin::Brand Image-->
            <span class="brand-image bg-white rounded-3 d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 34px; height: 34px; padding: 3px;">
              <img 
                  src="{{ asset('Img/logo Sobat.png') }}" 
                  alt="Sobat Logo" 
                  style="width: 100%; height: 100%; object-fit: contain;"
              />
            </span>
            <span class="brand-text fw-bold ms-2">SOBAT Apotek</span>
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="navigation"
              aria-label="Main navigation"
              data-accordion="false"
              id="navigation"
            >
              <li class="nav-item">
                <a href="{{ route('backend.beranda') }}" class="nav-link {{ Route::is('backend.beranda') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>
                    Dashboard
                  </p>
                </a>
              </li>

              @if (Auth::user()->role=="2"||Auth::user()->role=="2")
              <li class="nav-header">OPTION</li>
              <li class="nav-item">
                <a href="{{ route('backend.user.index') }}" class="nav-link {{ Route::is('backend.user.index') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-person-fill"></i>
                  <p>
                    User
                  </p>
                </a>
              </li>
              @endif
              @if (Auth::user()->role=="2"||Auth::user()->role=="2"||Auth::user()->role=="3"||Auth::user()->role=="4")
              <li class="nav-header">DATA MANAGEMENT</li>
              <li class="nav-item">
                <a href="{{ route('backend.category.index') }}" class="nav-link {{ Route::is('backend.category.index') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-folder-fill"></i>
                  <p>
                    Category
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('backend.product.index') }}" class="nav-link {{ Route::is('backend.product.index') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-box-seam-fill"></i>
                  <p>
                    Product
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('backend.supplier.index') }}" class="nav-link {{ Route::is('backend.supplier.index') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-building"></i>
                  <p>
                    Supplier
                  </p>
                </a>
              </li>
              @endif
              @if (Auth::user()->role=="2"||Auth::user()->role=="1")
              <li class="nav-header">TRANSACTION</li>
              <li class="nav-item">
                <a href="{{ route('backend.cashier.index') }}" class="nav-link {{ Route::is('backend.cashier.index') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-cart-fill"></i>
                  <p>
                    Cashier
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('backend.cashier.history') }}" class="nav-link {{ Route::is('backend.cashier.history') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-clock-history"></i>
                  <p>
                    History
                  </p>
                </a>
              </li>
              @endif
              <li class="nav-header">STOCK MANAGEMENT</li>
              @if (Auth::user()->role=="2"||Auth::user()->role=="1"||Auth::user()->role=="4")
              <li class="nav-item">
                <a href="{{ route('backend.stock.inven') }}" class="nav-link {{ Route::is('backend.stock.inven') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-journal-text"></i>
                  <p>
                    Inventory
                  </p>
                </a>
              </li>
              @endif
              @if (Auth::user()->role=="2"||Auth::user()->role=="4")
              <li class="nav-item">
                <a href="{{ route('backend.stock.index') }}" class="nav-link {{ Route::is('backend.stock.index') ? 'active' : '' }}">
                  <i class="nav-icon bi-boxes"></i>
                  <p>
                    Stock Adjustment
                  </p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('backend.analysis.index') }}" class="nav-link {{ Route::is('backend.analysis.index') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-list-ol"></i>
                  <p>
                    Analytics
                  </p>
                </a>
              </li>
              @endif

              <li class="nav-header">REPORT</li>
              <li class="nav-item">
                <a href="{{ route('backend.cashier.report') }}" class="nav-link {{ Route::is('backend.cashier.report') ? 'active' : '' }}">
                  <i class="nav-icon bi bi-graph-up-arrow"></i>
                  <p>
                    Sales
                  </p>
                </a>
              </li>
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        @yield('content')
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
      <!--begin::Footer-->
      <footer class="app-footer">
        <!--begin::To the end-->
        <div class="float-end d-none d-sm-inline">
          <img src="{{ asset('img/logo Sobat.png') }}" alt="Logo Sobat" height="25" class="me-2">
        </div>
        <!--end::To the end-->
        <!--begin::Copyright-->
        <strong>
          Copyright &copy; 2026&nbsp;
          <a href="https://github.com/Adryn27/Sobat" class="text-decoration-none">Sobat Apotek</a>.
        </strong>
        All rights reserved.
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="{{ asset('Base/dist/js/adminlte.js') }}"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables Core & Bootstrap 5 Integration -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
      $(document).ready(function() {
          $('#table').DataTable()
      });
    </script>

    {{-- Plugin --}}
    <script src="{{ asset('plugin/sweetalert2.all.min.js')}}"></script>

    @if (session('success'))
      <script>
        Swal.fire({
          icon: 'success',
          title: 'Berhasil',
          text: "{{ session('success') }}"
        })
      </script>
    @endif

    @if (session('error'))
      <script>
          Swal.fire({
              icon: 'error',
              title: 'Gagal',
              text: "{{ session('error') }}"
          })
      </script>
    @endif

    <script type="text/javascript">
      // Konfirmasi delete
      $('.show_confirm').click(function(event){
        var form=$(this).closest("form");
        var konfdelete=$(this).data("konf-delete");
        event.preventDefault();
        Swal.fire({
          title: 'Konfirmasi Hapus Data?',
          html: "Data yang dihapus <strong>" + konfdelete + "</strong> tidak dapat dikembalikan!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, dihapus',
          cancelButtonText: 'Batal',
        }).then((result)=>{
          if(result.isConfirmed){
                form.submit();
          }
        });
      });
    </script>

    <script>
      $('.show_reset').click(function(event){
          var form = $(this).closest("form");
          var konfReset = $(this).data("konf-reset");
          event.preventDefault();
          
          Swal.fire({
              title: 'Konfirmasi Reset Password?',
              html: "Sistem akan mengirimkan tautan pengaturan ulang kata sandi ke Gmail staf <strong>" + konfReset + "</strong>.",
              icon: 'warning', 
              showCancelButton: true,
              confirmButtonColor: '#3085d6', 
              cancelButtonColor: '#d33',  
              confirmButtonText: 'Ya, Reset Password',
              cancelButtonText: 'Batal',
          }).then((result) => {
              if(result.isConfirmed){
                  Swal.fire({
                      title: 'Mohon Tunggu...',
                      html: 'Sedang mengirimkan email ke staf.',
                      allowOutsideClick: false,
                      didOpen: () => {
                          Swal.showLoading();
                      }
                  });
                  form.submit();
              }
          });
      });
    </script>

    {{-- Preview --}}
    <script>
      //Preview Foto
      function previewFoto(){
        const foto = document.querySelector('input[name="foto"]');
        const fotoPreview = document.querySelector('.foto-preview');
        fotoPreview.style.display = 'block';
        const fotoReader = new FileReader();
        fotoReader.readAsDataURL(foto.files[0]);
        fotoReader.onload = function(fotoEvent) {
          fotoPreview.src = fotoEvent.target.result;
          fotoPreview.style.width = '100%';
        }
      }
    </script>

    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);

        // Disable OverlayScrollbars on mobile devices to prevent touch interference
        const isMobile = window.innerWidth <= 992;

        if (
          sidebarWrapper &&
          OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
          !isMobile
        ) {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure-->
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>
