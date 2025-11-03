@include('admin.part.head')
        <main class="p-6 md:p-8">
            <div class="bg-white p-6 rounded-xl shadow-md">
                <div class="flex border-b mb-4">
                    <button data-tab-target="#products" class="tab-button active-tab py-2 px-4 font-semibold text-gray-700 border-b-2 border-[var(--primary-color)]">Manajemen Produk</button>
                    <button data-tab-target="#categories" class="tab-button py-2 px-4 font-semibold text-gray-500">Manajemen Kategori</button>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <div id="header-title">
                        <h3 class="text-xl font-bold text-gray-800">Manajemen Produk</h3>
                    </div>
                    <div class="flex space-x-2">
                        <button id="addProductButton" class="btn-primary flex items-center space-x-2 px-4 py-2 rounded-lg text-sm font-medium">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Produk</span>
                        </button>
                        <button id="addCategoryButton" class="btn-primary flex items-center space-x-2 px-4 py-2 rounded-lg text-sm font-medium hidden">
                            <i class="fas fa-plus"></i>
                            <span>Tambah Kategori</span>
                        </button>
                    </div>
                </div>

                <div id="tab-content">
                    <div id="products" class="tab-pane active">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Produk</th>
                                        <th scope="col" class="px-6 py-3">Kategori</th>
                                        <th scope="col" class="px-6 py-3">Harga</th>
                                        <th scope="col" class="px-6 py-3">Stok</th>
                                        <th scope="col" class="px-6 py-3">Status</th>
                                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="productTableBody">
                                    <tr class="bg-white border-b"><td colspan="6" class="text-center p-4">Memuat data...</td></tr>
                                </tbody>
                            </table>
                        <div id="pagination" class="flex justify-center mt-4"></div>
                        </div>
                    </div>

                    <div id="categories" class="tab-pane hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">ID Kategori</th>
                                        <th scope="col" class="px-6 py-3">Nama Kategori</th>
                                        <th scope="col" class="px-6 py-3 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="categoryTableBody">
                                    <tr class="bg-white border-b"><td colspan="3" class="text-center p-4">Memuat data...</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<div id="productModal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 hidden opacity-0 invisible">
    <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-2xl transform -translate-y-10 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center p-4 border-b sticky top-0 bg-white z-10">
            <h3 id="productModalTitle" class="text-xl font-semibold">Tambah Produk Baru</h3>
            <button class="close-modal text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        <form id="productForm" class="p-6 space-y-4">
            <input type="hidden" id="productId" name="productId">
            <div>
                <label for="productName" class="block mb-2 text-sm font-medium text-gray-900">Nama Produk</label>
                <input type="text" name="nama_produk" id="productName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] block w-full p-2.5" placeholder="cth. Mimpi di Kanvas" required>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="category" class="block mb-2 text-sm font-medium text-gray-900">Kategori</label>
                    <select id="category" name="id_kategori" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] block w-full p-2.5" required>
                        <option value="">Pilih Kategori</option>
                        </select>
                </div>
                <div>
                    <label for="price" class="block mb-2 text-sm font-medium text-gray-900">Harga</label>
                    <input type="number" name="harga_produk" id="price" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] block w-full p-2.5" placeholder="cth. 4500000" required>
                </div>
            </div>
            <div>
                <label for="stock" class="block mb-2 text-sm font-medium text-gray-900">Stok</label>
                <input type="number" name="stok_produk" id="stock" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] block w-full p-2.5" placeholder="cth. 5" required>
            </div>
            <div>
                <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Deskripsi</label>
                <textarea id="description" name="detail_produk" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)]" placeholder="Deskripsi singkat produk..."></textarea>
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900" for="image">Unggah Gambar</label>
                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" id="image" name="gambar_produk" type="file">
            </div>
            <div class="flex justify-end items-center pt-4 border-t">
                <button type="button" class="close-modal text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2">Batal</button>
                <button type="submit" class="btn-primary font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="categoryModal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 hidden opacity-0 invisible">
    <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-md transform -translate-y-10">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 id="categoryModalTitle" class="text-xl font-semibold">Tambah Kategori Baru</h3>
            <button class="close-modal text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        <form id="categoryForm" class="p-6 space-y-4">
             <input type="hidden" id="categoryId" name="categoryId">
            <div>
                <label for="categoryName" class="block mb-2 text-sm font-medium text-gray-900">Nama Kategori</label>
                <input type="text" name="name" id="categoryName" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] block w-full p-2.5" placeholder="cth. Surealisme" required>
            </div>
            <div class="flex justify-end items-center pt-4 border-t">
                <button type="button" class="close-modal text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center mr-2">Batal</button>
                <button type="submit" class="btn-primary font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan</button>
            </div>
        </form>
    </div>
</div>


<div id="detailModal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 hidden opacity-0 invisible">
    <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-3xl transform -translate-y-10 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center p-4 border-b sticky top-0 bg-white">
            <h3 class="text-xl font-semibold">Detail Produk</h3>
            <button class="close-modal text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <img id="detailImage" src="https://placehold.co/400x400/eeeeee/cccccc?text=Image" class="w-full h-auto rounded-lg object-cover shadow-lg">
                </div>
                <div class="space-y-4">
                    <h4 id="detailProductName" class="text-3xl font-bold font-display text-gray-800">Nama Produk</h4>
                    <div>
                        <p class="text-sm text-gray-500">Kategori</p>
                        <p id="detailCategory" class="font-semibold text-gray-700">-</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Harga</p>
                        <p id="detailPrice" class="font-semibold text-2xl text-[var(--primary-color)]">-</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Stok Tersisa</p>
                        <p id="detailStock" class="font-semibold text-gray-700">-</p>
                    </div>
                    <div class="pt-2">
                        <p class="text-sm font-semibold text-gray-800 mb-1">Deskripsi</p>
                        <p id="detailDescription" class="text-gray-600 text-sm leading-relaxed">-</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="deleteModal" class="modal fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4 hidden opacity-0 invisible">
    <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-md transform -translate-y-10">
        <div class="p-6 text-center">
            <i class="fas fa-exclamation-triangle text-5xl text-red-500 mx-auto mb-4"></i>
            <h3 id="deleteModalText" class="mb-5 text-lg font-normal text-gray-500">Apakah Anda yakin ingin menghapus item ini?</h3>
            <button id="confirmDeleteButton" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center mr-2">
                Ya, Hapus
            </button>
            <button type="button" class="close-modal text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">
                Tidak, Batal
            </button>
        <div id="category-pagination" class="flex justify-center mt-4"></div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- UTILITIES ---
        const apiBaseUrl = '/api/art';

        let currentProductId = null;
        let currentCategoryId = null;
        let deleteEndpoint = null;
        let currentPage = 1;
        const perPage = 10;
        let allProducts = [];
        let currentCategoryPage = 1;
        let allCategories = [];

        // --- DOM ELEMENTS ---
        const modals = {
            product: document.getElementById('productModal'),
            category: document.getElementById('categoryModal'),
            delete: document.getElementById('deleteModal'),
            detail: document.getElementById('detailModal')
        };
        const forms = { product: document.getElementById('productForm'), category: document.getElementById('categoryForm') };
        const buttons = { addProduct: document.getElementById('addProductButton'), addCategory: document.getElementById('addCategoryButton'), confirmDelete: document.getElementById('confirmDeleteButton') };
        const tables = { productBody: document.getElementById('productTableBody'), categoryBody: document.getElementById('categoryTableBody') };
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabPanes = document.querySelectorAll('.tab-pane');

        // --- MODAL HANDLING ---
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
        document.querySelectorAll('.close-modal').forEach(button => button.addEventListener('click', (e) => closeModal(e.target.closest('.modal'))));
        window.addEventListener('click', (e) => { if (e.target.classList.contains('modal')) closeModal(e.target) });

        // --- TAB HANDLING ---
        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const target = document.querySelector(button.dataset.tabTarget);
                tabButtons.forEach(btn => {
                    btn.classList.remove('active-tab', 'border-[var(--primary-color)]', 'text-gray-700');
                    btn.classList.add('text-gray-500');
                });
                button.classList.add('active-tab', 'border-[var(--primary-color)]', 'text-gray-700');
                tabPanes.forEach(pane => pane.classList.add('hidden'));
                target.classList.remove('hidden');
                const isProductTab = button.dataset.tabTarget === '#products';
                buttons.addProduct.classList.toggle('hidden', !isProductTab);
                buttons.addCategory.classList.toggle('hidden', isProductTab);
                document.querySelector('#header-title h3').textContent = isProductTab ? 'Manajemen Produk' : 'Manajemen Kategori';
            });
        });

        // --- API & DATA FETCHING ---
        const fetchProducts = async () => {
            try {
                const response = await fetch(`${apiBaseUrl}/products/all`);
                if (!response.ok) throw new Error('Network response was not ok');
                const result = await response.json();
                allProducts = Array.isArray(result.data) ? result.data : [];
                renderPage(currentPage);
            } catch (error) {
                tables.productBody.innerHTML = `<tr><td colspan="6" class="text-center p-4 text-red-500">Gagal memuat data produk.</td></tr>`;
                console.error('Error fetching products:', error);
            }
        };

        const renderPage = (page) => {
            currentPage = page;
            const start = (page - 1) * perPage;
            const end = start + perPage;
            const productsData = allProducts.slice(start, end);
            const totalPages = Math.ceil(allProducts.length / perPage);
            renderProducts(productsData, { current_page: page, last_page: totalPages });
        };

        const fetchCategories = async () => {
            try {
                const response = await fetch(`${apiBaseUrl}/products/category/all`);
                if (!response.ok) throw new Error('Network response was not ok');
                const result = await response.json();
                allCategories = Array.isArray(result.data) ? result.data : [];
                renderCategoryPage(currentCategoryPage);
                populateCategoryDropdown(allCategories);
            } catch (error) {
                tables.categoryBody.innerHTML = `<tr><td colspan="3" class="text-center p-4 text-red-500">Gagal memuat data kategori.</td></tr>`;
                console.error('Error fetching categories:', error);
            }
        };

        const renderCategoryPage = (page) => {
            currentCategoryPage = page;
            const start = (page - 1) * perPage;
            const end = start + perPage;
            const categoriesData = allCategories.slice(start, end);
            const totalPages = Math.ceil(allCategories.length / perPage);
            renderCategories(categoriesData, { current_page: page, last_page: totalPages });
        };

        // --- RENDERING ---
        const renderProducts = (products, pagination) => {
            tables.productBody.innerHTML = '';
            if (products.length === 0) {
                tables.productBody.innerHTML = `<tr><td colspan="6" class="text-center p-4">Tidak ada data produk.</td></tr>`;
                return;
            }
            products.forEach(product => {
                const statusClass = product.stok_produk > 0 ? 'text-green-700 bg-green-100' : 'text-red-700 bg-red-100';
                const statusText = product.stok_produk > 0 ? 'Tersedia' : 'Habis';

                // MODIFIED: Construct image URL from base path and filename
                const imageUrl = product.gambar_produk ? `/images/products/${product.gambar_produk}` : 'https://placehold.co/40x40/eeeeee/cccccc?text='+product.nama_produk;

                const row = `
                    <tr class="bg-white border-b hover:bg-gray-50" data-product-id="${product.id_produk}" data-product='${JSON.stringify(product)}'>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap flex items-center">
                            <img src="${imageUrl}" class="w-10 h-10 rounded-md mr-3 object-cover">
                            ${product.nama_produk}
                        </th>
                        <td class="px-6 py-4">${product.nama_kategori || 'N/A'}</td>
                        <td class="px-6 py-4">Rp ${new Intl.NumberFormat('id-ID').format(product.harga_produk)}</td>
                        <td class="px-6 py-4">${product.stok_produk}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 font-semibold leading-tight ${statusClass} rounded-full">${statusText}</span>
                        </td>
                        <td class="px-6 py-4 text-center space-x-4">
                            <button class="font-medium text-gray-500 hover:text-gray-800 action-btn" data-action="detail"><i class="fas fa-eye"></i></button>
                            <button class="font-medium text-blue-600 hover:text-blue-800 action-btn" data-action="edit-product"><i class="fas fa-pencil-alt"></i></button>
                            <button class="font-medium text-red-600 hover:text-red-800 action-btn" data-action="delete-product"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>`;
                tables.productBody.insertAdjacentHTML('beforeend', row);
            });
            renderPagination(pagination);
        };

        const renderCategories = (categories, pagination) => {
            tables.categoryBody.innerHTML = '';
            if (categories.length === 0) {
                tables.categoryBody.innerHTML = `<tr><td colspan="3" class="text-center p-4">Tidak ada data kategori.</td></tr>`;
                return;
            }
            categories.forEach(category => {
                const row = `
                    <tr class="bg-white border-b hover:bg-gray-50" data-category-id="${category.id}" data-category-name="${category.nama_kategori}">
                        <td class="px-6 py-4">${category.id_kategori}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">${category.nama_kategori}</td>
                        <td class="px-6 py-4 text-center space-x-4">
                            <button class="font-medium text-blue-600 hover:text-blue-800 action-btn" data-action="edit-category"><i class="fas fa-pencil-alt"></i></button>
                            <button class="font-medium text-red-600 hover:text-red-800 action-btn" data-action="delete-category"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>`;
                tables.categoryBody.insertAdjacentHTML('beforeend', row);
            });
            renderCategoryPagination(pagination);
        };

        const populateCategoryDropdown = (categories) => {
            const select = document.getElementById('category');
            select.innerHTML = '<option value="">Pilih Kategori</option>';
            categories.forEach(cat => {
                select.innerHTML += `<option value="${cat.id_kategori}">${cat.nama_kategori}</option>`;
            });
        };

        const renderPagination = (pagination) => {
            const paginationDiv = document.getElementById('pagination');
            paginationDiv.innerHTML = '';
            if (pagination.last_page > 1) {
                let html = '';
                if (pagination.current_page > 1) {
                    html += `<button class="pagination-btn bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded mr-2" data-page="${pagination.current_page - 1}">Previous</button>`;
                }
                html += `<span class="text-gray-600">Page ${pagination.current_page} of ${pagination.last_page}</span>`;
                if (pagination.current_page < pagination.last_page) {
                    html += `<button class="pagination-btn bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded ml-2" data-page="${pagination.current_page + 1}">Next</button>`;
                }
                paginationDiv.innerHTML = html;
            }
        };

        const renderCategoryPagination = (pagination) => {
            const paginationDiv = document.getElementById('category-pagination');
            paginationDiv.innerHTML = '';
            if (pagination.last_page > 1) {
                let html = '';
                if (pagination.current_page > 1) {
                    html += `<button class="category-pagination-btn bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded mr-2" data-page="${pagination.current_page - 1}">Previous</button>`;
                }
                html += `<span class="text-gray-600">Page ${pagination.current_page} of ${pagination.last_page}</span>`;
                if (pagination.current_page < pagination.last_page) {
                    html += `<button class="category-pagination-btn bg-gray-200 hover:bg-gray-300 text-gray-700 px-3 py-1 rounded ml-2" data-page="${pagination.current_page + 1}">Next</button>`;
                }
                paginationDiv.innerHTML = html;
            }
        };

        // --- EVENT LISTENERS ---
        buttons.addProduct.addEventListener('click', () => {
            currentProductId = null;
            forms.product.reset();
            document.getElementById('productModalTitle').textContent = 'Tambah Produk Baru';
            openModal(modals.product);
        });
        buttons.addCategory.addEventListener('click', () => {
            currentCategoryId = null;
            forms.category.reset();
            document.getElementById('categoryModalTitle').textContent = 'Tambah Kategori Baru';
            openModal(modals.category);
        });

        document.body.addEventListener('click', (e) => {
            const button = e.target.closest('.action-btn');
            if (!button) return;
            const action = button.dataset.action;
            const row = button.closest('tr');

            if (action === 'edit-product') {
                const productData = JSON.parse(row.dataset.product);
                currentProductId = productData.id_produk;
                document.getElementById('productModalTitle').textContent = 'Edit Produk';
                document.getElementById('productId').value = productData.id_produk;
                document.getElementById('productName').value = productData.nama_produk;
                document.getElementById('category').value = productData.id_kategori;
                document.getElementById('price').value = productData.harga_produk;
                document.getElementById('stock').value = productData.stok_produk;
                document.getElementById('description').value = productData.detail_produk;
                openModal(modals.product);
            } else if (action === 'delete-product') {
                deleteEndpoint = `${apiBaseUrl}/products/del/${row.dataset.productId}/process`;
                document.getElementById('deleteModalText').textContent = 'Apakah Anda yakin ingin menghapus produk ini?';
                openModal(modals.delete);
            } else if (action === 'detail') {
                const product = JSON.parse(row.dataset.product);

                // MODIFIED: Construct image URL for detail modal
                const detailImageUrl = product.gambar_produk ? `/images/products/${product.gambar_produk}` : 'https://placehold.co/400x400/eeeeee/cccccc?text='+product.nama_produk;

                document.getElementById('detailImage').src = detailImageUrl;
                document.getElementById('detailProductName').textContent = product.nama_produk;
                document.getElementById('detailCategory').textContent = product.nama_kategori || 'N/A';
                document.getElementById('detailPrice').textContent = `Rp ${new Intl.NumberFormat('id-ID').format(product.harga_produk)}`;
                document.getElementById('detailStock').textContent = `${product.stok_produk} unit`;
                document.getElementById('detailDescription').textContent = product.detail_produk || '-';
                openModal(modals.detail);
            } else if (action === 'edit-category') {
                currentCategoryId = row.dataset.categoryId;
                document.getElementById('categoryModalTitle').textContent = 'Edit Kategori';
                document.getElementById('categoryId').value = currentCategoryId;
                document.getElementById('categoryName').value = row.dataset.categoryName;
                openModal(modals.category);
            } else if (action === 'delete-category') {
                deleteEndpoint = `${apiBaseUrl}/products/category/del/${row.dataset.categoryId}/process`;
                document.getElementById('deleteModalText').textContent = 'Apakah Anda yakin ingin menghapus kategori ini?';
                openModal(modals.delete);
            }
        });

        document.body.addEventListener('click', (e) => {
            if (e.target.classList.contains('pagination-btn')) {
                const page = parseInt(e.target.dataset.page);
                renderPage(page);
            }
            if (e.target.classList.contains('category-pagination-btn')) {
                const page = parseInt(e.target.dataset.page);
                renderCategoryPage(page);
            }
        });

        // --- FORM SUBMISSIONS ---
        forms.product.addEventListener('submit', async (e) => {
            e.preventDefault();
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar.',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            const formData = new FormData(forms.product);
            const url = currentProductId ? `${apiBaseUrl}/products/edit/${currentProductId}/process` : `${apiBaseUrl}/products/add/process`;
            if (currentProductId) formData.append('_method', 'PUT');

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    enctype: 'multipart/form-data',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();
                if (!response.ok) throw new Error(result.message || 'Gagal menyimpan produk');

                closeModal(modals.product);
                fetchProducts(currentPage);
                Swal.fire({
                    icon: 'success', title: 'Berhasil!', text: 'Produk berhasil disimpan.',
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
                });
            } catch (error) {
                console.error('Error saving product:', error);
                Swal.fire({
                    icon: 'error', title: 'Gagal!', text: error.message,
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
                });
            }
        });

        forms.category.addEventListener('submit', async (e) => {
            e.preventDefault();
            Swal.fire({
                title: 'Memproses...',
                text: 'Mohon tunggu sebentar.',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            const categoryData = { nama_kategori: document.getElementById('categoryName').value };
            const url = currentCategoryId ? `${apiBaseUrl}/products/category/edit/${currentCategoryId}/process` : `${apiBaseUrl}/category/product/add/process`;

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: JSON.stringify(categoryData),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();
                if (!response.ok) throw new Error(result.message || 'Gagal menyimpan kategori');

                closeModal(modals.category);
                fetchCategories();
                Swal.fire({
                    icon: 'success', title: 'Berhasil!', text: 'Kategori berhasil disimpan.',
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
                });
            } catch (error) {
                console.error('Error saving category:', error);
                Swal.fire({
                    icon: 'error', title: 'Gagal!', text: error.message,
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
                });
            }
        });

        buttons.confirmDelete.addEventListener('click', async () => {
            if (!deleteEndpoint) return;
            Swal.fire({
                title: 'Menghapus...',
                text: 'Mohon tunggu sebentar.',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            try {
                const response = await fetch(deleteEndpoint, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                const result = await response.json();
                if (!response.ok) throw new Error(result.message || 'Gagal menghapus');

                closeModal(modals.delete);
                if (deleteEndpoint.includes('/category/')) {
                    fetchCategories();
                } else {
                    fetchProducts(currentPage);
                }
                Swal.fire({
                    icon: 'success', title: 'Berhasil!', text: 'Item berhasil dihapus.',
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
                });
            } catch (error) {
                console.error('Error deleting item:', error);
                Swal.fire({
                    icon: 'error', title: 'Gagal!', text: error.message,
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true
                });
            } finally {
                deleteEndpoint = null;
            }
        });

        // --- INITIALIZATION & SIDEBAR ---
        fetchProducts();
        fetchCategories();
        const menuButton = document.getElementById('menu-button'), sidebar = document.getElementById('sidebar');
        if (menuButton && sidebar) {
            menuButton.addEventListener('click', () => sidebar.classList.toggle('-translate-x-full'));
            document.addEventListener('click', (e) => {
                if (!sidebar.contains(e.target) && !menuButton.contains(e.target) && !sidebar.classList.contains('-translate-x-full') && window.innerWidth < 768) {
                    sidebar.classList.add('-translate-x-full');
                }
            });
        }
    });
    </script>

@include('admin.part.foot')
