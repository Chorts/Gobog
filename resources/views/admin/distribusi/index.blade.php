@extends('layouts.app')
@section('title', 'Distribusi Koin')
@section('content')
<h1>Distribusi Koin Gobog (Rupiah &rarr; Gobog)</h1>
<p>Koin yang ditampilkan hanya yang berstatus <strong>tersedia</strong>.</p>
<hr>
@if($gobogs->isEmpty())
    <p>Tidak ada koin tersedia untuk didistribusikan.</p>
@else
    <table border="1" cellpadding="5">
        <thead>
            <tr><th>ID</th><th>Nilai (Rp)</th><th>Kode Unik</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @foreach($gobogs as $gobog)
            <tr>
                <td>{{ $gobog->id }}</td>
                <td>{{ number_format($gobog->nilai, 0, ',', '.') }}</td>
                <td>{{ $gobog->kode_unik }}</td>
                <td>
                    <form method="POST" action="{{ route('admin.distribusi.store') }}"
                          onsubmit="return confirm('Distribusikan koin Rp {{ number_format($gobog->nilai, 0, ',', '.') }} ini ke pengunjung?')">
                        @csrf
                        <input type="hidden" name="gobogs_id" value="{{ $gobog->id }}">
                        <button type="submit">Distribusikan</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif
@endsection
