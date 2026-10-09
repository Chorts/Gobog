@extends('layouts.app')
@section('title','Pengembalian Gobog')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-arrow-return-left me-2 text-info"></i>Pengembalian Gobog</h4>
    <span class="badge bg-info">Gobog &rarr; Rupiah</span>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-bottom bg-transparent fw-semibold">Scanner QR</div>
            <div class="card-body p-3 text-center">
                <div id="reader" class="mx-auto" style="max-width:320px"></div>
                <div class="d-flex gap-2 justify-content-center mt-3">
                    <button id="btnTambah" class="btn btn-success btn-sm" onclick="setScanMode('tambah')">
                        <i class="bi bi-plus-circle me-1"></i>Scan Tambah
                    </button>
                    <button id="btnHapus" class="btn btn-danger btn-sm" onclick="setScanMode('hapus')">
                        <i class="bi bi-dash-circle me-1"></i>Scan Hapus
                    </button>
                    <button id="btnStop" class="btn btn-outline-secondary btn-sm d-none" onclick="stopScan()">
                        <i class="bi bi-stop-circle me-1"></i>Stop
                    </button>
                </div>
                <div id="statusScan" class="mt-2 text-muted small"></div>
                <div id="hasilScan" class="mt-2"></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-bottom bg-transparent d-flex justify-content-between align-items-center">
                <span class="fw-semibold">Nota Pengembalian</span>
                <span id="totalBadge" class="badge bg-info text-dark">0 koin &bull; Rp 0</span>
            </div>
            <div class="card-body p-0">
                <div id="daftarEmpty" class="text-center py-5 text-muted">
                    <i class="bi bi-receipt fs-1 d-block mb-2"></i>
                    <p class="small">Scan koin untuk menambahkan ke nota pengembalian.</p>
                </div>
                <table id="daftarTable" class="table table-sm table-hover align-middle mb-0" style="display:none">
                    <thead class="table-light">
                        <tr><th>#</th><th>Kode Koin</th><th>Nilai</th></tr>
                    </thead>
                    <tbody id="daftarBody"></tbody>
                </table>
            </div>
            <div class="card-footer bg-transparent border-top">
                <form method="POST" action="{{ route('admin.rekap-pengembalian.store') }}" id="returForm">
                    @csrf
                    <div id="returInputs"></div>
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearDaftar()">
                            <i class="bi bi-trash me-1"></i>Bersihkan
                        </button>
                        <button type="submit" class="btn btn-success" id="btnKonfirmasi" disabled>
                            <i class="bi bi-check2-circle me-1"></i>Konfirmasi Pengembalian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let qr = null, scanMode = null, daftar = [], scanned = false;

function setScanMode(mode) {
    scanMode = mode;
    scanned  = false;
    document.getElementById('hasilScan').innerHTML = '';
    document.getElementById('statusScan').textContent =
        mode === 'tambah' ? '🟢 Mode: Scan TAMBAH — arahkan kamera ke koin.' : '🔴 Mode: Scan HAPUS — arahkan kamera ke koin.';
    startCamera();
}

function startCamera() {
    if (qr) return;
    document.getElementById('btnTambah').classList.add('d-none');
    document.getElementById('btnHapus').classList.add('d-none');
    document.getElementById('btnStop').classList.remove('d-none');
    qr = new Html5Qrcode('reader');
    qr.start({facingMode:'environment'},{fps:10,qrbox:{width:220,height:220}},onScan,null)
      .catch(e=>{ alert('Gagal akses kamera: '+e); stopScan(); });
}

function stopScan() {
    if (qr) qr.stop().then(()=>{ qr.clear(); qr=null; });
    document.getElementById('btnTambah').classList.remove('d-none');
    document.getElementById('btnHapus').classList.remove('d-none');
    document.getElementById('btnStop').classList.add('d-none');
    document.getElementById('statusScan').textContent = '';
}

function onScan(text) {
    if (scanned) return;
    scanned = true;
    stopScan();
    fetch('{{ route("admin.rekap-pengembalian.scan") }}', {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body:JSON.stringify({qr_data:text})
    }).then(r=>r.json()).then(d=>{
        if (scanMode === 'tambah') handleTambah(d);
        else handleHapus(d);
    }).catch(()=>{
        showAlert('danger','Gagal menghubungi server.');
    });
}

function handleTambah(d) {
    if (!d.valid) { showAlert('danger', d.message); return; }
    if (daftar.find(k=>k.id===d.gobog_id)) { showAlert('warning','Koin ini sudah ada di nota pengembalian.'); return; }
    daftar.push({id:d.gobog_id, kode:d.kode_unik, nilai:d.nilai});
    renderDaftar();
    showAlert('success','✔ ' + d.message);
}

function handleHapus(d) {
    if (!d.valid) { showAlert('danger', d.message); return; }
    const before = daftar.length;
    daftar = daftar.filter(k=>k.id!==d.gobog_id);
    if (daftar.length < before) { renderDaftar(); showAlert('info','✖ Koin dihapus dari nota pengembalian.'); }
    else showAlert('warning','Koin ini tidak ada di nota pengembalian.');
}

function renderDaftar() {
    const body   = document.getElementById('daftarBody');
    const inputs = document.getElementById('returInputs');
    const empty  = document.getElementById('daftarEmpty');
    const table  = document.getElementById('daftarTable');
    const badge  = document.getElementById('totalBadge');
    const btnK   = document.getElementById('btnKonfirmasi');

    body.innerHTML   = '';
    inputs.innerHTML = '';

    if (daftar.length === 0) {
        empty.style.display = 'block'; table.style.display = 'none';
        badge.textContent = '0 koin \u2022 Rp 0'; btnK.disabled = true;
        return;
    }

    empty.style.display = 'none'; table.style.display = '';
    btnK.disabled = false;

    let total = 0;
    daftar.forEach((k,i) => {
        total += Number(k.nilai);
        body.innerHTML += `<tr>
            <td class="text-muted small">${i+1}</td>
            <td><code class="small">${k.kode.substring(0,16)}…</code></td>
            <td>Rp ${Number(k.nilai).toLocaleString('id-ID')}</td>
        </tr>`;
        inputs.innerHTML += `<input type="hidden" name="gobog_ids[]" value="${k.id}">`;
    });

    badge.textContent = `${daftar.length} koin \u2022 Rp ${total.toLocaleString('id-ID')}`;
}

function clearDaftar() {
    if (daftar.length && !confirm('Bersihkan semua koin dari nota pengembalian?')) return;
    daftar = []; renderDaftar();
}

function showAlert(type, msg) {
    const el = document.getElementById('hasilScan');
    el.innerHTML = `<div class="alert alert-${type} py-2 small">${msg}</div>`;
    setTimeout(()=>{ el.innerHTML=''; }, 3000);
}
</script>
@endpush
@endsection