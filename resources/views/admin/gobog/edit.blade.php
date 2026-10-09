@extends('layouts.app')
@section('title','Edit Koin Gobog')
@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.gobog.show', $gobog) }}" class="btn btn-sm btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Edit Foto Koin #{{ $gobog->id }}</h4>
</div>
<div class="row justify-content-center">
    <div class="col-12 col-md-6 col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.gobog.update', $gobog) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Foto Fisik Koin</label>
                        @if($gobog->foto)
                            <div class="mb-2">
                                <img src="{{ asset('storage/'.$gobog->foto) }}" class="img-thumbnail" style="max-height:120px">
                            </div>
                        @endif
                        <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning fw-semibold"><i class="bi bi-save me-1"></i>Simpan</button>
                        <a href="{{ route('admin.gobog.show', $gobog) }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection