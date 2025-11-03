@include('partials.head')
<main class="max-w-[1200px] mx-auto px-6 py-12">
    <h1 class="font-display text-4xl font-bold mb-8 text-center">Keranjang Belanja Anda</h1>

    <div class="flex flex-col lg:flex-row gap-12">
        <!-- Cart Items -->
        <div id="cart-items-container" class="flex-grow">
            <p class="text-center text-gray-500">Memuat keranjang...</p>

             <!-- Empty Cart Message -->
            <div id="empty-cart" class="hidden text-center py-16">
                <i class="fas fa-shopping-cart fa-4x text-gray-300 mb-4"></i>
                <h2 class="text-2xl font-semibold mb-2">Keranjang Anda Kosong</h2>
                <p class="text-gray-500 mb-6">Sepertinya Anda belum menambahkan produk apapun.</p>
                <a href="/" class="btn-primary rounded-lg px-8 py-3 text-base font-medium">
                    Mulai Belanja
                </a>
            </div>
        </div>

        <!-- Order Summary -->
        <div id="order-summary" class="w-full lg:w-80 flex-shrink-0">
            <div class="bg-white rounded-lg shadow-sm p-6 sticky top-12">
                <h2 class="font-display text-2xl font-bold mb-6">Ringkasan Pesanan</h2>
                <div class="space-y-4 text-gray-600">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span id="summary-subtotal" class="font-medium">Rp 8.300.000</span>
                    </div>
                    <div class="border-t border-gray-200 my-4"></div>
                    <div class="flex justify-between font-bold text-lg text-gray-800">
                        <span>Total</span>
                        <span id="summary-total">Rp 8.350.000</span>
                    </div>
                </div>

                <button class="btn-primary w-full rounded-lg py-3 text-base font-medium mt-6" onclick="window.location.href='{{url('/checkout')}}'">
                    Lanjutkan ke Pembayaran
                </button>
            </div>
        </div>
    </div>
</main>
<script>
   const shippingCost = 0;

   function formatCurrency(amount) {
       return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount);
   }

   function getImageUrl(imagePath) {
       return imagePath && imagePath !== 'NULL' ? `{{ asset('images/products') }}/${imagePath}` : 'https://via.placeholder.com/400x400.png?text=No+Image';
   }

   function loadCart() {
       fetch('/api/cart/all')
           .then(response => response.json())
           .then(data => {
               const container = document.getElementById('cart-items-container');
               if (data.success && data.data.length > 0) {
                   container.innerHTML = '';
                   data.data.forEach(product => {
                       const itemHtml = `
                           <div class="cart-item flex flex-col sm:flex-row items-center gap-6 p-4 border-b border-gray-200" data-id="${product.id_produk}">
                               <img src="${getImageUrl(product.gambar_produk)}" alt="${product.nama_produk}" class="w-32 h-32 object-cover rounded-lg">
                               <div class="flex-grow text-center sm:text-left">
                                   <h3 class="font-medium text-lg">${product.nama_produk}</h3>
                                   <p class="text-sm text-gray-500">${product.kode_produk}</p>
                               </div>
                               <div class="flex items-center gap-4">
                                   <button onclick="updateQuantity(this, -1)" class="w-8 h-8 rounded-full border border-gray-300 hover:bg-gray-100">-</button>
                                   <span class="quantity font-medium">${product.jumlah}</span>
                                   <button onclick="updateQuantity(this, 1)" class="w-8 h-8 rounded-full border border-gray-300 hover:bg-gray-100">+</button>
                               </div>
                               <div class="font-semibold w-32 text-center">
                                   <span class="item-price" data-price="${product.harga_produk}">${formatCurrency(product.harga_produk * product.jumlah)}</span>
                               </div>
                               <button onclick="removeItem(this)" class="text-gray-400 hover:text-red-500">
                                   <i class="fas fa-trash-alt fa-lg"></i>
                               </button>
                           </div>
                       `;
                       container.innerHTML += itemHtml;
                   });
                   updateSummary();
               } else {
                   container.innerHTML = '';
                   const emptyCart = document.getElementById('empty-cart');
                   const orderSummary = document.getElementById('order-summary');
                   if (emptyCart) emptyCart.classList.remove('hidden');
                   if (orderSummary) orderSummary.classList.add('hidden');
               }
           })
           .catch(error => {
               console.error('Error loading cart:', error);
               document.getElementById('cart-items-container').innerHTML = '<p class="text-center text-red-500">Gagal memuat keranjang.</p>';
           });
   }

   function updateSummary() {
       const items = document.querySelectorAll('.cart-item');
       let subtotal = 0;

       if (items.length === 0) {
           const emptyCart = document.getElementById('empty-cart');
           const orderSummary = document.getElementById('order-summary');
           if (emptyCart) emptyCart.classList.remove('hidden');
           if (orderSummary) orderSummary.classList.add('hidden'); // Hide summary
       } else {
            const emptyCart = document.getElementById('empty-cart');
            const orderSummary = document.getElementById('order-summary');
            if (emptyCart) emptyCart.classList.add('hidden');
            if (orderSummary) orderSummary.classList.remove('hidden'); // Show summary
       }

       items.forEach(item => {
           const priceElement = item.querySelector('.item-price');
           const quantityElement = item.querySelector('.quantity');
           const price = parseFloat(priceElement.dataset.price);
           const quantity = parseInt(quantityElement.textContent);
           subtotal += price * quantity;
       });

       const total = subtotal;

       const subtotalEl = document.getElementById('summary-subtotal');
       const totalEl = document.getElementById('summary-total');
       if (subtotalEl) subtotalEl.textContent = formatCurrency(subtotal);
       if (totalEl) totalEl.textContent = formatCurrency(total);
   }

   function updateQuantity(button, change) {
       const item = button.closest('.cart-item');
       const quantityElement = item.querySelector('.quantity');
       const priceElement = item.querySelector('.item-price');
       const id_produk = item.dataset.id;

       let quantity = parseInt(quantityElement.textContent);
       quantity += change;

       if (quantity < 1) {
           quantity = 1;
       }

       // Update via API
       fetch('/api/cart/update', {
           method: 'POST',
           headers: {
               'Content-Type': 'application/json',
               'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
           },
           body: JSON.stringify({ id_produk: id_produk, jumlah: quantity })
       })
       .then(response => response.json())
       .then(data => {
           if (data.success) {
               quantityElement.textContent = quantity;
               const price = parseFloat(priceElement.dataset.price);
               priceElement.textContent = formatCurrency(price * quantity);
               updateSummary();
               Swal.fire({
                   toast: true,
                   position: 'bottom-end',
                   icon: 'success',
                   title: 'Jumlah berhasil diperbarui',
                   showConfirmButton: false,
                   timer: 3000
               });
           } else {
               Swal.fire({
                   toast: true,
                   position: 'bottom-end',
                   icon: 'error',
                   title: 'Gagal memperbarui jumlah',
                   showConfirmButton: false,
                   timer: 3000
               });
           }
       })
       .catch(error => {
           console.error('Error:', error);
           Swal.fire({
               toast: true,
               position: 'bottom-end',
               icon: 'error',
               title: 'Terjadi kesalahan',
               showConfirmButton: false,
               timer: 3000
           });
       });
   }

   function removeItem(button) {
       const item = button.closest('.cart-item');
       const id_produk = item.dataset.id;

       Swal.fire({
           title: 'Konfirmasi Hapus',
           text: 'Apakah Anda yakin ingin menghapus item ini dari keranjang?',
           icon: 'warning',
           showCancelButton: true,
           confirmButtonColor: '#d33',
           cancelButtonColor: '#3085d6',
           confirmButtonText: 'Ya, Hapus',
           cancelButtonText: 'Batal'
       }).then((result) => {
           if (result.isConfirmed) {
               // Remove via API
               fetch('/api/cart/remove', {
                   method: 'POST',
                   headers: {
                       'Content-Type': 'application/json',
                       'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                   },
                   body: JSON.stringify({ id_produk: id_produk })
               })
               .then(response => response.json())
               .then(data => {
                   if (data.success) {
                       item.remove();
                       updateSummary();
                       Swal.fire({
                           toast: true,
                           position: 'bottom-end',
                           icon: 'success',
                           title: 'Item berhasil dihapus',
                           showConfirmButton: false,
                           timer: 3000
                       });
                   } else {
                       Swal.fire({
                           toast: true,
                           position: 'bottom-end',
                           icon: 'error',
                           title: 'Gagal menghapus item',
                           showConfirmButton: false,
                           timer: 3000
                       });
                   }
               })
               .catch(error => {
                   console.error('Error:', error);
                   Swal.fire({
                       toast: true,
                       position: 'bottom-end',
                       icon: 'error',
                       title: 'Terjadi kesalahan',
                       showConfirmButton: false,
                       timer: 3000
                   });
               });
           }
       });
   }

   // Load cart on page load
   document.addEventListener('DOMContentLoaded', loadCart);
</script>
@include('partials.foot')
