@include('admin.part.head')
            <!-- Content -->
            <main class="p-6 md:p-8">
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Daftar Pelanggan</h3>
                        <div class="relative">
                            <input type="text" placeholder="Cari pelanggan..." class="pl-10 pr-4 py-2 rounded-full border focus:outline-none focus:ring-2 focus:ring-[var(--primary-color)] transition-all w-48 focus:w-64">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Pelanggan</th>
                                    <th scope="col" class="px-6 py-3">Email</th>
                                    <th scope="col" class="px-6 py-3">Bergabung Sejak</th>
                                    <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="customers-table-body">
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Detail Customer Modal -->
    <div id="detailModal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 hidden opacity-0 invisible">
        <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-4xl transform -translate-y-10 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center p-4 border-b sticky top-0 bg-white">
                <h3 class="text-xl font-semibold">Detail Pelanggan</h3>
                <button class="close-modal text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
            </div>
            <div class="p-6">
                <div class="flex items-start space-x-6 mb-6">
                    <img id="customer-avatar" src="https://placehold.co/80x80/FFC107/FFFFFF?text=B" alt="User" class="w-20 h-20 rounded-full">
                    <div>
                        <h4 id="customer-name" class="text-2xl font-bold">Budi Santoso</h4>
                        <p id="customer-email" class="text-gray-600">budi.santoso@example.com</p>
                        <p id="customer-join-date" class="text-sm text-gray-500 mt-1">Bergabung sejak 15 Juli 2024</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- Load Customers Data ---
        async function loadCustomers(searchTerm = '') {
            try {
                const response = await fetch('/api/admin/customers/all');
                const result = await response.json();

                if (result.success) {
                    displayCustomers(result.data, searchTerm);
                } else {
                    document.getElementById('customers-table-body').innerHTML = `
                        <tr class="bg-white border-b">
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada data pelanggan</td>
                        </tr>
                    `;
                }
            } catch (error) {
                console.error('Error loading customers:', error);
                document.getElementById('customers-table-body').innerHTML = `
                    <tr class="bg-white border-b">
                        <td colspan="4" class="px-6 py-4 text-center text-red-500">Error loading data</td>
                    </tr>
                `;
            }
        }

        function displayCustomers(customers, searchTerm = '') {
            const tbody = document.getElementById('customers-table-body');
            let html = '';

            // Filter customers if search term is provided
            const filteredCustomers = searchTerm ?
                customers.filter(customer =>
                    customer.nama_tampilan.toLowerCase().includes(searchTerm.toLowerCase()) ||
                    customer.email.toLowerCase().includes(searchTerm.toLowerCase())
                ) : customers;

            if (filteredCustomers.length === 0) {
                html = `
                    <tr class="bg-white border-b">
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            ${searchTerm ? 'Tidak ada pelanggan yang cocok dengan pencarian' : 'Belum ada data pelanggan'}
                        </td>
                    </tr>
                `;
            } else {
                filteredCustomers.forEach(customer => {
                    const avatarText = customer.nama_tampilan.charAt(0).toUpperCase();
                    const avatarColor = customer.nama_tampilan.split(' ').map(n => n[0]).join('').toUpperCase();
                    const joinDate = new Date(customer.created_at).toLocaleDateString('id-ID', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    });

                    html += `
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900 flex items-center">
                                <img src="https://placehold.co/40x40/${avatarColor}/FFFFFF?text=${avatarText}" alt="User" class="w-10 h-10 rounded-full mr-3">
                                ${customer.nama_tampilan}
                            </td>
                            <td class="px-6 py-4">${customer.email}</td>
                            <td class="px-6 py-4">${joinDate}</td>
                            <td class="px-6 py-4 text-center">
                                <button class="font-medium text-gray-600 hover:underline open-detail-modal"
                                        data-customer='${JSON.stringify({
                                            id: customer.id_user,
                                            name: customer.nama_tampilan,
                                            email: customer.email,
                                            join_date: joinDate
                                        }).replace(/'/g, "'")}'>
                                    <i class="fas fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
            }

            tbody.innerHTML = html;
        }

        // Load customers on page load
        document.addEventListener('DOMContentLoaded', () => {
            loadCustomers();
        });

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

        // Re-attach event listeners for dynamically created buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.open-detail-modal')) {
                const button = e.target.closest('.open-detail-modal');
                const customerData = JSON.parse(button.getAttribute('data-customer'));

                // Update modal content
                document.getElementById('customer-avatar').src = `https://placehold.co/80x80/${customerData.name.split(' ').map(n => n[0]).join('').toUpperCase()}/FFFFFF?text=${customerData.name.charAt(0).toUpperCase()}`;
                document.getElementById('customer-name').textContent = customerData.name;
                document.getElementById('customer-email').textContent = customerData.email;
                document.getElementById('customer-join-date').textContent = `Bergabung sejak ${customerData.join_date}`;

                openModal(detailModal);
            }
        });

        closeButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const modalToClose = e.target.closest('.modal');
                closeModal(modalToClose);
            });
        });

        window.addEventListener('click', (e) => {
            if (e.target === detailModal) closeModal(detailModal);
        });

        // --- Search Functionality ---
        const searchInput = document.querySelector('input[placeholder="Cari pelanggan..."]');

        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value;
            loadCustomers(searchTerm);
        });
    </script>
@include('admin.part.foot')
