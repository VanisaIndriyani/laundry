<x-layouts.customer title="Lacak Pesanan - LaundryKu">
    <div class="py-4">
        <!-- Header -->
        <div class="content-header mb-4 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="content-title mb-2">Lacak Pesanan</h1>
                <p class="content-subtitle">Masukkan kode pesanan untuk melihat status laundry Anda</p>
            </div>
            <div>
                <a href="{{ route('logout') }}" class="btn btn-outline-danger">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </a>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Tracking Form -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-custom">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('track.post') }}">
                            @csrf
                            <div class="mb-4">
                                <label for="order_code" class="form-label fw-bold">Kode Pesanan</label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                                    <input type="text" 
                                           class="form-control @error('order_code') is-invalid @enderror" 
                                           id="order_code" 
                                           name="order_code" 
                                           placeholder="Contoh: LND001" 
                                           value="{{ old('order_code') }}"
                                           required>
                                </div>
                                @error('order_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Kode pesanan dapat ditemukan di struk atau email konfirmasi
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary-custom btn-lg">
                                    <i class="bi bi-search me-2"></i>Lacak Pesanan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($order))
        <!-- Order Details -->
        <div class="row justify-content-center mt-4">
            <div class="col-lg-10">
                <div class="card card-custom">
                    <div class="card-header bg-white border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0" style="color: var(--primary-blue);">
                                <i class="bi bi-receipt me-2"></i>Detail Pesanan
                            </h5>
                            <span class="badge bg-primary fs-6">{{ $order->code }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Order Info -->
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Nama Pelanggan</label>
                                    <div class="info-value">{{ $order->customer_name }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Nomor Telepon</label>
                                    <div class="info-value">{{ $order->phone }}</div>
                                </div>
                            </div>
                          
                           
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Jumlah (kg)</label>
                                    <div class="info-value">{{ $order->quantity }} kg</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Total Harga</label>
                                    <div class="info-value fw-bold" style="color: var(--primary-blue);">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Status</label>
                                    <div class="info-value">{{ $statusLabels[$order->status] ?? ucwords(str_replace('_',' ', $order->status)) }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="info-label">Tanggal Masuk</label>
                                    <div class="info-value">{{ $order->created_at->format('d F Y, H:i') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Timeline -->
                        <div class="status-timeline">
                            <h6 class="fw-bold mb-3" style="color: var(--primary-blue);">
                                <i class="bi bi-clock-history me-2"></i>Status Pesanan
                            </h6>
                            
                            <div class="timeline">
                                @php
                                    $statuses = [
                                        'received' => ['label' => 'Pesanan Diterima', 'icon' => 'bi-check-circle'],
                                        'washing' => ['label' => 'Pencucian', 'icon' => 'bi-droplet'],
                                        'drying' => ['label' => 'Pengeringan', 'icon' => 'bi-wind'],
                                        'ironing' => ['label' => 'Penyetrikaan', 'icon' => 'bi-fire'],
                                        'ready' => ['label' => 'Siap Diambil', 'icon' => 'bi-bell'],
                                        'picked_up' => ['label' => 'Selesai', 'icon' => 'bi-check-circle-fill']
                                    ];
                                    $currentStatusIndex = array_search($order->status, array_keys($statuses));
                                @endphp
                                
                                @foreach($statuses as $status => $info)
                                    @php
                                        $statusIndex = array_search($status, array_keys($statuses));
                                        $isActive = $statusIndex <= $currentStatusIndex;
                                        $isCurrent = $status === $order->status;
                                    @endphp
                                    
                                    <div class="timeline-item {{ $isActive ? 'active' : '' }} {{ $isCurrent ? 'current' : '' }}">
                                        <div class="timeline-marker">
                                            <i class="bi {{ $info['icon'] }}"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">{{ $info['label'] }}</h6>
                                            @if($isCurrent)
                                                <p class="timeline-time text-primary fw-bold">Status Saat Ini</p>
                                            @elseif($isActive)
                                                <p class="timeline-time text-success">Selesai</p>
                                            @else
                                                <p class="timeline-time text-muted">Menunggu</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>



                        <!-- Notes -->
                        @if($order->notes)
                        <div class="mt-4">
                            <h6 class="fw-bold mb-2" style="color: var(--primary-blue);">
                                <i class="bi bi-chat-text me-2"></i>Catatan
                            </h6>
                            <div class="p-3 rounded bg-light">
                                {{ $order->notes }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

    
    </div>

    <style>
        .info-item {
            margin-bottom: 1rem;
        }
        
        .info-label {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 0.25rem;
            display: block;
        }
        
        .info-value {
            font-size: 1rem;
            color: #1f2937;
            font-weight: 600;
        }
        
        .timeline {
            position: relative;
            padding-left: 2rem;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 1rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 2rem;
        }
        
        .timeline-item:last-child {
            margin-bottom: 0;
        }
        
        .timeline-marker {
            position: absolute;
            left: -2rem;
            top: 0;
            width: 2rem;
            height: 2rem;
            background: #e5e7eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 0.875rem;
        }
        
        .timeline-item.active .timeline-marker {
            background: var(--primary-blue);
            color: white;
        }
        
        .timeline-item.current .timeline-marker {
            background: #f59e0b;
            color: white;
            animation: pulse 2s infinite;
        }
        
        .timeline-content {
            margin-left: 1rem;
        }
        
        .timeline-title {
            margin-bottom: 0.25rem;
            font-weight: 600;
            color: #1f2937;
        }
        
        .timeline-time {
            font-size: 0.875rem;
            margin-bottom: 0;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }
    </style>
</x-layouts.customer>