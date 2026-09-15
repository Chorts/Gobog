@extends('layouts.app')
@section('title', 'Tambah Koin Gobog')
@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.gobog.index') }}" class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h2 class="fw-bold mb-0"><i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Koin Gobog Baru</h2>
</div>
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $e)
                            <div><i class="bi bi-exclamation-circle me-1"></i>{{ $e }}</div>
                        @endforeach
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.gobog.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nilai Nominal (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="nilai" class="form-control @error('nilai') is-invalid @enderror"
                                   value="{{ old('nilai') }}" step="0.01" min="0" placeholder="Contoh: 5000" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Foto Fisik Koin <span class="text-muted fw-normal">(opsional)</span></label>
                        <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                    </div>
                    <div class="alert alert-info d-flex align-items-center py-2">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Kode unik, enkripsi AES-256-CBC, dan QR Code akan di-generate otomatis.</small>
                    </div>
                    <div class="d-grid gap-2 mt-3">
                        <button type="submit" class="btn btn-primary fw-semibold">
                            <i class="bi bi-qr-code me-2"></i>Simpan &amp; Generate QR Code
                        </button>
                        <a href="{{ route('admin.gobog.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection