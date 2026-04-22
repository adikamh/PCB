<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>PCB-KAL - Custom PCB</title>
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
                    <li class="nav-item"><a class="nav-link" href="{{ url('/') }}">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('cetak-pcb') }}">Cetak PCB</a></li>
                    
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

    {{-- Form Content --}}
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card shadow border-0 p-4">
                    <h2 class="text-success fw-bold text-center mb-2"><i class="bi bi-tools"></i> Form Kustom Manufaktur PCB</h2>
                    <p class="text-muted text-center mb-4">Isi spesifikasi teknis desain PCB Anda untuk kalkulasi biaya.</p>
                    
                    <form id="form-cetak">
                        @csrf
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label fw-bold mb-0">Nama Project / File Gerber</label>
                                <button type="button" id="resetForm" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-eraser-fill me-1"></i>Bersihkan Form
                                </button>
                            </div>
                            <input type="text" class="form-control" id="projectName" placeholder="Contoh: Power_Supply_V1" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jumlah Layer</label>
                            <select class="form-select" id="layerCount">
                                <option>Single Layer (1-Layer)</option>
                                <option selected>Double Layer (2-Layer)</option>
                                <option>Multi Layer (4-Layer)</option>
                            </select>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Lebar (mm)</label>
                                <input type="number" class="form-control" id="lebar" min="10" placeholder="Min. 10" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Panjang (mm)</label>
                                <input type="number" class="form-control" id="panjang" min="10" placeholder="Min. 10" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Jumlah (Pcs)</label>
                                <input type="number" class="form-control" id="jumlah" min="1" value="5" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Warna Solder Mask</label>
                                <select class="form-select" id="warna">
                                    <option value="Hijau">Hijau (Standard)</option>
                                    <option value="Biru">Biru</option>
                                    <option value="Merah">Merah</option>
                                    <option value="Hitam">Hitam</option>
                                    <option value="Ungu">Ungu</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ketebalan Papan (mm)</label>
                                <select class="form-select" id="ketebalan">
                                    <option>0.8 mm</option>
                                    <option selected>1.6 mm</option>
                                    <option>2.0 mm</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Catatan Produksi</label>
                            <textarea class="form-control" id="catatan" rows="2" placeholder="Catatan khusus untuk teknisi..."></textarea>
                        </div>

                        <div class="alert alert-info py-2 small">
                            <i class="bi bi-info-circle-fill"></i> Harga akan dikalkulasi otomatis setelah form disubmit.
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-success btn-lg fw-bold">Kirim Form Produksi</button>
                            <a href="{{ url('/') }}" class="btn btn-outline-secondary">Kembali ke Dashboard</a>
                        </div>
                    </form>
                </div>
            </div>
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
        <p class="mb-0 small">&copy; 2026 PCB-KAL - Manufaktur Presisi Tinggi</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('components.cookie-consent')
</body>
</html>