@extends('layouts.app')
@section('title','Scan Penjualan')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-qr-code-scan me-2 text-primary"></i>Scan Penjualan (Nota Belanja)</h4>
    <span class="badge bg-primary">Dicatat ke Laporan</span>
</div>
<div class="alert alert-warning d-flex gap-2 mb-4">
    <i class="bi bi-info-circle flex-shrink-0 mt-1"></i>
    <span>Gunakan <strong>Scan Tambah</strong> untuk memasukkan koin pembayaran pembeli ke nota, dan <strong>Scan Hapus</strong> jika ada koin yang ingin dibatalkan. Transaksi akan dicatat ke laporan saat Anda menekan <strong>Terima Pembayaran</strong>.</span>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header border-bottom bg-transparent fw-semibold">Scanner QR Koin</div>
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
                <span class="fw-semibold">Nota Belanja Tenan</span>
                <span id="totalBadge" class="badge bg-primary">0 koin &bull; Rp 0</span>
            </div>
            <div class="card-body p-0">
                <div id="notaEmpty" class="text-center py-5 text-muted">
                    <i class="bi bi-receipt fs-1 d-block mb-2"></i>
                    <p class="small">Scan koin pembeli untuk menambahkan ke nota belanja.</p>
                </div>
                <table id="notaTable" class="table table-sm table-hover align-middle mb-0" style="display:none">
                    <thead class="table-light">
                        <tr><th>#</th><th>Kode Koin</th><th>Nilai</th></tr>
                    </thead>
                    <tbody id="notaBody"></tbody>
                </table>
            </div>
            <div class="card-footer bg-transparent border-top">
                <form method="POST" action="{{ route('penjual.scan-penjualan.store') }}" id="notaForm">
                    @csrf
                    <div id="notaInputs"></div>
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="clearNota()">
                            <i class="bi bi-trash me-1"></i>Bersihkan
                        </button>
                        <button type="submit" class="btn btn-success" id="btnKonfirmasi" disabled>
                            <i class="bi bi-check2-circle me-1"></i>Terima Pembayaran
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
let qr = null, scanMode = null, nota = [], scanned = false;
let alertTimer = null;

function setScanMode(mode) {
    scanMode = mode;
    scanned  = false;
    document.getElementById('hasilScan').innerHTML = '';
    document.getElementById('statusScan').textContent =
        mode === 'tambah'
            ? '🟢 Mode: Scan TAMBAH — arahkan kamera ke koin pembayaran.'
            : '🔴 Mode: Scan HAPUS — arahkan kamera ke koin yang ingin dibatalkan.';
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
    fetch('{{ route("penjual.scan-penjualan.scan") }}', {
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
    if (!d.valid) {
        showRejectionAlert(d);
        return;
    }
    if (nota.find(k=>k.id===d.gobog_id)) {
        showAlert('warning','Koin ini sudah ada di nota belanja.');
        return;
    }
    nota.push({id:d.gobog_id, kode:d.kode_unik, nilai:d.nilai});
    renderNota();
    showAlert('success','✔ ' + d.message);
}

function handleHapus(d) {
    if (!d.valid) {
        showRejectionAlert(d);
        return;
    }
    const before = nota.length;
    nota = nota.filter(k=>k.id!==d.gobog_id);
    if (nota.length < before) {
        renderNota();
        showAlert('info','✖ Koin dihapus dari nota belanja.');
    } else {
        showAlert('warning','Koin ini tidak ada di nota belanja.');
    }
}

function renderNota() {
    const body   = document.getElementById('notaBody');
    const inputs = document.getElementById('notaInputs');
    const empty  = document.getElementById('notaEmpty');
    const table  = document.getElementById('notaTable');
    const badge  = document.getElementById('totalBadge');
    const btnK   = document.getElementById('btnKonfirmasi');

    body.innerHTML   = '';
    inputs.innerHTML = '';

    if (nota.length === 0) {
        empty.style.display = 'block'; table.style.display = 'none';
        badge.textContent = '0 koin \u2022 Rp 0'; btnK.disabled = true;
        return;
    }

    empty.style.display = 'none'; table.style.display = '';
    btnK.disabled = false;

    let total = 0;
    nota.forEach((k,i) => {
        total += Number(k.nilai);
        body.innerHTML += `<tr>
            <td class="text-muted small">${i+1}</td>
            <td><code class="small">${k.kode.substring(0,16)}…</code></td>
            <td>Rp ${Number(k.nilai).toLocaleString('id-ID')}</td>
        </tr>`;
        inputs.innerHTML += `<input type="hidden" name="gobog_ids[]" value="${k.id}">`;
    });

    badge.textContent = `${nota.length} koin \u2022 Rp ${total.toLocaleString('id-ID')}`;
}

function clearNota() {
    if (nota.length && !confirm('Bersihkan semua koin dari nota belanja?')) return;
    nota = []; renderNota();
}

function showRejectionAlert(d) {
    const el = document.getElementById('hasilScan');
    let h = `<div class="alert alert-danger py-2 small mb-0"><i class="bi bi-x-circle-fill me-2"></i><strong>${d.message}</strong>`;
    if (d.gobog_id) {
        h += `<form method="POST" action="{{ route('penjual.scan-penjualan.store') }}" class="mt-2">`;
        h += `<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
        h += `<input type="hidden" name="gobogs_id" value="${d.gobog_id}">`;
        h += `<input type="hidden" name="valid_status" value="0">`;
        h += `<button type="submit" class="btn btn-outline-danger btn-sm w-100"><i class="bi bi-x-circle me-1"></i>Tolak &amp; Catat Transaksi Palsu</button></form>`;
    }
    h += `</div>`;
    el.innerHTML = h;
}

function showAlert(type, msg) {
    const el = document.getElementById('hasilScan');
    el.innerHTML = `<div class="alert alert-${type} py-2 small mb-0">${msg}</div>`;
    if (alertTimer) {
        clearTimeout(alertTimer);
    }
    alertTimer = setTimeout(()=>{ el.innerHTML=''; alertTimer = null; }, 3000);
}
</script>
@endpush
@endsection