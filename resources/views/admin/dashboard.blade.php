<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard - PCB-KAL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-shield-lock"></i> Admin Panel - PCB-KAL
            </a>
            <div class="ms-auto">
                <span class="text-white me-3">
                    <i class="bi bi-person-circle"></i> {{ auth()->user()->name }}
                    <span class="badge bg-danger">Admin</span>
                </span>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5><i class="bi bi-people"></i> Total Users</h5>
                        <h2>{{ \App\Models\User::count() }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5><i class="bi bi-box"></i> Products</h5>
                        <h2>{{ \App\Models\Product::count() }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5><i class="bi bi-cart"></i> Orders</h5>
                        <h2>0</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5><i class="bi bi-currency-dollar"></i> Revenue</h5>
                        <h2>Rp 0</h2>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-people"></i> Daftar User</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(\App\Models\User::latest()->take(5)->get() as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'admin' ? 'danger' : 'secondary' }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('admin.users') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-speedometer2"></i> Menu Admin</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            {{-- LINK KE HALAMAN KELOLA PRODUK --}}
                            <a href="{{ route('admin.products.index') }}" class="list-group-item list-group-item-action">
                                <i class="bi bi-box"></i> Kelola Produk
                            </a>
                            <a href="{{ route('admin.users') }}" class="list-group-item list-group-item-action">
                                <i class="bi bi-people"></i> Kelola Users
                            </a>
                            <a href="{{ route('admin.orders') }}" class="list-group-item list-group-item-action">
                                <i class="bi bi-cart"></i> Kelola Pesanan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="bg-dark text-white text-center py-3 mt-5">
        <p class="mb-0">&copy; 2026 PCB-KAL Admin Panel</p>
    </footer>
    
    @include('components.cookie-consent')
</body>
</html>