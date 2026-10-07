@extends('layouts.app')
@section('title','Daftar Koin Gobog')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-coin me-2 text-warning"></i>Daftar Koin Gobog</h4>
    <a href="{{ route('admin.gobog.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i>Cetak Koin Baru
    </a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($gobogs->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                <p class="mb-1">Belum ada koin gobog.</p>
                <a href="{{ route('admin.gobog.create') }}" class="btn btn-sm btn-outline-primary">Cetak Koin Pertama</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nilai (Rp)</th>
                            <th>Status</th>
                            <th class="text-center">Sudah Dijual</th>
                            <th>Dibuat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gobogs as $gobog)
                        <tr>
                            <td class="text-muted small">{{ $gobog->id }}</td>
                            <td class="fw-semibold">Rp {{ number_format($gobog->nilai, 0, ',', '.') }}</td>
                            <td>
                                @if($gobog->status === 'tersedia')
                                    <span class="badge bg-success">Tersedia</span>
                                @else
                                    <span class="badge bg-secondary">Beredar</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary rounded-pill">{{ $gobog->terjual_count ?? 0 }}x</span>
                            </td>
                            <td class="small text-muted">{{ $gobog->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.gobog.show', $gobog) }}" class="btn btn-sm btn-outline-info me-1" title="Detail"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.gobog.edit', $gobog) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit"><i class="bi bi-pencil"></i></a>
                                <form method="POST" action="{{ route('admin.gobog.destroy', $gobog) }}" style="display:inline"
                                      onsubmit="return confirm('Hapus koin ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $gobogs->links() }}</div>
        @endif
    </div>
</div>
@endsection