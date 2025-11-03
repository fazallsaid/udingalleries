<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Udin Gallery</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
        }
        body {
            font-family: var(--font-body);
            background-color: var(--bg-color);
            color: var(--text-color);
        }
        .font-display {
            font-family: var(--font-display);
        }
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            transition: all 0.3s ease-in-out;
        }
        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-cover bg-center" style="background-image: url({{asset('images/hero/hero_4.jpg')}});">
    <div class="w-full max-w-md p-8 space-y-8 bg-white rounded-2xl shadow-2xl m-4">
        <div class="text-center">
            <h1 class="text-3xl font-bold font-display" style="color: var(--primary-color);">Admin Panel</h1>
            <p class="mt-2 text-gray-600">Selamat datang kembali, silakan masuk.</p>
        </div>
        <form class="mt-8 space-y-6" action="{{url('login/process')}}" method="POST">
            @csrf
            <input type="hidden" name="remember" value="true">
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email-address" class="sr-only">Alamat Email</label>
                    <input id="email-address" name="email" type="email" autocomplete="email" required class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] focus:z-10 sm:text-sm" placeholder="Alamat Email">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none rounded-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-[var(--primary-color)] focus:border-[var(--primary-color)] focus:z-10 sm:text-sm" placeholder="Password">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-[var(--primary-color)] focus:ring-[var(--primary-color)] border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                        Ingat saya
                    </label>
                </div>

                <div class="text-sm">
                    <a href="#" class="font-medium text-[var(--primary-color)] hover:text-[var(--primary-hover)]">
                        Lupa password?
                    </a>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md btn-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[var(--primary-hover)]">
                    Masuk
                </button>
            </div>
        </form>
    </div>
</body>
</html>
