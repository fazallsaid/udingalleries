@include('partials.head')
<main class="max-w-[1200px] mx-auto px-6 py-12">
    <h1 class="text-center text-4xl mb-12 font-bold">Daftar Produk</h1>

    <div id="products-container" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 max-w-6xl mx-auto">
        <p class="col-span-full text-center text-gray-500">Memuat produk...</p>
    </div>

    <div id="pagination-container" class="mt-12 flex justify-center items-center space-x-2">
        <!-- Pagination controls will be inserted here by JavaScript -->
    </div>
</main>
<script>
    let allProducts = [];
    let currentPage = 1;
    const productsPerPage = 12;

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

    function displayProducts(products) {
        const container = document.getElementById('products-container');
        container.innerHTML = '';

        if (products.length === 0) {
            container.innerHTML = '<p class="col-span-full text-center text-gray-500">Tidak ada produk ditemukan.</p>';
            return;
        }

        products.forEach(product => {
            const productCardHtml = `
                <div class="bg-white p-6 flex flex-col items-center rounded-lg shadow-sm hover:shadow-xl transition-shadow duration-300">
                    <img alt="${product.nama_produk}" class="mb-6 w-full h-48 object-cover rounded-md" src="${getImageUrl(product.gambar_produk)}" />
                    <p class="text-gray-400 text-sm mb-1">${product.kode_produk}</p>
                    <p class="text-lg mb-1 font-medium text-center">${product.nama_produk}</p>
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
    }

    function renderPagination(totalPages) {
        const container = document.getElementById('pagination-container');
        container.innerHTML = '';

        if (totalPages <= 1) return;

        // Previous button
        const prevButton = document.createElement('button');
        prevButton.textContent = 'Sebelumnya';
        prevButton.className = `px-4 py-2 rounded-lg border ${currentPage === 1 ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'bg-white hover:bg-gray-50'}`;
        prevButton.disabled = currentPage === 1;
        prevButton.onclick = () => {
            if (currentPage > 1) {
                currentPage--;
                showPage(currentPage);
            }
        };
        container.appendChild(prevButton);

        // Page numbers
        const maxVisiblePages = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }

        for (let i = startPage; i <= endPage; i++) {
            const pageButton = document.createElement('button');
            pageButton.textContent = i;
            pageButton.className = `px-4 py-2 rounded-lg border ${i === currentPage ? 'bg-[var(--primary-color)] text-white' : 'bg-white hover:bg-gray-50'}`;
            pageButton.onclick = () => {
                currentPage = i;
                showPage(currentPage);
            };
            container.appendChild(pageButton);
        }

        // Next button
        const nextButton = document.createElement('button');
        nextButton.textContent = 'Selanjutnya';
        nextButton.className = `px-4 py-2 rounded-lg border ${currentPage === totalPages ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'bg-white hover:bg-gray-50'}`;
        nextButton.disabled = currentPage === totalPages;
        nextButton.onclick = () => {
            if (currentPage < totalPages) {
                currentPage++;
                showPage(currentPage);
            }
        };
        container.appendChild(nextButton);
    }

    function showPage(page) {
        const startIndex = (page - 1) * productsPerPage;
        const endIndex = startIndex + productsPerPage;
        const productsToShow = allProducts.slice(startIndex, endIndex);
        displayProducts(productsToShow);
        renderPagination(Math.ceil(allProducts.length / productsPerPage));
    }

    async function fetchProducts() {
        try {
            const response = await fetch('/api/art/products/all');
            if (!response.ok) {
                throw new Error('Gagal terhubung ke server.');
            }

            const result = await response.json();

            if (result.success && Array.isArray(result.data) && result.data.length > 0) {
                allProducts = result.data;
                showPage(1);
            } else {
                document.getElementById('products-container').innerHTML = '<p class="col-span-full text-center text-gray-500">Saat ini belum ada produk yang tersedia.</p>';
            }
        } catch (error) {
            console.error('Error fetching products:', error);
            document.getElementById('products-container').innerHTML = `<p class="col-span-full text-center text-red-500">${error.message}</p>`;
        }
    }

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
                            window.location.href = '/login?redirect=/products';
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
        fetch('/api/cart/all')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    const count = data.data.reduce((sum, item) => sum + item.jumlah, 0);
                    const badge = document.getElementById('cart-count');
                    if (count > 0) {
                        badge.textContent = count;
                        badge.style.display = 'inline-block';
                    } else {
                        badge.style.display = 'none';
                    }
                }
            })
            .catch(error => console.error('Error fetching cart:', error));
    }

    document.addEventListener('DOMContentLoaded', function() {
        fetchProducts();
        @if(session('userLogin'))
        updateCartCount();
        @endif
    });
</script>
@include('partials.foot')
