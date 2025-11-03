<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Masuk atau Daftar - Seniku</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Ubuntu:wght@400;500&display=swap" rel="stylesheet">

    <style>
        /* Mendefinisikan variabel untuk palet warna dan font */
        :root {
            --font-display: 'Playfair Display', serif;
            --font-body: 'Ubuntu', sans-serif;
            --text-color: #4A4A4A; /* Abu-abu tua */
            --primary-color: #8D5B4C; /* Cokelat artistik */
            --primary-hover: #7a4f42;
        }

        /* Terapkan font dan warna dasar ke seluruh halaman */
        body {
            font-family: var(--font-body);
            background-color: #FFFFFF; /* Latar belakang putih */
            color: var(--text-color);
        }

        /* Menggunakan font Playfair Display untuk semua judul utama */
        h1, h2, h3, h4, .font-display {
            font-family: var(--font-display);
        }

        /* Efek transisi yang halus untuk semua tombol dan link */
        a, button {
            transition: all 0.3s ease-in-out;
        }

        /* Style kustom untuk tombol utama */
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }
        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px); /* Efek sedikit terangkat saat di-hover */
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .form-input {
            background-color: #F9FAFB;
            border: 1px solid #D1D5DB;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out, background-color 0.2s ease-in-out;
        }
        .form-input:focus {
             border-color: var(--primary-color);
             background-color: #FFFFFF;
             box-shadow: 0 0 0 3px rgba(141, 91, 76, 0.2);
             outline: none;
        }
    </style>
</head>

<body>
    <!-- Main Content -->
    <main class="min-h-screen flex flex-col md:flex-row">

        <!-- Kolom Kiri: Form -->
        <div class="w-full md:w-1/2 p-8 sm:p-12 flex flex-col justify-center">
            <div class="w-full max-w-md mx-auto">
                 <!-- Login Card -->
                <div id="login-card" class="transition-all duration-300">
                    <h1 class="font-display text-4xl font-bold mb-3">Selamat Datang</h1>
                    <p class="text-gray-500 mb-8">Silakan masuk untuk melanjutkan.</p>
                    <form id="login-form" class="space-y-4" action="{{url('login/process')}}" method="POST">
                        @csrf
                        <div>
                            <label for="login-email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                            <input type="email" name="email" id="login-email" class="form-input rounded-md w-full p-3" placeholder="email@example.com" required>
                        </div>
                        <div>
                            <label for="login-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="login-password" class="form-input rounded-md w-full p-3 pr-10" placeholder="••••••••" required>
                                <button type="button" onclick="togglePassword('login-password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-eye text-gray-400 hover:text-gray-600" id="login-password-icon"></i>
                                </button>
                            </div>
                        </div>
                        <div class="text-right">
                            <a href="{{url('forgot-password')}}" class="text-sm font-medium hover:underline" style="color: var(--primary-color);">Lupa Password?</a>
                        </div>
                        <button type="submit" class="btn-primary w-full rounded-lg py-3 text-base font-medium">Masuk</button>
                    </form>
                    <p class="text-center text-sm text-gray-600 mt-6">
                        Belum punya akun?
                        <a href="#" id="show-register" class="font-bold hover:underline" style="color: var(--primary-color);">Daftar di sini</a>
                    </p>
                </div>

                <!-- Register Card -->
                <div id="register-card" class="transition-all duration-300 hidden">
                    <h1 class="font-display text-4xl font-bold mb-3">Buat Akun Baru</h1>
                    <p class="text-gray-500 mb-8">Gratis dan mudah.</p>
                    <form id="register-form" class="space-y-4" action="{{url('register/process')}}" method="POST">
                        @csrf
                        <div>
                            <label for="register-fullname" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <input type="text" name="nama_tampilan" id="register-fullname" class="form-input rounded-md w-full p-3" placeholder="Contoh: Budi Santoso" required>
                        </div>
                        <div>
                            <label for="register-email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                            <input type="email" name="email" id="register-email" class="form-input rounded-md w-full p-3" placeholder="email@example.com" required>
                        </div>
                        <div>
                            <label for="nomor_whatsapp" class="block text-sm font-medium text-gray-700 mb-1">Nomor Whatsapp</label>
                            <input type="text" name="nomor_whatsapp" id="nomor_whatsapp" class="form-input rounded-md w-full p-3" placeholder="081234567890" required>
                        </div>
                        <div>
                            <label for="register-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="register-password" class="form-input rounded-md w-full p-3 pr-10" placeholder="••••••••" required>
                                <button type="button" onclick="togglePassword('register-password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-eye text-gray-400 hover:text-gray-600" id="register-password-icon"></i>
                                </button>
                            </div>
                        </div>
                         <div>
                            <label for="register-confirm-password" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                            <div class="relative">
                                <input type="password" name="more_password" id="register-confirm-password" class="form-input rounded-md w-full p-3 pr-10" placeholder="••••••••" required>
                                <button type="button" onclick="togglePassword('register-confirm-password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-eye text-gray-400 hover:text-gray-600" id="register-confirm-password-icon"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn-primary w-full rounded-lg py-3 text-base font-medium">Buat Akun</button>
                    </form>
                    <p class="text-center text-sm text-gray-600 mt-6">
                        Sudah punya akun?
                        <a href="#" id="show-login" class="font-bold hover:underline" style="color: var(--primary-color);">Masuk di sini</a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Kolom Kanan: Gambar -->
        <div class="hidden md:block w-1/2 bg-cover bg-center" style="background-image: url({{asset('images/hero/hero_3.jpg')}});">
            <!-- Gambar diatur melalui background-image untuk kontrol yang lebih baik -->
        </div>

    </main>

    <script>
        const loginCard = document.getElementById('login-card');
        const registerCard = document.getElementById('register-card');
        const showRegisterLink = document.getElementById('show-register');
        const showLoginLink = document.getElementById('show-login');

        showRegisterLink.addEventListener('click', (e) => {
            e.preventDefault();
            loginCard.classList.add('hidden');
            registerCard.classList.remove('hidden');
        });

        showLoginLink.addEventListener('click', (e) => {
            e.preventDefault();
            registerCard.classList.add('hidden');
            loginCard.classList.remove('hidden');
        });

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(inputId + '-icon');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Loading animation on form submit
        document.getElementById('login-form').addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sedang masuk...';
            button.disabled = true;
        });

        document.getElementById('register-form').addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sedang membuat akun...';
            button.disabled = true;
        });
    </script>
</body>
</html>
