@extends('backend.layout.app')
@section('content')

<style>
    /* Styling untuk Dashboard Variatif */
    .card-stat { border-left: 5px solid #4e73df; }
    .card-stat-fast { border-left-color: #1cc88a; }
    .card-stat-kritis { border-left-color: #e74a3b; }
    .card-stat-sales { border-left-color: #f6c23e; }
    .badge-prio { font-size: 0.85em; }
    .card:hover {
        transform: translateY(-5px);
        transition: transform 0.3s ease;
        cursor: pointer;
    }
</style>

<div class="container-fluid py-4">
    <div class="mb-4">
        {{-- BARIS JUDUL --}}
        <div class="mb-3">
            <h3 class="fw-bold mb-0">{{ $judul }}</h3>
            <span id="dateRangeDisplay" class="text-muted small fw-bold"></span>
        </div>
    
        {{-- BARIS FILTER & TOMBOL --}}
        <div class="row align-items-end">
            <div class="col-md-4 col-lg-3">
                {{-- <label class="fw-bold small mb-1">Periode Analisis</label> --}}
                <select id="days" class="form-select" onchange="loadDashboard()">
                    <option value="1">Hari Ini</option>
                    <option value="7">7 Hari</option>
                    <option value="30" selected>30 Hari</option>
                    <option value="90">3 Bulan</option>
                    <option value="180">6 Bulan</option>
                    <option value="365">1 Tahun</option>
                </select>
            </div>
        </div>
    </div>

    {{-- STAT CARDS --}}
    <div class="row mb-4">
        {{-- Total Obat --}}
        <div class="col-lg-3 mb-3">
            <div class="card shadow-sm border-0 text-white" style="background: linear-gradient(45deg, #4e73df, #224abe);">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3"><i class="bi bi-capsule fs-1 opacity-75"></i></div>
                    <div>
                        <small class="text-white-50">Total Obat</small>
                        <h2 id="totalObat" class="mb-0">0</h2>
                    </div>
                </div>
            </div>
        </div>
    
        {{-- Fast Moving --}}
        <div class="col-lg-3 mb-3">
            <div class="card shadow-sm border-0 text-white" style="background: linear-gradient(45deg, #1cc88a, #13855d);">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3"><i class="bi bi-graph-up-arrow fs-1 opacity-75"></i></div>
                    <div>
                        <small class="text-white-50">Fast Moving</small>
                        <h2 id="fastMoving" class="mb-0">0</h2>
                    </div>
                </div>
            </div>
        </div>
    
        {{-- Stok Kritis --}}
        <div class="col-lg-3 mb-3">
            <div class="card shadow-sm border-0 text-white" style="background: linear-gradient(45deg, #e74a3b, #be2617);">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3"><i class="bi bi-exclamation-triangle fs-1 opacity-75"></i></div>
                    <div>
                        <small class="text-white-50">Stok Kritis</small>
                        <h2 id="stokKritis" class="mb-0">0</h2>
                    </div>
                </div>
            </div>
        </div>
    
        {{-- Total Volume --}}
        <div class="col-lg-3 mb-3">
            <div class="card shadow-sm border-0 text-white" style="background: linear-gradient(45deg, #f6c23e, #dda20a);">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3"><i class="bi bi-cart-check fs-1 opacity-75"></i></div>
                    <div>
                        <small class="text-white-50">Volume Terjual</small>
                        <h5 id="totalSales" class="mb-0">0 Pcs</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- CHARTS & TABLES --}}
    <div class="row mb-4">
        <div class="col-lg-6"><div class="card shadow-sm border-0"><div class="card-header fw-bold">Tren Penjualan (Volume)</div><div class="card-body"><canvas id="salesChart"></canvas></div></div></div>
        <div class="col-lg-6"><div class="card shadow-sm border-0"><div class="card-header fw-bold">Top Intensitas Permintaan</div><div class="card-body"><canvas id="c4Chart"></canvas></div></div></div>
    </div>
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold py-3 d-flex align-items-center">
                    <div>
                        <i class="bi bi-award text-primary me-2"></i> Top Prioritas Restock (SAW)
                    </div>
                    
                    <button onclick="refreshDashboard()" class="btn btn-success btn-sm ms-auto" id="refreshBtn">
                        <i class="bi bi-arrow-repeat me-1" id="refreshIcon"></i>
                        <span id="btnText">Hitung Ulang</span>
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Nama Obat</th>
                                <th>Status Prioritas</th>
                                <th class="text-center">Stok / Max</th>
                                <th class="text-end">Skor SAW</th>
                            </tr>
                        </thead>
                        <tbody id="tableRestock">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white fw-bold py-3 text-danger">
                    <i class="bi bi-exclamation-octagon-fill me-2"></i> Alert Stok Kritis
                </div>
                <div class="list-group list-group-flush" id="alertStok">
                    </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let salesChart, c4Chart, sawChart, priorityChart;

    async function loadDashboard() {
        try {
            const days = document.getElementById('days').value;
            const response = await fetch("{{ route('backend.data') }}?days=" + days);
            const data = await response.json();

            // Tampilkan rentang tanggal yang dikirim dari Controller
            const dateRangeSpan = document.getElementById('dateRangeDisplay');
            dateRangeSpan.innerHTML = `
                <i class="bi bi-calendar-event me-1"></i> 
                Periode: ${data.start_date} ${data.start_date !== data.end_date ? ' s/d ' + data.end_date : ''}
            `;

            // 2. Update Stats
            document.getElementById('totalObat').innerText = data.total_obat;
            document.getElementById('fastMoving').innerText = data.fast_moving;
            document.getElementById('stokKritis').innerText = data.stok_kritis;
            document.getElementById('totalSales').innerText = data.total_sales;

            // 3. Update Tabel Restock
            let restock = '';
            data.top_restock.forEach((item, index) => {
                const formattedScore = parseFloat(item.nilai_saw).toFixed(3);
                const badgeClass = item.status === 'Prioritas Sedang' ? 'bg-warning text-dark' : `bg-${item.color.split(' ')[0]} text-white`;

                restock += `<tr>
                    <td class="text-center"><span class="badge bg-light text-dark rounded-circle">${index + 1}</span></td>
                    <td class="fw-bold">${item.nama_obat}</td>
                    <td>
                        <span class="badge ${badgeClass} shadow-sm px-2 py-1">
                            ${item.status}
                        </span>
                    </td>
                    <td class="text-center">${item.stok} / ${item.stok_maksimal}</td>
                    <td class="text-end font-monospace fw-bold text-primary">${formattedScore}</td>
                </tr>`;
            });
            document.getElementById('tableRestock').innerHTML = restock;

            // 4. Update Alert Stok
            let kritis = '';
            if (data.alert_stok.length > 0) {
                data.alert_stok.forEach(item => {
                    kritis += `
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div>
                                <div class="fw-bold text-dark">${item.nama_obat}</div>
                                <small class="text-muted">Stok saat ini: ${item.stok}</small>
                            </div>
                            <span class="badge bg-danger rounded-pill">Kritis</span>
                        </div>
                    `;
                });
            } else {
                kritis = `<div class="p-3 text-center text-muted">
                    <i class="bi bi-check-circle fs-3 text-success"></i>
                    <p class="mt-2">Semua stok aman!</p>
                </div>`;
            }
            document.getElementById('alertStok').innerHTML = kritis;

            // 5. Update Charts
            updateCharts(data);

        } catch (error) {
            console.error("Error loading dashboard:", error);
        }
    }

    function updateCharts(data) {
        // Hancurkan chart lama agar tidak tumpang tindih
        const charts = [salesChart, c4Chart, sawChart, priorityChart];
        charts.forEach(c => { if(c) c.destroy(); });

        // Sales Chart
        if (salesChart) salesChart.destroy();
        salesChart = new Chart(document.getElementById('salesChart'), {
            type: 'line',
            data: { 
                labels: data.sales_labels, 
                datasets: [{ 
                    label: 'Volume Penjualan', 
                    data: data.sales_values, 
                    borderColor: '#4e73df', 
                    backgroundColor: '#4e73df', // Warna titik
                    tension: 0.3, // Efek lengkung
                    fill: false,
                    pointRadius: 6,       // Membuat titik lebih menonjol jika data cuma 1
                    pointHoverRadius: 8   // Membesar saat di-hover
                }] 
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true, // Memastikan grafik dimulai dari 0
                        ticks: {
                            precision: 0 // Menghindari angka desimal pada volume
                        }
                    }
                },
                plugins: {
                    legend: { display: true }
                }
            }
        });

        // Bar Chart C4 (Top Intensitas)
        if(c4Chart) c4Chart.destroy();
        c4Chart = new Chart(document.getElementById('c4Chart'), {
            type: 'bar',
            data: { 
                labels: data.c4_labels, 
                datasets: [{ 
                    label: 'Intensitas', 
                    data: data.c4_values, 
                    backgroundColor: data.c4_colors
                }] 
            },
            options: {
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            generateLabels: function(chart) {
                                return [
                                    { text: 'Sangat Tinggi', fillStyle: '#e74a3b' },
                                    { text: 'Tinggi', fillStyle: '#fd7e14' },
                                    { text: 'Sedang', fillStyle: '#f6c23e' },
                                    { text: 'Rendah', fillStyle: '#1cc88a' }
                                ];
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            // Menambahkan label kategori ke dalam tooltip
                            label: function(context) {
                                let val = context.raw;
                                let cat = data.c4_categories[context.dataIndex];
                                return `Intensitas: ${val} (${cat})`;
                            }
                        }
                    }
                }
            }
        });
    }

    async function refreshDashboard() {
        const btn = document.getElementById('refreshBtn');
        const icon = document.getElementById('refreshIcon');
        
        // 1. Berikan feedback loading
        btn.disabled = true;
        icon.classList.add('bi-spin');
        
        // Gunakan span untuk teks agar tidak mengganggu ikon
        // Pastikan di HTML, teks "Hitung Ulang" dibungkus <span>
        const btnText = document.getElementById('btnText'); 
        if(btnText) btnText.innerText = " Menghitung...";

        try {
            const response = await fetch("{{ route('backend.analysis.process') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ days: document.getElementById('days').value })
            });

            if (!response.ok) throw new Error("Gagal melakukan kalkulasi");

            await loadDashboard();

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Data SAW diperbarui.',
                timer: 1500,
                showConfirmButton: false
            });

        } catch (error) {
            console.error("Error:", error);
            Swal.fire({ icon: 'error', title: 'Oops...', text: 'Gagal kalkulasi.' });
        } finally {
            // 4. Reset tombol dengan aman
            btn.disabled = false;
            icon.classList.remove('bi-spin');
            if(btnText) btnText.innerText = " Hitung Ulang";
        }
    }

    document.addEventListener('DOMContentLoaded', loadDashboard);
</script>
@endsection