@include('partials.head')
<main class="max-w-[1200px] mx-auto px-6">

    <!-- Product Categories -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
        <h2 class="text-center text-3xl mb-8 font-bold">Kategori Produk</h2>
        <div id="categories-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <p class="col-span-full text-center text-gray-500">Memuat kategori...</p>
        </div>
    </div>

    <!-- Products by Category -->
    <div id="products-section" class="max-w-7xl mx-auto px-4 sm:px-6 py-12" style="display: none;">
        <div class="flex items-center justify-between mb-8">
            <h2 id="category-title" class="text-3xl font-bold">Produk Kategori</h2>
            <button onclick="backToCategories()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Kategori
            </button>
        </div>
        <div id="products-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <p class="col-span-full text-center text-gray-500">Memuat produk...</p>
        </div>
    </div>
</main>

<script>
    function addToCart(id_produk) {
        fetch('/api/user/check-login')
            .then(response => response.json())
            .then(data => {
                if (data.logged_in) {
                    fetch('/api/cart/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ id_produk: id_produk, jumlah: 1 })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateCartCount();
                            Swal.fire({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                icon: 'success',
                                title: 'Produk berhasil ditambahkan ke keranjang!'
                            });
                        } else {
                            Swal.fire({
                                toast: true,
                                position: 'bottom-end',
                                showConfirmButton: false,
                                timer: 3000,
                                timerProgressBar: true,
                                icon: 'error',
                                title: data.message || 'Gagal menambahkan ke keranjang'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            toast: true,
                            position: 'bottom-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            icon: 'error',
                            title: 'Terjadi kesalahan'
                        });
                    });
                } else {
                    window.location.href = '/login?redirect=/cart';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    toast: true,
                    position: 'bottom-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    icon: 'error',
                    title: 'Terjadi kesalahan'
                });
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }

        function getImageUrl(imagePath) {
            return imagePath && imagePath !== 'NULL' ? `{{ asset('images/products') }}/${imagePath}` : 'https://via.placeholder.com/400x400.png?text=No+Image';
        }

        function getCategoryIcon(categoryName) {
            const categoryIcons = {
                'lukisan': 'fas fa-palette',
                'patung': 'fas fa-cube',
                'fotografi': 'fas fa-camera',
                'digital': 'fas fa-laptop',
                'kerajinan': 'fas fa-tools',
                'seni rupa': 'fas fa-paint-brush',
                'kaligrafi': 'fas fa-pen-fancy',
                'ukir': 'fas fa-gavel',
                'batik': 'fas fa-tshirt',
                'wayang': 'fas fa-theater-masks'
            };

            // Default icon if category not found
            return categoryIcons[categoryName.toLowerCase()] || 'fas fa-palette';
        }

        async function fetchCategories() {
            const container = document.getElementById('categories-container');

            try {
                const response = await fetch('/api/art/products/category/all');
                if (!response.ok) {
                    throw new Error('Gagal terhubung ke server.');
                }

                const result = await response.json();

                if (result.success && Array.isArray(result.data) && result.data.length > 0) {
                    const categories = result.data;
                    container.innerHTML = '';

                    categories.forEach(category => {
                        const categoryIcon = getCategoryIcon(category.nama_kategori);
                        const categoryCardHtml = `
                            <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-lg transition-shadow text-center cursor-pointer" onclick="showProductsByCategory(${category.id_kategori}, '${category.nama_kategori}')">
                                <div class="text-4xl mb-4 text-blue-500"><i class="${categoryIcon}"></i></div>
                                <h3 class="text-lg font-semibold mb-2">${category.nama_kategori}</h3>
                                <p class="text-gray-600">Koleksi seni kategori ${category.nama_kategori}</p>
                            </div>
                        `;
                        container.innerHTML += categoryCardHtml;
                    });
                } else {
                    container.innerHTML = '<p class="col-span-full text-center text-gray-500">Belum ada kategori yang tersedia.</p>';
                }
            } catch (error) {
                console.error('Error fetching categories:', error);
                container.innerHTML = `<p class="col-span-full text-center text-red-500">${error.message}</p>`;
            }
        }

        async function fetchFeaturedProducts() {
            const container = document.getElementById('featured-products-container');

            try {
                const response = await fetch('/api/art/products/all');
                if (!response.ok) {
                    throw new Error('Gagal terhubung ke server.');
                }

                const result = await response.json();

                if (result.success && Array.isArray(result.data) && result.data.length > 0) {
                    const products = result.data.slice(0, 8); // Show first 8 products
                    container.innerHTML = '';

                    products.forEach(product => {
                        const productCardHtml = `
                            <div class="bg-white p-6 flex flex-col items-center rounded-lg shadow-sm hover:shadow-xl transition-shadow duration-300">
                                <img alt="${product.nama_produk}" class="mb-6 w-full h-48 object-cover rounded-md" src="${getImageUrl(product.gambar_produk)}" />
                                <p class="text-gray-400 text-sm mb-1">${product.kode_produk}</p>
                                <p class="text-lg mb-1 font-medium">${product.nama_produk}</p>
                                <div class="flex flex-col items-center space-y-1 text-lg font-semibold mb-4">
                                    <span style="color: var(--primary-color);">${formatRupiah(product.harga_produk)}</span>
                                </div>
                                <div class="w-full flex flex-col space-y-2 mt-auto">
                                    <button onclick="addToCart(${product.id_produk})" class="btn-primary rounded-lg py-2 text-sm font-medium w-full">Tambah Ke Keranjang</button>
                                    <a href="/details/${product.kode_produk}/${product.slug_produk}" class="text-center rounded-lg py-2 text-sm font-medium w-full border border-gray-300 hover:bg-gray-100">Detail</a>
                                </div>
                            </div>
                        `;
                        container.innerHTML += productCardHtml;
                    });
                } else {
                    container.innerHTML = '<p class="col-span-full text-center text-gray-500">Belum ada produk yang tersedia.</p>';
                }
            } catch (error) {
                console.error('Error fetching products:', error);
                container.innerHTML = `<p class="col-span-full text-center text-red-500">${error.message}</p>`;
            }
        }

        fetchCategories();
        fetchFeaturedProducts();

        async function showProductsByCategory(id_kategori, nama_kategori) {
            // Hide categories section and show products section
            document.getElementById('categories-container').parentElement.style.display = 'none';
            document.getElementById('products-section').style.display = 'block';

            // Update category title
            document.getElementById('category-title').textContent = `Produk Kategori: ${nama_kategori}`;

            // Fetch and display products
            const productsContainer = document.getElementById('products-container');

            try {
                const response = await fetch(`/api/art/products/category/${id_kategori}`);
                if (!response.ok) {
                    throw new Error('Gagal terhubung ke server.');
                }

                const result = await response.json();

                if (result.success && Array.isArray(result.data) && result.data.length > 0) {
                    const products = result.data;
                    productsContainer.innerHTML = '';

                    products.forEach(product => {
                        const productCardHtml = `
                            <div class="bg-white p-6 flex flex-col items-center rounded-lg shadow-sm hover:shadow-xl transition-shadow duration-300">
                                <img alt="${product.nama_produk}" class="mb-6 w-full h-48 object-cover rounded-md" src="${getImageUrl(product.gambar_produk)}" />
                                <p class="text-gray-400 text-sm mb-1">${product.kode_produk}</p>
                                <p class="text-lg mb-1 font-medium">${product.nama_produk}</p>
                                <div class="flex flex-col items-center space-y-1 text-lg font-semibold mb-4">
                                    <span style="color: var(--primary-color);">${formatRupiah(product.harga_produk)}</span>
                                </div>
                                <div class="w-full flex flex-col space-y-2 mt-auto">
                                    <button onclick="addToCart(${product.id_produk})" class="btn-primary rounded-lg py-2 text-sm font-medium w-full">Tambah Ke Keranjang</button>
                                    <a href="/details/${product.kode_produk}/${product.slug_produk}" class="text-center rounded-lg py-2 text-sm font-medium w-full border border-gray-300 hover:bg-gray-100">Detail</a>
                                </div>
                            </div>
                        `;
                        productsContainer.innerHTML += productCardHtml;
                    });
                } else {
                    productsContainer.innerHTML = '<p class="col-span-full text-center text-gray-500">Belum ada produk dalam kategori ini.</p>';
                }
            } catch (error) {
                console.error('Error fetching products by category:', error);
                productsContainer.innerHTML = `<p class="col-span-full text-center text-red-500">${error.message}</p>`;
            }
        }

        function backToCategories() {
            // Show categories section and hide products section
            document.getElementById('categories-container').parentElement.style.display = 'block';
            document.getElementById('products-section').style.display = 'none';
        }

        // Make functions global so they can be called from onclick
        window.showProductsByCategory = showProductsByCategory;
        window.backToCategories = backToCategories;
    });
</script>
@include('partials.foot')
