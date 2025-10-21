<x-layouts.admin title="Daftar Order">
    <style>
        /* Spacing improvement for a more comfortable layout */
        .table-comfy th, .table-comfy td { padding: 0.75rem 1rem; }
        .table-comfy tbody tr { border-bottom: 1px solid rgba(0,0,0,.06); }
        .filter-card .form-label { font-weight: 600; }
        .filter-card .form-control, .filter-card .form-select { padding: 0.6rem 0.75rem; }
    </style>
    <div class="container-fluid py-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="fw-bold mb-1">Daftar Order</h2>
                <p class="text-muted mb-0">Kelola pesanan dan pantau status pengerjaan.</p>
            </div>
            <div>
                <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Buat Order
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm filter-card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.orders') }}" class="row g-3 align-items-end">
                    <div class="col-12 col-md-5">
                        <label class="form-label">Cari</label>
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Kode, Nama, atau No. HP">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">Semua</option>
                            @foreach(['received'=>'Diterima','washing'=>'Dicuci','drying'=>'Dikeringkan','ironing'=>'Disetrika','ready'=>'Siap Diambil','picked_up'=>'Selesai'] as $val=>$label)
                                <option value="{{ $val }}" @selected(request('status')===$val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label">Per Halaman</label>
                        <select name="per_page" class="form-select">
                            @foreach([10,25,50,100] as $n)
                                <option value="{{ $n }}" @selected(request('per_page',25)==$n)>{{ $n }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-2 d-grid d-md-block">
                        <button class="btn btn-outline-primary me-2"><i class="bi bi-search me-1"></i>Filter</button>
                        <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg me-1"></i>Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle table-comfy mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode</th>
                            <th>Pelanggan</th>
                            <th>Status</th>
                            <th class="text-end">Jumlah (kg)</th>
                            <th class="text-end">Total (Rp)</th>
                            <th>Dibuat</th>
                            <th>Jatuh Tempo</th>
                            <th>Selesai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>
                                    <span class="fw-bold" style="color: var(--primary-blue);">{{ $order->code }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $order->customer_name ?? '-' }}</div>
                                    @if(!empty($order->phone))
                                        <small class="text-muted">{{ $order->phone }}</small>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusClass = match($order->status) {
                                            'received' => 'badge bg-secondary',
                                            'washing' => 'badge bg-info',
                                            'drying' => 'badge bg-warning text-dark',
                                            'ironing' => 'badge bg-primary',
                                            'ready' => 'badge bg-success',
                                            'picked_up' => 'badge bg-dark',
                                            default => 'badge bg-secondary'
                                        };
                                        $statusText = match($order->status) {
                                            'received' => 'Diterima',
                                            'washing' => 'Dicuci',
                                            'drying' => 'Dikeringkan',
                                            'ironing' => 'Disetrika',
                                            'ready' => 'Siap Diambil',
                                            'picked_up' => 'Selesai',
                                            default => ucfirst($order->status ?? 'Diproses')
                                        };
                                    @endphp
                                    <span class="{{ $statusClass }}">{{ $statusText }}</span>
                                </td>
                                <td class="text-end">{{ number_format($order->quantity ?? 0, 2, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</td>
                                <td>{{ optional($order->created_at)->format('d M Y H:i') }}</td>
                                <td>{{ optional($order->due_at)->format('d M Y H:i') }}</td>
                                <td>{{ optional($order->completed_at)->format('d M Y H:i') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="d-flex gap-2 align-items-center">
                                        @csrf
                                        <select name="status" class="form-select form-select-sm" style="max-width: 200px;">
                                            @foreach(['received'=>'Diterima','washing'=>'Dicuci','drying'=>'Dikeringkan','ironing'=>'Disetrika','ready'=>'Siap Diambil','picked_up'=>'Selesai'] as $val=>$label)
                                                <option value="{{ $val }}" @selected($order->status === $val)>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <button class="btn btn-sm btn-outline-primary"><i class="bi bi-check2"></i> Update</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox" style="font-size: 1.5rem;"></i>
                                    <div class="mt-1">Belum ada order.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer bg-white">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</x-layouts.admin>