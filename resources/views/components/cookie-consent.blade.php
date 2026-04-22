{{-- resources/views/components/cookie-consent.blade.php --}}
@php
    // Double check untuk memastikan variabel ada
    $showConsent = !($cookieConsentGiven ?? false);
@endphp

@if($showConsent)
<div id="cookie-consent-banner" class="cookie-consent-banner" style="position: fixed; bottom: 0; left: 0; right: 0; background: rgba(0, 0, 0, 0.95); backdrop-filter: blur(10px); z-index: 99999; padding: 1rem; border-top: 1px solid rgba(255, 255, 255, 0.1);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="d-flex align-items-center gap-3">
                    <div class="cookie-icon" style="font-size: 2rem; color: #ffc107;">
                        <i class="bi bi-cookie"></i>
                    </div>
                    <div class="cookie-text">
                        <h5 style="margin: 0 0 0.5rem 0; color: white;">
                            <i class="bi bi-shield-check"></i> Kami Menggunakan Cookie
                        </h5>
                        <p style="margin: 0; color: #ccc; font-size: 0.9rem;">
                            Kami menggunakan cookie untuk meningkatkan pengalaman Anda. 
                            Dengan mengklik "Terima Semua", Anda menyetujui penggunaan cookie kami.
                            <a href="#" data-bs-toggle="modal" data-bs-target="#cookiePolicyModal" style="color: #ffc107;">Pelajari lebih lanjut</a>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <button id="accept-cookies" class="btn btn-primary btn-sm me-2">
                    <i class="bi bi-check-lg"></i> Terima Semua
                </button>
                <button id="reject-cookies" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x-lg"></i> Tolak
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Cookie Policy Modal --}}
<div class="modal fade" id="cookiePolicyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-file-text"></i> Kebijakan Cookie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <h6>Apa itu Cookie?</h6>
                <p>Cookie adalah file kecil yang disimpan di perangkat Anda untuk meningkatkan pengalaman browsing.</p>
                
                <h6>Cookie yang Kami Gunakan</h6>
                <ul>
                    <li><strong>Cookie Penting</strong> - Diperlukan untuk fungsi dasar website (login, session)</li>
                    <li><strong>Cookie Preferensi</strong> - Menyimpan pengaturan seperti tema dark/light</li>
                    <li><strong>Cookie Remember Me</strong> - Untuk fitur "Remember Me" saat login</li>
                </ul>
                
                <h6>Durasi Penyimpanan</h6>
                <p>Cookie session: sampai browser ditutup<br>Cookie persisten: 30 hari - 1 tahun</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Mengerti</button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes slideUp {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.cookie-consent-banner {
    animation: slideUp 0.5s ease;
}

body.dark-mode .cookie-consent-banner {
    background: rgba(0, 0, 0, 0.98) !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const acceptBtn = document.getElementById('accept-cookies');
    const rejectBtn = document.getElementById('reject-cookies');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    
    if (acceptBtn) {
        acceptBtn.addEventListener('click', async function() {
            try {
                const response = await fetch('{{ route("cookie.accept") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('cookie-consent-banner').style.display = 'none';
                    // Tampilkan alert sukses jika fungsi showAlert tersedia
                    if (typeof showAlert === 'function') {
                        showAlert(data.message, 'success');
                    } else {
                        alert(data.message);
                    }
                    setTimeout(() => location.reload(), 1500);
                }
            } catch (error) {
                console.error('Error:', error);
                // Fallback: reload page
                location.reload();
            }
        });
    }
    
    if (rejectBtn) {
        rejectBtn.addEventListener('click', async function() {
            try {
                const response = await fetch('{{ route("cookie.reject") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('cookie-consent-banner').style.display = 'none';
                    if (typeof showAlert === 'function') {
                        showAlert(data.message, 'info');
                    } else {
                        alert(data.message);
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                document.getElementById('cookie-consent-banner').style.display = 'none';
            }
        });
    }
});
</script>
@endif