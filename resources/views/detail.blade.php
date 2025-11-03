@include('partials.head')
<main class="max-w-[1200px] mx-auto px-6 py-12">
    <!-- Product Detail Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-start">
        <!-- Product Images -->
        <div class="flex flex-col gap-4">
            <div class="rounded-lg overflow-hidden">
                <img id="mainImage" alt="Product Image" class="w-full h-auto object-cover" src="" />
            </div>
        </div>

        <!-- Product Info -->
        <div>
            <p id="product-category" class="text-sm text-gray-500 mb-2">Loading...</p>
            <h1 id="product-title" class="font-display text-4xl font-bold mb-4">Loading...</h1>
            <div class="flex items-center gap-3 text-3xl font-semibold mb-6">
                <span id="product-price" style="color: var(--primary-color);">Loading...</span>
            </div>
            <p id="product-description" class="text-gray-600 leading-relaxed mb-6">
                Loading...
            </p>
            <div class="flex items-center gap-4 mb-8">
                <label for="quantity" class="font-medium">Jumlah:</label>
                <div class="flex items-center border border-gray-300 rounded-md">
                    <button type="button" onclick="decrementQuantity()" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-l-md">-</button>
                    <input type="number" id="quantity" name="quantity" min="1" value="1" class="w-20 text-center border-0 focus:ring-0">
                    <button type="button" onclick="incrementQuantity()" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-r-md">+</button>
                </div>
            </div>
            <button onclick="addToCart(mainProductId)" class="btn-primary w-full rounded-lg py-3 text-base font-medium">
                <i class="fas fa-shopping-cart mr-2"></i> Tambah Ke Keranjang
            </button>
        </div>
    </div>

    <!-- Related Products Section -->
    <div class="mt-24">
        <h2 class="text-center text-3xl mb-12 font-bold">Anda Mungkin Juga Suka</h2>
        <div id="related-products" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 max-w-6xl mx-auto">
            <!-- Related products will be loaded here -->
        </div>
    </div>
</main>
<script>
    let mainProductId = null;

    async function loadProductDetails() {
        const productCode = '{{ $product_code }}';
        const productSlug = '{{ $product_slug }}';
        const apiUrl = `/api/art/details/${productCode}/${productSlug}`;

        try {
            const response = await fetch(apiUrl);
            const data = await response.json();

            if (data.success && data.data.length > 0) {
                const product = data.data[0];
                mainProductId = product.id_produk;

                // Update category
                document.getElementById('product-category').textContent = product.nama_kategori || 'Unknown';

                // Update title
                document.getElementById('product-title').textContent = product.nama_produk;

                // Update price
                const price = 'Rp ' + product.harga_produk.toLocaleString('id-ID');
                document.getElementById('product-price').textContent = price;

                // Update description
                document.getElementById('product-description').textContent = product.detail_produk;

                // Set main image
                const imageUrl = product.gambar_produk ? `/images/products/${product.gambar_produk}` : '';
                document.getElementById('mainImage').src = imageUrl;
                document.getElementById('mainImage').alt = product.nama_produk;
            } else {
                console.error('Product not found');
            }
        } catch (error) {
            console.error('Error fetching product details:', error);
        }
    }

    function incrementQuantity() {
        const quantityInput = document.getElementById('quantity');
        quantityInput.value = parseInt(quantityInput.value) + 1;
    }

    function decrementQuantity() {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    }

    async function loadRelatedProducts() {
        const productCode = '{{ $product_code }}';
        const productSlug = '{{ $product_slug }}';
        const apiUrl = `/api/art/related/${productCode}/${productSlug}`;

        try {
            const response = await fetch(apiUrl);
            const data = await response.json();

            const relatedContainer = document.getElementById('related-products');

            if (data.success && data.data.length > 0) {
                let html = '';
                data.data.forEach(product => {
                    const imageUrl = product.gambar_produk ? `/images/products/${product.gambar_produk}` : 'https://via.placeholder.com/400x300?text=No+Image';
                    const price = 'Rp ' + product.harga_produk.toLocaleString('id-ID');

                    html += `
                        <div class="bg-white p-6 flex flex-col items-center rounded-lg shadow-sm hover:shadow-xl transition-shadow duration-300">
                            <img alt="${product.nama_produk}" class="mb-6 w-full h-48 object-cover rounded-md" src="${imageUrl}" />
                            <p class="text-gray-400 text-sm mb-1">${product.nama_kategori || 'Kategori'}</p>
                            <p class="text-lg mb-1 font-medium">${product.nama_produk}</p>
                            <div class="flex space-x-3 text-lg font-semibold mb-4">
                                <span style="color: var(--primary-color);">${price}</span>
                            </div>
                            <div class="w-full flex flex-col space-y-2 mt-auto">
                                <button onclick="addToCart(${product.id_produk})" class="btn-primary rounded-lg py-2 text-sm font-medium w-full">Tambah Ke Keranjang</button>
                                <a href="/details/${product.kode_produk}/${product.slug_produk}" class="rounded-lg py-2 text-sm font-medium w-full border border-gray-300 hover:bg-gray-100 text-center">Detail</a>
                            </div>
                        </div>
                    `;
                });
                relatedContainer.innerHTML = html;
            } else {
                relatedContainer.innerHTML = '<p class="text-center text-gray-500 col-span-full">Tidak ada produk terkait ditemukan.</p>';
            }
        } catch (error) {
            console.error('Error fetching related products:', error);
            document.getElementById('related-products').innerHTML = '<p class="text-center text-gray-500 col-span-full">Gagal memuat produk terkait.</p>';
        }
    }

    function addToCart(id_produk) {
        fetch('/api/user/check-login')
            .then(response => response.json())
            .then(data => {
                if (data.logged_in) {
                    const quantity = document.getElementById('quantity').value;
                    fetch('/api/cart/add', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ id_produk: id_produk, jumlah: parseInt(quantity) })
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

    function updateCartCount() {
        fetch('/api/cart/count')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const cartCountElement = document.querySelector('.cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = data.count;
                    }
                }
            })
            .catch(error => console.error('Error updating cart count:', error));
    }

    // Load product details on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadProductDetails().then(() => {
            loadRelatedProducts();
        });
    });
</script>
@include('partials.foot')
