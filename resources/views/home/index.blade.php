<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PCB-KAL - Beranda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body data-login="{{ auth()->check() ? 'true' : 'false' }}">
    <div id="alert-placeholder"></div>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}"><i class="bi bi-cpu"></i> PCB-KAL</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link active" href="{{ url('/') }}">Beranda</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('cetak-pcb') }}">Cetak PCB</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#wishlistModal">
                            <i class="bi bi-cart-fill"></i> Wishlist 
                            <span id="wishlist-badge" class="badge bg-danger rounded-pill">0</span>
                        </button>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <button id="theme-toggle" class="btn btn-sm btn-outline-light">
                            <i class="bi bi-moon-stars"></i>
                        </button>
                    </li>
                    <li class="nav-item ms-lg-3">
                        @auth
                            <div class="dropdown">
                                <button class="btn btn-success btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle"></i> {{ auth()->user()->username }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    @if(auth()->user()->isAdmin())
                                    <li>
                                        <a class="dropdown-item text-warning" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-shield-lock"></i> Admin Panel
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    @endif
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="bi bi-box-arrow-right"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-success btn-sm px-4">Login</a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero text-center text-white py-5">
        <div class="container">
            <h1 class="display-4 fw-bold">PCB Manufaktur Sistem</h1>
            <p class="lead">Penyedia Main Board IoT Original dan Jasa Cetak PCB Presisi.</p>
        </div>
    </header>

    {{-- Dashboard Cards - Dinamis --}}
    <div class="container mt-5">
        <div class="row text-center">
            <div class="col-md-4 mb-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <h5>Total Produk</h5>
                        <h2>{{ $totalProducts ?? 0 }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <h5>Stok Tersedia</h5>
                        <h2>{{ $totalStock ?? 0 }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card dashboard-card">
                    <div class="card-body">
                        <h5>Kategori</h5>
                        <h2>{{ $totalCategories ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Products Section - Dinamis dari Database --}}
    <div class="container py-5">
        <h3 class="mb-5 text-center fw-bold">Daftar Produk</h3>
        <div class="row g-4">
            @forelse($products ?? [] as $product)
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 product-card">
                    <img src="{{ $product->image_url ?? asset('images/default.jpg') }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="fw-bold product-name">{{ $product->name }}</h5>
                        <p class="text-muted small">{{ Str::limit($product->description ?? '', 100) }}</p>
                        
                        @if($product->category)
                            <p class="text-muted small">
                                Kategori: <span class="badge bg-secondary">{{ $product->category->name }}</span>
                            </p>
                        @endif

                        @if(($product->stock ?? 0) > 0)
                            <p class="text-muted small">Stok: <span class="stock-count">{{ $product->stock }}</span></p>
                        @else
                            <p class="text-muted small text-danger">Stok Habis</p>
                        @endif

                        <div class="mt-auto">
                            <p class="fw-bold text-success">{{ $product->formatted_price ?? 'Rp 0' }}</p>
                            <div class="d-flex gap-2">
                                @if($product->name == 'Jasa Cetak PCB Custom')
                                    <a href="{{ route('cetak-pcb') }}" class="btn btn-success w-100">Custom Order</a>
                                @elseif(($product->stock ?? 0) > 0)
                                    <button class="btn btn-success w-100 btn-buy" data-product-id="{{ $product->id }}">Beli</button>
                                    <button class="btn btn-outline-danger w-100 btn-wishlist" data-product="{{ $product->name }}">♥ Wishlist</button>
                                @else
                                    <button class="btn btn-secondary w-100" disabled>Stok Habis</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center">
                <p class="text-muted">Belum ada produk.</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Wishlist Modal --}}
    <div class="modal fade" id="wishlistModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Daftar Wishlist</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ul id="wishlist-container" class="list-group list-group-flush"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="clear-wishlist">Hapus Semua</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-4">
        <p class="mb-0">&copy; 2026 PCB-KAL Management System.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('components.cookie-consent')
</body>
</html>