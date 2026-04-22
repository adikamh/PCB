document.addEventListener("DOMContentLoaded", () => {
    const themeBtn = document.getElementById("theme-toggle");
    const alertPlaceholder = document.getElementById("alert-placeholder");
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#password');
    const eyeIcon = document.querySelector('#eyeIcon');

    if (togglePassword && password && eyeIcon) {
        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            eyeIcon.classList.toggle('bi-eye');
            eyeIcon.classList.toggle('bi-eye-slash');
        });
    }

    const showAlert = (message, type = 'danger') => {
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
    };

    const syncFormTheme = () => {
        const isDark = document.body.classList.contains("dark-mode");
        document.querySelectorAll('.form-control, .form-select, .input-group-text, .modal-content, .list-group-item').forEach(el => {
            if (isDark) {
                el.style.backgroundColor = "#2b2b2b";
                el.style.color = "white";
                el.style.borderColor = "#444";
            } else {
                el.style.backgroundColor = "";
                el.style.color = "";
                el.style.borderColor = "";
            }
        });
    };

    if (themeBtn) {
        if (localStorage.getItem("theme") === "dark") {
            document.body.classList.add("dark-mode");
            themeBtn.innerHTML = '<i class="bi bi-sun"></i>';
            syncFormTheme();
        }

        themeBtn.addEventListener("click", () => {
            document.body.classList.toggle("dark-mode");
            const isDark = document.body.classList.contains("dark-mode");
            localStorage.setItem("theme", isDark ? "dark" : "light");
            themeBtn.innerHTML = isDark ? '<i class="bi bi-sun"></i>' : '<i class="bi bi-moon-stars"></i>';
            syncFormTheme();
        });
    }

    const checkAuth = (customMessage = "Silakan login terlebih dahulu untuk mengakses fitur ini.") => {
        const loggedIn = document.body.getAttribute("data-login") === "true";
        if (!loggedIn) {
            showAlert(customMessage, 'warning');
            return false;
        }
        return true;
    };

    window.checkLoginBeforeRedirect = (target) => {
        if (checkAuth("Anda harus login untuk melakukan pemesanan custom!")) {
            window.location.href = target;
        } else {
            setTimeout(() => {
                window.location.href = "/login";
            }, 2000);
        }
    };

    const badge = document.getElementById("wishlist-badge");
    const container = document.getElementById("wishlist-container");

    const updateWishlistUI = () => {
        const items = JSON.parse(sessionStorage.getItem("wishlist")) || [];
        if (badge) badge.innerText = items.length;
        if (container) {
            container.innerHTML = items.length ? "" : '<li class="list-group-item text-center text-muted">Wishlist masih kosong.</li>';
            items.forEach((item, idx) => {
                const li = document.createElement("li");
                li.className = "list-group-item d-flex justify-content-between align-items-center";
                if (typeof item === 'object' && item !== null) {
                    li.innerHTML = `
                        <div>
                            <strong>${item.project || 'PCB Custom'}</strong><br>
                            <small>${item.layer || '-'} | ${item.ukuran || '-'} | ${item.warna || '-'}</small>
                        </div>
                        <button class="btn btn-sm btn-outline-danger remove-item" data-index="${idx}">Hapus</button>
                    `;
                } else {
                    li.innerHTML = `<span>${item}</span><button class="btn btn-sm btn-outline-danger remove-item" data-index="${idx}">Hapus</button>`;
                }
                container.appendChild(li);
            });
        }
        syncFormTheme();
    };

    document.querySelectorAll(".btn-wishlist").forEach(btn => {
        btn.addEventListener("click", (e) => {
            if (!checkAuth("Login diperlukan untuk menambah wishlist.")) return;
            const cardBody = e.target.closest(".card-body");
            if (!cardBody) return;
            const nameEl = cardBody.querySelector(".product-name");
            if (!nameEl) return;
            const name = nameEl.innerText;
            const items = JSON.parse(sessionStorage.getItem("wishlist")) || [];
            const isDuplicate = items.some(item => typeof item === 'string' && item === name);
            
            if (isDuplicate) {
                showAlert(`"${name}" sudah ada di wishlist!`, 'info');
                return;
            }
            items.push(name);
            sessionStorage.setItem("wishlist", JSON.stringify(items));
            updateWishlistUI();
            showAlert(`"${name}" berhasil ditambahkan ke wishlist!`, 'success');
        });
    });

    const formCetak = document.getElementById("form-cetak");
    if (formCetak) {
        formCetak.addEventListener("submit", async (e) => {
            e.preventDefault();
            if (!checkAuth("Maaf, Anda harus login terlebih dahulu untuk mengirim form produksi!")) return;
            
            const projectName = document.getElementById("projectName")?.value || "Tanpa Nama";
            const layerCount = document.getElementById("layerCount")?.value || "-";
            const lebar = document.getElementById("lebar")?.value || "0";
            const panjang = document.getElementById("panjang")?.value || "0";
            const jumlah = document.getElementById("jumlah")?.value || "1";
            const warna = document.getElementById("warna")?.value || "-";
            const ketebalan = document.getElementById("ketebalan")?.value || "-";
            const catatan = document.getElementById("catatan")?.value || "";
            
            if (!projectName || lebar === "0" || panjang === "0") {
                showAlert("Harap isi Nama Project, Lebar, dan Panjang dengan benar!", 'danger');
                return;
            }
            
            const pcbItem = {
                project: projectName,
                layer: layerCount,
                ukuran: `${lebar} x ${panjang} mm (${jumlah} pcs)`,
                warna: warna,
                ketebalan: ketebalan,
                catatan: catatan,
                type: "pcb",
                createdAt: new Date().toISOString()
            };
            
            const items = JSON.parse(sessionStorage.getItem("wishlist")) || [];
            const isDuplicate = items.some(item => {
                return typeof item === 'object' && item !== null && item.type === 'pcb' && item.project === projectName;
            });
            
            if (isDuplicate) {
                showAlert(`Project "${projectName}" sudah ada di wishlist!`, 'info');
                return;
            }
            
            items.push(pcbItem);
            sessionStorage.setItem("wishlist", JSON.stringify(items));
            updateWishlistUI();
            
            try {
                const response = await fetch('/api/pcb-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify(pcbItem)
                });
                if (response.ok) {
                    console.log('Order saved to database');
                }
            } catch (error) {
                console.log('Order saved to session storage only');
            }
            
            showAlert(`Project "${projectName}" berhasil ditambahkan ke wishlist!`, 'success');
            formCetak.reset();
        });
    }

    const clearBtn = document.getElementById("clear-wishlist");
    if (clearBtn) {
        clearBtn.onclick = () => {
            if (confirm("Hapus semua item dari wishlist?")) {
                sessionStorage.removeItem("wishlist");
                updateWishlistUI();
                showAlert("Wishlist telah dikosongkan.", 'info');
            }
        };
    }

    window.removeItem = (idx) => {
        const items = JSON.parse(sessionStorage.getItem("wishlist")) || [];
        items.splice(idx, 1);
        sessionStorage.setItem("wishlist", JSON.stringify(items));
        updateWishlistUI();
    };

    if (container) {
        container.addEventListener("click", (e) => {
            const removeBtn = e.target.closest(".remove-item");
            if (removeBtn) {
                const index = removeBtn.getAttribute("data-index");
                if (index !== null) window.removeItem(parseInt(index));
            }
        });
    }

    document.querySelectorAll(".btn-buy").forEach(btn => {
        btn.addEventListener("click", (e) => {
            if (!checkAuth("Login diperlukan untuk membeli produk.")) return;
            const cardBody = e.target.closest(".card-body");
            if (!cardBody) return;
            const stockEl = cardBody.querySelector(".stock-count");
            if (!stockEl) return;
            
            let stock = parseInt(stockEl.innerText);
            if (stock > 0) {
                stockEl.innerText = --stock;
                showAlert("Pesanan Diterima! Silakan cek menu transaksi.", 'success');
            } else {
                showAlert("Maaf, stok produk ini telah habis!", 'danger');
            }
        });
    });

    const loginForm = document.querySelector('form[action$="/login"]');
    if (loginForm && !loginForm.hasAttribute('data-ajax-handled')) {
        loginForm.setAttribute('data-ajax-handled', 'true');
        
        loginForm.addEventListener("submit", async (e) => {
            e.preventDefault();
            
            const submitBtn = loginForm.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn?.innerHTML || 'Login';
            
            if (submitBtn) {
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses...';
                submitBtn.disabled = true;
            }
            
            const formData = new FormData(loginForm);
            
            try {
                const response = await fetch(loginForm.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: formData
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    showAlert('Login berhasil! Mengalihkan...', 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect || '/';
                    }, 1000);
                } else {
                    showAlert(data.message || 'Username atau password salah!', 'danger');
                    if (submitBtn) {
                        submitBtn.innerHTML = originalBtnText;
                        submitBtn.disabled = false;
                    }
                }
            } catch (error) {
                loginForm.submit();
            }
        });
    }

    const resetFormBtn = document.getElementById("resetForm");
    if (resetFormBtn) {
        resetFormBtn.addEventListener("click", () => {
            if (confirm("Apakah Anda yakin ingin membersihkan semua field form?")) {
                const form = document.getElementById("form-cetak");
                if (form) form.reset();
                showAlert("Form telah direset.", 'info');
            }
        });
    }

    updateWishlistUI();

    if (!document.querySelector('meta[name="csrf-token"]')) {
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = document.querySelector('input[name="_token"]')?.value || '';
        document.head.appendChild(meta);
    }
});