@extends('layouts.app')
@section('title', 'Edit Koin Gobog')
@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.gobog.index') }}" class="btn btn-outline-secondary btn-sm me-3">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h2 class="fw-bold mb-0"><i class="bi bi-pencil me-2 text-warning"></i>Edit Koin Gobog #{{ $gobog->id }}</h2>
</div>
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $e)
                            <div><i class="bi bi-exclamation-circle me-1"></i>{{ $e }}</div>
                        @endforeach
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.gobog.update', $gobog) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nilai Nominal (Rp)</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="number" name="nilai" class="form-control @error('nilai') is-invalid @enderror"
                                   value="{{ old('nilai', $gobog->nilai) }}" step="0.01" min="0" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Ganti Foto <span class="text-muted fw-normal">(opsional)</span></label>
                        @if($gobog->foto)
                            <div class="mb-2">
                                <img src="{{ asset('storage/'.$gobog->foto) }}" alt="Foto koin" class="img-thumbnail" style="max-height:120px">
                            </div>
                        @endif
                        <input type="file" name="foto" class="form-control" accept="image/*">
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning fw-semibold">
                            <i class="bi bi-save me-2"></i>Perbarui
                        </button>
                        <a href="{{ route('admin.gobog.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection