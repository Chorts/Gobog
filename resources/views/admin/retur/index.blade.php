@extends('layouts.app')
@section('title', 'Retur Koin')
@section('content')
<h1>Retur Koin Gobog (Gobog &rarr; Rupiah)</h1>
<p>Scan QR Code koin yang dibawa pengunjung untuk memverifikasi keasliannya.</p>

<button id="startBtn" onclick="startScan()">&#128247; Mulai Scan QR untuk Retur</button>
<button id="stopBtn" onclick="stopScan()" style="display:none">Stop Scan</button>

<div id="reader" style="width:350px;margin-top:15px"></div>
<div id="hasil" style="margin-top:15px"></div>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let qr = null, scanned = false;

function startScan() {
    scanned = false;
    document.getElementById('hasil').innerHTML = '';
    document.getElementById('startBtn').style.display = 'none';
    document.getElementById('stopBtn').style.display = 'inline';
    qr = new Html5Qrcode('reader');
    qr.start({facingMode:'environment'},{fps:10,qrbox:{width:250,height:250}},onScan,null)
      .catch(e=>{ alert('Gagal akses kamera: '+e); stopScan(); });
}

function stopScan() {
    if(qr) qr.stop().then(()=>{ qr.clear(); qr=null; });
    document.getElementById('startBtn').style.display = 'inline';
    document.getElementById('stopBtn').style.display = 'none';
}

function onScan(text) {
    if(scanned) return;
    scanned = true;
    stopScan();
    fetch('{{ route("admin.retur.scan") }}', {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body:JSON.stringify({qr_data:text})
    }).then(r=>r.json()).then(tampilkan).catch(()=>{
        document.getElementById('hasil').innerHTML='<p style="color:red">Gagal menghubungi server.</p>';
    });
}

function tampilkan(d) {
    let h = '';
    if(d.valid) {
        h += '<p style="color:green"><strong>&#9989; '+d.message+'</strong></p>';
        h += '<p>Nilai: Rp '+Number(d.nilai).toLocaleString('id-ID')+'</p>';
        h += '<p>Status koin: '+d.status+'</p>';
        h += '<form method="POST" action="{{ route("admin.retur.store") }}">';
        h += '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        h += '<input type="hidden" name="gobogs_id" value="'+d.gobog_id+'">';
        h += '<input type="hidden" name="valid_status" value="1">';
        h += '<button type="submit">&#9989; Konfirmasi Retur (ASLI)</button></form>';
    } else {
        h += '<p style="color:red"><strong>&#10060; '+d.message+'</strong></p>';
        h += '<form method="POST" action="{{ route("admin.retur.store") }}">';
        h += '<input type="hidden" name="_token" value="{{ csrf_token() }}">';
        h += '<input type="hidden" name="gobogs_id" value="0">';
        h += '<input type="hidden" name="valid_status" value="0">';
        h += '<button type="submit">&#9888; Catat sebagai PALSU</button></form>';
    }
    document.getElementById('hasil').innerHTML = h;
}
</script>
@endsection
