@extends('layouts.app')
@section('title', 'Distribusi Koin')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="bi bi-arrow-right-circle me-2 text-success"></i>Distribusi Koin</h2>
    <span class="badge bg-success fs-6">Rupiah &rarr; Gobog</span>
</div>
<div class="alert alert-info d-flex align-items-center mb-4">
    <i class="bi bi-info-circle-fill me-2 flex-shrink-0"></i>
    <span>Pilih koin yang ingin diberikan kepada pengunjung. Hanya koin berstatus <strong>tersedia</strong> yang tampil.</span>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($gobogs->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                <p>Tidak ada koin tersedia untuk didistribusikan.</p>
                <a href="{{ route('admin.gobog.create') }}" class="btn btn-outline-primary">Tambah Koin Baru</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nilai (Rp)</th>
                            <th>Kode Unik</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gobogs as $gobog)
                        <tr>
                            <td class="text-muted small">{{ $gobog->id }}</td>
                            <td class="fw-bold text-success">{{ number_format($gobog->nilai, 0, ',', '.') }}</td>
                            <td><code class="small">{{ Str::limit($gobog->kode_unik, 20) }}</code></td>
                            <td class="text-center">
                                <form method="POST" action="{{ route('admin.distribusi.store') }}"
                                      onsubmit="return confirm('Distribusikan koin Rp {{ number_format($gobog->nilai, 0, chr(44), chr(46)) }} ke pengunjung?')">
                                    @csrf
                                    <input type="hidden" name="gobogs_id" value="{{ $gobog->id }}">
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <i class="bi bi-send me-1"></i>Distribusikan
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection