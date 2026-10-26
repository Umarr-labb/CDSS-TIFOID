<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.pwa-head')
    <title>@yield('title', 'CDSS Demam Tifoid')</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <style>
        :root {
            --primary: #087f8c;
            --primary-dark: #075e68;
            --success: #2a9d8f;
            --warning: #e7a64b;
            --danger:  #d95d65;
            --sidebar-w: 260px;
            --bg-light: #f3f7fa;
            --bs-primary: #087f8c;
            --bs-primary-rgb: 8, 127, 140;
            --bs-info: #087f8c;
            --bs-info-rgb: 8, 127, 140;
            --bs-info-bg-subtle: #e7f6f5;
            --bs-info-text-emphasis: #075e68;
            --bs-link-color: #087f8c;
            --bs-link-hover-color: #075e68;
        }

        * { font-family: 'Inter', sans-serif; }

        body {
            background: var(--bg-light);
            margin: 0;
            min-height: 100vh;
            color: #183247;
        }

        /* ============ SIDEBAR ============ */
        .sidebar {
            position: fixed;
            top: 0; left: 0; bottom: 0;
            width: var(--sidebar-w);
            background: linear-gradient(180deg, #087f8c 0%, #075e68 100%);
            color: #fff;
            padding: 20px 0;
            z-index: 1000;
            transition: all .3s ease;
            box-shadow: 4px 0 20px rgba(0,0,0,.08);
        }
        .sidebar-brand {
            padding: 0 24px 20px;
            border-bottom: 1px solid rgba(255,255,255,.15);
            display: flex; align-items: center; gap: 12px;
        }
        .sidebar-brand .logo {
            width: 42px; height: 42px;
            background: #fff;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            color: var(--primary);
            font-size: 22px; font-weight: bold;
        }
        .sidebar-brand h5 { margin: 0; font-weight: 700; font-size: 16px; }
        .sidebar-brand small { font-size: 11px; opacity: .8; }

        .sidebar-menu { padding: 16px 12px; list-style: none; margin: 0; }
        .sidebar-menu .menu-label {
            font-size: 11px; text-transform: uppercase;
            letter-spacing: 1px; opacity: .6;
            padding: 12px 14px 6px;
        }
        .sidebar-menu a {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 14px;
            color: rgba(255,255,255,.85);
            text-decoration: none;
            border-radius: 10px;
            font-size: 14px; font-weight: 500;
            margin-bottom: 4px;
            transition: all .2s;
        }
        .sidebar-menu a:hover {
            background: rgba(255,255,255,.1);
            color: #fff;
            transform: translateX(3px);
        }
        .sidebar-menu a.active {
            background: #fff;
            color: var(--primary);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0,0,0,.15);
        }
        .sidebar-menu a i { font-size: 18px; }

        /* ============ MAIN ============ */
        .main-content {
            margin-left: var(--sidebar-w);
            padding: 24px 28px;
            min-height: 100vh;
            transition: all .3s;
        }

        .topbar {
            background: linear-gradient(110deg, #fff 0%, #fbfefe 100%);
            border-radius: 16px;
            padding: 14px 22px;
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 4px 20px rgba(0,0,0,.04);
            margin-bottom: 24px;
            border: 1px solid rgba(227, 237, 241, .9);
        }
        .sidebar-toggle {
            display: none;
            width: 40px; height: 40px;
            align-items: center; justify-content: center;
            border: 1px solid #dce7ec; border-radius: 10px;
            color: var(--primary-dark); background: #fff;
        }
        .topbar h4 { margin: 0; font-weight: 700; color: #1f2937; }
        .topbar small { color: #6b7280; }

        .user-chip {
            display: flex; align-items: center; gap: 12px;
            padding: 6px 14px 6px 6px;
            background: #eaf7f6;
            border-radius: 50px;
            cursor: pointer;
        }
        .user-chip .avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--success));
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700;
        }
        .user-chip .info { line-height: 1.2; }
        .user-chip .info strong { font-size: 14px; color: #1f2937; display: block; }
        .user-chip .info span { font-size: 12px; color: #6b7280; text-transform: capitalize; }

        /* ============ STAT CARD ============ */
        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 4px 20px rgba(0,0,0,.04);
            transition: all .3s;
            border-left: 5px solid var(--primary);
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(8,127,140,.15);
        }
        .stat-card .icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 24px;
            background: rgba(8,127,140,.1);
            color: var(--primary);
            margin-bottom: 14px;
        }
        .stat-card h3 {
            font-size: 28px; font-weight: 700; margin: 0;
            color: #1f2937;
        }
        .stat-card p {
            margin: 4px 0 0; font-size: 13px; color: #6b7280;
            font-weight: 500;
        }
        .stat-card.green  { border-left-color: var(--success); }
        .stat-card.green .icon { background: rgba(42,157,143,.1); color: var(--success); }
        .stat-card.orange { border-left-color: var(--warning); }
        .stat-card.orange .icon { background: rgba(244,162,97,.1); color: var(--warning); }
        .stat-card.red    { border-left-color: var(--danger); }
        .stat-card.red .icon { background: rgba(230,57,70,.1); color: var(--danger); }

        /* ============ PANEL ============ */
        .panel {
            background: #fff;
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 4px 20px rgba(0,0,0,.04);
            margin-bottom: 24px;
            border: 1px solid rgba(227, 237, 241, .9);
        }
        .panel-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 18px;
        }
        .panel-header h5 {
            margin: 0; font-weight: 700; font-size: 16px; color: #1f2937;
        }
        .panel-header .badge-soft {
            background: #e1f4f2; color: var(--primary-dark);
            font-size: 12px; font-weight: 600;
            padding: 4px 12px; border-radius: 50px;
        }

        /* ============ QUICK ACTION ============ */
        .quick-action {
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 22px 12px;
            border-radius: 14px;
            background: linear-gradient(135deg, #effaf9 0%, #dff3f1 100%);
            text-decoration: none;
            color: var(--primary);
            transition: all .3s;
            border: 2px dashed transparent;
        }
        .quick-action:hover {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: #fff;
            border-color: #fff;
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(8,127,140,.24);
        }
        .quick-action i { font-size: 32px; margin-bottom: 10px; }
        .quick-action span { font-weight: 600; font-size: 13px; text-align: center; }

        /* ============ TABLE ============ */
        .table-modern thead th {
            font-size: 12px; text-transform: uppercase;
            color: #6b7280; font-weight: 600;
            border-bottom: 2px solid #f0f0f0;
            padding: 12px 8px;
        }
        .table-modern tbody td {
            font-size: 14px; padding: 12px 8px;
            border-bottom: 1px solid #f5f5f5;
            vertical-align: middle;
        }
        .table-modern tbody tr { transition: background-color .15s ease; }
        .table-modern tbody tr:hover { background: #f7fbfc; }
        .form-control, .form-select {
            border-color: #dce7ec;
            border-radius: 10px;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 .2rem rgba(8,127,140,.12);
        }
        .btn { border-radius: 10px; font-weight: 600; transition: transform .15s ease, box-shadow .15s ease; }
        .btn-primary {
            --bs-btn-bg: var(--primary);
            --bs-btn-border-color: var(--primary);
            --bs-btn-hover-bg: var(--primary-dark);
            --bs-btn-hover-border-color: var(--primary-dark);
            --bs-btn-active-bg: var(--primary-dark);
            --bs-btn-active-border-color: var(--primary-dark);
        }
        .btn-primary:hover { box-shadow: 0 7px 16px rgba(8,127,140,.18); transform: translateY(-1px); }
        .btn-outline-primary {
            --bs-btn-color: var(--primary);
            --bs-btn-border-color: #9acacb;
            --bs-btn-hover-bg: var(--primary);
            --bs-btn-hover-border-color: var(--primary);
        }
        .pagination { gap: 4px; }
        .page-link { border-radius: 8px !important; color: var(--primary); }
        .page-item.active .page-link { background: var(--primary); border-color: var(--primary); }
        .workflow-steps {
            display: flex; align-items: center; gap: 12px;
            padding: 15px 20px; border: 1px solid #e3edf1;
            border-radius: 14px; background: #fff;
        }
        .workflow-step {
            display: inline-flex; align-items: center; gap: 8px;
            color: #8193a1; font-size: .82rem; font-weight: 700;
        }
        .workflow-step b {
            display: grid; width: 28px; height: 28px; place-items: center;
            border-radius: 50%; background: #edf3f6; font-size: .75rem;
        }
        .workflow-step.active { color: var(--primary-dark); }
        .workflow-step.active b { color: #fff; background: var(--primary); }
        .workflow-step.complete { color: var(--primary-dark); }
        .workflow-step.complete b { color: var(--primary-dark); background: #e7f6f5; }
        .workflow-divider { width: 44px; height: 1px; background: #d8e4e9; }
        .workflow-form-footer {
            display: flex; align-items: center; justify-content: space-between; gap: 14px;
        }
        .badge-role {
            font-size: 11px; padding: 4px 10px;
            border-radius: 50px; font-weight: 600;
        }
        .badge-admin   { background: #fee2e2; color: #991b1b; }
        .badge-dokter  { background: #dbeafe; color: #1e40af; }
        .badge-perawat { background: #d1fae5; color: #065f46; }
        .badge-kuat    { background: #d1fae5; color: #065f46; }
        .badge-sedang  { background: #fef3c7; color: #92400e; }
        .badge-lemah   { background: #fee2e2; color: #991b1b; }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .sidebar-toggle { display: inline-flex; }
            .topbar { gap: 12px; padding: 12px 15px; }
        }
        @media (max-width: 600px) {
            .workflow-steps { gap: 7px; padding: 12px; }
            .workflow-step { gap: 5px; font-size: .68rem; }
            .workflow-step b { width: 25px; height: 25px; }
            .workflow-divider { flex: 1; width: auto; }
            .workflow-form-footer { align-items: stretch; flex-direction: column; }
            .main-content { padding: 16px 14px; }
            .topbar { align-items: flex-start; }
            .topbar h4 { font-size: 1rem; line-height: 1.25; }
            .topbar small { font-size: .72rem; line-height: 1.35; }
            .topbar .user-chip .info { display: none; }
            .topbar .user-chip { padding: 4px; }
            .topbar .user-chip .avatar { width: 34px; height: 34px; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ==================== SIDEBAR ==================== --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="logo"><i class="bi bi-heart-pulse-fill"></i></div>
        <div>
            <h5>CDSS Tifoid</h5>
            <small>Puskesmas X</small>
        </div>
    </div>

    <ul class="sidebar-menu">
        <li class="menu-label">Menu Utama</li>
        <li>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
        </li>

        {{-- Menu Khusus Admin --}}
        @if(auth()->user()->role === 'admin')
            <li class="menu-label">Master Data</li>
            <li>
                <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Akun Dokter & Perawat
                </a>
            </li>
            <li>
                <a href="{{ route('penyakit.index') }}" class="{{ request()->routeIs('penyakit.*') ? 'active' : '' }}">
                    <i class="bi bi-virus"></i> Data Penyakit
                </a>
            </li>
            <li>
                <a href="{{ route('gejala.index') }}" class="{{ request()->routeIs('gejala.*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard2-pulse"></i> Data Gejala
                </a>
            </li>
            <li>
                <a href="{{ route('rule.index') }}" class="{{ request()->routeIs('rule.*') ? 'active' : '' }}">
                    <i class="bi bi-diagram-3-fill"></i> Basis Aturan
                </a>
            </li>
        @endif

        {{-- Menu Dokter & Perawat --}}
        @if(in_array(auth()->user()->role, ['admin','dokter','perawat']))
            <li class="menu-label">Pelayanan</li>
            <li>
                <a href="{{ route('konsultasi.createPasien') }}" class="{{ request()->routeIs('konsultasi.createPasien') ? 'active' : '' }}">
                    <i class="bi bi-plus-circle-fill"></i> Konsultasi Baru
                </a>
            </li>
            <li>
                <a href="{{ route('konsultasi.riwayat') }}" class="{{ request()->routeIs('konsultasi.riwayat') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> Riwayat Diagnosis
                </a>
            </li>
        @endif

        <li class="menu-label">Akun</li>
        <li>
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</aside>

{{-- ==================== MAIN ==================== --}}
<main class="main-content">

    {{-- Topbar --}}
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button type="button" class="sidebar-toggle" id="sidebar-toggle" aria-label="Buka menu navigasi" aria-controls="sidebar" aria-expanded="false">
                <i class="bi bi-list fs-4"></i>
            </button>
            <div>
                <h4>@yield('page-title', 'Dashboard')</h4>
                <small>@yield('page-subtitle', 'Selamat datang di sistem pendukung keputusan klinis')</small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="text-muted small d-none d-md-inline">
                <i class="bi bi-calendar3"></i> {{ now()->translatedFormat('d F Y') }}
            </span>
            <div class="user-chip dropdown">
                <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="info">
                    <strong>{{ auth()->user()->name }}</strong>
                    <span>{{ auth()->user()->role }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Content --}}
    @yield('content')

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            const isOpen = sidebar.classList.toggle('show');
            sidebarToggle.setAttribute('aria-expanded', String(isOpen));
        });
    }
</script>
@stack('scripts')
@include('partials.pwa-install')
</body>
</html>
