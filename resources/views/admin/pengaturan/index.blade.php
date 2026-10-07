@extends('layouts.app')
@section('title','Pengaturan Sistem')
@section('content')
<h4 class="fw-bold mb-4"><i class="bi bi-gear me-2"></i>Pengaturan Sistem</h4>
<div class="row g-4">
    <div class="col-12 col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom fw-semibold">Harga Universal Koin Gobog</div>
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="fs-2 fw-bold text-primary">Rp {{ number_format($harga, 0, ',', '.') }}</div>
                    <div class="text-muted small">Harga koin saat ini</div>
                </div>
                @if($errors->any())
                    <div class="alert alert-danger py-2">
                        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.pengaturan.update') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Harga Baru (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="harga_gobog" class="form-control"
                                   value="{{ old('harga_gobog', $harga) }}" min="0" step="500" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Keterangan Perubahan <span class="text-muted fw-normal">(opsional)</span></label>
                        <input type="text" name="keterangan" class="form-control"
                               placeholder="Contoh: Penyesuaian harga acara" value="{{ old('keterangan') }}">
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-semibold">
                        <i class="bi bi-save me-1"></i>Simpan Pengaturan
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom fw-semibold">
                <i class="bi bi-clock-history me-2"></i>Riwayat Perubahan Harga
            </div>
            <div class="card-body p-0">
                @if($riwayat->isEmpty())
                    <div class="text-center py-4 text-muted small">Belum ada riwayat perubahan harga.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr><th>Harga</th><th>Keterangan</th><th>Waktu</th></tr>
                            </thead>
                            <tbody>
                                @foreach($riwayat as $r)
                                <tr>
                                    <td class="fw-semibold">Rp {{ number_format($r->harga, 0, ',', '.') }}</td>
                                    <td class="text-muted small">{{ $r->keterangan ?? '-' }}</td>
                                    <td class="small text-muted">{{ $r->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection