@extends('layouts.app')
@section('title','Rekap Pengembalian Gobog')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-arrow-return-left me-2 text-info"></i>Rekap Pengembalian Gobog</h4>
    <span class="badge bg-info">Gobog &rarr; Rupiah</span>
</div>
<div class="alert alert-secondary d-flex gap-2 align-items-center mb-4">
    <i class="bi bi-camera-video flex-shrink-0"></i>
    <span>Scan QR Code koin yang dikembalikan pengunjung untuk memverifikasi keasliannya.</span>
</div>
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 text-center">
                <div id="reader" class="mx-auto mb-3" style="width:100%;max-width:350px"></div>
                <div class="d-flex gap-2 justify-content-center mb-3">
                    <button id="startBtn" class="btn btn-primary" onclick="startScan()">
                        <i class="bi bi-camera me-1"></i>Mulai Scan
                    </button>
                    <button id="stopBtn" class="btn btn-outline-secondary d-none" onclick="stopScan()">
                        <i class="bi bi-stop-circle me-1"></i>Stop
                    </button>
                </div>
                <div id="hasil"></div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
let qr=null,scanned=false;
function startScan(){
    scanned=false;
    document.getElementById('hasil').innerHTML='';
    document.getElementById('startBtn').classList.add('d-none');
    document.getElementById('stopBtn').classList.remove('d-none');
    qr=new Html5Qrcode('reader');
    qr.start({facingMode:'environment'},{fps:10,qrbox:{width:250,height:250}},onScan,null)
      .catch(e=>{alert('Gagal akses kamera: '+e);stopScan();});
}
function stopScan(){
    if(qr)qr.stop().then(()=>{qr.clear();qr=null;});
    document.getElementById('startBtn').classList.remove('d-none');
    document.getElementById('stopBtn').classList.add('d-none');
}
function onScan(text){
    if(scanned)return;scanned=true;stopScan();
    fetch('{{ route("admin.rekap-pengembalian.scan") }}',{
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body:JSON.stringify({qr_data:text})
    }).then(r=>r.json()).then(tampilkan).catch(()=>{
        document.getElementById('hasil').innerHTML='<div class="alert alert-danger">Gagal menghubungi server.</div>';
    });
}
function tampilkan(d){
    let h='';
    if(d.valid){
        h+=`<div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i><strong>${d.message}</strong></div>`;
        h+=`<p class="mb-1"><strong>Nilai:</strong> Rp ${Number(d.nilai).toLocaleString('id-ID')}</p>`;
        h+=`<form method="POST" action="{{ route('admin.rekap-pengembalian.store') }}">`;
        h+=`<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
        h+=`<input type="hidden" name="gobogs_id" value="${d.gobog_id}">`;
        h+=`<input type="hidden" name="valid_status" value="1">`;
        h+=`<button type="submit" class="btn btn-success w-100"><i class="bi bi-check2-circle me-2"></i>Konfirmasi Pengembalian (ASLI)</button></form>`;
    }else{
        h+=`<div class="alert alert-danger"><i class="bi bi-x-circle-fill me-2"></i><strong>${d.message}</strong></div>`;
        h+=`<form method="POST" action="{{ route('admin.rekap-pengembalian.store') }}">`;
        h+=`<input type="hidden" name="_token" value="{{ csrf_token() }}">`;
        h+=`<input type="hidden" name="gobogs_id" value="0">`;
        h+=`<input type="hidden" name="valid_status" value="0">`;
        h+=`<button type="submit" class="btn btn-outline-danger w-100"><i class="bi bi-exclamation-triangle me-2"></i>Catat sebagai PALSU</button></form>`;
    }
    h+=`<button class="btn btn-outline-secondary w-100 mt-2" onclick="startScan()"><i class="bi bi-arrow-clockwise me-1"></i>Scan Lagi</button>`;
    document.getElementById('hasil').innerHTML=h;
}
</script>
@endpush
@endsection