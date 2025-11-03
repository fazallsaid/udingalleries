@include('partials.head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <main class="max-w-[1200px] mx-auto px-6 py-12">
        <h1 class="text-4xl font-bold mb-8">Akun Saya</h1>

        <div class="tabs">
            <button class="tab-button active" onclick="openTab('dashboard')">Dashboard</button>
            <button class="tab-button" onclick="openTab('pembelian')">Pembelian</button>
            <button class="tab-button" onclick="openTab('profil')">Profil</button>
            <button class="tab-button" onclick="openTab('akun')">Akun</button>
        </div>

        <div id="dashboard" class="tab-content active">
            <div class="bg-white p-8 rounded-lg shadow-sm">
                <h2 class="text-2xl font-semibold mb-6">Dashboard</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium">Total Pembelian</h3>
                        <p class="text-3xl font-bold text-primary" id="totalPurchases">Loading...</p>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-lg">
                        <h3 class="text-lg font-medium">Total Barang Dibeli</h3>
                        <p class="text-3xl font-bold text-primary" id="totalItems">Loading...</p>
                    </div>
                </div>
                <h3 class="text-xl font-semibold mt-8 mb-4">Pembelian Terbaru</h3>
                <div class="space-y-4" id="recentTransactions">
                    <p>Loading...</p>
                </div>
            </div>
        </div>

        <div id="pembelian" class="tab-content">
            <div class="bg-white p-8 rounded-lg shadow-sm">
                <h2 class="text-2xl font-semibold mb-6">Daftar Pembelian</h2>
                <div class="space-y-4" id="allTransactions">
                    <p>Loading...</p>
                </div>
            </div>
        </div>

        <div id="profil" class="tab-content">
            <div class="bg-white p-8 rounded-lg shadow-sm">
                <h2 class="text-2xl font-semibold mb-6">Pengaturan Profil</h2>
                <form onsubmit="submitProfile(event)">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Tampilan</label>
                            <input type="text" name="nama_tampilan" id="nama_tampilan" class="form-input" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" class="form-input" required>
                        </div>
                        <button type="submit" class="btn-primary px-6 py-2 rounded-lg">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="akun" class="tab-content">
            <div class="bg-white p-8 rounded-lg shadow-sm">
                <h2 class="text-2xl font-semibold mb-6">Pengaturan Akun</h2>
                <form onsubmit="submitPassword(event)">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password Lama</label>
                            <div class="relative">
                                <input type="password" name="old_password" id="old_password" class="form-input pr-10" required>
                                <button type="button" onclick="togglePassword('old_password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-eye text-gray-400 hover:text-gray-600" id="old_password_icon"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password Baru</label>
                            <div class="relative">
                                <input type="password" name="password" id="new_password" class="form-input pr-10" required>
                                <button type="button" onclick="togglePassword('new_password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-eye text-gray-400 hover:text-gray-600" id="new_password_icon"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="confirm_password" class="form-input pr-10" required>
                                <button type="button" onclick="togglePassword('confirm_password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-eye text-gray-400 hover:text-gray-600" id="confirm_password_icon"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn-primary px-6 py-2 rounded-lg">Ubah Password</button>
                    </div>
                </form>
            </div>
        </div>

        <div id="uploadModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full mx-4">
                <h3 class="text-xl font-semibold mb-4">Upload Bukti Pembayaran</h3>
                <form id="uploadForm" enctype="multipart/form-data">
                    <input type="hidden" name="kode_transaksi" id="transactionCode">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Gambar Bukti Pembayaran</label>
                        <input type="file" name="bukti_pembayaran" id="buktiInput" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" required>
                    </div>
                    <div id="preview" class="mb-4 hidden">
                        <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                        <img id="previewImg" class="max-w-full max-h-64 w-auto h-auto rounded border object-contain">
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 transition">Batal</button>
                        <button type="submit" class="btn-primary px-4 py-2 rounded">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
    <script>
        let userData = null;

        document.addEventListener('DOMContentLoaded', function() {
            loadUserData();
            loadUserProfile();
        });

        function loadUserData() {
            fetch('/api/user/data', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        userData = data.data;
                        updateDashboard();
                        updateTransactions();
                    } else {
                        console.error('Failed to load user data:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error loading user data:', error);
                });
        }

        function updateDashboard() {
            if(!userData) return;

            document.getElementById('totalPurchases').textContent = userData.totalPurchases;
            document.getElementById('totalItems').textContent = userData.totalItems;

            const recentTransactionsDiv = document.getElementById('recentTransactions');
            if(userData.transactions.length > 0) {
                const recentTransactions = userData.transactions.slice(0, 5);
                recentTransactionsDiv.innerHTML = recentTransactions.map(transaction => `
                    <div class="border p-4 rounded-lg">
                        <p><strong>Kode Transaksi: ${transaction.kode_transaksi}</strong></p>
                        <p>Total: Rp ${new Intl.NumberFormat('id-ID').format(transaction.total_transaksi)}</p>
                        <p>Tanggal: ${new Date(transaction.tanggal_dan_waktu_pembelian).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</p>
                        <p>Status: ${transaction.status_pembayaran}</p>
                        <a href="https://wa.me/6281260072530" target="_blank" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors mt-2"><i class="fab fa-whatsapp mr-2"></i>Hubungi Penjual</a>
                        ${transaction.status_pembayaran == 'Belum Bayar' ? `<button onclick="openUploadModal('${transaction.kode_transaksi}')" class="btn-primary px-4 py-2 rounded mt-2">Upload Bukti Pembayaran</button>` : ''}
                    </div>
                `).join('');
            } else {
                recentTransactionsDiv.innerHTML = '<p>Belum ada pembelian.</p>';
            }
        }

        function updateTransactions() {
            if(!userData) return;

            const allTransactionsDiv = document.getElementById('allTransactions');
            if(userData.transactions.length > 0) {
                allTransactionsDiv.innerHTML = userData.transactions.map(transaction => `
                    <div class="border p-4 rounded-lg">
                        <h4 class="font-bold text-lg mb-2">Kode Transaksi: ${transaction.kode_transaksi}</h4>
                        <p class="text-sm text-gray-600 mb-2">Tanggal: ${new Date(transaction.tanggal_dan_waktu_pembelian).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</p>
                        <p class="text-sm text-gray-600 mb-2">Status Pembayaran: ${transaction.status_pembayaran}</p>
                        <p class="text-sm text-gray-600 mb-2">Status Pengiriman: ${transaction.status_pengiriman}</p>
                        <a href="https://wa.me/6281260072530" target="_blank" class="inline-block bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-600 transition-colors mt-2"><i class="fab fa-whatsapp mr-2"></i>Hubungi Penjual</a>
                        <div class="mb-3">
                            <h5 class="font-semibold mb-1">Detail Produk:</h5>
                            ${transaction.items.map(item => `
                                <div class="ml-4 text-sm border-l-2 border-gray-200 pl-2 mb-1">
                                    <p><strong>${item.nama_produk}</strong> (${item.kode_produk})</p>
                                    <p>Jumlah: ${item.jumlah} | Harga: Rp ${new Intl.NumberFormat('id-ID').format(item.harga_produk)} | Total: Rp ${new Intl.NumberFormat('id-ID').format(item.total)}</p>
                                </div>
                            `).join('')}
                        </div>
                        <p class="font-semibold text-right">Total Transaksi: Rp ${new Intl.NumberFormat('id-ID').format(transaction.total_transaksi)}</p>
                        ${transaction.status_pembayaran == 'Belum Bayar' ? `<button onclick="openUploadModal('${transaction.kode_transaksi}')" class="btn-primary px-4 py-2 rounded mt-2">Upload Bukti Pembayaran</button>` : ''}
                    </div>
                `).join('');
            } else {
                allTransactionsDiv.innerHTML = '<p>Belum ada pembelian.</p>';
            }
        }

        function loadUserProfile() {
            fetch('/api/user/profile', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    document.getElementById('nama_tampilan').value = data.data.nama_tampilan;
                    document.getElementById('email').value = data.data.email;
                } else {
                    console.error('Failed to load user profile:', data.message);
                }
            })
            .catch(error => {
                console.error('Error loading user profile:', error);
            });
        }

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + '_icon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function openTab(tabName) {
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.classList.remove('active'));
            const buttons = document.querySelectorAll('.tab-button');
            buttons.forEach(button => button.classList.remove('active'));
            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }

        function submitProfile(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Sedang memproses...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData(event.target);
            const data = {
                nama_tampilan: formData.get('nama_tampilan'),
                email: formData.get('email')
            };

            fetch('/api/user/profile/update', {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();

                if(data.success){
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        icon: 'success',
                        title: data.message
                    }).then(() => {
                        loadUserProfile();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: data.message || 'Terjadi kesalahan saat memperbarui profil.'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Terjadi kesalahan saat memperbarui profil.'
                });
            });
        }

        function submitPassword(event) {
            event.preventDefault();

            Swal.fire({
                title: 'Sedang memproses...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData(event.target);
            const data = {
                old_pass: formData.get('old_password'),
                new_pass: formData.get('password'),
                confirm_pass: formData.get('password_confirmation')
            };

            fetch('/api/user/password/update', {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();

                if(data.success){
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        icon: 'success',
                        title: data.message
                    }).then(() => {
                        event.target.reset();
                        ['old_password', 'new_password', 'confirm_password'].forEach(id => {
                            const input = document.getElementById(id);
                            const icon = document.getElementById(id + '_icon');
                            input.type = 'password';
                            icon.classList.remove('fa-eye-slash');
                            icon.classList.add('fa-eye');
                        });
                    });
                } else {
                    let errorMessage = data.message || 'Terjadi kesalahan saat memperbarui password.';

                    if (errorMessage.includes('validation.min.string')) {
                        errorMessage = 'Password harus 6 karakter';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMessage
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Terjadi kesalahan saat memperbarui password.'
                });
            });
        }

        let currentTransactionCode = null;

        function openUploadModal(transactionCode) {
            currentTransactionCode = transactionCode;
            document.getElementById('transactionCode').value = transactionCode;
            document.getElementById('uploadModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('uploadModal').classList.add('hidden');
            document.getElementById('uploadForm').reset();
            document.getElementById('preview').classList.add('hidden');
        }

        document.getElementById('buktiInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('preview').classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                alert('Harap pilih file gambar yang valid.');
                this.value = '';
            }
        });

        document.getElementById('uploadForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            Swal.fire({
                title: 'Sedang memproses...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                showConfirmButton: false,
                willOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('/api/customer/pay/add', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.close();

                if(data.success){
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message || 'Bukti pembayaran berhasil diupload dan akan diverifikasi oleh admin.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        closeModal();
                        location.reload();
                    });
                } else {
                    if(data.message && data.message.includes('tidak terdeteksi sebagai bukti pembayaran')) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Upload Gagal',
                            text: 'Gambar yang Anda upload bukan bukti pembayaran, silakan coba lagi dengan bukti pembayaran yang sah.',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Upload Gagal',
                            text: data.message || 'Terjadi kesalahan saat upload bukti pembayaran.',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.close();
                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: 'Terjadi kesalahan saat upload. Silakan coba lagi.',
                    confirmButtonText: 'OK'
                });
            });
        });
    </script>
@include('partials.foot')
