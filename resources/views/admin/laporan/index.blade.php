@extends('layouts.app')
@section('title','Laporan Penjualan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-file-earmark-bar-graph me-2 text-danger"></i>Laporan</h4>
    <a href="{{ route('admin.laporan.export-pdf', request()->query()) }}" class="btn btn-danger btn-sm">
        <i class="bi bi-file-pdf me-1"></i>Export PDF
    </a>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="row g-2 align-items-end">
            <div class="col-auto">
                <select name="filter" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="harian"   {{ request('filter','harian')==='harian'  ?'selected':'' }}>Harian</option>
                    <option value="mingguan" {{ request('filter')==='mingguan'          ?'selected':'' }}>Mingguan</option>
                    <option value="bulanan"  {{ request('filter')==='bulanan'           ?'selected':'' }}>Bulanan</option>
                    <option value="tahunan"  {{ request('filter')==='tahunan'           ?'selected':'' }}>Tahunan</option>
                </select>
            </div>
            @if(in_array(request('filter','harian'), ['harian','mingguan']))
                <div class="col-auto"><input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal', now()->toDateString()) }}"></div>
            @elseif(request('filter')==='bulanan')
                <div class="col-auto"><input type="number" name="bulan" class="form-control form-control-sm" style="width:72px" min="1" max="12" value="{{ request('bulan', now()->month) }}"></div>
                <div class="col-auto"><input type="number" name="tahun" class="form-control form-control-sm" style="width:90px" value="{{ request('tahun', now()->year) }}"></div>
            @else
                <div class="col-auto"><input type="number" name="tahun" class="form-control form-control-sm" style="width:90px" value="{{ request('tahun', now()->year) }}"></div>
            @endif
            <div class="col-auto"><button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search me-1"></i>Tampilkan</button></div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-success text-white fw-semibold d-flex justify-content-between">
        <span><i class="bi bi-bag-check me-2"></i>Rekap Penjualan Gobog</span>
        <span class="badge bg-white text-success">{{ $penjualanAdmins->count() }} transaksi</span>
    </div>
    <div class="card-body p-0">
        @if($penjualanAdmins->isEmpty())
            <p class="text-center text-muted py-4 mb-0">Tidak ada data.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light"><tr><th>#</th><th>Admin</th><th>Nilai (Rp)</th><th>Waktu</th></tr></thead>
                    <tbody>
                        @foreach($penjualanAdmins as $item)
                        <tr>
                            <td class="text-muted small">{{ $item->id }}</td>
                            <td>{{ $item->user->nama }}</td>
                            <td>{{ number_format($item->gobog->nilai, 0, ',', '.') }}</td>
                            <td class="small">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-success fw-bold">
                        <tr>
                            <td colspan="2" class="text-end">Grand Total:</td>
                            <td colspan="2">Rp {{ number_format($penjualanAdmins->sum(fn($i)=>$i->gobog->nilai), 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-primary text-white fw-semibold d-flex justify-content-between">
        <span><i class="bi bi-shop me-2"></i>Transaksi Tenan</span>
        <span class="badge bg-white text-primary">{{ $penjualanTenans->count() }} transaksi</span>
    </div>
    <div class="card-body p-0">
        @if($penjualanTenans->isEmpty())
            <p class="text-center text-muted py-4 mb-0">Tidak ada data.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light"><tr><th>#</th><th>Penjual</th><th>Nilai (Rp)</th><th>Status</th><th>Waktu</th></tr></thead>
                    <tbody>
                        @foreach($penjualanTenans as $item)
                        <tr>
                            <td class="text-muted small">{{ $item->id }}</td>
                            <td>{{ $item->user->nama }}</td>
                            <td>{{ number_format($item->gobog->nilai, 0, ',', '.') }}</td>
                            <td><span class="badge {{ $item->valid_status ? 'bg-success' : 'bg-danger' }}">{{ $item->valid_status ? 'ASLI' : 'PALSU' }}</span></td>
                            <td class="small">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-primary fw-bold">
                        <tr>
                            <td colspan="2" class="text-end">Grand Total (ASLI):</td>
                            <td colspan="3">Rp {{ number_format($penjualanTenans->where('valid_status',1)->sum(fn($i)=>$i->gobog->nilai), 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-info text-white fw-semibold d-flex justify-content-between">
        <span><i class="bi bi-arrow-return-left me-2"></i>Rekap Pengembalian</span>
        <span class="badge bg-white text-info">{{ $returnGobogs->count() }} transaksi</span>
    </div>
    <div class="card-body p-0">
        @if($returnGobogs->isEmpty())
            <p class="text-center text-muted py-4 mb-0">Tidak ada data.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light"><tr><th>#</th><th>Admin</th><th>Nilai (Rp)</th><th>Status</th><th>Waktu</th></tr></thead>
                    <tbody>
                        @foreach($returnGobogs as $item)
                        <tr>
                            <td class="text-muted small">{{ $item->id }}</td>
                            <td>{{ $item->user->nama }}</td>
                            <td>{{ number_format($item->gobog->nilai, 0, ',', '.') }}</td>
                            <td><span class="badge {{ $item->valid_status ? 'bg-success' : 'bg-danger' }}">{{ $item->valid_status ? 'ASLI' : 'PALSU' }}</span></td>
                            <td class="small">{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-info fw-bold">
                        <tr>
                            <td colspan="2" class="text-end">Grand Total (ASLI):</td>
                            <td colspan="3">Rp {{ number_format($returnGobogs->where('valid_status',1)->sum(fn($i)=>$i->gobog->nilai), 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-warning fw-semibold d-flex justify-content-between">
        <span><i class="bi bi-people me-2"></i>Gobog Beredar di Masyarakat</span>
        <span class="badge bg-dark">{{ $gobogBeredar->count() }} koin</span>
    </div>
    <div class="card-body p-0">
        @if($gobogBeredar->isEmpty())
            <p class="text-center text-muted py-4 mb-0">Tidak ada koin yang beredar.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light"><tr><th>#</th><th>Kode</th><th>Nilai (Rp)</th></tr></thead>
                    <tbody>
                        @foreach($gobogBeredar as $g)
                        <tr>
                            <td class="text-muted small">{{ $g->id }}</td>
                            <td><code class="small">{{ Str::limit($g->kode_unik, 20) }}</code></td>
                            <td>{{ number_format($g->nilai, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-warning fw-bold">
                        <tr>
                            <td colspan="2" class="text-end">Total Nominal Beredar:</td>
                            <td>Rp {{ number_format($totalBeredar, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection