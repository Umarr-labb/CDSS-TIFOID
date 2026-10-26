<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.pwa-head')
    <title>Portal Akses | CDSS Tifoid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('auth.partials.login-styles')
</head>
<body>
    <main class="login-shell d-flex align-items-center justify-content-center">
        <section class="login-panel">
            <div class="login-hero">
                <div class="login-brand mb-3">
                    <span class="brand-badge">CDSS</span>
                    Tifoid
                </div>
                <h1 class="login-title display-6 mb-3">Portal akses klinis yang lebih cepat & aman</h1>
                <p class="login-subtitle mb-4">Pilih peran Anda untuk masuk ke dashboard kerja dan memantau proses konsultasi pasien dengan lebih terstruktur.</p>

                <div class="feature-list">
                    <div class="feature-item">
                        <span class="feature-icon">✓</span>
                        <span>Diagnosis terarah</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">✓</span>
                        <span>Data pasien terjaga</span>
                    </div>
                    <div class="feature-item">
                        <span class="feature-icon">✓</span>
                        <span>Workflow klinis rapi</span>
                    </div>
                </div>

                <div class="mini-stats">
                    <div class="stat-box">
                        <strong>3</strong>
                        <span>Peran akses</span>
                    </div>
                    <div class="stat-box">
                        <strong>24/7</strong>
                        <span>Monitoring</span>
                    </div>
                </div>
            </div>

            <div class="login-roles">
                <div class="role-header">
                    <span class="eyebrow">Pilih akses</span>
                    <h2>Masuk sebagai</h2>
                </div>

                <div class="role-grid">
                    <a href="{{ route('admin.login') }}" class="role-card">
                        <span class="role-mark">A</span>
                        <h2>Admin</h2>
                        <p>Kelola data, akses, dan pengaturan sistem secara menyeluruh.</p>
                        <span class="role-link">Masuk sebagai admin &rarr;</span>
                    </a>
                    <a href="{{ route('dokter.login') }}" class="role-card">
                        <span class="role-mark">D</span>
                        <h2>Dokter</h2>
                        <p>Akses diagnosis, hasil konsultasi, dan evaluasi pasien.</p>
                        <span class="role-link">Masuk sebagai dokter &rarr;</span>
                    </a>
                    <a href="{{ route('perawat.login') }}" class="role-card">
                        <span class="role-mark">P</span>
                        <h2>Perawat</h2>
                        <p>Kelola konsultasi pasien dan dukung proses penanganan klinis.</p>
                        <span class="role-link">Masuk sebagai perawat &rarr;</span>
                    </a>
                </div>
            </div>
        </section>
    </main>
    @include('partials.pwa-install')
</body>
</html>
