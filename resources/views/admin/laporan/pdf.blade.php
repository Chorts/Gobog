<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Koin Gobog</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; }
        h2 { margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #333; padding: 4px 8px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h1>Laporan Koin Gobog - Pasar Preng Sewu</h1>
    <p>Dicetak: {{ now()->format('d/m/Y H:i') }}</p>

    <h2>Distribusi Admin (Rupiah &rarr; Gobog)</h2>
    <table>
        <thead><tr><th>ID</th><th>Admin</th><th>Nilai (Rp)</th><th>Waktu</th></tr></thead>
        <tbody>
        @foreach($penjualanAdmins as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->user->nama }}</td>
                <td>{{ number_format($item->gobog->nilai, 0, ',', '.') }}</td>
                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        @endforeach
        @if($penjualanAdmins->isEmpty())<tr><td colspan="4">Tidak ada data.</td></tr>@endif
        </tbody>
    </table>

    <h2>Transaksi Tenan (Gobog diterima pedagang)</h2>
    <table>
        <thead><tr><th>ID</th><th>Penjual</th><th>Nilai (Rp)</th><th>Status</th><th>Waktu</th></tr></thead>
        <tbody>
        @foreach($penjualanTenans as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->user->nama }}</td>
                <td>{{ number_format($item->gobog->nilai, 0, ',', '.') }}</td>
                <td>{{ $item->valid_status ? 'ASLI' : 'PALSU' }}</td>
                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        @endforeach
        @if($penjualanTenans->isEmpty())<tr><td colspan="5">Tidak ada data.</td></tr>@endif
        </tbody>
    </table>

    <h2>Retur (Gobog &rarr; Rupiah)</h2>
    <table>
        <thead><tr><th>ID</th><th>Admin</th><th>Nilai (Rp)</th><th>Status</th><th>Waktu</th></tr></thead>
        <tbody>
        @foreach($returnGobogs as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ $item->user->nama }}</td>
                <td>{{ number_format($item->gobog->nilai, 0, ',', '.') }}</td>
                <td>{{ $item->valid_status ? 'ASLI' : 'PALSU' }}</td>
                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        @endforeach
        @if($returnGobogs->isEmpty())<tr><td colspan="5">Tidak ada data.</td></tr>@endif
        </tbody>
    </table>
</body>
</html>
