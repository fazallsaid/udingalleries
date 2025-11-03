<footer class="text-white px-6 sm:px-10 md:px-16" style="background-color: #3D405B;">
    <div class="max-w-7xl mx-auto">
        <!-- Main Footer Section -->
        <div class="py-12 flex flex-col md:flex-row justify-between items-center gap-8">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{url('/')}}">
                    <img src="{{asset('images/logo/ugallery.png')}}" class="w-20 rounded-lg" alt="Udin Gallery Logo">
                </a>
            </div>

            <!-- Navigation & Social Links -->
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="flex justify-center items-center space-x-6">
                    <a href="https://wa.me/6281260072530" target="_blank" class="text-gray-300 hover:text-white" aria-label="Instagram">
                        <i class="fab fa-whatsapp text-xl"></i> Hubungi Kami
                    </a>
                </div>
            </div>
        </div>

        <!-- Sub-footer with Copyright -->
        <div class="py-6 border-t border-gray-500/30 text-center">
            <p class="text-sm text-gray-300">&copy; {{ date('Y') }} Udin Gallery. All rights reserved.</p>
        </div>
    </div>
<nav class="md:hidden fixed bottom-0 inset-x-0 bg-white shadow-[0_-2px_5px_rgba(0,0,0,0.05)] z-50">
    <div class="flex justify-around items-center h-16">
        <a href="{{url('/')}}" class="mobile-nav-link flex flex-col items-center text-center text-gray-600 hover:text-[var(--primary-color)] {{ request()->is('/') ? 'active' : '' }}">
            <i class="fas fa-home text-xl mb-1"></i>
            <span class="text-xs">Beranda</span>
        </a>
        <a href="{{url('/products')}}" class="mobile-nav-link flex flex-col items-center text-center text-gray-600 hover:text-[var(--primary-color)] {{ request()->is('products') ? 'active' : '' }}">
            <i class="fas fa-shopping-bag text-xl mb-1"></i>
            <span class="text-xs">Produk</span>
        </a>
        <a href="{{url('/cart')}}" class="mobile-nav-link flex flex-col items-center text-center text-gray-600 hover:text-[var(--primary-color)] {{ request()->is('cart') ? 'active' : '' }}">
            <i class="fas fa-shopping-cart text-xl mb-1"></i>
            <span class="text-xs">Keranjang</span>
        </a>
        @if(session('userLogin'))
        <a href="{{url('/my')}}" class="mobile-nav-link flex flex-col items-center text-center text-gray-600 hover:text-[var(--primary-color)] {{ request()->is('my') ? 'active' : '' }}">
            <i class="fas fa-user text-xl mb-1"></i>
            <span class="text-xs">Akun</span>
        </a>
        @else
        <a href="{{url('/login')}}" class="mobile-nav-link flex flex-col items-center text-center text-gray-600 hover:text-[var(--primary-color)]">
            <i class="fas fa-user text-xl mb-1"></i>
            <span class="text-xs">Akun</span>
        </a>
        @endif
    </div>
</nav>
</body>
</html>
</footer>
