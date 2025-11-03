@include('admin.part.head')

            <!-- Content -->
            <main class="p-6 md:p-8">
                <!-- Stat Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="glass-card p-6 rounded-xl shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm">Total Pendapatan</p>
                                <p class="text-3xl font-bold text-gray-800 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-[var(--primary-color)]/20 flex items-center justify-center">
                                <i class="fas fa-dollar-sign text-2xl text-[var(--primary-color)]"></i>
                            </div>
                        </div>
                        <p class="text-xs mt-4 {{ $revenueChangePercent >= 0 ? 'text-green-500' : 'text-red-500' }}">
                            {{ $revenueChangePercent >= 0 ? '+' : '' }}{{ number_format($revenueChangePercent, 1) }}% dari bulan lalu
                        </p>
                    </div>
                     <div class="glass-card p-6 rounded-xl shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm">Produk Terjual</p>
                                <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($productsSold, 0, ',', '.') }}</p>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-blue-500/20 flex items-center justify-center">
                                <i class="fas fa-box-open text-2xl text-blue-500"></i>
                            </div>
                        </div>
                        <p class="text-xs mt-4 {{ $productsSoldChangePercent >= 0 ? 'text-green-500' : 'text-red-500' }}">
                            {{ $productsSoldChangePercent >= 0 ? '+' : '' }}{{ number_format($productsSoldChangePercent, 1) }}% dari bulan lalu
                        </p>
                    </div>
                     <div class="glass-card p-6 rounded-xl shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm">Pelanggan Baru</p>
                                <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($newCustomers, 0, ',', '.') }}</p>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-green-500/20 flex items-center justify-center">
                                <i class="fas fa-user-plus text-2xl text-green-500"></i>
                            </div>
                        </div>
                       <p class="text-xs mt-4 {{ $newCustomersChangePercent >= 0 ? 'text-green-500' : 'text-red-500' }}">
                           {{ $newCustomersChangePercent >= 0 ? '+' : '' }}{{ number_format($newCustomersChangePercent, 1) }}% dari bulan lalu
                       </p>
                    </div>
                     <div class="glass-card p-6 rounded-xl shadow-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 text-sm">Transaksi Tertunda</p>
                                <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($pendingTransactions, 0, ',', '.') }}</p>
                            </div>
                            <div class="w-12 h-12 rounded-full bg-yellow-500/20 flex items-center justify-center">
                                <i class="fas fa-hourglass-half text-2xl text-yellow-500"></i>
                            </div>
                        </div>
                       <p class="text-xs mt-4 {{ $pendingTransactionsChange >= 0 ? 'text-red-500' : 'text-green-500' }}">
                           {{ $pendingTransactionsChange >= 0 ? '+' : '' }}{{ $pendingTransactionsChange }} dari bulan lalu
                       </p>
                    </div>
                </div>

                <!-- Chart and Recent Activities -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
                    <!-- Sales Chart -->
                    <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-md">
                        <h3 class="font-bold text-lg text-gray-800">Tinjauan Penjualan</h3>
                        <div class="relative" style="height: 300px;">
                            <canvas id="salesChart" class="mt-4"></canvas>
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="bg-white p-6 rounded-xl shadow-md">
                        <h3 class="font-bold text-lg text-gray-800 mb-4">Transaksi Terbaru</h3>
                        <div class="space-y-4">
                            @foreach($recentTransactions as $transaction)
                             <div class="flex items-center">
                                 <img src="https://placehold.co/40x40/FFC107/FFFFFF?text={{ substr($transaction['customer_name'], 0, 1) }}" alt="User" class="w-10 h-10 rounded-full">
                                 <div class="ml-3 flex-1">
                                     <p class="text-sm font-semibold">{{ $transaction['customer_name'] }}</p>
                                     <p class="text-xs text-gray-500">Membeli "{{ $transaction['product_name'] }}"</p>
                                 </div>
                                 <span class="text-sm font-bold text-green-500">{{ $transaction['amount'] }}</span>
                             </div>
                            @endforeach
                         </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Sidebar Toggle for Mobile
        const menuButton = document.getElementById('menu-button');
        const sidebar = document.getElementById('sidebar');

        menuButton.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });

        // Close sidebar if clicking outside of it on mobile
        document.addEventListener('click', (e) => {
            if (!sidebar.contains(e.target) && !menuButton.contains(e.target) && !sidebar.classList.contains('-translate-x-full') && window.innerWidth < 768) {
                sidebar.classList.add('-translate-x-full');
            }
        });

        // Chart.js Implementation
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('salesChart');
            if (ctx) {
                const salesLabels = @json($salesLabels);
                const salesData = @json($salesData);

                const salesChart = new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: salesLabels,
                        datasets: [{
                            label: 'Pendapatan (dalam juta Rp)',
                            data: salesData,
                            backgroundColor: 'rgba(141, 91, 76, 0.2)',
                            borderColor: 'rgba(141, 91, 76, 1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: 'rgba(141, 91, 76, 1)',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return value + 'jt';
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        });
    </script>
@include('admin.part.foot')
