@extends('layouts.app')
@section('title', 'Edit Koin Gobog')
@section('content')
<h1>Edit Koin Gobog #{{ $gobog->id }}</h1>
@if($errors->any())
    <div>@foreach($errors->all() as $e)<p style="color:red">{{ $e }}</p>@endforeach</div>
@endif
<form method="POST" action="{{ route('admin.gobog.update', $gobog) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div>
        <label>Nilai Nominal (Rp):</label><br>
        <input type="number" name="nilai" value="{{ old('nilai', $gobog->nilai) }}" step="0.01" min="0" required>
    </div>
    <br>
    <div>
        <label>Ganti Foto (opsional):</label><br>
        @if($gobog->foto)
            <p>Foto saat ini: <img src="{{ asset('storage/'.$gobog->foto) }}" width="100"></p>
        @endif
        <input type="file" name="foto" accept="image/*">
    </div>
    <br>
    <button type="submit">Perbarui</button>
    <a href="{{ route('admin.gobog.index') }}"><button type="button">Batal</button></a>
</form>
@endsection
