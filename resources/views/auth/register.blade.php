<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - PCB-KAL</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .password-strength {
            height: 5px;
            margin-top: 8px;
            border-radius: 3px;
            transition: all 0.3s ease;
        }
        .strength-weak { width: 33%; background-color: #dc3545; }
        .strength-medium { width: 66%; background-color: #ffc107; }
        .strength-strong { width: 100%; background-color: #198754; }
        .password-requirement {
            font-size: 12px;
            margin-top: 5px;
        }
        .requirement-met {
            color: #198754;
        }
        .requirement-unmet {
            color: #dc3545;
        }
        .requirement-met i, .requirement-unmet i {
            font-size: 10px;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div id="alert-placeholder"></div>

    <div class="login-wrapper">
        <div class="login-card card">
            <div class="card-body p-4">
                <div class="login-header">
                    <h2>Daftar Akun</h2>
                    <p class="text-muted">Buat akun baru untuk mulai berbelanja</p>
                </div>

                {{-- Error Alert --}}
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form id="register-form" action="{{ route('register') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" name="name" class="form-control" 
                                value="{{ old('name') }}" required autofocus>
                        </div>
                        <div class="invalid-feedback name-error"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-at"></i></span>
                            <input type="text" name="username" class="form-control" 
                                value="{{ old('username') }}" required>
                        </div>
                        <small class="text-muted">Minimal 3 karakter, tanpa spasi</small>
                        <div class="invalid-feedback username-error"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control" 
                                value="{{ old('email') }}" required>
                        </div>
                        <small class="text-muted">Email harus valid dan belum terdaftar</small>
                        <div class="invalid-feedback email-error"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" required>
                            <span class="input-group-text toggle-password" style="cursor: pointer;">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                        
                        {{-- Password Strength Indicator --}}
                        <div class="password-strength" id="password-strength"></div>
                        
                        {{-- Password Requirements --}}
                        <div class="password-requirement">
                            <div id="req-length" class="requirement-unmet">
                                <i class="bi bi-x-circle"></i> Minimal 8 karakter
                            </div>
                            <div id="req-uppercase" class="requirement-unmet">
                                <i class="bi bi-x-circle"></i> Mengandung huruf BESAR (A-Z)
                            </div>
                            <div id="req-lowercase" class="requirement-unmet">
                                <i class="bi bi-x-circle"></i> Mengandung huruf kecil (a-z)
                            </div>
                        </div>
                        
                        <div class="invalid-feedback password-error"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                            <span class="input-group-text toggle-password-confirm" style="cursor: pointer;">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
                        <div id="password-match-hint" class="small mt-1"></div>
                        <div class="invalid-feedback password_confirmation-error"></div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="terms" class="form-check-input" id="terms" required>
                        <label class="form-check-label" for="terms">
                            Saya menyetujui <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal">Syarat & Ketentuan</a>
                        </label>
                        <div class="invalid-feedback terms-error"></div>
                    </div>

                    <button type="submit" class="btn btn-login w-100" id="submit-btn">Daftar</button>
                </form>

                <div class="text-center mt-4">
                    <p class="mb-0">Sudah punya akun? 
                        <a href="{{ route('login') }}" class="text-decoration-none">Login disini</a>
                    </p>
                    <hr class="my-3">
                    <a href="{{ url('/') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-house-door"></i> Kembali ke Beranda
                    </a>
                    <button id="theme-toggle" class="btn btn-sm btn-outline-secondary ms-2">
                        <i class="bi bi-moon-stars"></i> Ganti Tema
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Terms Modal --}}
    <div class="modal fade" id="termsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Syarat & Ketentuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <h6>1. Ketentuan Umum</h6>
                    <p>Dengan mendaftar, Anda menyetujui untuk menggunakan layanan kami dengan bijak.</p>
                    <h6>2. Privasi</h6>
                    <p>Data pribadi Anda akan kami jaga kerahasiaannya.</p>
                    <h6>3. Keamanan Password</h6>
                    <p>Password harus terdiri dari minimal 8 karakter dengan kombinasi huruf besar dan kecil.</p>
                    <h6>4. Pembelian</h6>
                    <p>Setiap pembelian produk mengikuti ketentuan yang berlaku.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Saya Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    {{-- Script untuk register dengan validasi password --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const passwordInput = document.querySelector('#password');
            const passwordConfirmInput = document.querySelector('#password_confirmation');
            const submitBtn = document.querySelector('#submit-btn');
            
            // Toggle Password
            const togglePassword = document.querySelector('.toggle-password');
            const togglePasswordConfirm = document.querySelector('.toggle-password-confirm');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.type === 'password' ? 'text' : 'password';
                    passwordInput.type = type;
                    this.querySelector('i').classList.toggle('bi-eye');
                    this.querySelector('i').classList.toggle('bi-eye-slash');
                });
            }

            if (togglePasswordConfirm && passwordConfirmInput) {
                togglePasswordConfirm.addEventListener('click', function() {
                    const type = passwordConfirmInput.type === 'password' ? 'text' : 'password';
                    passwordConfirmInput.type = type;
                    this.querySelector('i').classList.toggle('bi-eye');
                    this.querySelector('i').classList.toggle('bi-eye-slash');
                });
            }

            // Function to check password requirements
            function checkPasswordRequirements(password) {
                const requirements = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password)
                };
                
                // Update UI
                const reqLength = document.getElementById('req-length');
                const reqUppercase = document.getElementById('req-uppercase');
                const reqLowercase = document.getElementById('req-lowercase');
                
                if (reqLength) {
                    reqLength.className = requirements.length ? 'requirement-met' : 'requirement-unmet';
                    reqLength.innerHTML = requirements.length ? 
                        '<i class="bi bi-check-circle"></i> Minimal 8 karakter (✓)' : 
                        '<i class="bi bi-x-circle"></i> Minimal 8 karakter';
                }
                
                if (reqUppercase) {
                    reqUppercase.className = requirements.uppercase ? 'requirement-met' : 'requirement-unmet';
                    reqUppercase.innerHTML = requirements.uppercase ? 
                        '<i class="bi bi-check-circle"></i> Mengandung huruf BESAR (A-Z) (✓)' : 
                        '<i class="bi bi-x-circle"></i> Mengandung huruf BESAR (A-Z)';
                }
                
                if (reqLowercase) {
                    reqLowercase.className = requirements.lowercase ? 'requirement-met' : 'requirement-unmet';
                    reqLowercase.innerHTML = requirements.lowercase ? 
                        '<i class="bi bi-check-circle"></i> Mengandung huruf kecil (a-z) (✓)' : 
                        '<i class="bi bi-x-circle"></i> Mengandung huruf kecil (a-z)';
                }
                
                // Update strength bar
                const strengthBar = document.getElementById('password-strength');
                if (strengthBar) {
                    const metCount = Object.values(requirements).filter(Boolean).length;
                    strengthBar.className = 'password-strength';
                    if (metCount === 1) strengthBar.classList.add('strength-weak');
                    else if (metCount === 2) strengthBar.classList.add('strength-medium');
                    else if (metCount === 3) strengthBar.classList.add('strength-strong');
                    else strengthBar.style.width = '0';
                }
                
                return requirements.length && requirements.uppercase && requirements.lowercase;
            }
            
            // Check password match
            function checkPasswordMatch() {
                const password = passwordInput?.value || '';
                const confirm = passwordConfirmInput?.value || '';
                const matchHint = document.getElementById('password-match-hint');
                
                if (confirm.length > 0) {
                    if (password === confirm) {
                        matchHint.innerHTML = '<span class="text-success"><i class="bi bi-check-circle"></i> Password cocok</span>';
                        matchHint.style.color = '#198754';
                        return true;
                    } else {
                        matchHint.innerHTML = '<span class="text-danger"><i class="bi bi-x-circle"></i> Password tidak cocok</span>';
                        matchHint.style.color = '#dc3545';
                        return false;
                    }
                } else {
                    matchHint.innerHTML = '';
                    return false;
                }
            }
            
            // Real-time password validation
            if (passwordInput) {
                passwordInput.addEventListener('input', function() {
                    const isValid = checkPasswordRequirements(this.value);
                    if (passwordConfirmInput?.value) {
                        checkPasswordMatch();
                    }
                });
            }
            
            if (passwordConfirmInput) {
                passwordConfirmInput.addEventListener('input', function() {
                    checkPasswordMatch();
                });
            }

            // Form Submit dengan AJAX
            const form = document.getElementById('register-form');
            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    // Validate password before submit
                    const password = passwordInput?.value || '';
                    const isPasswordValid = checkPasswordRequirements(password);
                    const isMatch = checkPasswordMatch();
                    
                    if (!isPasswordValid) {
                        showCustomAlert('Password harus minimal 8 karakter dengan kombinasi huruf besar dan kecil!', 'warning');
                        return;
                    }
                    
                    if (passwordConfirmInput?.value && !isMatch) {
                        showCustomAlert('Konfirmasi password tidak cocok!', 'warning');
                        return;
                    }
                    
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Memproses...';
                    submitBtn.disabled = true;
                    
                    // Hapus error sebelumnya
                    document.querySelectorAll('.is-invalid').forEach(el => {
                        el.classList.remove('is-invalid');
                    });
                    document.querySelectorAll('.invalid-feedback').forEach(el => {
                        el.style.display = 'none';
                        el.innerHTML = '';
                    });
                    
                    const formData = new FormData(this);
                    
                    try {
                        const response = await fetch(this.action, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if (response.ok && data.success) {
                            showCustomAlert(data.message, 'success');
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1500);
                        } else if (data.errors) {
                            for (const [field, messages] of Object.entries(data.errors)) {
                                const input = document.querySelector(`[name="${field}"]`);
                                const errorDiv = document.querySelector(`.${field}-error`);
                                if (input) {
                                    input.classList.add('is-invalid');
                                }
                                if (errorDiv) {
                                    errorDiv.style.display = 'block';
                                    errorDiv.innerHTML = messages[0];
                                }
                            }
                            submitBtn.innerHTML = originalText;
                            submitBtn.disabled = false;
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showCustomAlert('Terjadi kesalahan, silakan coba lagi', 'danger');
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                    }
                });
            }
            
            // Function show custom alert
            function showCustomAlert(message, type) {
                const alertPlaceholder = document.getElementById('alert-placeholder');
                if (!alertPlaceholder) return;
                
                const titles = {
                    danger: 'Gagal!',
                    success: 'Berhasil!',
                    warning: 'Peringatan',
                    info: 'Informasi'
                };
                
                const icons = {
                    danger: 'bi-x-lg',
                    success: 'bi-check-lg',
                    warning: 'bi-exclamation-lg',
                    info: 'bi-info-lg'
                };
                
                const wrapper = document.createElement('div');
                wrapper.className = 'custom-alert-card';
                wrapper.innerHTML = `
                    <div class="alert-icon-circle icon-${type}">
                        <i class="bi ${icons[type]}"></i>
                    </div>
                    <div class="alert-title title-${type}">${titles[type]}</div>
                    <div class="alert-message">${message}</div>
                    <button class="btn-alert-ok icon-${type}" id="close-alert-btn">OK</button>
                `;
                
                alertPlaceholder.style.display = 'flex';
                alertPlaceholder.innerHTML = '';
                alertPlaceholder.appendChild(wrapper);
                
                const closeBtn = wrapper.querySelector('#close-alert-btn');
                closeBtn.onclick = () => {
                    alertPlaceholder.style.display = 'none';
                    alertPlaceholder.innerHTML = '';
                };
            }
        });
    </script>
    @include('components.cookie-consent')
</body>
</html>