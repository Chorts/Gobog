@extends('layouts.app')
@section('title', 'Detail Koin Gobog')
@section('content')
<h1>Detail Koin Gobog #{{ $gobog->id }}</h1>
<table border="1" cellpadding="5">
    <tr><th>ID</th><td>{{ $gobog->id }}</td></tr>
    <tr><th>Nilai</th><td>Rp {{ number_format($gobog->nilai, 0, ',', '.') }}</td></tr>
    <tr><th>Kode Unik</th><td>{{ $gobog->kode_unik }}</td></tr>
    <tr><th>Status</th><td>{{ $gobog->status }}</td></tr>
    <tr><th>Dibuat</th><td>{{ $gobog->created_at->format('d/m/Y H:i') }}</td></tr>
</table>
<br>
@if($gobog->foto)
    <h3>Foto Fisik Koin:</h3>
    <img src="{{ asset('storage/'.$gobog->foto) }}" alt="Foto koin" style="max-width:300px">
    <br><br>
@endif
@if($gobog->qr_code)
    <h3>QR Code:</h3>
    <img src="{{ asset($gobog->qr_code) }}" alt="QR Code" style="width:200px;height:200px">
    <br><small>QR Code berisi kode_enkripsi AES-256-CBC dari koin ini.</small>
@else
    <p>QR Code belum tersedia.</p>
@endif
<br>
<a href="{{ route('admin.gobog.edit', $gobog) }}"><button>Edit</button></a>
<a href="{{ route('admin.gobog.index') }}"><button>Kembali</button></a>
@endsection
