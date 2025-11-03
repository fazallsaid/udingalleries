@include('admin.part.head')
            <!-- Content -->
            <main class="p-6 md:p-8">
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <div class="flex flex-wrap justify-between items-center gap-4 mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Daftar Transaksi</h3>
                        <div class="flex flex-wrap items-center gap-2">
                            <button id="exportPdfButton" class="bg-red-500 text-white flex items-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium hover:bg-red-600 transition-colors">
                                <i class="fas fa-file-pdf"></i>
                                <span>Ekspor PDF</span>
                            </button>
                            <button id="exportExcelButton" class="bg-green-500 text-white flex items-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium hover:bg-green-600 transition-colors">
                                <i class="fas fa-file-excel"></i>
                                <span>Ekspor Excel</span>
                            </button>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-4">
                        <input type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] block w-full p-2.5" placeholder="Tanggal Mulai">
                        <input type="date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] block w-full p-2.5" placeholder="Tanggal Akhir">
                        <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] block w-full p-2.5">
                            <option selected>Semua Status Pembayaran</option>
                            <option value="unpaid">Belum Bayar</option>
                            <option value="pending">Menunggu Verifikasi</option>
                            <option value="paid">Lunas</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                        <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] block w-full p-2.5">
                            <option selected>Semua Status Pengiriman</option>
                            <option value="belum_dikirim">Belum Dikirim</option>
                            <option value="sedang_dikirim">Sedang Dikirim</option>
                            <option value="diterima">Diterima</option>
                        </select>
                        <button class="btn-primary rounded-lg text-sm">Filter</button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">ID Transaksi</th>
                                    <th scope="col" class="px-6 py-3">Pelanggan</th>
                                    <th scope="col" class="px-6 py-3">Tanggal</th>
                                    <th scope="col" class="px-6 py-3">Total</th>
                                    <th scope="col" class="px-6 py-3">Status Pembayaran</th>
                                    <th scope="col" class="px-6 py-3">Status Pengiriman</th>
                                    <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="transactionsTableBody">
                                <!-- Data will be loaded here via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Detail Transaction Modal -->
    <div id="detailModal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 hidden opacity-0 invisible">
        <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-4xl transform -translate-y-10 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center p-4 border-b sticky top-0 bg-white">
                <h3 class="text-xl font-semibold">Detail Transaksi</h3>
                <button class="close-modal text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <h4 class="font-bold text-lg">Informasi Pesanan</h4>
                        <div>
                            <p class="text-sm text-gray-500">ID Transaksi</p>
                            <p class="font-semibold font-mono text-sm" data-field="transaction-id">INV-20240825-001</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tanggal Pesanan</p>
                            <p class="font-semibold" data-field="order-date">25 Agustus 2024, 14:30 WIB</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Status Pembayaran</p>
                            <p class="font-semibold" data-field="payment-status"><span class="px-2 py-1 text-xs font-semibold leading-tight text-green-700 bg-green-100 rounded-full">Lunas</span></p>
                        </div>
                         <div>
                             <p class="text-sm text-gray-500">Status Pengiriman</p>
                             <p class="font-semibold" data-field="shipping-status"><span class="px-2 py-1 text-xs font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full">Belum Dikirim</span></p>
                         </div>
                    </div>
                    <div class="space-y-4">
                        <h4 class="font-bold text-lg">Informasi Pelanggan</h4>
                        <div>
                            <p class="text-sm text-gray-500">Nama</p>
                            <p class="font-semibold" data-field="customer-name">Budi Santoso</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-semibold" data-field="customer-email">budi.santoso@example.com</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Alamat Pengiriman</p>
                            <p class="font-semibold text-sm" data-field="shipping-address">Jl. Merdeka No. 123, Jakarta Pusat, DKI Jakarta, 10110</p>
                        </div>
                    </div>
                </div>
                <div class="mt-6">
                    <h4 class="font-bold text-lg mb-2">Rincian Produk</h4>
                    <div class="border rounded-lg overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="p-3 text-left">Produk</th>
                                    <th class="p-3 text-center">Jumlah</th>
                                    <th class="p-3 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border-b">
                                    <td class="p-3 flex items-center">
                                        <img src="https://placehold.co/40x40/F9F6F1/8D5B4C?text=Art1" class="w-10 h-10 rounded-md mr-3">
                                        Product Name
                                    </td>
                                    <td class="p-3 text-center">1</td>
                                    <td class="p-3 text-right">Rp 0</td>
                                </tr>
                            </tbody>
                            <tfoot class="font-semibold">
                                <tr>
                                    <td colspan="2" class="p-3 text-right">Subtotal</td>
                                    <td class="p-3 text-right" data-field="subtotal">Rp 0</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="p-3 text-right">Ongkos Kirim</td>
                                    <td class="p-3 text-right">Rp 0</td>
                                </tr>
                                <tr class="bg-gray-50">
                                    <td colspan="2" class="p-3 text-right text-base">Total</td>
                                    <td class="p-3 text-right text-base text-[var(--primary-color)]" data-field="total">Rp 0</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Payment Proof Section -->
                <div class="mt-6" id="payment-proof-section" style="display: none;">
                    <h4 class="font-bold text-lg mb-2">Bukti Pembayaran</h4>
                    <div class="border rounded-lg p-4 bg-gray-50">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm text-gray-600">Status Verifikasi:</p>
                                <span id="payment-proof-status" class="px-2 py-1 text-xs font-semibold leading-tight rounded-full"></span>
                            </div>
                            <div id="verification-buttons" class="flex space-x-2" style="display: none;">
                                <button id="approve-payment" class="bg-green-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-600 transition-colors">
                                    <i class="fas fa-check mr-2"></i>Setujui
                                </button>
                                <button id="reject-payment" class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-600 transition-colors">
                                    <i class="fas fa-times mr-2"></i>Tolak
                                </button>
                            </div>
                        </div>
                        <div class="text-center">
                            <img id="payment-proof-image" src="" alt="Bukti Pembayaran" class="max-w-full max-h-96 mx-auto rounded-lg shadow-md cursor-pointer" onclick="openImageModal(this.src)">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Shipping Status Modal -->
    <div id="shippingModal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 hidden opacity-0 invisible">
        <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-md transform -translate-y-10">
            <div class="flex justify-between items-center p-4 border-b sticky top-0 bg-white">
                <h3 class="text-xl font-semibold">Ubah Status Pengiriman</h3>
                <button class="close-modal text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="p-6">
                <div id="shipping-status-options" class="space-y-3">
                    <!-- Options will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- Sidebar ---
        const menuButton = document.getElementById('menu-button');
        const sidebar = document.getElementById('sidebar');
        menuButton.addEventListener('click', () => sidebar.classList.toggle('-translate-x-full'));
        document.addEventListener('click', (e) => {
            if (!sidebar.contains(e.target) && !menuButton.contains(e.target) && !sidebar.classList.contains('-translate-x-full') && window.innerWidth < 768) {
                sidebar.classList.add('-translate-x-full');
            }
        });

        // --- Modal ---
        const detailModal = document.getElementById('detailModal');
        const shippingModal = document.getElementById('shippingModal');
        const openDetailButtons = document.querySelectorAll('.open-detail-modal');
        const closeButtons = document.querySelectorAll('.close-modal');

        const openModal = (modal) => {
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('invisible', 'opacity-0');
                modal.querySelector('.modal-content').classList.remove('-translate-y-10');
            }, 10);
        };

        const closeModal = (modal) => {
            if (!modal) return;
            modal.querySelector('.modal-content').classList.add('-translate-y-10');
            modal.classList.add('invisible', 'opacity-0');
            setTimeout(() => modal.classList.add('hidden'), 300);
        };

        openDetailButtons.forEach(button => {
            button.addEventListener('click', () => openModal(detailModal));
        });

        closeButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const modalToClose = e.target.closest('.modal');
                closeModal(modalToClose);
            });
        });

        window.addEventListener('click', (e) => {
            if (e.target === detailModal) closeModal(detailModal);
            if (e.target === shippingModal) closeModal(shippingModal);
        });

        // --- Export Buttons ---
        document.getElementById('exportPdfButton').addEventListener('click', () => {
            // Get current filter values
            const startDate = document.querySelector('input[type="date"]:nth-of-type(1)').value;
            const endDate = document.querySelector('input[type="date"]:nth-of-type(2)').value;
            const paymentStatus = document.querySelector('select:nth-of-type(1)').value;
            const shippingStatus = document.querySelector('select:nth-of-type(2)').value;

            // Build query parameters
            const params = new URLSearchParams();
            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);
            if (paymentStatus && paymentStatus !== 'Semua Status Pembayaran') params.append('payment_status', paymentStatus);
            if (shippingStatus && shippingStatus !== 'Semua Status Pengiriman') params.append('shipping_status', shippingStatus);

            const url = '/api/admin/transactions/export/pdf' + (params.toString() ? '?' + params.toString() : '');
            window.open(url, '_blank');
        });

        document.getElementById('exportExcelButton').addEventListener('click', () => {
            // Get current filter values
            const startDate = document.querySelector('input[type="date"]:nth-of-type(1)').value;
            const endDate = document.querySelector('input[type="date"]:nth-of-type(2)').value;
            const paymentStatus = document.querySelector('select:nth-of-type(1)').value;
            const shippingStatus = document.querySelector('select:nth-of-type(2)').value;

            // Build query parameters
            const params = new URLSearchParams();
            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);
            if (paymentStatus && paymentStatus !== 'Semua Status Pembayaran') params.append('payment_status', paymentStatus);
            if (shippingStatus && shippingStatus !== 'Semua Status Pengiriman') params.append('shipping_status', shippingStatus);

            const url = '/api/admin/transactions/export/excel' + (params.toString() ? '?' + params.toString() : '');
            window.location.href = url;
        });

        // --- Filter Button ---
        document.querySelector('.btn-primary').addEventListener('click', () => {
            loadTransactions();
        });

        // --- Load Transactions Data ---
        async function loadTransactions() {
            try {
                // Get filter values
                const startDate = document.querySelector('input[type="date"]:nth-of-type(1)').value;
                const endDate = document.querySelector('input[type="date"]:nth-of-type(2)').value;
                const paymentStatus = document.querySelector('select:nth-of-type(1)').value;
                const shippingStatus = document.querySelector('select:nth-of-type(2)').value;

                // Build query parameters
                const params = new URLSearchParams();
                if (startDate) params.append('start_date', startDate);
                if (endDate) params.append('end_date', endDate);
                if (paymentStatus && paymentStatus !== 'Semua Status Pembayaran') params.append('payment_status', paymentStatus);
                if (shippingStatus && shippingStatus !== 'Semua Status Pengiriman') params.append('shipping_status', shippingStatus);

                const url = '/api/art/transactions/all' + (params.toString() ? '?' + params.toString() : '');

                const response = await fetch(url);
                const data = await response.json();

                if (data.success) {
                    const tbody = document.getElementById('transactionsTableBody');
                    tbody.innerHTML = '';

                    data.data.forEach(transaction => {
                        const date = new Date(transaction.tanggal_dan_waktu_pembelian);
                        const formattedDate = date.toLocaleDateString('id-ID', {
                            day: 'numeric',
                            month: 'short',
                            year: 'numeric'
                        });

                        const total = 'Rp ' + new Intl.NumberFormat('id-ID').format(transaction.grand_total);

                        // Status badge based on payment status
                        let statusBadge = '';
                        let statusClass = '';
                        switch(transaction.status_pembayaran) {
                            case 'Belum Bayar':
                                statusBadge = 'Belum Bayar';
                                statusClass = 'text-red-700 bg-red-100';
                                break;
                            case 'Menunggu Verifikasi':
                                statusBadge = 'Menunggu Verifikasi';
                                statusClass = 'text-yellow-700 bg-yellow-100';
                                break;
                            case 'Lunas':
                                statusBadge = 'Lunas';
                                statusClass = 'text-green-700 bg-green-100';
                                break;
                            case 'Ditolak':
                                statusBadge = 'Ditolak';
                                statusClass = 'text-red-700 bg-red-100';
                                break;
                            default:
                                statusBadge = transaction.status_pembayaran || 'Unknown';
                                statusClass = 'text-gray-700 bg-gray-100';
                        }

                        // Shipping status badge
                        let shippingStatusBadge = '';
                        let shippingStatusClass = '';
                        switch(transaction.status_pengiriman) {
                            case 'Belum Dikirim':
                                shippingStatusBadge = 'Belum Dikirim';
                                shippingStatusClass = 'text-gray-700 bg-gray-100';
                                break;
                            case 'Sedang Dikirim':
                                shippingStatusBadge = 'Sedang Dikirim';
                                shippingStatusClass = 'text-blue-700 bg-blue-100';
                                break;
                            case 'Diterima':
                                shippingStatusBadge = 'Diterima';
                                shippingStatusClass = 'text-green-700 bg-green-100';
                                break;
                            default:
                                shippingStatusBadge = transaction.status_pengiriman || 'Unknown';
                                shippingStatusClass = 'text-gray-700 bg-gray-100';
                        }

                        const row = document.createElement('tr');
                        row.className = 'bg-white border-b hover:bg-gray-50';
                        row.innerHTML = `
                            <td class="px-6 py-4 font-mono text-xs">${transaction.kode_transaksi}</td>
                            <td class="px-6 py-4 font-medium text-gray-900">${transaction.customer_name}</td>
                            <td class="px-6 py-4">${formattedDate}</td>
                            <td class="px-6 py-4">${total}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 font-semibold leading-tight ${statusClass} rounded-full">${statusBadge}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 font-semibold leading-tight ${shippingStatusClass} rounded-full shipping-status-badge cursor-pointer" data-transaction-code="${transaction.kode_transaksi}" data-current-status="${transaction.status_pengiriman}">${shippingStatusBadge}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button class="font-medium text-gray-600 hover:underline open-detail-modal" data-transaction='${JSON.stringify(transaction)}'>
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });

                    // Re-attach event listeners for detail buttons
                    document.querySelectorAll('.open-detail-modal').forEach(button => {
                        button.addEventListener('click', (e) => {
                            const transaction = JSON.parse(e.currentTarget.getAttribute('data-transaction'));
                            openDetailModal(transaction.kode_transaksi);
                        });
                    });
                }
            } catch (error) {
                console.error('Error loading transactions:', error);
            }
        }

        async function openDetailModal(trCode) {
            try {
                const response = await fetch('/api/art/transactions/' + trCode);
                const data = await response.json();

                if (data.success && data.data.length > 0) {
                    const products = data.data;
                    const firstProduct = products[0];

                    const date = new Date(firstProduct.tanggal_dan_waktu_pembelian);
                    const formattedDate = date.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });

                    const total = products.reduce((sum, p) => sum + p.total, 0);
                    const formattedTotal = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
                    const subtotal = formattedTotal; // Assuming subtotal is same as total for now

                    // Update modal content
                    document.querySelector('#detailModal [data-field="transaction-id"]').textContent = firstProduct.kode_transaksi;
                    document.querySelector('#detailModal [data-field="order-date"]').textContent = formattedDate;

                    // Payment status badge
                    let paymentStatusBadge = '';
                    let paymentStatusClass = '';
                    switch(firstProduct.status_pembayaran) {
                        case 'Belum Bayar':
                            paymentStatusBadge = 'Belum Bayar';
                            paymentStatusClass = 'text-red-700 bg-red-100';
                            break;
                        case 'Menunggu Verifikasi':
                            paymentStatusBadge = 'Menunggu Verifikasi';
                            paymentStatusClass = 'text-yellow-700 bg-yellow-100';
                            break;
                        case 'Lunas':
                            paymentStatusBadge = 'Lunas';
                            paymentStatusClass = 'text-green-700 bg-green-100';
                            break;
                        case 'Ditolak':
                            paymentStatusBadge = 'Ditolak';
                            paymentStatusClass = 'text-red-700 bg-red-100';
                            break;
                        default:
                            paymentStatusBadge = firstProduct.status_pembayaran || 'Unknown';
                            paymentStatusClass = 'text-gray-700 bg-gray-100';
                    }
                    document.querySelector('#detailModal [data-field="payment-status"]').innerHTML = `<span class="px-2 py-1 text-xs font-semibold leading-tight ${paymentStatusClass} rounded-full">${paymentStatusBadge}</span>`;

                    // Shipping status badge
                    let shippingStatusBadge = '';
                    let shippingStatusClass = '';
                    switch(firstProduct.status_pengiriman) {
                        case 'Belum Dikirim':
                            shippingStatusBadge = 'Belum Dikirim';
                            shippingStatusClass = 'text-gray-700 bg-gray-100';
                            break;
                        case 'Sedang Dikirim':
                            shippingStatusBadge = 'Sedang Dikirim';
                            shippingStatusClass = 'text-blue-700 bg-blue-100';
                            break;
                        case 'Diterima':
                            shippingStatusBadge = 'Diterima';
                            shippingStatusClass = 'text-green-700 bg-green-100';
                            break;
                        default:
                            shippingStatusBadge = firstProduct.status_pengiriman || 'Unknown';
                            shippingStatusClass = 'text-gray-700 bg-gray-100';
                    }
                    document.querySelector('#detailModal [data-field="shipping-status"]').innerHTML = `<span class="px-2 py-1 text-xs font-semibold leading-tight ${shippingStatusClass} rounded-full">${shippingStatusBadge}</span>`;

                    document.querySelector('#detailModal [data-field="customer-name"]').textContent = firstProduct.nama_tampilan;
                    document.querySelector('#detailModal [data-field="customer-email"]').textContent = firstProduct.email;

                    // Format shipping address
                    const address = firstProduct;
                    const fullAddress = `${address.alamat}, ${address.nama_kecamatan}, ${address.nama_kota}, ${address.nama_provinsi} ${address.kode_pos}`;
                    document.querySelector('#detailModal [data-field="shipping-address"]').textContent = fullAddress;

                    // Update product details
                    const tbody = document.querySelector('#detailModal tbody');
                    tbody.innerHTML = '';

                    products.forEach(product => {
                        const row = document.createElement('tr');
                        row.className = 'border-b';
                        const imageSrc = product.gambar_produk ? `/images/products/${product.gambar_produk}` : 'https://placehold.co/40x40/F9F6F1/8D5B4C?text=Art';
                        const subtotalProduct = 'Rp ' + new Intl.NumberFormat('id-ID').format(product.total);
                        row.innerHTML = `
                            <td class="p-3 flex items-center">
                                <img src="${imageSrc}" class="w-10 h-10 rounded-md mr-3">
                                ${product.nama_produk}
                            </td>
                            <td class="p-3 text-center">${product.jumlah}</td>
                            <td class="p-3 text-right">${subtotalProduct}</td>
                        `;
                        tbody.appendChild(row);
                    });

                    // Update totals
                    document.querySelector('#detailModal [data-field="subtotal"]').textContent = subtotal;
                    document.querySelector('#detailModal [data-field="total"]').textContent = formattedTotal;

                    // Handle payment proof display
                    const paymentProofSection = document.getElementById('payment-proof-section');
                    const paymentProofImage = document.getElementById('payment-proof-image');
                    const paymentProofStatus = document.getElementById('payment-proof-status');
                    const verificationButtons = document.getElementById('verification-buttons');

                    if (firstProduct.bukti_pembayaran && firstProduct.status_pembayaran === 'Menunggu Verifikasi') {
                        // Show payment proof section
                        paymentProofSection.style.display = 'block';
                        paymentProofImage.src = `/images/pembayaran/${firstProduct.bukti_pembayaran}`;

                        // Set status badge
                        paymentProofStatus.className = 'px-2 py-1 text-xs font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full';
                        paymentProofStatus.textContent = 'Menunggu Verifikasi';

                        // Show verification buttons
                        verificationButtons.style.display = 'flex';

                        // Store transaction code for verification
                        document.getElementById('approve-payment').setAttribute('data-transaction-code', firstProduct.kode_transaksi);
                        document.getElementById('reject-payment').setAttribute('data-transaction-code', firstProduct.kode_transaksi);
                    } else if (firstProduct.bukti_pembayaran && (firstProduct.status_pembayaran === 'Lunas' || firstProduct.status_pembayaran === 'Ditolak')) {
                        // Show payment proof section for completed transactions
                        paymentProofSection.style.display = 'block';
                        paymentProofImage.src = `/images/pembayaran/${firstProduct.bukti_pembayaran}`;

                        // Set status badge based on final status
                        if (firstProduct.status_pembayaran === 'Lunas') {
                            paymentProofStatus.className = 'px-2 py-1 text-xs font-semibold leading-tight text-green-700 bg-green-100 rounded-full';
                            paymentProofStatus.textContent = 'Disetujui';
                        } else {
                            paymentProofStatus.className = 'px-2 py-1 text-xs font-semibold leading-tight text-red-700 bg-red-100 rounded-full';
                            paymentProofStatus.textContent = 'Ditolak';
                        }

                        // Hide verification buttons
                        verificationButtons.style.display = 'none';
                    } else {
                        // Hide payment proof section
                        paymentProofSection.style.display = 'none';
                    }

                    openModal(detailModal);
                }
            } catch (error) {
                console.error('Error loading transaction details:', error);
            }
        }

        // Image Modal for full-size payment proof view
        function openImageModal(src) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-75 z-[60] flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="relative max-w-4xl max-h-full">
                    <img src="${src}" alt="Bukti Pembayaran" class="max-w-full max-h-full object-contain">
                    <button onclick="this.parentElement.parentElement.remove()" class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300">&times;</button>
                </div>
            `;
            document.body.appendChild(modal);

            // Close on background click
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.remove();
                }
            });
        }

        // Payment verification functions
        document.addEventListener('click', function(e) {
            if (e.target.id === 'approve-payment') {
                const transactionCode = e.target.getAttribute('data-transaction-code');
                verifyPayment(transactionCode, 'approve');
            } else if (e.target.id === 'reject-payment') {
                const transactionCode = e.target.getAttribute('data-transaction-code');
                verifyPayment(transactionCode, 'reject');
            }
        });

        async function verifyPayment(transactionCode, action) {
            const confirmMessage = action === 'approve' ?
                'Apakah Anda yakin ingin menyetujui pembayaran ini?' :
                'Apakah Anda yakin ingin menolak pembayaran ini?';

            Swal.fire({
                title: 'Konfirmasi',
                text: confirmMessage,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch('/api/admin/verify-payment', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                kode_transaksi: transactionCode,
                                action: action
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                closeModal(document.getElementById('detailModal'));
                                loadTransactions();
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal',
                                text: 'Error: ' + (data.message || 'Terjadi kesalahan'),
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    } catch (error) {
                        console.error('Error verifying payment:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Terjadi kesalahan saat memverifikasi pembayaran',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }

        // Shipping status modal functions
        function openShippingModal(transactionCode, currentStatus) {
            const optionsContainer = document.getElementById('shipping-status-options');
            optionsContainer.innerHTML = '';

            const statuses = [
                { value: 'Belum Dikirim', label: 'Belum Dikirim', color: 'text-gray-700 bg-gray-100' },
                { value: 'Sedang Dikirim', label: 'Sedang Dikirim', color: 'text-blue-700 bg-blue-100' },
                { value: 'Diterima', label: 'Diterima', color: 'text-green-700 bg-green-100' }
            ];

            const statusOrder = ['Belum Dikirim', 'Sedang Dikirim', 'Diterima'];
            const currentIndex = statusOrder.indexOf(currentStatus);

            statuses.forEach(status => {
                const statusIndex = statusOrder.indexOf(status.value);
                let disabled = false;
                let buttonText = status.label;

                if (statusIndex < currentIndex) {
                    disabled = true;
                    buttonText += ' (Tidak Dapat Dipilih)';
                } else if (statusIndex === currentIndex) {
                    buttonText += ' (Status Saat Ini)';
                }

                const button = document.createElement('button');
                button.className = `w-full text-left px-4 py-3 rounded-lg border transition-colors ${
                    disabled ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white hover:bg-gray-50 border-gray-200'
                }`;
                button.disabled = disabled;
                button.innerHTML = `
                    <span class="px-2 py-1 text-xs font-semibold leading-tight ${status.color} rounded-full">${buttonText}</span>
                `;

                if (!disabled) {
                    button.addEventListener('click', () => updateShippingStatus(transactionCode, status.value));
                }

                optionsContainer.appendChild(button);
            });

            // Add cancel button if not "Belum Dikirim"
            if (currentStatus !== 'Belum Dikirim') {
                const cancelButton = document.createElement('button');
                cancelButton.className = 'w-full text-left px-4 py-3 rounded-lg border bg-red-50 hover:bg-red-100 border-red-200 text-red-700 transition-colors';
                cancelButton.innerHTML = '<i class="fas fa-times mr-2"></i>Batalkan Pengiriman';
                cancelButton.addEventListener('click', () => updateShippingStatus(transactionCode, 'Belum Dikirim'));
                optionsContainer.appendChild(cancelButton);
            }

            openModal(shippingModal);
        }

        async function updateShippingStatus(transactionCode, newStatus) {
            Swal.fire({
                title: 'Konfirmasi',
                text: `Apakah Anda yakin ingin mengubah status pengiriman menjadi "${newStatus}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const response = await fetch('/api/admin/update-shipping-status', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                kode_transaksi: transactionCode,
                                status_pengiriman: newStatus
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                title: 'Berhasil',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                closeModal(shippingModal);
                                loadTransactions();
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal',
                                text: 'Error: ' + (data.message || 'Terjadi kesalahan'),
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    } catch (error) {
                        console.error('Error updating shipping status:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Terjadi kesalahan saat mengubah status pengiriman',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        }

        // Event listener for shipping status badges
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('shipping-status-badge')) {
                const transactionCode = e.target.getAttribute('data-transaction-code');
                const currentStatus = e.target.getAttribute('data-current-status');
                openShippingModal(transactionCode, currentStatus);
            }
        });

        // Load transactions on page load
        document.addEventListener('DOMContentLoaded', loadTransactions);
    </script>
@include('admin.part.foot')
