@extends('layouts.app')
@section('title', 'Detail Koin Gobog')
@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.gobog.index') }}" class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h2 class="fw-bold mb-0"><i class="bi bi-eye me-2 text-info"></i>Detail Koin Gobog #{{ $gobog->id }}</h2>
</div>
<div class="row g-4">
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent fw-semibold">Informasi Koin</div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th class="text-muted" style="width:40%">ID</th><td>{{ $gobog->id }}</td></tr>
                    <tr><th class="text-muted">Nilai</th><td class="fw-bold text-success">Rp {{ number_format($gobog->nilai, 0, ',', '.') }}</td></tr>
                    <tr><th class="text-muted">Kode Unik</th><td><code class="small">{{ $gobog->kode_unik }}</code></td></tr>
                    <tr>
                        <th class="text-muted">Status</th>
                        <td>
                            @if($gobog->status === 'tersedia')
                                <span class="badge bg-success">tersedia</span>
                            @else
                                <span class="badge bg-secondary">tidak tersedia</span>
                            @endif
                        </td>
                    </tr>
                    <tr><th class="text-muted">Dibuat</th><td>{{ $gobog->created_at->format('d/m/Y H:i') }}</td></tr>
                </table>
                @if($gobog->foto)
                    <hr>
                    <p class="fw-semibold text-muted small mb-2">Foto Fisik Koin:</p>
                    <img src="{{ asset('storage/'.$gobog->foto) }}" alt="Foto koin" class="img-fluid rounded" style="max-height:200px">
                @endif
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent fw-semibold">QR Code</div>
            <div class="card-body text-center">
                @if($gobog->qr_code)
                    <img src="{{ asset($gobog->qr_code) }}" alt="QR Code" class="img-fluid" style="max-width:220px">
                    <p class="text-muted small mt-2">Berisi kode enkripsi AES-256-CBC koin ini.</p>
                @else
                    <p class="text-muted">QR Code belum tersedia.</p>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="mt-3">
    <a href="{{ route('admin.gobog.edit', $gobog) }}" class="btn btn-warning me-2">
        <i class="bi bi-pencil me-1"></i>Edit
    </a>
    <a href="{{ route('admin.gobog.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i>Kembali
    </a>
</div>
@endsection