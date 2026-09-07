@extends('layouts.app')
@section('title', 'Laporan Penjualan')
@section('content')
<h1>Laporan Penjualan Koin Gobog</h1>

<form method="GET" action="{{ route('admin.laporan.index') }}">
    <label>Filter:
        <select name="filter" onchange="this.form.submit()">
            <option value="harian"   {{ request('filter','harian')==='harian'  ?'selected':'' }}>Harian</option>
            <option value="mingguan" {{ request('filter')==='mingguan'          ?'selected':'' }}>Mingguan</option>
            <option value="bulanan"  {{ request('filter')==='bulanan'           ?'selected':'' }}>Bulanan</option>
            <option value="tahunan"  {{ request('filter')==='tahunan'           ?'selected':'' }}>Tahunan</option>
        </select>
    </label>
    @if(in_array(request('filter','harian'), ['harian','mingguan']))
        <label>Tanggal: <input type="date" name="tanggal" value="{{ request('tanggal', now()->toDateString()) }}"></label>
    @elseif(request('filter')==='bulanan')
        <label>Bulan: <input type="number" name="bulan" min="1" max="12" value="{{ request('bulan', now()->month) }}" style="width:60px"></label>
        <label>Tahun: <input type="number" name="tahun" min="2020" max="2099" value="{{ request('tahun', now()->year) }}" style="width:80px"></label>
    @else
        <label>Tahun: <input type="number" name="tahun" min="2020" max="2099" value="{{ request('tahun', now()->year) }}" style="width:80px"></label>
    @endif
    <button type="submit">Tampilkan</button>
</form>

<a href="{{ route('admin.laporan.export-pdf', request()->query()) }}"><button>&#128196; Export PDF</button></a>
<hr>

<h2>Distribusi Admin (Rupiah &rarr; Gobog)</h2>
@if($penjualanAdmins->isEmpty())
    <p>Tidak ada data.</p>
@else
    <table border="1" cellpadding="5">
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
        </tbody>
    </table>
@endif

<h2>Transaksi Tenan (Gobog diterima pedagang)</h2>
@if($penjualanTenans->isEmpty())
    <p>Tidak ada data.</p>
@else
    <table border="1" cellpadding="5">
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
        </tbody>
    </table>
@endif

<h2>Retur (Gobog &rarr; Rupiah)</h2>
@if($returnGobogs->isEmpty())
    <p>Tidak ada data.</p>
@else
    <table border="1" cellpadding="5">
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
        </tbody>
    </table>
@endif
@endsection
