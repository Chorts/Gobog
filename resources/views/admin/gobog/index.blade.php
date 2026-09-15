@extends('layouts.app')
@section('title', 'Daftar Koin Gobog')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="bi bi-coin me-2 text-warning"></i>Daftar Koin Gobog</h2>
    <a href="{{ route('admin.gobog.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Tambah Koin Baru
    </a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @if($gobogs->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                <p>Belum ada data koin gobog.</p>
                <a href="{{ route('admin.gobog.create') }}" class="btn btn-outline-primary">Tambah Koin Pertama</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nilai (Rp)</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gobogs as $gobog)
                        <tr>
                            <td class="text-muted small">{{ $gobog->id }}</td>
                            <td class="fw-semibold">{{ number_format($gobog->nilai, 0, ',', '.') }}</td>
                            <td>
                                @if($gobog->status === 'tersedia')
                                    <span class="badge bg-success">tersedia</span>
                                @else
                                    <span class="badge bg-secondary">tidak tersedia</span>
                                @endif
                            </td>
                            <td class="small">{{ $gobog->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.gobog.show', $gobog) }}" class="btn btn-sm btn-outline-info me-1">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.gobog.edit', $gobog) }}" class="btn btn-sm btn-outline-warning me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.gobog.destroy', $gobog) }}"
                                      style="display:inline" onsubmit="return confirm('Hapus koin ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
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