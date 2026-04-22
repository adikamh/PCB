<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - PCB-KAL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body data-login="{{ auth()->check() ? 'true' : 'false' }}">
    <div id="alert-placeholder"></div>

    <div class="login-wrapper">
        <div class="login-card card">
            <div class="card-body p-4">
                <div class="login-header">
                    <h2>Halo..</h2>
                    <p class="text-muted">Silahkan login ke akun Anda</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>
                                <strong>Login Gagal!</strong> {{ $errors->first() }}
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST" id="login-form">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" 
                            value="{{ old('username', $rememberedUsername ?? '') }}" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" name="password" id="password" class="form-control" required>
                            <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                <i class="bi bi-eye" id="eyeIcon"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember" 
                            {{ $rememberedChecked ?? false ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            <i class="bi bi-save"></i> Remember me
                        </label>
                    </div>
                    <button type="submit" class="btn btn-login w-100" id="login-submit-btn">
                        <i class="bi bi-box-arrow-in-right"></i> Login
                    </button>
                </form>
                
                <div class="text-center mt-3">
                    <p class="mb-0">Belum punya akun? 
                        <a href="{{ route('register') }}" class="text-decoration-none">Daftar disini</a>
                    </p>
                </div>
                
                <div class="text-center mt-4 d-flex justify-content-center gap-2">
                    <a href="{{ url('/') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-house-door"></i> Kembali ke Beranda
                    </a>
                    <button id="theme-toggle" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-moon-stars"></i> Ganti Tema
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('components.cookie-consent')
</body>
</html>