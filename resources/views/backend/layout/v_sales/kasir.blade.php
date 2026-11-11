@extends('backend.layout.app')
@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .kasir-container {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        color: #334155;
        font-size: 14px;
    }
    .card-minimal {
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        background: #ffffff;
    }
    .table-modern thead {
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    .table-modern th {
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 10px 12px;
    }
    .table-modern td {
        padding: 10px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
    }
    .total-box {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
    }
    .btn-checkout {
        background-color: #10b981;
        color: white;
        border: none;
        font-size: 14px;
        transition: background-color 0.2s ease;
    }
    .btn-checkout:hover {
        background-color: #059669;
        color: white;
    }
    .btn-checkout:disabled {
        background-color: #cbd5e1;
        color: #94a3b8;
        cursor: not-allowed;
    }
    .form-control-sm {
        font-size: 13px;
        border-radius: 4px;
    }
    .form-control:focus {
        border-color: #cbd5e1;
        box-shadow: none;
    }
    .dropdown-item.item-obat-pilihan:hover {
        background-color: #f8fafc;
    }
</style>

<div class="container-fluid px-3 kasir-container mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold m-0 text-dark">{{ $judul }}</h3>
            <small class="text-muted" style="font-size: 12px;">Apotek Sapphire Farma</small>
        </div>
    
        <div class="col-sm-6 d-flex flex-column align-items-end justify-content-center">
            <ol class="breadcrumb m-0 p-0 bg-transparent mb-1">
                <li class="breadcrumb-item"><a href="{{ route('backend.beranda') }}" class="text-decoration-none">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Cashier</li>
            </ol>
    
            <span class="badge bg-white text-secondary border px-2 py-1.5 shadow-sm" style="font-size: 11px; max-width: max-content;">
                <i class="bi bi-calendar3 text-success me-1"></i> {{ date('d M Y') }}
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success py-2 px-3 border-0 small mb-3">
            <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger py-2 px-3 border-0 small mb-3">
            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger py-2 px-3 border-0 small mb-3">
            <div class="fw-bold mb-1"><i class="bi bi-exclamation-circle-fill me-1"></i> Gagal Memproses Transaksi:</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('backend.cashier.store') }}" method="POST">
        @csrf
        <div class="row g-3">
            
            <div class="col-lg-8">
                <div class="card card-minimal mb-3">
                    <div class="card-body p-3">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-9 position-relative">
                                <label class="form-label mb-1 fw-bold text-secondary text-uppercase" style="font-size: 11px;">Cari SKU / Nama Obat</label>
                                
                                <div class="dropdown">
                                    <input type="text" id="pilih-obat-input" class="form-control form-control-sm" placeholder="Ketik nama obat atau kode obat" autocomplete="off">
                                    
                                    <ul class="dropdown-menu w-100 shadow-sm py-0 mt-1" id="dropdown-obat" style="max-height: 250px; overflow-y: auto; font-size: 13px; border-color: #e2e8f0;">
                                        @foreach($index as $med)
                                            <li>
                                                @php
                                                    $namaSatuan = 'Pcs';
                                                    if($med->satuan == '0') $namaSatuan = 'Box';
                                                    elseif($med->satuan == '1') $namaSatuan = 'Botol';
                                                    elseif($med->satuan == '2') $namaSatuan = 'Strip';
                                                @endphp
                                                <a class="dropdown-item item-obat-pilihan border-bottom py-2 px-3" href="#" 
                                                   data-value="{{ $med->kode_obat }} — {{ $med->nama_obat }}"
                                                   data-id="{{ $med->id }}"
                                                   data-nama="{{ $med->nama_obat }}"
                                                   data-harga="{{ $med->harga_offline }}"
                                                   data-stok="{{ $med->stok }}"
                                                   data-satuan="{{ $namaSatuan }}">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="fw-bold text-dark">{{ $med->kode_obat }} — {{ $med->nama_obat }}</div>
                                                        <span class="badge bg-light text-dark border" style="font-size: 10px;">{{ $namaSatuan }}</span>
                                                    </div>
                                                    <small class="text-muted">Harga: Rp {{ number_format($med->harga_offline, 0, ',', '.') }} | Stok: <span class="fw-semibold">{{ $med->stok }}</span></small>
                                                </a>
                                            </li>
                                        @endforeach
                                        <li id="obat-tidak-ditemukan" style="display: none;"><span class="dropdown-item text-muted py-3 text-center">Obat tidak ditemukan</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-3 mt-md-4 pt-md-1">
                                <button type="button" id="btn-tambah-item" class="btn btn-sm btn-dark w-100 py-1.5">
                                    <i class="bi bi-plus-lg me-1"></i> Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-minimal">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-modern m-0" id="tabel-keranjang">
                                <thead>
                                    <tr>
                                        <th style="width: 40%">Nama Item</th>
                                        <th style="width: 20%" class="text-end">Harga Satuan</th>
                                        <th style="width: 20%" class="text-center">Jumlah</th>
                                        <th style="width: 15%" class="text-end">Subtotal</th>
                                        <th style="width: 5%" class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="keranjang-kosong">
                                        <td colspan="5" class="text-center py-4 text-muted" style="font-size: 13px;">
                                            <i class="bi bi-basket2 d-block mb-1 fs-5 text-black-50"></i>
                                            Belum ada item obat yang dipilih.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-minimal sticky-top" style="top: 15px;">
                    <div class="card-body p-3">
                        
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                            <span class="text-muted fw-bold text-uppercase" style="font-size: 11px;">No. Invoice</span>
                            <span class="fw-bold text-dark small">{{ $invoice }}</span>
                            <input type="hidden" name="no_invoice" value="{{ $invoice }}">
                        </div>

                        <div class="total-box p-3 text-end mb-3">
                            <span class="text-muted d-block mb-1 text-uppercase fw-semibold" style="font-size: 11px;">Total Tagihan</span>
                            <h3 class="m-0 fw-bold text-dark" style="letter-spacing: -0.5px;">Rp <span id="text-total">0</span></h3>
                            <input type="hidden" name="total_harga" id="input-total" value="0">
                        </div>

                        <div class="mb-3">
                            <label class="form-label mb-1 fw-bold text-secondary text-uppercase" style="font-size: 11px;">Uang Diterima (Rp)</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white border-end-0 text-muted" style="font-size: 13px;">Rp</span>
                                <input type="number" name="bayar" id="input-bayar" class="form-control form-control-sm border-start-0 fw-bold" placeholder="0" required>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3 py-1 px-1">
                            <span class="text-muted fw-bold text-uppercase" style="font-size: 11px;">Kembalian</span>
                            <span class="fw-bold text-secondary fs-5" id="text-kembali">Rp 0</span>
                            <input type="hidden" id="input-kembali" value="0">
                        </div>

                        <button type="submit" id="btn-simpan-transaksi" class="btn btn-checkout btn-sm w-100 fw-semibold py-2" disabled>
                            <i class="bi bi-check-circle me-1"></i> Simpan Transaksi
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    
    let selectedData = null; 
    $('#pilih-obat-input').focus();
    
    cekStatusTombol();

    function cekStatusTombol() {
        let jumlahItem = $('#tabel-keranjang tbody tr').not('#keranjang-kosong').length;
        let totalHarga = parseInt($('#input-total').val()) || 0;

        if (jumlahItem > 0 && totalHarga > 0) {
            $('#btn-simpan-transaksi').removeAttr('disabled');
        } else {
            $('#btn-simpan-transaksi').attr('disabled', true);
        }
    }
    
    $('#pilih-obat-input').on('input', function() {
        let value = $(this).val().toLowerCase();
        let anyMatch = false;
        selectedData = null; 

        if (value.length > 0) {
            $('#dropdown-obat').addClass('show'); 
        } else {
            $('#dropdown-obat').removeClass('show');
        }

        $('#dropdown-obat li').each(function() {
            let item = $(this).find('.item-obat-pilihan');
            if (item.length > 0) {
                let text = item.data('value').toLowerCase();
                if (text.indexOf(value) > -1) {
                    $(this).show();
                    anyMatch = true;
                } else {
                    $(this).hide();
                }
            }
        });

        if (anyMatch) {
            $('#obat-tidak-ditemukan').hide();
        } else {
            $('#obat-tidak-ditemukan').show();
        }
    });

    $(document).on('click', '.item-obat-pilihan', function(e) {
        e.preventDefault();
        
        selectedData = {
            id: $(this).data('id'),
            nama: $(this).data('nama'),
            harga: parseInt($(this).data('harga')),
            stok: parseInt($(this).data('stok')),
            satuan: $(this).data('satuan'),
            value: $(this).data('value')
        };

        $('#pilih-obat-input').val(selectedData.value);
        $('#dropdown-obat').removeClass('show'); 
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.dropdown').length) {
            $('#dropdown-obat').removeClass('show');
        }
    });

    $('#btn-tambah-item').on('click', function() {
        if (!selectedData) {
            let currentVal = $('#pilih-obat-input').val().toLowerCase();
            $('.item-obat-pilihan').each(function() {
                if ($(this).data('value').toLowerCase() === currentVal || $(this).data('value').split(' — ')[0].toLowerCase() === currentVal) {
                    selectedData = {
                        id: $(this).data('id'),
                        nama: $(this).data('nama'),
                        harga: parseInt($(this).data('harga')),
                        stok: parseInt($(this).data('stok')),
                        satuan: $(this).data('satuan')
                    };
                    return false;
                }
            });
        }

        if (!selectedData) {
            alert('Silakan pilih obat yang terdaftar di sistem!');
            return;
        }

        let id = selectedData.id;
        let nama = selectedData.nama;
        let harga = selectedData.harga;
        let stok = selectedData.stok;
        let satuan = selectedData.satuan;

        if (stok <= 0) {
            alert('Gagal menambah item. Stok obat ini kosong!');
            resetPencarian();
            return;
        }

        $('#keranjang-kosong').hide();

        if ($(`#qty-${id}`).length > 0) {
            let currentQty = parseInt($(`#qty-${id}`).val());
            if (currentQty >= stok) {
                alert('Stok maksimal di rak obat sudah tercapai!');
                return;
            }
            $(`#qty-${id}`).val(currentQty + 1).trigger('input');
            resetPencarian();
            return;
        }

        // Menambahkan elemen input jumlah beserta badge teks satuan di sampingnya secara fleksibel
        let barisBaru = `
            <tr id="baris-${id}">
                <td>
                    <div class="fw-bold text-dark">${nama}</div>
                    <input type="hidden" name="obat_id[]" value="${id}">
                </td>
                <td class="text-end text-secondary">
                    Rp ${harga.toLocaleString('id-ID')}
                    <input type="hidden" name="harga_jual[]" value="${harga}">
                </td>
                <td>
                    <div class="d-flex align-items-center justify-content-center gap-1">
                        <input type="number" name="jumlah[]" id="qty-${id}" class="form-control form-control-sm text-center hitung-subtotal m-0" value="1" min="1" max="${stok}" data-harga="${harga}" style="width: 55px; height: 28px; border-radius: 4px; padding: 2px;">
                        <span class="badge bg-light text-dark border lh-sm" style="padding: 6px 8px; font-size: 11px; height: 28px; display: flex; align-items: center;">${satuan}</span>
                    </div>
                </td>
                <td class="text-end fw-bold text-dark">
                    <span class="text-subtotal">Rp ${harga.toLocaleString('id-ID')}</span>
                    <input type="hidden" name="subtotal[]" class="input-subtotal" value="${harga}">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm text-danger p-0 border-0 btn-hapus" data-id="${id}">
                        <i class="bi bi-trash3"></i>
                    </button>
                </td>
            </tr>
        `;
        
        $('#tabel-keranjang tbody').append(barisBaru);
        hitungTotalSemua();
        resetPencarian();
    });

    $('#pilih-obat-input').on('keypress', function(e) {
        if (e.which == 13) {
            e.preventDefault();
            $('#btn-tambah-item').click();
        }
    });

    function resetPencarian() {
        $('#pilih-obat-input').val('').focus();
        $('#dropdown-obat').removeClass('show');
        selectedData = null;
    }

    $(document).on('input', '.hitung-subtotal', function() {
        let qty = parseInt($(this).val()) || 0;
        let harga = parseInt($(this).data('harga'));
        let maxStok = parseInt($(this).attr('max'));

        if (qty > maxStok) {
            alert('Stok obat aktual gudang tidak mencukupi!');
            $(this).val(maxStok);
            qty = maxStok;
        }

        let subtotal = qty * harga;
        $(this).closest('tr').find('.text-subtotal').text('Rp ' + subtotal.toLocaleString('id-ID'));
        $(this).closest('tr').find('.input-subtotal').val(subtotal);
        
        hitungTotalSemua();
    });

    $(document).on('click', '.btn-hapus', function() {
        let id = $(this).data('id');
        $(`#baris-${id}`).remove();
        
        if ($('#tabel-keranjang tbody tr').length === 1) {
            $('#keranjang-kosong').show();
        }
        hitungTotalSemua();
    });

    $('#input-bayar').on('input', function() {
        let total = parseInt($('#input-total').val()) || 0;
        let bayar = parseInt($(this).val()) || 0;
        let kembali = bayar - total;

        if (kembali < 0) {
            $('#text-kembali').text('Rp 0').removeClass('text-success').addClass('text-secondary');
        } else {
            $('#text-kembali').text('Rp ' + kembali.toLocaleString('id-ID')).removeClass('text-secondary').addClass('text-success');
        }
    });

    function hitungTotalSemua() {
        let total = 0;
        $('.input-subtotal').each(function() {
            total += parseInt($(this).val()) || 0;
        });

        $('#text-total').text(total.toLocaleString('id-ID'));
        $('#input-total').val(total);
        $('#input-bayar').trigger('input');
        
        cekStatusTombol();
    }
});
</script>
@endsection