@extends('layouts.app')
@section('title','Detail Koin Gobog')
@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.gobog.index') }}" class="btn btn-sm btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Detail Koin #{{ $gobog->id }}</h4>
</div>
<div class="row g-4">
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent fw-semibold border-bottom">Informasi Koin</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th class="text-muted" style="width:40%">ID</th><td>{{ $gobog->id }}</td></tr>
                    <tr><th class="text-muted">Nilai</th><td class="fw-bold text-success">Rp {{ number_format($gobog->nilai, 0, ',', '.') }}</td></tr>
                    <tr><th class="text-muted">Status</th>
                        <td>
                            @if($gobog->status === 'tersedia')
                                <span class="badge bg-success">Tersedia</span>
                            @else
                                <span class="badge bg-secondary">Beredar</span>
                            @endif
                        </td>
                    </tr>
                    <tr><th class="text-muted">Sudah Dijual</th>
                        <td><span class="badge bg-primary rounded-pill">{{ $gobog->terjual_count ?? 0 }}x</span></td>
                    </tr>
                    <tr><th class="text-muted">Dibuat</th><td>{{ $gobog->created_at->format('d/m/Y H:i') }}</td></tr>
                </table>
                @if($gobog->foto)
                    <hr>
                    <p class="small text-muted fw-semibold mb-1">Foto Fisik Koin:</p>
                    <img src="{{ asset('storage/'.$gobog->foto) }}" class="img-fluid rounded" style="max-height:180px">
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent fw-semibold border-bottom d-flex justify-content-between align-items-center">
                QR Code
                @if($gobog->qr_code)
                    <a href="{{ asset($gobog->qr_code) }}"
                       download="qr_gobog_{{ $gobog->id }}.svg"
                       class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-download me-1"></i>Download QR
                    </a>
                @endif
            </div>
            <div class="card-body text-center">
                @if($gobog->qr_code)
                    <img src="{{ asset($gobog->qr_code) }}" class="img-fluid" style="max-width:220px">
                    <p class="text-muted small mt-2">Berisi kode enkripsi AES-256-CBC koin ini.</p>
                @else
                    <p class="text-muted">QR Code belum tersedia.</p>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="mt-3 d-flex gap-2">
    <a href="{{ route('admin.gobog.edit', $gobog) }}" class="btn btn-warning btn-sm"><i class="bi bi-pencil me-1"></i>Edit Foto</a>
    <a href="{{ route('admin.gobog.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
</div>
@endsection