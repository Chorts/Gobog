@extends('layouts.app')
@section('title','Edit Pengguna')
@section('content')
<div class="d-flex align-items-center mb-4">
    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h4 class="fw-bold mb-0">Edit Pengguna #{{ $user->id }}</h4>
</div>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger py-2">
                        @foreach($errors->all() as $e)<div><i class="bi bi-exclamation-circle me-1"></i>{{ $e }}</div>@endforeach
                    </div>
                @endif
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $user->nama) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Username</label>
                        <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Password <span class="text-muted fw-normal">(kosongkan jika tidak ingin mengubah)</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Isi hanya jika ingin ganti password">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Role / Hak Akses</label>
                        <select name="role" id="roleSelect" class="form-select" onchange="toggleTenanSection()">
                            <option value="penjual" {{ old('role', $user->role)==='penjual' ? 'selected' : '' }}>Penjual / Lapak</option>
                            <option value="admin" {{ old('role', $user->role)==='admin' ? 'selected' : '' }}>Administrator Loket</option>
                        </select>
                    </div>

                    <div id="tenanSection" class="border rounded p-3 mb-4 bg-light">
                        <label class="form-label fw-semibold small d-block">Pilihan Tenan / Lapak</label>
                        <div class="form-check form-check-inline mb-2">
                            <input class="form-check-input" type="radio" name="tenan_option" id="optExisting" value="existing"
                                   {{ old('tenan_option','existing')==='existing' ? 'checked' : '' }} onchange="toggleTenanInput()">
                            <label class="form-check-label small" for="optExisting">Pilih Tenan yang Sudah Ada</label>
                        </div>
                        <div class="form-check form-check-inline mb-2">
                            <input class="form-check-input" type="radio" name="tenan_option" id="optNew" value="new"
                                   {{ old('tenan_option')==='new' ? 'checked' : '' }} onchange="toggleTenanInput()">
                            <label class="form-check-label small" for="optNew">+ Daftarkan Tenan Baru</label>
                        </div>

                        <div id="selectExistingGroup" class="mt-2">
                            <select name="tenans_idtenans" class="form-select form-select-sm">
                                <option value="">-- Pilih Tenan --</option>
                                @foreach($tenans as $t)
                                    <option value="{{ $t->idtenans }}" {{ old('tenans_idtenans', $user->tenans_idtenans)==$t->idtenans ? 'selected' : '' }}>
                                        {{ $t->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="inputNewGroup" class="mt-2" style="display:none">
                            <input type="text" name="nama_tenan_baru" class="form-control form-control-sm"
                                   value="{{ old('nama_tenan_baru') }}" placeholder="Ketik nama tenan baru">
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning fw-semibold">
                            <i class="bi bi-save me-1"></i>Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleTenanSection() {
    const role = document.getElementById('roleSelect').value;
    const section = document.getElementById('tenanSection');
    section.style.display = role === 'penjual' ? 'block' : 'none';
}

function toggleTenanInput() {
    const isNew = document.getElementById('optNew').checked;
    document.getElementById('selectExistingGroup').style.display = isNew ? 'none' : 'block';
    document.getElementById('inputNewGroup').style.display = isNew ? 'block' : 'none';
}

document.addEventListener('DOMContentLoaded', function() {
    toggleTenanSection();
    toggleTenanInput();
});
</script>
@endpush
@endsection