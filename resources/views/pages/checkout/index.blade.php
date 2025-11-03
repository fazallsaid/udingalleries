@include('partials.head')
<main class="max-w-[1200px] mx-auto px-6 py-12">
    <h1 class="font-display text-4xl font-bold mb-8 text-center">Checkout</h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm p-8">
                <h2 class="font-display text-2xl font-bold mb-6">Alamat Pengiriman</h2>
                <form class="space-y-4" id="checkoutForm">
                    <div>
                        <label for="fullname" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" id="fullname" name="fullname" class="form-input" value="{{ $user->nama_tampilan ?? '' }}" placeholder="Contoh: Budi Santoso" readonly>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                        <input type="email" id="email" name="email" class="form-input" value="{{ $user->email ?? '' }}" placeholder="Contoh: budi.s@example.com" readonly>
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                        <input type="tel" id="phone" name="phone" class="form-input" value="{{ $user->nomor_whatsapp ?? '' }}" placeholder="Contoh: 081234567890" readonly>
                    </div>

                    <div id="addressSelectionContainer">
                        </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 hidden" id="cityPostcodeContainer">
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                            <input type="text" id="city" name="city" class="form-input" placeholder="Contoh: Jakarta Selatan" readonly>
                        </div>
                        <div>
                            <label for="postcode" class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                            <input type="text" id="postcode" name="postcode" class="form-input" placeholder="Contoh: 12190" readonly>
                        </div>
                    </div>

                </form>

                <div class="block lg:hidden">
                    <h2 class="font-display text-2xl font-bold mb-6">Pesanan Anda</h2>
                    <div id="cartItemsMobile" class="space-y-4">
                    </div>
                    <div id="shippingSectionMobile" class="space-y-4 text-gray-600 mt-6">
                        <div class="flex justify-between">
                            <span>Pengiriman</span>
                            <span id="shipping-mobile" class="font-medium">Rp 0</span>
                        </div>
                    </div>
                </div>

                <h2 class="font-display text-2xl font-bold mt-10 mb-6">Metode Pembayaran</h2>
                <div class="space-y-4">
                    <div class="flex items-center p-4 border border-gray-300 rounded-md cursor-pointer hover:border-gray-500" id="checkoutBankSelectionBox">
                        <input type="radio" name="payment" value="bank_transfer" class="h-4 w-4 text-gray-800 focus:ring-gray-600" checked>
                        <span class="ml-4 font-medium" id="checkoutSelectedBankText">Transfer Bank</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full">
            <div class="bg-white rounded-lg p-6 fixed bottom-[4rem] left-4 right-4 z-50 lg:static lg:sticky lg:top-12 lg:left-auto lg:right-auto lg:bottom-auto lg:z-auto" style="box-shadow: 0px 0px 26px 1px #c4c4c4;">
                <h2 class="hidden lg:block font-display text-2xl font-bold mb-6">Pesanan Anda</h2>
                <div id="cartItems" class="hidden lg:block space-y-4">
                    <div class="text-center text-gray-500 py-8">
                        <p>Loading cart items...</p>
                    </div>
                </div>

                <div class="space-y-4 text-gray-600 mt-6">
                    <div class="hidden">
                        <span>Subtotal</span>
                        <span id="subtotal" class="font-medium">Rp 0</span>
                    </div>
                    <div class="hidden lg:flex justify-between">
                        <span>Pengiriman</span>
                        <span id="shipping" class="font-medium">Rp 0</span>
                    </div>
                    <div class="border-t border-gray-200 my-4"></div>
                    <div class="flex justify-between font-bold text-lg text-gray-800">
                        <span>Total</span>
                        <span id="total">Rp 0</span>
                    </div>
                </div>
                <button class="btn-primary w-full rounded-lg py-3 text-base font-medium mt-6" type="submit" id="createOrderBtn">
                    Buat Pesanan
                </button>
            </div>
        </div>
    </div>
</main>

<div id="addressModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-[60] flex items-end justify-center p-4 md:items-center md:justify-center">
    <div id="modalContent" class="relative w-full md:w-1/2 max-h-[70vh] overflow-hidden rounded-t-lg md:rounded-md bg-white shadow-lg flex flex-col">
        <div class="p-5 border-b border-gray-200">
            <h3 class="text-lg leading-6 font-medium text-gray-900 text-center">Tambah Alamat Baru</h3>
        </div>

        <div class="px-7 py-3 space-y-4 text-left overflow-y-auto custom-scrollbar">
            <div>
                <label for="province-modal" class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                <select id="province-modal" class="form-input"></select>
            </div>
            <div>
                <label for="city-modal" class="block text-sm font-medium text-gray-700 mb-1">Kota/Kabupaten</label>
                <select id="city-modal" class="form-input"></select>
            </div>
            <div>
                <label for="district-modal" class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
                <select id="district-modal" class="form-input"></select>
            </div>
            <div>
                <label for="address-modal" class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap (Nama Jalan, RT/RW, Kelurahan)</label>
                <textarea id="address-modal" rows="3" class="form-input" placeholder="Contoh: Jl. Sudirman No. 12, RT 01/RW 02, Kel. Kebayoran Lama"></textarea>
            </div>
            <div>
                <label for="postcode-modal" class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                <input type="text" id="postcode-modal" class="form-input" placeholder="Contoh: 12190">
            </div>
        </div>

        <div class="p-4 border-t border-gray-200">
            <div class="items-center">
                <button id="saveAddressBtn" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Simpan Alamat
                </button>
                <button id="closeModalBtn" class="px-4 py-2 bg-gray-200 text-gray-700 text-base font-medium rounded-md w-full shadow-sm mt-2 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userId = {{ session('userId') ?? 'null' }};
    const addressSelectionContainer = document.getElementById('addressSelectionContainer');
    const cityPostcodeContainer = document.getElementById('cityPostcodeContainer');
    const addressModal = document.getElementById('addressModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const saveAddressBtn = document.getElementById('saveAddressBtn');

    // Modal elements for Wilayah.id
    const provinceSelect = document.getElementById('province-modal');
    const citySelect = document.getElementById('city-modal');
    const districtSelect = document.getElementById('district-modal');
    const addressInput = document.getElementById('address-modal');
    const postcodeInput = document.getElementById('postcode-modal');

    // Load cart items
    loadCartItems();

    // Load addresses
    loadUserAddresses();

    // Load selected bank from localStorage
    loadSelectedBank();

    // Add click event listener to bank selection box
    document.getElementById('checkoutBankSelectionBox').addEventListener('click', openBankModal);

    // Fungsi untuk memuat alamat pengguna
    function loadUserAddresses() {
        if (!userId) {
            showAddAddressButton();
            return;
        }

        fetch(`/api/users/address/${userId}/all`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.length > 0) {
                    // Jika ada alamat, tampilkan list dengan checkbox
                    const addresses = data.data;
                    const container = document.createElement('div');
                    container.className = 'space-y-3';

                    const label = document.createElement('label');
                    label.className = 'block text-sm font-medium text-gray-700 mb-3';
                    label.textContent = 'Pilih Alamat Pengiriman';

                    container.appendChild(label);

                    addresses.forEach(address => {
                        const addressDiv = document.createElement('div');
                        addressDiv.className = 'flex items-start space-x-3 p-4 border border-gray-200 rounded-lg hover:border-gray-300 cursor-pointer';
                        addressDiv.dataset.addressId = address.id_alamat;

                        const radioInput = document.createElement('input');
                        radioInput.type = 'radio';
                        radioInput.name = 'selected_address';
                        radioInput.value = address.id_alamat;
                        radioInput.className = 'mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500';
                        radioInput.checked = address.is_primary == 1; // Check if primary

                        const contentDiv = document.createElement('div');
                        contentDiv.className = 'flex-1';

                        const addressText = document.createElement('div');
                        addressText.className = 'text-sm text-gray-900';
                        addressText.textContent = `${address.alamat}, ${address.nama_kecamatan}, ${address.nama_kota}, ${address.nama_provinsi} ${address.kode_pos}`;

                        const primaryCheckboxDiv = document.createElement('div');
                        primaryCheckboxDiv.className = 'mt-2';

                        const primaryCheckbox = document.createElement('label');
                        primaryCheckbox.className = 'inline-flex items-center text-xs text-gray-600';

                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.className = 'h-3 w-3 text-blue-600 focus:ring-blue-500 rounded';
                        checkbox.checked = address.is_primary == 1;
                        checkbox.addEventListener('change', function() {
                            setPrimaryAddress(address.id_alamat);
                        });

                        const checkboxText = document.createElement('span');
                        checkboxText.className = 'ml-1';
                        checkboxText.textContent = 'Jadikan alamat utama';

                        primaryCheckbox.appendChild(checkbox);
                        primaryCheckbox.appendChild(checkboxText);
                        primaryCheckboxDiv.appendChild(primaryCheckbox);

                        contentDiv.appendChild(addressText);
                        contentDiv.appendChild(primaryCheckboxDiv);

                        addressDiv.appendChild(radioInput);
                        addressDiv.appendChild(contentDiv);

                        // Add click handler to select address
                        addressDiv.addEventListener('click', function(e) {
                            if (e.target.type !== 'checkbox') {
                                radioInput.checked = true;
                                handleAddressSelection({ target: { value: address.id_alamat, dataset: { address: address.alamat, city: address.nama_kota, postcode: address.kode_pos } } });
                            }
                        });

                        container.appendChild(addressDiv);
                    });

                    // Tambahkan tombol untuk menambahkan alamat baru
                    const addButton = document.createElement('button');
                    addButton.id = 'addAddressBtn';
                    addButton.className = 'btn-primary w-full rounded-lg py-2 mt-3';
                    addButton.textContent = 'Tambah Alamat Baru';
                    addButton.type = 'button';
                    addButton.addEventListener('click', openModal);

                    container.appendChild(addButton);

                    addressSelectionContainer.innerHTML = '';
                    addressSelectionContainer.appendChild(container);

                    // Check if there's a primary address and calculate shipping cost
                    const primaryAddress = addresses.find(addr => addr.is_primary == 1);
                    if (primaryAddress) {
                        calculateShippingCost(primaryAddress.id_alamat);
                    } else {
                        // No primary address selected, reset shipping cost
                        resetShippingCost();
                    }

                } else {
                    // Jika tidak ada alamat, tampilkan tombol "Tambah Alamat"
                    showAddAddressButton();
                }
            })
            .catch(error => {
                console.error('Error fetching addresses:', error);
                showAddAddressButton();
            });
    }

    // Fungsi untuk menampilkan tombol tambah alamat
    function showAddAddressButton() {
        const button = document.createElement('button');
        button.id = 'addAddressBtn';
        button.className = 'btn-primary w-full rounded-lg py-2 mt-2';
        button.textContent = 'Tambah Alamat';
        button.type = 'button';

        const label = document.createElement('label');
        label.className = 'block text-sm font-medium text-gray-700 mb-1';
        label.textContent = 'Alamat Pengiriman';

        addressSelectionContainer.innerHTML = '';
        addressSelectionContainer.appendChild(label);
        addressSelectionContainer.appendChild(button);

        cityPostcodeContainer.classList.add('hidden');

        // Reset shipping cost when no addresses exist
        resetShippingCost();

        button.addEventListener('click', openModal);
    }

    // Fungsi untuk menangani perubahan pada radio button alamat
    function handleAddressSelection(event) {
        if (event.target.value) {
            cityPostcodeContainer.classList.remove('hidden');
            document.getElementById('city').value = event.target.dataset.city;
            document.getElementById('postcode').value = event.target.dataset.postcode;

            // Calculate shipping cost for selected address
            calculateShippingCost(event.target.value);
        } else {
            cityPostcodeContainer.classList.add('hidden');
            // Reset shipping cost when no address is selected
            resetShippingCost();
        }
    }

    // Fungsi untuk membuka modal
    function openModal() {
        addressModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Mencegah scrolling di belakang modal
        loadProvinces();
    }

    // Fungsi untuk menutup modal
    function closeModal() {
        addressModal.classList.add('hidden');
        document.body.style.overflow = 'auto'; // Mengaktifkan kembali scrolling
    }

    // Event listener untuk tombol dan modal
    closeModalBtn.addEventListener('click', closeModal);
    addressModal.addEventListener('click', function(e) {
        if (!e.target.closest('#modalContent')) {
            closeModal();
        }
    });

    // Handle form submission
    saveAddressBtn.addEventListener('click', function(e) {
        // Validasi form
        if (!provinceSelect.value || !citySelect.value || !districtSelect.value || !addressInput.value.trim() || !postcodeInput.value.trim()) {
            Swal.fire('Peringatan!', 'Semua field harus diisi!', 'warning');
            return;
        }

        // Show loading
        saveAddressBtn.disabled = true;
        saveAddressBtn.textContent = 'Menyimpan...';

        // Validasi form dan kirim data ke API
        const newAddress = {
            id_user: userId,
            alamat: addressInput.value.trim(),
            nama_provinsi: provinceSelect.options[provinceSelect.selectedIndex].text,
            nama_kota: citySelect.options[citySelect.selectedIndex].text,
            nama_kecamatan: districtSelect.options[districtSelect.selectedIndex].text,
            kode_pos: postcodeInput.value.trim(),
        };

        // Contoh mengirim data ke API (Anda perlu menyesuaikan endpoint dan metode)
        fetch('/api/users/address/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify(newAddress),
        })
        .then(response => {
            saveAddressBtn.disabled = false;
            saveAddressBtn.textContent = 'Simpan Alamat';

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire('Berhasil!', 'Alamat berhasil disimpan!', 'success');
                closeModal();
                loadUserAddresses(); // Muat ulang daftar alamat
            } else {
                Swal.fire('Gagal!', 'Gagal menyimpan alamat: ' + (data.message || 'Unknown error'), 'error');
            }
        })
        .catch(error => {
            console.error('Error saving address:', error);
            Swal.fire('Error!', 'Terjadi kesalahan saat menyimpan alamat. Silakan coba lagi.', 'error');
        });
    });

    // Fungsi untuk memuat data dari API lokal
    function loadProvinces() {
        provinceSelect.innerHTML = '<option value="">Sedang memuat provinsi</option>';
        fetch('/api/regions/provinces')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    provinceSelect.innerHTML = '<option value="">Pilih Provinsi</option>';
                    data.data.forEach(province => {
                        const option = document.createElement('option');
                        option.value = province.id;
                        option.textContent = province.name;
                        provinceSelect.appendChild(option);
                    });
                }
            })
            .catch(error => console.error('Error fetching provinces:', error));
    }

    provinceSelect.addEventListener('change', function() {
        const provinceId = this.value;
        if (provinceId) {
            citySelect.innerHTML = '<option value="">Sedang memuat kota</option>';
            districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            fetch(`/api/regions/regencies/${provinceId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
                        data.data.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.id;
                            option.textContent = city.name;
                            citySelect.appendChild(option);
                        });
                    }
                })
                .catch(error => console.error('Error fetching cities:', error));
        } else {
            citySelect.innerHTML = '<option value="">Pilih Kota/Kabupaten</option>';
            districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        }
    });

    citySelect.addEventListener('change', function() {
        const cityId = this.value;
        if (cityId) {
            districtSelect.innerHTML = '<option value="">Sedang memuat kecamatan</option>';
            fetch(`/api/regions/districts/${cityId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
                        data.data.forEach(district => {
                            const option = document.createElement('option');
                            option.value = district.id;
                            option.textContent = district.name;
                            districtSelect.appendChild(option);
                        });
                    }
                })
                .catch(error => console.error('Error fetching districts:', error));
        } else {
            districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
        }
    });

    // Handle checkout process
    document.getElementById('createOrderBtn').addEventListener('click', function(e) {
        e.preventDefault();

        // Check if address is selected
        const selectedAddress = document.querySelector('input[name="selected_address"]:checked');

        if (!selectedAddress) {
            Swal.fire('Peringatan!', 'Silakan pilih alamat pengiriman terlebih dahulu.', 'warning');
            return;
        }

        // Check if shipping method is selected
        const shippingMethodSelect = document.getElementById('shippingMethodSelect');
        if (shippingMethodSelect && !shippingMethodSelect.value) {
            Swal.fire('Peringatan!', 'Silakan pilih metode pengiriman terlebih dahulu.', 'warning');
            return;
        }

        // Get payment method
        const selectedPayment = document.querySelector('input[name="payment"]:checked');
        if (!selectedPayment) {
            Swal.fire('Peringatan!', 'Silakan pilih metode pembayaran terlebih dahulu.', 'warning');
            return;
        }

        // Show confirmation dialog
        Swal.fire({
            title: 'Konfirmasi Pesanan',
            text: 'Apakah Anda yakin ingin membuat pesanan?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Buat Pesanan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Disable button
                const createOrderBtn = document.getElementById('createOrderBtn');
                createOrderBtn.disabled = true;
                createOrderBtn.textContent = 'Memproses...';

                // Prepare order data
                const orderData = {
                    id_user: userId,
                    id_alamat: selectedAddress.value,
                    metode_pembayaran: selectedPayment.value,
                    metode_pengiriman: shippingMethodSelect.value,
                    total: parseInt(document.getElementById('total').textContent.replace(/[^\d]/g, '')) || 0
                };

                // Show processing alert
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Make API call
                fetch('/api/cust/transactions/create', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify(orderData),
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire('Berhasil!', 'Pesanan Anda telah dibuat!', 'success').then(() => {
                            // Redirect to orders page
                            window.location.href = '/my';
                        });
                    } else {
                        Swal.fire('Gagal!', 'Gagal membuat pesanan: ' + (data.message || 'Unknown error'), 'error');
                        // Re-enable button
                        createOrderBtn.disabled = false;
                        createOrderBtn.textContent = 'Buat Pesanan';
                    }
                })
                .catch(error => {
                    console.error('Error creating order:', error);
                    Swal.fire('Error!', 'Terjadi kesalahan saat membuat pesanan. Silakan coba lagi.', 'error');
                    // Re-enable button
                    createOrderBtn.disabled = false;
                    createOrderBtn.textContent = 'Buat Pesanan';
                });
            }
        });
    });

    // Function to calculate shipping cost
    function calculateShippingCost(addressId) {
        fetch('/api/shipping/calculate-cost', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                id_alamat: addressId,
                weight: 1000, // 1kg default
                courier: null // Get all couriers
            }),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Update shipping options
                updateShippingOptions(data.data.shipping_options);

                // Set cheapest option as default
                if (data.data.cheapest) {
                    document.getElementById('shipping').textContent = data.data.cheapest.formatted_cost;
                    document.getElementById('shipping-mobile').textContent = data.data.cheapest.formatted_cost;
                    updateTotal();
                }
            } else {
                console.error('Error calculating shipping cost:', data.message);
                // Fallback to default shipping cost
                document.getElementById('shipping').textContent = 'Rp 50.000';
                document.getElementById('shipping-mobile').textContent = 'Rp 50.000';
                updateTotal();
            }
        })
        .catch(error => {
            console.error('Error calculating shipping cost:', error);
            // Fallback to default shipping cost
            document.getElementById('shipping').textContent = 'Rp 50.000';
            document.getElementById('shipping-mobile').textContent = 'Rp 50.000';
            updateTotal();
        });
    }

    // Function to update shipping options UI
    function updateShippingOptions(options) {
        let shippingMethodContainer = document.getElementById('shippingMethodContainer');

        if (!shippingMethodContainer) {
            // Create shipping method container if it doesn't exist
            const container = document.createElement('div');
            container.id = 'shippingMethodContainer';
            container.className = '';

            const label = document.createElement('label');
            label.className = 'block text-sm font-medium text-gray-700 mb-2';
            label.textContent = 'Pilih Metode Pengiriman';

            const select = document.createElement('select');
            select.id = 'shippingMethodSelect';
            select.className = 'form-input';
            select.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const cost = selectedOption.dataset.cost;
                document.getElementById('shipping').textContent = 'Rp ' + parseInt(cost).toLocaleString('id-ID');
                document.getElementById('shipping-mobile').textContent = 'Rp ' + parseInt(cost).toLocaleString('id-ID');
                updateTotal();
            });

            container.appendChild(label);
            container.appendChild(select);

            // Insert after cart items
            const cartItemsMobile = document.getElementById('cartItemsMobile');
            const cartItems = document.getElementById('cartItems');
            const cartContainer = cartItemsMobile.offsetParent ? cartItemsMobile : cartItems;
            cartContainer.insertAdjacentElement('afterend', container);

            shippingMethodContainer = container;
        }

        const select = document.getElementById('shippingMethodSelect');
        select.innerHTML = '<option value="">Pilih Metode Pengiriman</option>';

        options.forEach(option => {
            const optionElement = document.createElement('option');
            optionElement.value = `${option.courier}-${option.service}`;
            optionElement.textContent = `${option.courier} ${option.service} - ${option.description} (${option.etd} hari)`;
            optionElement.dataset.cost = option.cost;
            select.appendChild(optionElement);
        });

        // Auto-select cheapest option and show container
        if (options.length > 0) {
            select.value = `${options[0].courier}-${options[0].service}`;
            document.getElementById('shipping').textContent = options[0].formatted_cost;
            document.getElementById('shipping-mobile').textContent = options[0].formatted_cost;
            shippingMethodContainer.style.display = 'block'; // Show the container
        } else {
            shippingMethodContainer.style.display = 'none'; // Hide if no options
        }
    }

    // Function to reset shipping cost when no address is selected
    function resetShippingCost() {
        document.getElementById('shipping').textContent = 'Rp 0';
        document.getElementById('shipping-mobile').textContent = 'Rp 0';

        // Hide shipping method selector if it exists
        const shippingMethodContainer = document.getElementById('shippingMethodContainer');
        if (shippingMethodContainer) {
            shippingMethodContainer.style.display = 'none';
        }

        updateTotal();
    }

    // Function to update total cost
    function updateTotal() {
        const subtotalText = document.getElementById('subtotal').textContent;
        const shippingText = document.getElementById('shipping').textContent;

        // Extract numbers from formatted text
        const subtotal = parseInt(subtotalText.replace(/[^\d]/g, '')) || 0;
        const shipping = parseInt(shippingText.replace(/[^\d]/g, '')) || 0;

        const total = subtotal + shipping;
        document.getElementById('total').textContent = `Rp ${total.toLocaleString('id-ID')}`;
    }

    // Function to set primary address
    function setPrimaryAddress(addressId) {
        fetch('/api/users/address/set-primary', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                id_alamat: addressId,
                id_user: userId
            }),
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                Swal.fire('Berhasil!', 'Alamat utama berhasil diubah!', 'success');
                loadUserAddresses(); // Reload addresses to update UI
            } else {
                Swal.fire('Gagal!', 'Gagal mengubah alamat utama: ' + (data.message || 'Unknown error'), 'error');
                loadUserAddresses(); // Reload to revert UI changes
            }
        })
        .catch(error => {
            console.error('Error setting primary address:', error);
            Swal.fire('Error!', 'Terjadi kesalahan saat mengubah alamat utama.', 'error');
            loadUserAddresses(); // Reload to revert UI changes
        });
    }

    // Function to load selected bank from localStorage
    function loadSelectedBank() {
        const selectedBank = localStorage.getItem('selectedBank');
        if (selectedBank) {
            const bank = JSON.parse(selectedBank);
            document.getElementById('checkoutSelectedBankText').textContent = bank.name;
        }
    }

    // Function to open bank selection modal
    function openBankModal() {
        const modal = document.getElementById('bankModal');
        if (!modal) {
            createBankModal();
        }
        document.getElementById('bankModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        loadBankAccounts();
    }

    // Function to create bank selection modal
    function createBankModal() {
        const modalHtml = `
            <div id="bankModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-end justify-center p-4 md:items-center md:justify-center">
                <div id="modalContent" class="relative w-full md:w-1/2 max-h-[70vh] overflow-hidden rounded-t-lg md:rounded-md bg-white shadow-lg flex flex-col">
                    <div class="p-5 border-b border-gray-200">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 text-center">Pilih Bank untuk Transfer</h3>
                    </div>

                    <div class="px-7 py-3 space-y-4 text-left overflow-y-auto custom-scrollbar" id="bankList">
                        <p class="text-center text-gray-500">Memuat daftar bank...</p>
                    </div>

                    <div class="p-4 border-t border-gray-200">
                        <div class="items-center">
                            <button id="closeBankModalBtn" class="px-4 py-2 bg-gray-200 text-gray-700 text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-400">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        // Add event listener for close button
        document.getElementById('closeBankModalBtn').addEventListener('click', closeBankModal);

        // Add event listener for modal background click
        document.getElementById('bankModal').addEventListener('click', function(e) {
            if (!e.target.closest('#modalContent')) {
                closeBankModal();
            }
        });
    }

    // Function to close bank modal
    function closeBankModal() {
        document.getElementById('bankModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Function to load bank accounts from API
    function loadBankAccounts() {
        fetch('/api/admin/bank-accounts/active')
            .then(response => response.json())
            .then(data => {
                const bankList = document.getElementById('bankList');
                if (data.success && data.data.length > 0) {
                    const activeBanks = data.data.filter(bank => bank.aktif === 'ya');
                    if (activeBanks.length > 0) {
                        bankList.innerHTML = '';
                        activeBanks.forEach(bank => {
                            const bankDiv = document.createElement('div');
                            bankDiv.className = 'flex items-center p-4 border border-gray-200 rounded-lg hover:border-gray-300 cursor-pointer';
                            bankDiv.dataset.bankId = bank.id_akun_bank;
                            bankDiv.dataset.bankName = bank.nama_bank;
                            bankDiv.dataset.accountNumber = bank.nomor_rekening;

                            const radioInput = document.createElement('input');
                            radioInput.type = 'radio';
                            radioInput.name = 'selected_bank';
                            radioInput.value = bank.id_akun_bank;
                            radioInput.className = 'h-4 w-4 text-blue-600 focus:ring-blue-500';

                            const contentDiv = document.createElement('div');
                            contentDiv.className = 'flex-1 ml-4';

                            const bankName = document.createElement('div');
                            bankName.className = 'font-medium text-gray-900';
                            bankName.textContent = bank.nama_bank;

                            const accountNumber = document.createElement('div');
                            accountNumber.className = 'text-sm text-gray-600';
                            accountNumber.textContent = bank.nomor_rekening;

                            contentDiv.appendChild(bankName);
                            contentDiv.appendChild(accountNumber);

                            bankDiv.appendChild(radioInput);
                            bankDiv.appendChild(contentDiv);

                            // Add click handler
                            bankDiv.addEventListener('click', function() {
                                radioInput.checked = true;
                                selectBank(bank);
                            });

                            bankList.appendChild(bankDiv);
                        });
                    } else {
                        bankList.innerHTML = '<p class="text-center text-gray-500">Tidak ada rekening bank aktif tersedia.</p>';
                    }
                } else {
                    bankList.innerHTML = '<p class="text-center text-gray-500">Tidak ada rekening bank tersedia.</p>';
                }
            })
            .catch(error => {
                console.error('Error loading bank accounts:', error);
                document.getElementById('bankList').innerHTML = '<p class="text-center text-red-500">Gagal memuat daftar bank.</p>';
            });
    }

    // Function to select a bank
    function selectBank(bank) {
        // Store selected bank in localStorage
        const selectedBank = {
            id: bank.id_akun_bank,
            name: bank.nama_bank,
            accountNumber: bank.nomor_rekening
        };
        localStorage.setItem('selectedBank', JSON.stringify(selectedBank));

        // Update UI
        document.getElementById('checkoutSelectedBankText').textContent = bank.nama_bank;

        // Close modal
        closeBankModal();

        // Show success message
        Swal.fire({
            toast: true,
            position: 'bottom-end',
            icon: 'success',
            title: `Bank ${bank.nama_bank} dipilih`,
            showConfirmButton: false,
            timer: 3000
        });
    }

    // Bagian loadCartItems tetap sama seperti sebelumnya
    function loadCartItems() {
        fetch('/api/cart/all')
            .then(response => response.json())
            .then(data => {
                const cartItemsContainer = document.getElementById('cartItems');
                const subtotalElement = document.getElementById('subtotal');
                const totalElement = document.getElementById('total');

                if (data.success && data.data.length > 0) {
                    let subtotal = 0;
                    const itemsHtml = data.data.map(item => {
                        const itemTotal = item.harga_produk * item.jumlah;
                        subtotal += itemTotal;

                        const imageUrl = item.gambar_produk
                            ? `/images/products/${item.gambar_produk}`
                            : '/images/products/sample/seni_1.png';

                        return `
                            <div class="flex items-center gap-4 border-b border-gray-200 pb-4">
                                <img src="${imageUrl}" alt="${item.nama_produk}" class="w-20 h-20 object-cover rounded-md">
                                <div class="flex-grow">
                                    <h3 class="font-medium">${item.nama_produk}</h3>
                                    <p class="text-sm text-gray-500">Jumlah: ${item.jumlah}</p>
                                </div>
                                <span class="font-semibold">Rp ${itemTotal.toLocaleString('id-ID')}</span>
                            </div>
                        `;
                    }).join('');

                    cartItemsContainer.innerHTML = itemsHtml;
                    const cartItemsMobile = document.getElementById('cartItemsMobile');
                    if (cartItemsMobile) {
                        cartItemsMobile.innerHTML = itemsHtml;
                    }

                    subtotalElement.textContent = `Rp ${subtotal.toLocaleString('id-ID')}`;

                    // Update total (shipping cost will be updated by calculateShippingCost if address is selected)
                    updateTotal();
                } else {
                    const emptyHtml = `
                        <div class="text-center text-gray-500 py-8">
                            <p>Keranjang Anda kosong</p>
                            <a href="/" class="text-blue-600 hover:text-blue-800">Kembali ke beranda</a>
                        </div>
                    `;
                    cartItemsContainer.innerHTML = emptyHtml;
                    const cartItemsMobile = document.getElementById('cartItemsMobile');
                    if (cartItemsMobile) {
                        cartItemsMobile.innerHTML = emptyHtml;
                    }
                    subtotalElement.textContent = 'Rp 0';
                    totalElement.textContent = 'Rp 0';
                }
            })
            .catch(error => {
                console.error('Error fetching cart:', error);
                const errorHtml = `
                    <div class="text-center text-red-500 py-8">
                        <p>Error loading cart items</p>
                    </div>
                `;
                document.getElementById('cartItems').innerHTML = errorHtml;
                const cartItemsMobile = document.getElementById('cartItemsMobile');
                if (cartItemsMobile) {
                    cartItemsMobile.innerHTML = errorHtml;
                }
            });
    }

});
</script>

@include('partials.foot')
