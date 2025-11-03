@include('admin.part.head')

<main class="p-6 md:p-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Pengaturan</h1>

        <div class="border-b border-gray-200 mb-6">
            <nav class="flex space-x-8" aria-label="Tabs">
                <button class="tab-button active whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-indigo-500 text-indigo-600" onclick="showTab('profile', event)">
                    Pengaturan Profil
                </button>
                <button class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" onclick="showTab('account', event)">
                    Pengaturan Akun
                </button>
                <button class="tab-button whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" onclick="showTab('bank', event)">
                    Pengaturan Rekening Bank
                </button>
            </nav>
        </div>

        <div id="profile" class="tab-content active">
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-semibold mb-4">Pengaturan Profil</h2>
                <form id="profileForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Nama Tampilan</label>
                        <input type="text" id="nama_tampilan" name="nama_tampilan" class="form-input mt-1" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" class="form-input mt-1" required>
                    </div>
                    <button type="submit" class="btn-primary px-4 py-2 rounded">Simpan Perubahan</button>
                </form>
            </div>
        </div>

        <div id="account" class="tab-content" style="display: none;">
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-semibold mb-4">Pengaturan Akun</h2>
                <form id="passwordForm">
                    <div class="mb-4 relative">
                        <label class="block text-sm font-medium text-gray-700">Password Lama</label>
                        <input type="password" id="old_password" name="old_pass" class="form-input mt-1 pr-10" required>
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center mt-1" onclick="togglePassword(this, 'old_password')">
                            <svg class="h-5 w-5 text-gray-400 mt-[1.5rem]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <div class="mb-4 relative">
                        <label class="block text-sm font-medium text-gray-700">Password Baru</label>
                        <input type="password" id="password" name="new_pass" class="form-input mt-1 pr-10" required>
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center mt-1" onclick="togglePassword(this, 'password')">
                            <svg class="h-5 w-5 text-gray-400 mt-[1.5rem]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <div class="mb-4 relative">
                        <label class="block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirmation" name="confirm_pass" class="form-input mt-1 pr-10" required>
                        <button type="button" class="absolute inset-y-0 right-0 pr-3 flex items-center mt-1" onclick="togglePassword(this, 'password_confirmation')">
                            <svg class="h-5 w-5 text-gray-400 mt-[1.5rem]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <button type="submit" class="btn-primary px-4 py-2 rounded">Ubah Password</button>
                </form>
            </div>
        </div>

        <div id="bank" class="tab-content" style="display: none;">
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-semibold mb-4">Pengaturan Rekening Bank</h2>
                <div id="bankAccounts" class="mb-6">
                </div>
                <button onclick="showAddBankForm()" class="btn-primary px-4 py-2 rounded mb-4">Tambah Rekening Bank</button>

                <div id="addBankForm" style="display: none;">
                    <form id="bankForm">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nama Bank</label>
                            <input type="text" id="nama_bank" name="nama_bank" class="form-input mt-1" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Nomor Rekening</label>
                            <input type="text" id="nomor_rekening" name="nomor_rekening" class="form-input mt-1" required>
                        </div>
                        <button type="submit" class="btn-primary px-4 py-2 rounded mr-2">Simpan</button>
                        <button type="button" onclick="hideAddBankForm()" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                    </form>
                </div>

                <div id="editBankModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                        <div class="mt-3">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Rekening Bank</h3>
                            <form id="editBankForm">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Nama Bank</label>
                                    <input type="text" id="edit_nama_bank" name="nama_bank" class="form-input mt-1" required>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700">Nomor Rekening</label>
                                    <input type="text" id="edit_nomor_rekening" name="nomor_rekening" class="form-input mt-1" required>
                                </div>
                                <input type="hidden" id="edit_bank_id" name="id">
                                <div class="flex justify-end space-x-2">
                                    <button type="button" onclick="hideEditBankModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                                    <button type="submit" class="btn-primary px-4 py-2 rounded">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Fungsi untuk menampilkan tab
        window.showTab = function(tabName, event) {
            document.querySelectorAll('.tab-content').forEach(content => {
                content.style.display = 'none';
                content.classList.remove('active');
            });
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('active', 'border-indigo-500', 'text-indigo-600');
                button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });
            document.getElementById(tabName).style.display = 'block';
            document.getElementById(tabName).classList.add('active');
            if (event && event.target) {
                event.target.classList.add('active', 'border-indigo-500', 'text-indigo-600');
                event.target.classList.remove('border-transparent', 'text-gray-500');
            }
        }

        // Fungsi untuk form tambah bank
        window.showAddBankForm = function() {
            document.getElementById('addBankForm').style.display = 'block';
        }
        window.hideAddBankForm = function() {
            document.getElementById('addBankForm').style.display = 'none';
            document.getElementById('bankForm').reset();
        }

        // Fungsi untuk modal edit bank
        window.hideEditBankModal = function() {
            document.getElementById('editBankModal').classList.add('hidden');
        }

        // Memuat data profil
        fetch('/api/admin/profile')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('nama_tampilan').value = data.data.nama_tampilan;
                    document.getElementById('email').value = data.data.email;
                }
            });

        // Submit form profil
        document.getElementById('profileForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('/api/admin/profile/update', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil', 'Profil berhasil diperbarui.', 'success');
                } else {
                    Swal.fire('Error', data.message || 'Gagal memperbarui profil.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
            });
        });

        // Submit form password
        document.getElementById('passwordForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('/api/admin/password/update', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil', 'Password berhasil diperbarui.', 'success');
                    this.reset();
                } else {
                    Swal.fire('Error', data.message || 'Gagal memperbarui password.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
            });
        });

        // Memuat data rekening bank
        window.loadBankAccounts = function() {
            fetch('/api/admin/bank-accounts')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('bankAccounts');
                    container.innerHTML = '';
                    if (data.success && data.data.length > 0) {
                        data.data.forEach(account => {
                            const isActive = account.aktif === 'ya';
                            const buttonText = isActive ? 'Nonaktifkan' : 'Aktifkan';
                            const buttonClass = isActive ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600';

                            container.innerHTML += `
                                <div class="border p-4 rounded mb-2 flex justify-between items-center">
                                    <div>
                                        <p class="font-bold">${account.nama_bank}</p>
                                        <p class="text-gray-600">${account.nomor_rekening}</p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button onclick="editBankAccount(${account.id_akun_bank})" class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">Edit</button>
                                        <button onclick="toggleBankAccount(${account.id_akun_bank})" class="text-white px-3 py-1 rounded text-sm ${buttonClass}">${buttonText}</button>
                                        <button onclick="deleteBankAccount(${account.id_akun_bank})" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Hapus</button>
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        container.innerHTML = '<p class="text-gray-500">Belum ada rekening bank yang ditambahkan.</p>';
                    }
                });
        }

        loadBankAccounts();

        // Submit form tambah bank
        document.getElementById('bankForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('/api/admin/bank-accounts/add', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    hideAddBankForm();
                    loadBankAccounts();
                    Swal.fire('Berhasil', 'Rekening bank berhasil ditambahkan.', 'success');
                } else {
                    Swal.fire('Error', data.message || 'Gagal menambahkan rekening bank.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
            });
        });

        // Fungsi untuk membuka modal edit
        window.editBankAccount = function(id) {
            fetch(`/api/admin/bank-accounts/${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('edit_nama_bank').value = data.data.nama_bank;
                        document.getElementById('edit_nomor_rekening').value = data.data.nomor_rekening;
                        document.getElementById('edit_bank_id').value = data.data.id_akun_bank;
                        document.getElementById('editBankModal').classList.remove('hidden');
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Gagal mengambil data untuk diedit!', 'error');
                });
        }

        // Submit form update bank
        document.getElementById('editBankForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const bankId = document.getElementById('edit_bank_id').value;
            const formData = new FormData(this);

            fetch(`/api/admin/bank-accounts/update/${bankId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    hideEditBankModal();
                    loadBankAccounts();
                    Swal.fire('Berhasil', 'Data bank berhasil diupdate.', 'success');
                } else {
                    Swal.fire('Error', data.message || 'Gagal mengupdate data.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
            });
        });

        window.deleteBankAccount = function(id) {
            Swal.fire({
                title: 'Anda yakin?',
                text: "Rekening bank ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/api/admin/bank-accounts/delete/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Terhapus!', 'Rekening bank berhasil dihapus.', 'success');
                            loadBankAccounts();
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Gagal menghapus data.', 'error');
                    });
                }
            });
        }

        // Fungsi untuk mengubah status (aktif/nonaktif)
        window.toggleBankAccount = function(id) {
            fetch(`/api/admin/bank/active/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire('Berhasil', data.message, 'success');
                    loadBankAccounts();
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                const errorMessage = error.message || 'Terjadi kesalahan pada server.';
                Swal.fire('Error', errorMessage, 'error');
            });
        }

        // Fungsi untuk toggle password visibility
        window.togglePassword = function(button, inputId) {
            const input = document.getElementById(inputId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            const icon = button.querySelector('svg');
            if (type === 'password') {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            } else {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />';
            }
        }
    });
    </script>

@include('admin.part.foot')
