@extends('backend.layout.app')

@section('content')

<div class="container-fluid p-4">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="fw-bold">{{ $judul }}</h3>
            <p class="text-muted small">{{ $subjudul }}</p>
            @php
                use Carbon\Carbon;
                $endDate = Carbon::now();
                $startDate = Carbon::now()->subDays($days);
            @endphp

            <span class="badge bg-light text-primary border border-primary-subtle px-3 py-2">
                <i class="bi bi-calendar-range me-1"></i> 
                Periode: 
                <strong>{{ $startDate->format('d M Y') }}</strong> 
                s/d 
                <strong>{{ $endDate->format('d M Y') }}</strong>
            </span>
        </div>

        <div class="d-flex gap-2">

            {{-- REFRESH SAW --}}
            <form action="{{ route('backend.analysis.process') }}" method="POST">
                @csrf

                <input type="hidden"
                    name="days"
                    value="{{ $days }}">

                <button class="btn btn-success btn-sm">
                    <i class="bi bi-arrow-clockwise me-1"></i>
                    Refresh
                </button>
            </form>

            {{-- OPEN MODAL --}}
            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalBobot">
                <i class="bi bi-sliders me-1"></i> Bobot
            </button>

        </div>
    </div>

    {{-- ALERT CARD --}}
    @if(session('success'))
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body bg-success text-white">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body bg-danger text-white">
                {{ session('error') }}
            </div>
        </div>
    @endif


    {{-- FILTER --}}
    <form action="{{ route('backend.analysis.index') }}" method="GET" class="mb-3 d-flex gap-2">
        <select name="days" class="form-select w-auto">
            <option value="30" {{ $days == 30 ? 'selected' : '' }}>30 Hari</option>
            <option value="90" {{ $days == 90 ? 'selected' : '' }}>3 Bulan</option>
            <option value="180" {{ $days == 180 ? 'selected' : '' }}>6 Bulan</option>
            <option value="365" {{ $days == 365 ? 'selected' : '' }}>1 Tahun</option>
        </select>

        <button class="btn btn-primary btn-sm">Filter</button>
    </form>
    
    <div class="row">
        @php
            $kriteria = [
                ['nama' => 'C1 (Volume)', 'val' => $saw->c1_volume, 'icon' => 'bi-bar-chart-fill', 'color' => 'primary'],
                ['nama' => 'C2 (Frekuensi)', 'val' => $saw->c2_frekuensi, 'icon' => 'bi-clock-history', 'color' => 'success'],
                ['nama' => 'C3 (Rasio Stok)', 'val' => $saw->c3_rasio, 'icon' => 'bi-box-seam', 'color' => 'info'],
            ];
        @endphp
    
        @foreach($kriteria as $item)
        <div class="col-md-4 mb-4">
            <div class="card border-left-{{ $item['color'] }} shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-{{ $item['color'] }} text-uppercase mb-1">
                                {{ $item['nama'] }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $item['val'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi {{ $item['icon'] }} fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- TABLE --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-bold">
            Ranking SAW
        </div>

        <div class="table-responsive">
            <table class="table table-hover text-center align-middle mb-0">
                <thead class="table-light small">
                    <tr>
                        <th>Rank</th>
                        <th class="text-start">Obat</th>
                        <th>Volume</th>
                        <th>Frekuensi</th>
                        <th>Stok</th>
                        <th>SAW</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($index as $row)

                    @php
                        $score = $row->nilai_saw;

                        $status = $score >= 0.75 ? 'Prioritas Tinggi' :
                                  ($score >= 0.5 ? 'Prioritas Sedang' : 'Prioritas Rendah');

                        $color = $score >= 0.75 ? 'danger' :
                                 ($score >= 0.5 ? 'warning text-dark' : 'success');
                    @endphp

                    <tr>
                        <td class="fw-bold">#{{ $loop->iteration }}</td>

                        <td class="text-start">
                            <div class="fw-bold">{{ $row->nama_obat }}</div>
                            <small class="text-muted">{{ $row->kode_obat }}</small>
                        </td>

                        <td>{{ $row->detailPenjualan->sum('jumlah') }}</td>
                        <td>{{ $row->detailPenjualan->count() }}</td>
                        <td>{{ $row->stok }} / {{ $row->stok_maksimal }}</td>
                        {{-- <td>
                            @php
                                $c4 = $row->stok > 0 ? ($row->detailPenjualan->sum('jumlah') / $row->stok) : 0;
                            @endphp

                            @if($c4 >= 2)
                                {{ number_format($c4, 2) }} <br>
                                <span class="badge bg-danger">Sangat Tinggi</span>
                            @elseif($c4 > 1)
                                {{ number_format($c4, 2) }} <br>
                                <span class="badge" style="background-color: #fd7e14; color: #fff;">Tinggi</span>
                            @elseif($c4 >= 0.5)
                                {{ number_format($c4, 2) }} <br>
                                <span class="badge bg-warning text-dark">Sedang</span>
                            @else
                                {{ number_format($c4, 2) }} <br>
                                <span class="badge bg-success">Rendah</span>
                            @endif
                        </td> --}}

                        <td class="fw-bold text-primary">
                            {{ number_format($row->nilai_saw, 4) }}
                        </td>

                        <td>
                            <span class="badge bg-{{ $color }}">
                                {{ $status }}
                            </span>
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>


{{-- ================= MODAL BOBOT ================= --}}
<div class="modal fade" id="modalBobot" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('backend.analysis.update', $saw->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">Atur Bobot SAW</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-2">
                        <label>C1 Volume</label>
                        <input type="number" step="0.01" class="form-control"
                               name="c1_volume" value="{{ $saw->c1_volume }}">
                    </div>

                    <div class="mb-2">
                        <label>C2 Frekuensi</label>
                        <input type="number" step="0.01" class="form-control"
                               name="c2_frekuensi" value="{{ $saw->c2_frekuensi }}">
                    </div>

                    <div class="mb-2">
                        <label>C3 Rasio Stok</label>
                        <input type="number" step="0.01" class="form-control"
                               name="c3_rasio" value="{{ $saw->c3_rasio }}">
                    </div>

                    {{-- <div class="mb-2">
                        <label>C4 Intensitas</label>
                        <input type="number" step="0.01" class="form-control"
                               name="c4_intensitas" value="{{ $saw->c4_intensitas }}">
                    </div> --}}

                    <small class="text-muted">
                        Total bobot harus = 1.00
                    </small>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection