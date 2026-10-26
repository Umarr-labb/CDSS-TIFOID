<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('partials.pwa-head')
    <title>Login Dokter | CDSS Tifoid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('auth.partials.login-styles')
</head>
<body>
    <main class="login-shell d-flex align-items-center justify-content-center">
        <section class="login-form-card">
            <a href="{{ route('login') }}" class="back-link">&larr; Kembali memilih akses</a>
            <div class="login-brand mt-4 mb-2">CDSS Tifoid / Dokter</div>
            <h1 class="login-title h2 mb-2">Login Dokter</h1>
            <p class="login-subtitle mb-4">Masuk untuk melihat diagnosis dan konsultasi.</p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('dokter.login.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Username</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="dokter123" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn login-button text-white w-100 py-2">Mulai konsultasi</button>
                </form>
        </section>
    </main>
    @include('partials.pwa-install')
</body>
</html>
