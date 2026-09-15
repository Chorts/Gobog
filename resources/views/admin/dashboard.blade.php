@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="bi bi-speedometer2 me-2 text-primary"></i>Dashboard Admin</h2>
    <span class="text-muted">Selamat datang, {{ auth()->user()->nama }}</span>
</div>
<div class="row g-4">
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('admin.gobog.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <i class="bi bi-coin fs-1 text-warning mb-3 d-block"></i>
                    <h5 class="card-title fw-bold">Kelola Gobog</h5>
                    <p class="card-text text-muted small">Cetak &amp; kelola koin gobog</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('admin.distribusi.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <i class="bi bi-arrow-right-circle fs-1 text-success mb-3 d-block"></i>
                    <h5 class="card-title fw-bold">Distribusi</h5>
                    <p class="card-text text-muted small">Rupiah &rarr; Gobog ke pengunjung</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('admin.retur.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <i class="bi bi-arrow-left-circle fs-1 text-info mb-3 d-block"></i>
                    <h5 class="card-title fw-bold">Retur</h5>
                    <p class="card-text text-muted small">Gobog &rarr; Rupiah kembali</p>
                </div>
            </div>
        </a>
    </div>
    <div class="col-sm-6 col-lg-3">
        <a href="{{ route('admin.laporan.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center p-4">
                    <i class="bi bi-file-earmark-bar-graph fs-1 text-danger mb-3 d-block"></i>
                    <h5 class="card-title fw-bold">Laporan</h5>
                    <p class="card-text text-muted small">Lihat &amp; export laporan</p>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection