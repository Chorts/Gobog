@extends('layouts.app')
@section('title','Cetak Koin Gobog Baru')
@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.gobog.index') }}" class="btn btn-sm btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Cetak Koin Gobog Baru</h4>
</div>
<div class="row justify-content-center">
    <div class="col-12 col-md-6 col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="alert alert-info d-flex gap-2 py-2 mb-4">
                    <i class="bi bi-info-circle flex-shrink-0 mt-1"></i>
                    <div>
                        Harga koin saat ini: <strong>Rp {{ number_format($harga, 0, ',', '.') }}</strong><br>
                        <small class="text-muted">Ubah harga di <a href="{{ route('admin.pengaturan.index') }}">Pengaturan</a>.</small>
                    </div>
                </div>
                @if($errors->any())
                    <div class="alert alert-danger py-2">
                        @foreach($errors->all() as $e)<div><i class="bi bi-exclamation-circle me-1"></i>{{ $e }}</div>@endforeach
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.gobog.store') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Jumlah Koin yang Dicetak</label>
                        <div class="input-group">
                            <input type="number" name="jumlah" class="form-control form-control-lg text-center @error('jumlah') is-invalid @enderror"
                                   value="{{ old('jumlah', 1) }}" min="1" max="500" required placeholder="10">
                            <span class="input-group-text">koin</span>
                        </div>
                        <div class="form-text">Maks. 500 koin sekali cetak.</div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary fw-semibold">
                            <i class="bi bi-qr-code me-2"></i>Cetak &amp; Generate QR Code
                        </button>
                        <a href="{{ route('admin.gobog.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection