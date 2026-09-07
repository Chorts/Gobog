@extends('layouts.app')
@section('title', 'Tambah Koin Gobog')
@section('content')
<h1>Tambah Koin Gobog Baru</h1>
@if($errors->any())
    <div>@foreach($errors->all() as $e)<p style="color:red">{{ $e }}</p>@endforeach</div>
@endif
<form method="POST" action="{{ route('admin.gobog.store') }}" enctype="multipart/form-data">
    @csrf
    <div>
        <label>Nilai Nominal (Rp):</label><br>
        <input type="number" name="nilai" value="{{ old('nilai') }}" step="0.01" min="0" required>
    </div>
    <br>
    <div>
        <label>Foto Fisik Koin (opsional):</label><br>
        <input type="file" name="foto" accept="image/*">
    </div>
    <br>
    <p><em>Kode unik, enkripsi AES-256-CBC, dan QR Code akan di-generate otomatis.</em></p>
    <button type="submit">Simpan &amp; Generate QR Code</button>
    <a href="{{ route('admin.gobog.index') }}"><button type="button">Batal</button></a>
</form>
@endsection
