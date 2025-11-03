@include('partials.head')
    <main class="max-w-[1200px] mx-auto px-6">
        <section class="relative mt-6 rounded-3xl overflow-hidden">
            <div
                class="absolute inset-0 bg-cover bg-center brightness-[0.35]"
                style="background-image: url({{ asset('images/hero/hero_2.png') }});">
            </div>

            <div class="relative flex flex-col justify-center px-6 py-10 md:py-16 md:px-14 flex-1 text-gray-800">
                <h1 class="font-bold text-4xl md:text-5xl leading-tight max-w-md text-[#fff]" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.4);">
                    Temukan karya seni <br /> terbaik untuk <br /> ruangan Anda.
                </h1>
                <p class="mt-4 text-lg max-w-md text-white" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.4);">
                    Setiap karya seni memiliki cerita, temukan yang paling berkesan untuk Anda.
                </p>
                <a href="/categories" class="mt-8 btn-primary rounded-lg px-8 py-3 w-max text-base font-medium shadow-lg inline-block">
                    Lihat Koleksi
                </a>
            </div>
        </section>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
            <h1 class="text-center text-4xl mb-12 font-bold">Produk Terpopuler</h1>

            <div id="popular-products-container" class="max-w-6xl mx-auto">
                <p class="text-center text-gray-500">Memuat produk terpopuler...</p>
            </div>

            <div class="mt-12 flex justify-center">
                <a href="#all-products-section" id="view-all-products-button" class="btn-primary rounded-full px-10 py-3 text-lg font-normal transition-colors shadow-sm">
                    Lihat Semua Produk
                </a>
            </div>
        </div>

        <div id="all-products-section" class="max-w-7xl mx-auto px-6 py-12">
            <h1 class="text-center text-4xl mb-12 font-bold">Semua Produk</h1>

            <div id="all-products-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 max-w-6xl mx-auto">
                <p class="col-span-full text-center text-gray-500">Memuat semua produk...</p>
            </div>

            <div class="mt-12 flex justify-center">
                <button id="load-more-button" class="btn-primary rounded-full px-10 py-3 text-lg font-normal transition-colors shadow-sm">
                    Muat Lebih Banyak
                </button>
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
                    Swal.fire({
                        toast: true,
                        position: 'top',
                        showConfirmButton: true,
                        confirmButtonText: 'Login',
                        icon: 'warning',
                        title: 'Anda harus login terlebih dahulu untuk menambahkan produk ke keranjang.',
                        timer: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '/login?redirect=/';
                        }
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
    }

    document.addEventListener('DOMContentLoaded', function() {
        let allProducts = [];
        let currentIndex = 0;
        const batchSize = 10;

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }

        function getImageUrl(imagePath) {
            return (imagePath && typeof imagePath === 'string' && imagePath.trim() !== '' && imagePath !== 'NULL') ? `{{ asset('images/products') }}/${imagePath}` : 'https://via.placeholder.com/400x400.png?text=No+Image';
        }

        function displayProducts(count) {
            const container = document.getElementById('all-products-container');
            const productsToShow = allProducts.slice(currentIndex, currentIndex + count);
            productsToShow.forEach(product => {
                const productCardHtml = `
                    <div class="bg-white p-6 flex flex-col items-center rounded-lg shadow-sm hover:shadow-xl transition-shadow duration-300">
                        <img alt="${product.nama_produk}" class="mb-6 w-full h-48 object-cover rounded-md" src="${getImageUrl(product.gambar_produk)}" />
                        <p class="text-gray-400 text-sm mb-1">${product.kode_produk}</p>
                        <p class="text-lg mb-1 font-medium">${product.nama_produk}</p>
                        <div class="flex space-x-3 text-lg font-semibold mb-4">
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
            currentIndex += count;
            const loadMoreButton = document.getElementById('load-more-button');
            if (currentIndex >= allProducts.length) {
                loadMoreButton.style.display = 'none';
            }
        }

        async function fetchPopularProducts() {
            const container = document.getElementById('popular-products-container');
            const viewAllButton = document.getElementById('view-all-products-button');

            try {
                const response = await fetch(`/api/art/products/popular/all`);
                if (!response.ok) {
                    throw new Error('Gagal terhubung ke server.');
                }

                const result = await response.json();

                // --- PERUBAHAN UTAMA LOGIKA DIMULAI DI SINI ---

                // 1. Kondisi Ideal: Sukses dan ada data produk di dalamnya
                if (result.success && Array.isArray(result.data) && result.data.length > 0) {
                    const products = result.data;
                    container.innerHTML = ''; // Hapus pesan "memuat"

                    if (products.length < 4) {
                        if (viewAllButton) viewAllButton.style.display = 'none';
                    }

                    const grid = document.createElement('div');
                    grid.className = 'grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8';

                    const mainProduct = products[0];
                    const mainProductHtml = `
                        <a href="/produk/${mainProduct.id_produk}" class="group relative block lg:col-span-2 h-[350px] md:h-[450px] rounded-2xl overflow-hidden shadow-lg">
                            <img src="${getImageUrl(mainProduct.gambar_produk)}" alt="${mainProduct.nama_produk}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-105" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                            <div class="relative h-full flex flex-col justify-end p-6 sm:p-8 text-white">
                                <p class="text-sm font-medium uppercase text-white/80">${mainProduct.kode_produk}</p>
                                <h3 class="text-3xl md:text-4xl font-bold mt-1" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">${mainProduct.nama_produk}</h3>
                                <div class="mt-4">
                                    <span class="text-2xl font-semibold" style="color: var(--primary-color);">${formatRupiah(mainProduct.harga_produk)}</span>
                                </div>
                            </div>
                        </a>
                    `;

                    let secondaryProductsHtml = '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-1 gap-6 lg:gap-8">';
                    if (products[1]) {
                        const product = products[1];
                        secondaryProductsHtml += `
                            <a href="/produk/${product.id_produk}" class="group relative block h-[250px] rounded-2xl overflow-hidden shadow-lg">
                                <img src="${getImageUrl(product.gambar_produk)}" alt="${product.nama_produk}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-105" />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                                <div class="relative h-full flex flex-col justify-end p-4 text-white">
                                    <p class="text-sm font-medium uppercase text-white/80">${product.kode_produk}</p>
                                    <h3 class="text-xl font-bold mt-1" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">${product.nama_produk}</h3>
                                    <div class="mt-2">
                                        <span class="text-lg font-semibold" style="color: var(--primary-color);">${formatRupiah(product.harga_produk)}</span>
                                    </div>
                                </div>
                            </a>
                        `;
                    }
                    if (products[2]) {
                        const product = products[2];
                        secondaryProductsHtml += `
                            <a href="/produk/${product.id_produk}" class="group relative block h-[250px] rounded-2xl overflow-hidden shadow-lg">
                                <img src="${getImageUrl(product.gambar_produk)}" alt="${product.nama_produk}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-105" />
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>
                                <div class="relative h-full flex flex-col justify-end p-4 text-white">
                                    <p class="text-sm font-medium uppercase text-white/80">${product.kode_produk}</p>
                                    <h3 class="text-xl font-bold mt-1" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">${product.nama_produk}</h3>
                                    <div class="mt-2">
                                        <span class="text-lg font-semibold" style="color: var(--primary-color);">${formatRupiah(product.harga_produk)}</span>
                                    </div>
                                </div>
                            </a>
                        `;
                    }
                    secondaryProductsHtml += '</div>';
                    grid.innerHTML = mainProductHtml + secondaryProductsHtml; // Anda bisa melengkapi bagian ini dari kode sebelumnya
                    container.appendChild(grid);

                // 2. Kondisi Data Kosong: API merespons success: false dan tidak ada info lain
                } else if (result.success === false && !result.data && !result.message) {
                    container.innerHTML = '<p class="text-center text-gray-500">Produk terpopuler tidak ditemukan.</p>';
                    if (viewAllButton) viewAllButton.style.display = 'none';

                // 3. Kondisi Lainnya: Dianggap sebagai error
                } else {
                    throw new Error(result.message || 'Gagal memuat data produk populer.');
                }

            } catch (error) {
                console.error('Error fetching popular products:', error);
                container.innerHTML = `<p class="text-center text-red-500">${error.message}</p>`;
                if (viewAllButton) viewAllButton.style.display = 'none';
            }
        }

        async function fetchAllProducts() {
            const container = document.getElementById('all-products-container');
            const viewAllButton = document.getElementById('view-all-products-button');
            const loadMoreButton = document.getElementById('load-more-button');

            try {
                const response = await fetch(`/api/art/products/all`);
                if (!response.ok) {
                    throw new Error('Gagal terhubung ke server.');
                }

                const result = await response.json();

                // --- PERUBAHAN UTAMA LOGIKA DIMULAI DI SINI ---

                // 1. Kondisi Ideal: Sukses dan ada data produk di dalamnya
                if (result.success && Array.isArray(result.data) && result.data.length > 0) {
                    const products = result.data;
                    container.innerHTML = '';

                    allProducts = products;
                    currentIndex = 0;
                    displayProducts(10);
                    if (allProducts.length <= 10) {
                        if (loadMoreButton) loadMoreButton.style.display = 'none';
                    }

                // 2. Kondisi Data Kosong: API merespons success: false dan tidak ada info lain
                } else if (result.success === false && !result.data && !result.message) {
                    container.innerHTML = '<p class="col-span-full text-center text-gray-500">Saat ini belum ada produk yang tersedia.</p>';
                    if (viewAllButton) viewAllButton.style.display = 'none';
                    if (loadMoreButton) loadMoreButton.style.display = 'none';

                // 3. Kondisi Lainnya: Dianggap sebagai error
                } else {
                    throw new Error(result.message || 'Gagal memuat semua produk.');
                }

            } catch (error) {
                console.error('Error fetching all products:', error);
                container.innerHTML = `<p class="col-span-full text-center text-red-500">${error.message}</p>`;
                if (viewAllButton) viewAllButton.style.display = 'none';
                if (loadMoreButton) loadMoreButton.style.display = 'none';
            }
        }

        fetchPopularProducts();
        fetchAllProducts();

        document.getElementById('load-more-button').addEventListener('click', () => {
            displayProducts(10);
        });

    });
</script>
@include('partials.foot')
