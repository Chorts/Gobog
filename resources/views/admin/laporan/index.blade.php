@extends('layouts.app')
@section('title', 'Laporan Penjualan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="bi bi-file-earmark-bar-graph me-2 text-danger"></i>Laporan Penjualan</h2>
    <a href="{{ route('admin.laporan.export-pdf', request()->query()) }}" class="btn btn-danger">
        <i class="bi bi-file-pdf me-1"></i> Export PDF
    </a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="row g-3 align-items-end">
            <div class="col-auto">
                <label class="form-label fw-semibold small">Filter</label>
                <select name="filter" class="form-select" onchange="this.form.submit()">
                    <option value="harian"   {{ request('filter','harian')==='harian'  ?'selected':'' }}>Harian</option>
                    <option value="mingguan" {{ request('filter')==='mingguan'          ?'selected':'' }}>Mingguan</option>
                    <option value="bulanan"  {{ request('filter')==='bulanan'           ?'selected':'' }}>Bulanan</option>
                    <option value="tahunan"  {{ request('filter')==='tahunan'           ?'selected':'' }}>Tahunan</option>
                </select>
            </div>
            @if(in_array(request('filter','harian'), ['harian','mingguan']))
                <div class="col-auto">
                    <label class="form-label fw-semibold small">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal', now()->toDateString()) }}">
                </div>
            @elseif(request('filter')==='bulanan')
                <div class="col-auto">
                    <label class="form-label fw-semibold small">Bulan</label>
                    <input type="number" name="bulan" class="form-control" style="width:80px" min="1" max="12" value="{{ request('bulan', now()->month) }}">
                </div>
                <div class="col-auto">
                    <label class="form-label fw-semibold small">Tahun</label>
                    <input type="number" name="tahun" class="form-control" style="width:100px" min="2020" max="2099" value="{{ request('tahun', now()->year) }}">
                </div>
            @else
                <div class="col-auto">
                    <label class="form-label fw-semibold small">Tahun</label>
                    <input type="number" name="tahun" class="form-control" style="width:100px" min="2020" max="2099" value="{{ request('tahun', now()->year) }}">
                </div>
            @endif
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i>Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Distribusi Admin --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-success text-white fw-semibold">
        <i class="bi bi-arrow-right-circle me-2"></i>Distribusi Admin (Rupiah &rarr; Gobog)
        <span class="badge bg-white text-success ms-2">{{ $penjualanAdmins->count() }} transaksi</span>
    </div>
    <div class="card-body p-0">
        @if($penjualanAdmins->isEmpty())
            <p class="text-muted text-center py-4 mb-0">Tidak ada data pada periode ini.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light"><tr><th>#</th><th>Admin</th><th>Nilai (Rp)</th><th>Waktu</th></tr></thead>
                    <tbody>
                        @foreach($penjualanAdmins as $item)
                        <tr>
                            <td class="text-muted small">{{ $item->id }}</td>
                            <td>{{ $item->user->nama }}</td>
                            <td class="fw-semibold text-success">{{ number_format($item->gobog->nilai, 0, ',', '.') }}</td>
                            <td class="small">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- Transaksi Tenan --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-primary text-white fw-semibold">
        <i class="bi bi-shop me-2"></i>Transaksi Tenan (Gobog diterima pedagang)
        <span class="badge bg-white text-primary ms-2">{{ $penjualanTenans->count() }} transaksi</span>
    </div>
    <div class="card-body p-0">
        @if($penjualanTenans->isEmpty())
            <p class="text-muted text-center py-4 mb-0">Tidak ada data pada periode ini.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light"><tr><th>#</th><th>Penjual</th><th>Nilai (Rp)</th><th>Status Koin</th><th>Waktu</th></tr></thead>
                    <tbody>
                        @foreach($penjualanTenans as $item)
                        <tr>
                            <td class="text-muted small">{{ $item->id }}</td>
                            <td>{{ $item->user->nama }}</td>
                            <td class="fw-semibold">{{ number_format($item->gobog->nilai, 0, ',', '.') }}</td>
                            <td>
                                @if($item->valid_status)
                                    <span class="badge bg-success">ASLI</span>
                                @else
                                    <span class="badge bg-danger">PALSU</span>
                                @endif
                            </td>
                            <td class="small">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

{{-- Retur --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-info text-white fw-semibold">
        <i class="bi bi-arrow-left-circle me-2"></i>Retur (Gobog &rarr; Rupiah)
        <span class="badge bg-white text-info ms-2">{{ $returnGobogs->count() }} transaksi</span>
    </div>
    <div class="card-body p-0">
        @if($returnGobogs->isEmpty())
            <p class="text-muted text-center py-4 mb-0">Tidak ada data pada periode ini.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light"><tr><th>#</th><th>Admin</th><th>Nilai (Rp)</th><th>Status Koin</th><th>Waktu</th></tr></thead>
                    <tbody>
                        @foreach($returnGobogs as $item)
                        <tr>
                            <td class="text-muted small">{{ $item->id }}</td>
                            <td>{{ $item->user->nama }}</td>
                            <td class="fw-semibold">{{ number_format($item->gobog->nilai, 0, ',', '.') }}</td>
                            <td>
                                @if($item->valid_status)
                                    <span class="badge bg-success">ASLI</span>
                                @else
                                    <span class="badge bg-danger">PALSU</span>
                                @endif
                            </td>
                            <td class="small">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection