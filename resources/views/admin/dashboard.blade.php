@extends('layouts.app')
@section('title','Dashboard Admin')
@section('content')
<h4 class="fw-bold mb-4">Dashboard Admin</h4>
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <a href="{{ route('admin.gobog.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <i class="bi bi-coin fs-1 text-warning d-block mb-2"></i>
                    <div class="fw-bold">Kelola Gobog</div>
                    <div class="text-muted small">Cetak &amp; kelola koin</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a href="{{ route('admin.rekap-penjualan.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <i class="bi bi-bag-check fs-1 text-success d-block mb-2"></i>
                    <div class="fw-bold">Penjualan Gobog</div>
                    <div class="text-muted small">Jual koin ke pengunjung</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a href="{{ route('admin.rekap-pengembalian.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <i class="bi bi-arrow-return-left fs-1 text-info d-block mb-2"></i>
                    <div class="fw-bold">Pengembalian Gobog</div>
                    <div class="text-muted small">Tukar koin ke Rupiah</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a href="{{ route('admin.laporan.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <i class="bi bi-file-earmark-bar-graph fs-1 text-danger d-block mb-2"></i>
                    <div class="fw-bold">Laporan</div>
                    <div class="text-muted small">Lihat &amp; export laporan</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-lg-3">
        <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-4">
                    <i class="bi bi-people fs-1 text-primary d-block mb-2"></i>
                    <div class="fw-bold">Pengguna &amp; Tenan</div>
                    <div class="text-muted small">Kelola akun &amp; lapak</div>
                </div>
            </div>
        </a>
    </div>
</div>
@endsection