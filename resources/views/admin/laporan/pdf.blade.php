<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Koin Gobog</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; margin: 20px; }
        h1 { text-align: center; font-size: 18px; margin-bottom: 4px; }
        .subtitle { text-align: center; font-size: 11px; color: #666; margin-bottom: 20px; }
        h2 { font-size: 13px; background: #f0f0f0; padding: 6px 10px; margin: 20px 0 6px; border-left: 4px solid #333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; font-size: 11px; }
        th { background: #333; color: #fff; padding: 5px 8px; text-align: left; }
        td { border-bottom: 1px solid #ddd; padding: 4px 8px; }
        tr:nth-child(even) td { background: #fafafa; }
        .badge-asli { color: #155724; font-weight: bold; }
        .badge-palsu { color: #721c24; font-weight: bold; }
        .no-data { color: #888; font-style: italic; padding: 8px; }
        .footer { text-align: center; font-size: 10px; color: #888; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 8px; }
    </style>
</head>
<body>
    <h1>Laporan Koin Gobog</h1>
    <p class="subtitle">Pasar Preng Sewu &mdash; Dicetak: {{ now()->format('d/m/Y H:i') }}</p>

    <h2>Distribusi Admin (Rupiah &rarr; Gobog)</h2>
    <table>
        <thead><tr><th>#</th><th>Admin</th><th>Nilai (Rp)</th><th>Waktu</th></tr></thead>
        <tbody>
            @forelse($penjualanAdmins as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->user->nama }}</td>
                    <td>{{ number_format($item->gobog->nilai, 0, ',', '.') }}</td>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="no-data">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Transaksi Tenan (Gobog diterima pedagang)</h2>
    <table>
        <thead><tr><th>#</th><th>Penjual</th><th>Nilai (Rp)</th><th>Status</th><th>Waktu</th></tr></thead>
        <tbody>
            @forelse($penjualanTenans as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->user->nama }}</td>
                    <td>{{ number_format($item->gobog->nilai, 0, ',', '.') }}</td>
                    <td class="{{ $item->valid_status ? 'badge-asli' : 'badge-palsu' }}">
                        {{ $item->valid_status ? 'ASLI' : 'PALSU' }}
                    </td>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="no-data">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Retur (Gobog &rarr; Rupiah)</h2>
    <table>
        <thead><tr><th>#</th><th>Admin</th><th>Nilai (Rp)</th><th>Status</th><th>Waktu</th></tr></thead>
        <tbody>
            @forelse($returnGobogs as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->user->nama }}</td>
                    <td>{{ number_format($item->gobog->nilai, 0, ',', '.') }}</td>
                    <td class="{{ $item->valid_status ? 'badge-asli' : 'badge-palsu' }}">
                        {{ $item->valid_status ? 'ASLI' : 'PALSU' }}
                    </td>
                    <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="no-data">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Sistem Koin Gobog &mdash; Pasar Preng Sewu</div>
</body>
</html>