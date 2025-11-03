<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Udin Gallery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Ubuntu:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --font-display: 'Playfair Display', serif;
            --font-body: 'Ubuntu', sans-serif;
            --bg-color: #F9F6F1;
            --text-color: #4A4A4A;
            --primary-color: #8D5B4C;
            --primary-hover: #7a4f42;
            --card-bg: #FFFFFF;
        }
        body {
            font-family: var(--font-body);
            background-color: var(--bg-color);
            color: var(--text-color);
        }
        h1, h2, h3, h4, .font-display {
            font-family: var(--font-display);
        }
        a, button {
            transition: all 0.3s ease-in-out;
        }
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        #search-input {
            width: 0; padding-left: 0; padding-right: 0; opacity: 0;
            transition: all 0.5s ease-in-out;
            transform-origin: right center;
        }
        #search-input.active {
            width: 348px; opacity: 1; padding-left: 1rem; padding-right: 1rem;
        }
        .mobile-nav-link.active { color: var(--primary-color); }

        /* --- CSS BARU UNTUK LOGO SCROLL --- */
        #main-logo {
            transition: width 0.3s ease-in-out;
        }
        #main-header.header-scrolled #main-logo {
            width: 8%; /* Ukuran logo saat discroll ke bawah */
        }
        .form-input { /* Made border thicker and darker */
            background-color: #F9FAFB; /* Light gray background */
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out, background-color 0.2s ease-in-out;
            border-width: 1px;
            width: 100%;
            padding: 9px;
        }
        .form-input:focus {
             background-color: #FFFFFF; /* Change background to white on focus */
        }

        .user-dropdown {
            position: relative;
        }
        .user-dropdown:hover .dropdown-menu {
            display: block;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
            min-width: 150px;
        }
        .dropdown-menu a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: #333;
        }
        .dropdown-menu a:hover {
            background: #f5f5f5;
        }
        .tabs {
            display: flex;
            border-bottom: 1px solid #ccc;
            margin-bottom: 20px;
        }
        .tab-button {
            background: none;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            border-bottom: 2px solid transparent;
        }
        .tab-button.active {
            border-bottom: 2px solid var(--primary-color);
            color: var(--primary-color);
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .cart-badge {
            /* Properti dasar masih sama */
            background-color: #E95465;
            color: white;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            position: absolute;
            top: -8px;
            right: -7px;
            font-size: 11px;
            font-weight: bold;
            border: 2px solid var(--bg-color);

            /* Untuk centering horizontal */
            text-align: center;

            /* Untuk centering vertikal yang presisi */
            line-height: 16px;
        }
        .cart-button {
            position: relative;
        }

        /* Active navigation link styling */
        .nav-link.active {
            border-bottom: 2px solid var(--primary-color);
            color: var(--primary-color);
        }
        .nav-link:hover {
            border-bottom: 2px solid var(--primary-color);
            color: var(--primary-color);
        }

    </style>
</head>

<body class="text-black">
    <header id="main-header" class="sticky top-0 z-50 w-full bg-[#F9F6F1] shadow-sm">
        <div class="flex items-center justify-between px-6 py-5 max-w-[1200px] mx-auto">
            <div class="font-display font-bold text-2xl select-none" style="color: var(--primary-color);">
                <a href="{{url('/')}}" style="text-decoration: none;"><img id="main-logo" src="{{asset('images/logo/ugallery.png')}}" class="w-[10%] rounded-lg" /></a>
            </div>
            <nav class="hidden md:flex space-x-8 text-sm font-normal pr-[1rem]">
                <a class="px-2 py-1 rounded transition nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{url('/')}}">Beranda</a>
                <a class="px-2 py-1 rounded transition nav-link {{ request()->is('products') ? 'active' : '' }}" href="{{url('/products')}}">Produk</a>
                <a class="px-2 py-1 rounded transition nav-link {{ request()->is('categories') ? 'active' : '' }}" href="{{url('/categories')}}">Kategori</a>
            </nav>
            <div class="flex items-center space-x-2 md:space-x-4 text-sm font-normal">
                <div id="search-container" class="relative flex items-center">
                    <input id="search-input" type="text" placeholder="Cari produk..." class="h-9 rounded-lg border-[1px] border-gray-300 focus:outline-none focus:ring-1 focus:ring-[var(--primary-color)]" />
                    <button id="search-button" aria-label="Cari" class="p-1 hover:text-gray-700 z-10"><i class="fas fa-search"></i></button>
                    <div id="search-dropdown" class="absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-b-lg shadow-lg z-50 hidden max-h-60 overflow-y-auto"></div>
                </div>
                <button onclick="window.location.href='{{url('/cart')}}'" aria-label="Keranjang" class="cart-button p-1 hover:text-gray-700 hidden md:block"><i class="fas fa-shopping-cart"></i><span id="cart-count" class="cart-badge" style="display: none;">0</span></button>
                <div class="h-6 border-l border-gray-300 hidden md:block"></div>
                @if(session('userLogin'))
                    <div class="user-dropdown relative hidden md:block">
                        <div class="flex items-center space-x-2 px-5 py-2 text-sm font-medium cursor-pointer">
                            <i class="fas fa-user"></i>
                            <span>{{ App\Models\Users::find(session('userId'))->nama_tampilan }}</span>
                        </div>
                        <div class="dropdown-menu">
                            <a href="{{url('/my')}}">Akun Saya</a>
                            <a href="{{url('/logout')}}">Keluar</a>
                        </div>
                    </div>
                @else
                    <button class="btn-primary rounded-lg px-5 py-2 text-sm font-medium transition hidden md:block" onclick="window.location.href='{{url('login')}}'">Masuk</button>
                @endif
            </div>
        </div>
    </header>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.getElementById('main-header');
            window.addEventListener('scroll', function() {
                if (window.scrollY > 50) {
                    header.classList.add('header-scrolled');
                } else {
                    header.classList.remove('header-scrolled');
                }
            });
        });

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

        @if(session('userLogin'))
        updateCartCount();
        @endif

        // Search functionality
        const searchInput = document.getElementById('search-input');
        const searchButton = document.getElementById('search-button');
        const searchDropdown = document.getElementById('search-dropdown');

        searchButton.addEventListener('click', function() {
            searchInput.classList.toggle('active');
            if (searchInput.classList.contains('active')) {
                searchInput.focus();
            } else {
                searchDropdown.classList.add('hidden');
            }
        });


        searchInput.addEventListener('input', function() {
            const query = searchInput.value.trim();
            if (query.length > 0) {
                fetch('/api/art/products/search/' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        searchDropdown.innerHTML = '';
                        if (data.success && data.data && data.data.length > 0) {
                            data.data.forEach(product => {
                                const item = document.createElement('a');
                                item.href = '/details/' + product.kode_produk + '/' + product.slug_produk;
                                item.className = 'block px-4 py-2 hover:bg-gray-100 text-sm text-gray-800';
                                item.textContent = product.nama_produk;
                                searchDropdown.appendChild(item);
                            });
                            searchDropdown.classList.remove('hidden');
                        } else {
                            searchDropdown.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500">Tidak ada produk ditemukan</div>';
                            searchDropdown.classList.remove('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching search results:', error);
                        searchDropdown.classList.add('hidden');
                    });
            } else {
                searchDropdown.classList.add('hidden');
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchButton.contains(e.target) && !searchDropdown.contains(e.target)) {
                searchDropdown.classList.add('hidden');
                searchInput.classList.remove('active');
            }
        });
    </script>
