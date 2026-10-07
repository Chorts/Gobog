@extends('layouts.app')
@section('title','Dashboard Penjual')
@section('content')
<h4 class="fw-bold mb-4">Dashboard Penjual</h4>
<div class="row g-3">
    <div class="col-12 col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h6 class="fw-bold text-muted mb-3"><i class="bi bi-person-badge me-2"></i>Informasi Akun</h6>
                <p class="mb-1"><strong>Nama:</strong> {{ auth()->user()->nama }}</p>
                @if(auth()->user()->tenan)
                    <p class="mb-1"><strong>Lapak:</strong> {{ auth()->user()->tenan->nama }}</p>
                @endif
                <p class="mb-0"><strong>Role:</strong> <span class="badge bg-primary">{{ auth()->user()->role }}</span></p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-7">
        <div class="row g-3">
            <div class="col-6">
                <a href="{{ route('penjual.cek-keaslian.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100 text-center p-3">
                        <i class="bi bi-patch-check fs-1 text-success d-block mb-2"></i>
                        <div class="fw-bold small">Cek Keaslian</div>
                        <div class="text-muted" style="font-size:.75rem">Hanya verifikasi</div>
                    </div>
                </a>
            </div>
            <div class="col-6">
                <a href="{{ route('penjual.scan-penjualan.index') }}" class="text-decoration-none">
                    <div class="card border-0 shadow-sm h-100 text-center p-3">
                        <i class="bi bi-qr-code-scan fs-1 text-primary d-block mb-2"></i>
                        <div class="fw-bold small">Scan Penjualan</div>
                        <div class="text-muted" style="font-size:.75rem">Catat transaksi</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection