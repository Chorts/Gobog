<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Gobog') - Pasar Preng Sewu</title>
</head>
<body>
    <nav>
        <strong>Sistem Koin Gobog - Pasar Preng Sewu</strong>
        &nbsp;|&nbsp;
        Halo, {{ auth()->user()->nama }} ({{ auth()->user()->role }})
        &nbsp;|&nbsp;
        @if(auth()->user()->role === 'admin')
            <a href="{{ route('admin.dashboard') }}">Dashboard</a> |
            <a href="{{ route('admin.gobog.index') }}">Kelola Gobog</a> |
            <a href="{{ route('admin.distribusi.index') }}">Distribusi</a> |
            <a href="{{ route('admin.retur.index') }}">Retur</a> |
            <a href="{{ route('admin.laporan.index') }}">Laporan</a> |
        @else
            <a href="{{ route('penjual.dashboard') }}">Dashboard</a> |
            <a href="{{ route('penjual.scan.index') }}">Scan Koin</a> |
        @endif
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </nav>
    <hr>

    @if(session('success'))
        <p style="color:green"><strong>{{ session('success') }}</strong></p>
    @endif
    @if(session('error'))
        <p style="color:red"><strong>{{ session('error') }}</strong></p>
    @endif

    @yield('content')
</body>
</html>
