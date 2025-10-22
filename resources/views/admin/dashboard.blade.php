<x-layouts.admin :title="'Dashboard Admin'">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="content-title mb-2">Dashboard Admin</h1>
            <p class="content-subtitle">Kelola operasional laundry dan pantau kinerja bisnis</p>
        </div>
        <div class="d-flex gap-2">
            <a href="?days=7" class="btn btn-sm btn-outline-light {{ request('days', 30) == 7 ? 'active' : '' }}">7 Hari</a>
            <a href="?days=30" class="btn btn-sm btn-outline-light {{ request('days', 30) == 30 ? 'active' : '' }}">30 Hari</a>
            <a href="?days=90" class="btn btn-sm btn-outline-light {{ request('days', 30) == 90 ? 'active' : '' }}">90 Hari</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <!-- Orders Statistics -->
        <div class="col-lg-3 col-md-6">
            <div class="card card-custom h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-basket3" style="font-size: 2.5rem; color: var(--secondary-blue);"></i>
                    </div>
                    <h3 class="fw-bold" style="color: var(--primary-blue);">{{ $totalOrders }}</h3>
                    <p class="text-muted mb-0">Total Pesanan</p>
                    <small class="text-success">
                        <i class="bi bi-arrow-up"></i> {{ $orderChange }}% dari bulan lalu
                    </small>
                </div>
            </div>
        </div>
        
        <!-- Revenue -->
        <div class="col-lg-3 col-md-6">
            <div class="card card-custom h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-currency-dollar" style="font-size: 2.5rem; color: #10b981;"></i>
                    </div>
                    <h3 class="fw-bold text-success">Rp {{ number_format($totalIncome, 0, ',', '.') }}</h3>
                    <p class="text-muted mb-0">Total Pendapatan</p>
                    <small class="text-success">
                        <i class="bi bi-arrow-up"></i> {{ $revenueChange }}% dari bulan lalu
                    </small>
                </div>
            </div>
        </div>
        
        <!-- Expenses -->
        <div class="col-lg-3 col-md-6">
            <div class="card card-custom h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-graph-down" style="font-size: 2.5rem; color: #ef4444;"></i>
                    </div>
                    <h3 class="fw-bold text-danger">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
                    <p class="text-muted mb-0">Total Pengeluaran</p>
                    <small class="text-danger">
                        <i class="bi bi-arrow-up"></i> {{ $expenseChange }}% dari bulan lalu
                    </small>
                </div>
            </div>
        </div>
        
        <!-- Net Profit -->
        <div class="col-lg-3 col-md-6">
            <div class="card card-custom h-100">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="bi bi-graph-up-arrow" style="font-size: 2.5rem; color: var(--accent-blue);"></i>
                    </div>
                    <h3 class="fw-bold" style="color: var(--primary-blue);">Rp {{ number_format($net, 0, ',', '.') }}</h3>
                    <p class="text-muted mb-0">Keuntungan Bersih</p>
                    <small class="text-success">
                        <i class="bi bi-arrow-up"></i> {{ $profitChange }}% dari bulan lalu
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics -->
    <div class="row g-4 mb-4">
        <!-- Revenue Chart -->
        <div class="col-lg-8">
            <div class="card card-custom">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0" style="color: var(--primary-blue);">
                            <i class="bi bi-graph-up me-2"></i>Grafik Pendapatan & Pengeluaran
                        </h5>
                        <div class="d-flex gap-2">
                            <span class="badge bg-success">Pendapatan</span>
                            {{-- Pengeluaran dihapus --}}
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Order Status Distribution -->
        <div class="col-lg-4">
            <div class="card card-custom">
                <div class="card-header bg-white border-0">
                    <h5 class="fw-bold mb-0" style="color: var(--primary-blue);">
                        <i class="bi bi-pie-chart me-2"></i>Status Pesanan
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders and Quick Actions -->
    <div class="row g-4">
        <!-- Recent Orders -->
        <div class="col-lg-8">
            <div class="card card-custom">
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0" style="color: var(--primary-blue);">
                            <i class="bi bi-clock-history me-2"></i>Pesanan Terbaru
                        </h5>
                        <a href="/admin/orders" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye me-1"></i>Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Kode</th>
                                    <th>Pelanggan</th>
                                    <th>Layanan</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders ?? [] as $order)
                                <tr>
                                    <td><span class="fw-bold" style="color: var(--primary-blue);">{{ $order->code ?? 'LND001' }}</span></td>
                                    <td>{{ $order->customer_name ?? 'John Doe' }}</td>
                                    <td>{{ $order->service_type ?? 'Cuci Kering' }}</td>
                                    <td>
                                        @php
                                            $statusClass = match($order->status ?? 'received') {
                                                'received' => 'status-badge status-pending',
                                                'washing', 'drying', 'ironing' => 'status-badge status-processing',
                                                'ready' => 'status-badge status-ready',
                                                'picked_up' => 'status-badge status-completed',
                                                default => 'status-badge status-pending'
                                            };
                                            $statusText = match($order->status ?? 'received') {
                                                'received' => 'Diterima',
                                                'washing' => 'Dicuci',
                                                'drying' => 'Dikeringkan',
                                                'ironing' => 'Disetrika',
                                                'ready' => 'Siap Diambil',
                                                'picked_up' => 'Selesai',
                                                default => 'Diproses'
                                            };
                                        @endphp
                                        <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                    </td>
                                    <td class="fw-bold">Rp {{ number_format($order->total_price ?? 15000, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('admin.orders') }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                        <p class="mt-2 mb-0">Belum ada pesanan hari ini</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card card-custom">
                <div class="card-header bg-white border-0">
                    <h5 class="fw-bold mb-0" style="color: var(--primary-blue);">
                        <i class="bi bi-lightning me-2"></i>Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="/admin/orders/create" class="btn btn-primary-custom">
                            <i class="bi bi-plus-lg me-2"></i>Tambah Pesanan Baru
                        </a>
                        <a href="{{ route('admin.orders') }}" class="btn btn-outline-primary">
                            <i class="bi bi-list-ul me-2"></i>Kelola Pesanan
                        </a>
                        <a href="/admin/customers" class="btn btn-outline-primary">
                            <i class="bi bi-people me-2"></i>Data Pelanggan
                        </a>
                        <a href="/admin/reports" class="btn btn-outline-primary">
                            <i class="bi bi-graph-up me-2"></i>Laporan Lengkap
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- System Status -->
            <div class="card card-custom mt-4">
                <div class="card-header bg-white border-0">
                    <h6 class="fw-bold mb-0" style="color: var(--primary-blue);">
                        <i class="bi bi-gear me-2"></i>Status Sistem
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Server</span>
                        <span class="badge bg-success">Online</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Database</span>
                        <span class="badge bg-success">Connected</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Last Backup</span>
                        <span class="text-success">{{ now()->format('d/m H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sample data - will be replaced with actual data from controller
        const labels = @json($labels);
        const incomeData = @json($incomeData);
        const expenseData = @json($expenseData);

        // Revenue Chart (Combined Income & Expense)
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: incomeData,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#e5e7eb',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#6b7280'
                        }
                    },
                    y: {
                        grid: {
                            color: '#f3f4f6'
                        },
                        ticks: {
                            color: '#6b7280',
                            callback: function(value) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                }
            }
        });

        // Order Status Chart (Pie Chart)
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Menunggu', 'Diproses', 'Siap Diambil', 'Selesai'],
                datasets: [{
                    data: [5, 12, 8, 25],
                    backgroundColor: [
                        '#f59e0b',
                        '#3b82f6',
                        '#10b981',
                        '#6b7280'
                    ],
                    borderWidth: 0,
                    cutout: '60%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#e5e7eb',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });

        // Auto refresh charts every 5 minutes
        setInterval(function() {
            // This would typically fetch new data from the server
            console.log('Refreshing dashboard data...');
        }, 300000);
    </script>
</x-layouts.admin>