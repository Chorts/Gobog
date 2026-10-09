@extends('layouts.app')
@section('title','Cek Keaslian Gobog')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-patch-check me-2 text-success"></i>Cek Keaslian Gobog</h4>
    <span class="badge bg-secondary">Hanya Verifikasi — Tidak Dicatat</span>
</div>
<div class="alert alert-info d-flex gap-2 mb-4">
    <i class="bi bi-info-circle flex-shrink-0 mt-1"></i>
    <span>Halaman ini hanya untuk memeriksa apakah koin <strong>asli atau palsu</strong>. Transaksi tidak akan dicatat. Gunakan <a href="{{ route('penjual.scan-penjualan.index') }}">Scan Penjualan</a> untuk mencatat transaksi.</span>
</div>
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 text-center">
                <div id="reader" class="mx-auto mb-3" style="max-width:320px"></div>
                <div class="d-flex gap-2 justify-content-center mb-3">
                    <button id="startBtn" class="btn btn-success" onclick="startScan()"><i class="bi bi-camera me-1"></i>Cek Keaslian</button>
                    <button id="stopBtn" class="btn btn-outline-secondary d-none" onclick="stopScan()"><i class="bi bi-stop-circle me-1"></i>Stop</button>
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
    scanned=false;document.getElementById('hasil').innerHTML='';
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
    fetch('{{ route("penjual.cek-keaslian.scan") }}',{
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body:JSON.stringify({qr_data:text})
    }).then(r=>r.json()).then(d=>{
        let h=d.valid
            ?`<div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i><strong>${d.message}</strong></div><p>Nilai: Rp ${Number(d.nilai).toLocaleString('id-ID')} &bull; Status: ${d.status}</p>`
            :`<div class="alert alert-danger"><i class="bi bi-x-circle-fill me-2"></i><strong>${d.message}</strong></div>`;
        h+=`<button class="btn btn-outline-secondary w-100 mt-2" onclick="startScan()"><i class="bi bi-arrow-clockwise me-1"></i>Scan Lagi</button>`;
        document.getElementById('hasil').innerHTML=h;
    }).catch(()=>{document.getElementById('hasil').innerHTML='<div class="alert alert-danger">Gagal menghubungi server.</div>';});
}
</script>
@endpush
@endsection