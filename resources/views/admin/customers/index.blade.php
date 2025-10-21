<x-layouts.admin title="Pelanggan">
    <div class="container-fluid py-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="fw-bold mb-1">Data Pelanggan</h2>
                <p class="text-muted mb-0">Ringkasan pelanggan berdasarkan pesanan.</p>
            </div>
            <div>
                <a href="{{ route('admin.orders') }}" class="btn btn-primary">
                    <i class="bi bi-list-task me-1"></i> Lihat Pesanan
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary p-3 me-3">
                                <i class="bi bi-people fs-4"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Total Pelanggan</div>
                                <div class="h4 mb-0">{{ number_format($summary['total_customers']) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-success bg-opacity-10 text-success p-3 me-3">
                                <i class="bi bi-receipt fs-4"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Total Pesanan</div>
                                <div class="h4 mb-0">{{ number_format($summary['total_orders']) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle bg-warning bg-opacity-10 text-warning p-3 me-3">
                                <i class="bi bi-cash-coin fs-4"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Total Pembelanjaan</div>
                                <div class="h4 mb-0">Rp {{ number_format($summary['total_spent'], 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.customers') }}" class="row g-3 align-items-end">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Cari</label>
                        <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Nama, Telepon, atau Kode">
                    </div>
                    <div class="col-6 col-md-3">
                        <label class="form-label">Per Halaman</label>
                        <select name="per_page" class="form-select">
                            @foreach([10,20,50,100] as $n)
                                <option value="{{ $n }}" @selected($perPage==$n)>{{ $n }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-3 d-grid">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search me-1"></i> Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                             
                                <th class="text-center">Jumlah Pesanan</th>
                                <th class="text-end">Total Belanja</th>
                                <th>Terakhir Order</th>
                                <th style="width: 140px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customers as $c)
                                <tr>
                                    <td class="fw-semibold">{{ $c->customer_name ?? '-' }}</td>
                                   
                                    <td class="text-center">{{ number_format($c->orders_count) }}</td>
                                    <td class="text-end">Rp {{ number_format($c->total_spent, 0, ',', '.') }}</td>
                                    <td>
                                        @php $dt = $c->last_order_at ? \Carbon\Carbon::parse($c->last_order_at) : null; @endphp
                                        {{ $dt ? $dt->format('d M Y H:i') : '-' }}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.orders', ['q' => $c->customer_name]) }}">
                                                <i class="bi bi-list-ul"></i>
                                            </a>
                                            @if(!empty($c->phone))
                                          
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Tidak ada data pelanggan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-3 border-top">
                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>