@extends('layouts.app')
@section('title', 'Daftar Koin Gobog')
@section('content')
<h1>Daftar Koin Gobog</h1>
<a href="{{ route('admin.gobog.create') }}"><button>+ Tambah Koin Baru</button></a>
<hr>
@if($gobogs->isEmpty())
    <p>Belum ada data koin.</p>
@else
    <table border="1" cellpadding="5">
        <thead>
            <tr><th>ID</th><th>Nilai (Rp)</th><th>Status</th><th>Dibuat</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @foreach($gobogs as $gobog)
            <tr>
                <td>{{ $gobog->id }}</td>
                <td>{{ number_format($gobog->nilai, 0, ',', '.') }}</td>
                <td>{{ $gobog->status }}</td>
                <td>{{ $gobog->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('admin.gobog.show', $gobog) }}"><button>Detail</button></a>
                    <a href="{{ route('admin.gobog.edit', $gobog) }}"><button>Edit</button></a>
                    <form method="POST" action="{{ route('admin.gobog.destroy', $gobog) }}"
                          style="display:inline" onsubmit="return confirm('Hapus koin ini?')">
                        @csrf @method('DELETE')
                        <button type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <br>{{ $gobogs->links() }}
@endif
@endsection
