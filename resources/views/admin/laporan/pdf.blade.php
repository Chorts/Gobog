<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Koin Gobog</title>
    <style>
        body{font-family:Arial,sans-serif;font-size:11px;color:#333;margin:20px}
        h1{text-align:center;font-size:16px;margin-bottom:4px}
        .sub{text-align:center;font-size:10px;color:#888;margin-bottom:18px}
        h2{font-size:12px;background:#f0f0f0;padding:5px 8px;margin:16px 0 5px;border-left:4px solid #333}
        table{width:100%;border-collapse:collapse;margin-bottom:8px;font-size:10px}
        th{background:#333;color:#fff;padding:4px 7px;text-align:left}
        td{border-bottom:1px solid #ddd;padding:3px 7px}
        tr:nth-child(even)td{background:#fafafa}
        tfoot td{font-weight:bold;background:#e8f5e9;border-top:2px solid #333}
        .asli{color:#155724;font-weight:bold}.palsu{color:#721c24;font-weight:bold}
        .no-data{color:#888;font-style:italic}
        .footer{text-align:center;font-size:9px;color:#999;margin-top:24px;border-top:1px solid #ddd;padding-top:6px}
    </style>
</head>
<body>
    <h1>Laporan Gobog — Pasar Preng Sewu</h1>
    <p class="sub">Dicetak: {{ now()->format('d/m/Y H:i') }}</p>

    <h2>Rekap Penjualan Gobog</h2>
    <table>
        <thead><tr><th>#</th><th>Admin</th><th>Nilai (Rp)</th><th>Waktu</th></tr></thead>
        <tbody>
            @forelse($penjualanAdmins as $item)
                <tr><td>{{ $item->id }}</td><td>{{ $item->user->nama }}</td><td>{{ number_format($item->gobog->nilai,0,',','.') }}</td><td>{{ $item->created_at->format('d/m/Y H:i') }}</td></tr>
            @empty
                <tr><td colspan="4" class="no-data">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
        @if($penjualanAdmins->isNotEmpty())
        <tfoot><tr><td colspan="2" style="text-align:right">Grand Total:</td><td colspan="2">Rp {{ number_format($penjualanAdmins->sum(fn($i)=>$i->gobog->nilai),0,',','.') }}</td></tr></tfoot>
        @endif
    </table>

    <h2>Transaksi Tenan</h2>
    <table>
        <thead><tr><th>#</th><th>Penjual</th><th>Nilai (Rp)</th><th>Status</th><th>Waktu</th></tr></thead>
        <tbody>
            @forelse($penjualanTenans as $item)
                <tr><td>{{ $item->id }}</td><td>{{ $item->user->nama }}</td><td>{{ number_format($item->gobog->nilai,0,',','.') }}</td>
                <td class="{{ $item->valid_status?'asli':'palsu' }}">{{ $item->valid_status?'ASLI':'PALSU' }}</td>
                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td></tr>
            @empty
                <tr><td colspan="5" class="no-data">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
        @if($penjualanTenans->isNotEmpty())
        <tfoot><tr><td colspan="2" style="text-align:right">Grand Total (ASLI):</td><td colspan="3">Rp {{ number_format($penjualanTenans->where('valid_status',1)->sum(fn($i)=>$i->gobog->nilai),0,',','.') }}</td></tr></tfoot>
        @endif
    </table>

    <h2>Rekap Pengembalian</h2>
    <table>
        <thead><tr><th>#</th><th>Admin</th><th>Nilai (Rp)</th><th>Status</th><th>Waktu</th></tr></thead>
        <tbody>
            @forelse($returnGobogs as $item)
                <tr><td>{{ $item->id }}</td><td>{{ $item->user->nama }}</td><td>{{ number_format($item->gobog->nilai,0,',','.') }}</td>
                <td class="{{ $item->valid_status?'asli':'palsu' }}">{{ $item->valid_status?'ASLI':'PALSU' }}</td>
                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td></tr>
            @empty
                <tr><td colspan="5" class="no-data">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
        @if($returnGobogs->isNotEmpty())
        <tfoot><tr><td colspan="2" style="text-align:right">Grand Total (ASLI):</td><td colspan="3">Rp {{ number_format($returnGobogs->where('valid_status',1)->sum(fn($i)=>$i->gobog->nilai),0,',','.') }}</td></tr></tfoot>
        @endif
    </table>

    <h2>Gobog Beredar di Masyarakat</h2>
    <table>
        <thead><tr><th>#</th><th>ID Koin</th><th>Nilai (Rp)</th></tr></thead>
        <tbody>
            @forelse($gobogBeredar as $g)
                <tr><td>{{ $g->id }}</td><td>{{ $g->kode_unik }}</td><td>{{ number_format($g->nilai,0,',','.') }}</td></tr>
            @empty
                <tr><td colspan="3" class="no-data">Tidak ada koin beredar.</td></tr>
            @endforelse
        </tbody>
        @if($gobogBeredar->isNotEmpty())
        <tfoot><tr><td colspan="2" style="text-align:right">Total Nominal Beredar:</td><td>Rp {{ number_format($totalBeredar,0,',','.') }}</td></tr></tfoot>
        @endif
    </table>

    <div class="footer">Sistem Koin Gobog — Pasar Preng Sewu</div>
</body>
</html>