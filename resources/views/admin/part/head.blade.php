<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$title}}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" xintegrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>
    <style>
        :root {
            --font-display: 'Playfair Display', serif;
            --font-body: 'Ubuntu', sans-serif;
            --bg-color: #F9F6F1;
            --text-color: #4A4A4A;
            --primary-color: #8D5B4C;
            --primary-hover: #7a4f42;
        }
        body {
            font-family: var(--font-body);
            background-color: var(--bg-color);
            color: var(--text-color);
        }
        .font-display {
            font-family: var(--font-display);
        }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #aaa; }
        .sidebar-link.active {
            background-color: var(--primary-color);
            color: white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        .sidebar-link.active:hover {
            background-color: var(--primary-hover);
        }
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            transition: all 0.2s ease;
        }
        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        /* Modal Transition */
        .modal {
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }
        .modal-content {
            transition: transform 0.3s ease;
        }

        .tab-button.active-tab {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .form-input {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            background-color: #ffffff;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .form-input:focus {
            outline: none;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
    </style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmLogout(event) {
    event.preventDefault();
    Swal.fire({
        title: 'Konfirmasi Logout',
        text: 'Apakah Anda yakin ingin keluar dari sistem?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, Keluar',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '/logout';
        }
    });
}
</script>
</head>
<body class="bg-gray-100">

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-white border-r border-gray-200 flex flex-col p-4 transition-transform duration-300 ease-in-out -translate-x-full md:translate-x-0">
             <div class="text-center py-4 border-b border-gray-200">
                <h1 class="text-2xl font-bold font-display text-[var(--primary-color)]">
                    <img src="{{asset('images/logo/ugallery.png')}}" class="w-[77%] pl-[3rem]" />
                </h1>
                <p class="text-xs text-gray-500">ADMIN PANEL</p>
            </div>
            <nav class="mt-8 flex-1">
                <a href="{{ route('dashboard') }}"
                   class="sidebar-link flex items-center px-4 py-3 rounded-lg transition-colors duration-200
                          {{ request()->routeIs('dashboard') ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-tachometer-alt w-6 text-center"></i>
                    <span class="ml-4 font-medium">Dashboard</span>
                </a>

                <a href="{{ route('products') }}"
                   class="sidebar-link flex items-center mt-3 px-4 py-3 rounded-lg transition-colors duration-200
                          {{ request()->routeIs('products') ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-palette w-6 text-center"></i>
                    <span class="ml-4 font-medium">Produk</span>
                </a>

                <a href="{{ route('transactions') }}"
                   class="sidebar-link flex items-center mt-3 px-4 py-3 rounded-lg transition-colors duration-200
                          {{ request()->routeIs('transactions') ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-receipt w-6 text-center"></i>
                    <span class="ml-4 font-medium">Transaksi</span>
                </a>

                <a href="{{ route('customers') }}"
                   class="sidebar-link flex items-center mt-3 px-4 py-3 rounded-lg transition-colors duration-200
                          {{ request()->routeIs('customers') ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-users w-6 text-center"></i>
                    <span class="ml-4 font-medium">Pelanggan</span>
                <a href="{{ route('settings') }}"
                   class="sidebar-link flex items-center mt-3 px-4 py-3 rounded-lg transition-colors duration-200
                          {{ request()->routeIs('settings') ? 'active' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-cog w-6 text-center"></i>
                    <span class="ml-4 font-medium">Pengaturan</span>
                </a>
                </a>
            </nav>
            <div class="mt-auto">
                 <a href="{{url('/logout')}}" onclick="confirmLogout(event)" class="sidebar-link text-gray-700 flex items-center px-4 py-3 rounded-lg hover:bg-red-500 hover:text-white transition-colors duration-200">
                    <i class="fas fa-sign-out-alt w-6 text-center"></i>
                    <span class="ml-4 font-medium">Keluar</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            <!-- Header -->
            <header class="flex items-center justify-between p-4 bg-white/80 backdrop-blur-sm sticky top-0 z-20 border-b">
                <button id="menu-button" class="md:hidden text-gray-700">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h2 class="text-xl font-bold text-gray-800 hidden md:block"></h2>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <img src="https://placehold.co/40x40/8D5B4C/FFFFFF?text={{$users->nama_tampilan[0]}}" alt="{{$users->nama_tampilan}}" class="w-10 h-10 rounded-full">
                        <div>
                            <p class="font-semibold text-sm">{{$users->nama_tampilan}}</p>
                            <p class="text-xs text-gray-500">{{ucfirst($users->role)}}</p>
                        </div>
                    </div>
                </div>
            </header>
