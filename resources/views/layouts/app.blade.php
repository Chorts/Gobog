<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Gobog') - Pasar Preng Sewu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root { --sidebar-w: 240px; }
        body { background: #f0f2f5; min-height: 100vh; }

        /* Sidebar */
        #sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: #1a2035;
            position: fixed;
            top: 0; left: 0;
            z-index: 1050;
            transition: transform .25s ease;
            display: flex;
            flex-direction: column;
        }
        #sidebar .sidebar-brand {
            padding: 20px 18px 16px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        #sidebar .sidebar-brand h5 {
            color: #fff;
            font-weight: 700;
            margin: 0;
            font-size: 1rem;
        }
        #sidebar .sidebar-brand small { color: rgba(255,255,255,.45); font-size: .72rem; }

        #sidebar .nav-section {
            padding: 10px 12px 4px;
            font-size: .68rem;
            font-weight: 700;
            letter-spacing: .08em;
            color: rgba(255,255,255,.35);
            text-transform: uppercase;
        }
        #sidebar .nav-link {
            color: rgba(255,255,255,.65);
            border-radius: 8px;
            margin: 1px 10px;
            padding: 8px 12px;
            font-size: .875rem;
            transition: background .15s, color .15s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        #sidebar .nav-link:hover { background: rgba(255,255,255,.08); color: #fff; }
        #sidebar .nav-link.active { background: #0d6efd; color: #fff; }
        #sidebar .nav-link i { width: 18px; text-align: center; font-size: 1rem; }

        #sidebar .sidebar-footer {
            margin-top: auto;
            padding: 14px 12px;
            border-top: 1px solid rgba(255,255,255,.08);
        }
        #sidebar .user-info { color: rgba(255,255,255,.75); font-size: .8rem; }
        #sidebar .user-info .name { color: #fff; font-weight: 600; font-size: .875rem; }

        /* Main */
        #main { margin-left: var(--sidebar-w); min-height: 100vh; }
        #topbar {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: 10px 20px;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .page-content { padding: 24px; }

        /* Mobile */
        @media (max-width: 991.98px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #main { margin-left: 0; }
            #overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 1040; }
            #overlay.show { display: block; }
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
    <div class="sidebar-brand">
        <h5><i class="bi bi-coin me-2 text-warning"></i>Gobog</h5>
        <small>Pasar Preng Sewu</small>
    </div>

    <nav class="mt-2 flex-grow-1 overflow-auto">
        @auth
            @if(auth()->user()->role === 'admin')
                <div class="nav-section">Menu Utama</div>
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('admin.gobog.index') }}"
                   class="nav-link {{ request()->routeIs('admin.gobog.*') ? 'active' : '' }}">
                    <i class="bi bi-coin"></i> Kelola Gobog
                </a>

                <div class="nav-section mt-2">Transaksi</div>
                <a href="{{ route('admin.rekap-penjualan.index') }}"
                   class="nav-link {{ request()->routeIs('admin.rekap-penjualan.*') ? 'active' : '' }}">
                    <i class="bi bi-bag-check"></i> Rekap Penjualan
                </a>
                <a href="{{ route('admin.rekap-pengembalian.index') }}"
                   class="nav-link {{ request()->routeIs('admin.rekap-pengembalian.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-return-left"></i> Rekap Pengembalian
                </a>
                <a href="{{ route('admin.laporan.index') }}"
                   class="nav-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph"></i> Laporan
                </a>

                <div class="nav-section mt-2">Sistem</div>
                <a href="{{ route('admin.pengaturan.index') }}"
                   class="nav-link {{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}">
                    <i class="bi bi-gear"></i> Pengaturan
                </a>
            @else
                <div class="nav-section">Menu Penjual</div>
                <a href="{{ route('penjual.dashboard') }}"
                   class="nav-link {{ request()->routeIs('penjual.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('penjual.cek-keaslian.index') }}"
                   class="nav-link {{ request()->routeIs('penjual.cek-keaslian.*') ? 'active' : '' }}">
                    <i class="bi bi-patch-check"></i> Cek Keaslian
                </a>
                <a href="{{ route('penjual.scan-penjualan.index') }}"
                   class="nav-link {{ request()->routeIs('penjual.scan-penjualan.*') ? 'active' : '' }}">
                    <i class="bi bi-qr-code-scan"></i> Scan Penjualan
                </a>
            @endif
        @endauth
    </nav>

    <div class="sidebar-footer">
        @auth
            <div class="user-info mb-2">
                <div class="name"><i class="bi bi-person-circle me-1"></i>{{ auth()->user()->nama }}</div>
                <span class="badge bg-primary mt-1">{{ auth()->user()->role }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                </button>
            </form>
        @endauth
    </div>
</div>

<!-- Overlay (mobile) -->
<div id="overlay" onclick="closeSidebar()"></div>

<!-- Main -->
<div id="main">
    <!-- Topbar -->
    <div id="topbar" class="d-flex align-items-center">
        <button class="btn btn-sm btn-outline-secondary d-lg-none me-3" onclick="toggleSidebar()">
            <i class="bi bi-list fs-5"></i>
        </button>
        <span class="fw-semibold text-muted small">@yield('title', 'Dashboard')</span>
    </div>

    <!-- Content -->
    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
    document.getElementById('overlay').classList.toggle('show');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('show');
    document.getElementById('overlay').classList.remove('show');
}
</script>
@stack('scripts')
</body>
</html>