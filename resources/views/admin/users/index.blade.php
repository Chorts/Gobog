@extends('layouts.app')
@section('title','Kelola Pengguna')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-people me-2 text-primary"></i>Kelola Pengguna &amp; Tenan</h4>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-person-plus me-1"></i>Tambah Pengguna Baru
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Lapak / Tenan</th>
                        <th>Terdaftar</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="text-muted small">{{ $user->id }}</td>
                        <td class="fw-semibold">{{ $user->nama }}</td>
                        <td><code>{{ $user->username }}</code></td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge bg-primary">Admin Loket</span>
                            @else
                                <span class="badge bg-success">Penjual</span>
                            @endif
                        </td>
                        <td>
                            @if($user->tenan)
                                <span class="fw-semibold text-dark"><i class="bi bi-shop me-1 text-muted"></i>{{ $user->tenan->nama }}</span>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td class="small text-muted">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-warning me-1" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if(auth()->id() !== $user->id)
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline"
                                      onsubmit="return confirm('Hapus pengguna {{ $user->nama }}?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Belum ada pengguna.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $users->links() }}</div>
    </div>
</div>
@endsection