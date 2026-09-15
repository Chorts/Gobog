@extends('layouts.app')
@section('title', 'Dashboard Penjual')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard Penjual</h2>
</div>
<div class="row g-4">
    <div class="col-12 col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold"><i class="bi bi-person-badge me-2 text-primary"></i>Informasi Akun</h5>
                <hr>
                <p><strong>Nama:</strong> {{ auth()->user()->nama }}</p>
                @if(auth()->user()->tenan)
                    <p><strong>Lapak/Tenan:</strong> {{ auth()->user()->tenan->nama }}</p>
                @endif
                <p><strong>Role:</strong> <span class="badge bg-success">{{ auth()->user()->role }}</span></p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <a href="{{ route('penjual.scan.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 border-start border-primary border-4">
                <div class="card-body text-center p-5">
                    <i class="bi bi-qr-code-scan display-3 text-primary mb-3 d-block"></i>
                    <h4 class="fw-bold">Scan Koin Gobog</h4>
                    <p class="text-muted">Verifikasi keaslian koin sebelum menerima pembayaran</p>
                    <span class="btn btn-primary mt-2">Mulai Scan &rarr;</span>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection