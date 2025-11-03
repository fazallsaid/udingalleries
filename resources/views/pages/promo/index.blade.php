@include('partials.head')
<main class="max-w-[1200px] mx-auto px-6">
    <!-- Hero Section for Promotions -->
    <section class="relative mt-6 rounded-3xl overflow-hidden bg-gradient-to-r from-red-500 to-pink-500">
        <div class="relative flex flex-col justify-center px-6 py-10 md:py-16 md:px-14 flex-1 text-white">
            <h1 class="font-bold text-4xl md:text-5xl leading-tight max-w-md">
                Promo Spesial <br /> Diskon Hingga 50%!
            </h1>
            <p class="mt-4 text-lg max-w-md">
                Jangan lewatkan kesempatan untuk mendapatkan karya seni impian Anda dengan harga terbaik.
            </p>
            <button class="mt-8 btn-primary rounded-lg px-8 py-3 w-max text-base font-medium shadow-lg">
                Lihat Promo Hari Ini
            </button>
        </div>
    </section>

    <!-- Promo Categories -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-12">
        <h2 class="text-center text-3xl mb-8 font-bold">Kategori Promo</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-lg transition-shadow text-center">
                <div class="text-4xl mb-4 text-red-500"><i class="fas fa-percent"></i></div>
                <h3 class="text-lg font-semibold mb-2">Diskon Harga</h3>
                <p class="text-gray-600">Potongan harga hingga 50% untuk produk terpilih</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-lg transition-shadow text-center">
                <div class="text-4xl mb-4 text-blue-500"><i class="fas fa-gift"></i></div>
                <h3 class="text-lg font-semibold mb-2">Paket Bundling</h3>
                <p class="text-gray-600">Beli lebih dari satu produk dan dapatkan diskon tambahan</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-lg transition-shadow text-center">
                <div class="text-4xl mb-4 text-green-500"><i class="fas fa-truck"></i></div>
                <h3 class="text-lg font-semibold mb-2">Gratis Ongkir</h3>
                <p class="text-gray-600">Pengiriman gratis untuk pembelian minimal Rp 500.000</p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-lg transition-shadow text-center">
                <div class="text-4xl mb-4 text-purple-500"><i class="fas fa-star"></i></div>
                <h3 class="text-lg font-semibold mb-2">Produk Terlaris</h3>
                <p class="text-gray-600">Koleksi seni terlaris dengan harga spesial</p>
            </div>
        </div>
    </div>

    <!-- Featured Promo Products -->
    <div class="max-w-7xl mx-auto px-6 py-12">
        <h2 class="text-center text-3xl mb-8 font-bold">Produk Promo Hari Ini</h2>
        <div id="promo-products-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 max-w-6xl mx-auto">
            <p class="col-span-full text-center text-gray-500">Memuat produk promo...</p>
        </div>
        <div class="mt-12 flex justify-center">
            <button id="load-more-promo-button" class="btn-primary rounded-full px-10 py-3 text-lg font-normal transition-colors shadow-sm">
                Lihat Lebih Banyak Promo
            </button>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl p-8 text-center text-white">
            <h3 class="text-2xl font-bold mb-4">Jangan Lewatkan Kesempatan!</h3>
            <p class="text-lg mb-6">Daftar sekarang dan dapatkan notifikasi promo terbaru langsung di email Anda.</p>
            <button class="btn-primary rounded-lg px-8 py-3 text-base font-medium shadow-lg">
                Daftar Newsletter
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
        let promoProducts = [];
        let currentPromoIndex = 0;
        const promoBatchSize = 8;

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(number);
        }

        function getImageUrl(imagePath) {
            return imagePath && imagePath !== 'NULL' ? `{{ asset('storage/') }}/${imagePath}` : 'https://via.placeholder.com/400x400.png?text=No+Image';
        }

        function displayPromoProducts(count) {
            const container = document.getElementById('promo-products-container');
            const productsToShow = promoProducts.slice(currentPromoIndex, currentPromoIndex + count);
            productsToShow.forEach(product => {
                const discount = Math.floor(Math.random() * 30) + 10; // Random discount 10-40%
                const originalPrice = product.harga_produk;
                const discountedPrice = originalPrice * (1 - discount / 100);

                const productCardHtml = `
                    <div class="bg-white p-6 flex flex-col items-center rounded-lg shadow-sm hover:shadow-xl transition-shadow duration-300 relative">
                        <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold">
                            -${discount}%
                        </div>
                        <img alt="${product.nama_produk}" class="mb-6 w-full h-48 object-cover rounded-md" src="${getImageUrl(product.gambar_produk)}" />
                        <p class="text-gray-400 text-sm mb-1">${product.kode_produk}</p>
                        <p class="text-lg mb-1 font-medium">${product.nama_produk}</p>
                        <div class="flex flex-col items-center space-y-1 text-lg font-semibold mb-4">
                            <span class="text-gray-500 line-through text-sm">${formatRupiah(originalPrice)}</span>
                            <span style="color: var(--primary-color);">${formatRupiah(discountedPrice)}</span>
                        </div>
                        <div class="w-full flex flex-col space-y-2 mt-auto">
                            <button onclick="addToCart(${product.id_produk})" class="btn-primary rounded-lg py-2 text-sm font-medium w-full">Tambah Ke Keranjang</button>
                            <a href="/details/${product.kode_produk}/${product.slug_produk}" class="text-center rounded-lg py-2 text-sm font-medium w-full border border-gray-300 hover:bg-gray-100">Detail</a>
                        </div>
                    </div>
                `;
                container.innerHTML += productCardHtml;
            });
            currentPromoIndex += count;
            const loadMoreButton = document.getElementById('load-more-promo-button');
            if (currentPromoIndex >= promoProducts.length) {
                loadMoreButton.style.display = 'none';
            }
        }

        async function fetchPromoProducts() {
            const container = document.getElementById('promo-products-container');
            const loadMoreButton = document.getElementById('load-more-promo-button');

            try {
                const response = await fetch('/api/art/products/all');
                if (!response.ok) {
                    throw new Error('Gagal terhubung ke server.');
                }

                const result = await response.json();

                if (result.success && Array.isArray(result.data) && result.data.length > 0) {
                    const products = result.data;
                    container.innerHTML = '';

                    // Simulate promo products by taking first 12 products
                    promoProducts = products.slice(0, 12);
                    currentPromoIndex = 0;
                    displayPromoProducts(8);
                    if (promoProducts.length <= 8) {
                        if (loadMoreButton) loadMoreButton.style.display = 'none';
                    }
                } else {
                    container.innerHTML = '<p class="col-span-full text-center text-gray-500">Saat ini belum ada produk promo yang tersedia.</p>';
                    if (loadMoreButton) loadMoreButton.style.display = 'none';
                }
            } catch (error) {
                console.error('Error fetching promo products:', error);
                container.innerHTML = `<p class="col-span-full text-center text-red-500">${error.message}</p>`;
                if (loadMoreButton) loadMoreButton.style.display = 'none';
            }
        }

        fetchPromoProducts();

        document.getElementById('load-more-promo-button').addEventListener('click', () => {
            displayPromoProducts(8);
        });
    });
</script>
@include('partials.foot')
