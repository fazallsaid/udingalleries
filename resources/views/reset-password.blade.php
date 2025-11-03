<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>Reset Password - Udin Gallery</title>
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
                <div class="transition-all duration-300">
                    <h1 class="font-display text-4xl font-bold mb-3">Reset Password</h1>
                    <p class="text-gray-500 mb-8">Masukkan password baru Anda.</p>
                    <form class="space-y-4" action="{{url('reset-password/process')}}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                            <div class="relative">
                                <input type="password" name="password" id="password" class="form-input rounded-md w-full p-3 pr-10" placeholder="••••••••" required>
                                <button type="button" onclick="togglePassword('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-eye text-gray-400 hover:text-gray-600" id="password-icon"></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-input rounded-md w-full p-3 pr-10" placeholder="••••••••" required>
                                <button type="button" onclick="togglePassword('password_confirmation')" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <i class="fas fa-eye text-gray-400 hover:text-gray-600" id="password_confirmation-icon"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" class="btn-primary w-full rounded-lg py-3 text-base font-medium">Reset Password</button>
                    </form>
                    <p class="text-center text-sm text-gray-600 mt-6">
                        <a href="{{url('login')}}" class="font-bold hover:underline" style="color: var(--primary-color);">Kembali ke Login</a>
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
        document.querySelector('form').addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mereset...';
            button.disabled = true;
        });
    </script>
</body>
</html>
