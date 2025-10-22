@php
    $title = 'Laporan';
@endphp

<x-layouts.admin :title="$title">
    <div class="container-fluid py-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="mb-0">Laporan Transaksi</h4>
        </div>

        <!-- Filter -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.reports') }}" class="row g-2 align-items-end">
                    <div class="col-12 col-md-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ $start }}" class="form-control">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" value="{{ $end }}" class="form-control">
                    </div>
                    <div class="col-12 col-md-2">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            @php $statuses = ['' => 'Semua', 'received' => 'Diterima', 'washing' => 'Dicuci', 'drying' => 'Pengeringan', 'ironing' => 'Disetrika', 'ready' => 'Siap Diambil', 'picked_up' => 'Diambil']; @endphp
                            @foreach($statuses as $key => $label)
                                <option value="{{ $key }}" @if(($status ?? '') === $key) selected @endif>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6 col-md-2">
                        <label class="form-label">Per halaman</label>
                        <select name="per_page" class="form-select">
                            @foreach([10,25,50,100] as $pp)
                                <option value="{{ $pp }}" @if($perPage == $pp) selected @endif>{{ $pp }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-2 d-flex justify-content-end gap-2">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-funnel me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.reports') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Ringkasan -->
        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted">Total Pesanan</div>
                        <div class="fs-4 fw-bold">{{ number_format($summary['total_orders']) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted">Total Jumlah (kg)</div>
                        <div class="fs-4 fw-bold">{{ number_format($summary['total_quantity']) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted">Total Pendapatan (Rp)</div>
                        <div class="fs-4 fw-bold">Rp {{ number_format($summary['total_income']) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="text-muted">Distribusi Status</div>
                        <div class="small">
                            @foreach($statusCounts as $s => $cnt)
                                <div class="d-flex justify-content-between">
                                    <span class="text-capitalize">{{ str_replace(['received','washing','drying','ironing','ready','picked_up'], ['Diterima','Dicuci','Pengeringan','Disetrika','Siap Diambil','Diambil'], $s) }}</span>
                                    <span class="fw-semibold">{{ $cnt }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Pesanan -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Pelanggan</th>
                                <th>Status</th>
                                <th>Jumlah (kg)</th>
                                <th>Total (Rp)</th>
                                <th>Dibuat</th>
                                <th>Selesai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $o)
                                <tr>
                                    <td class="font-monospace">{{ $o->code }}</td>
                                    <td>
                                        <div class="fw-semibold">{{ $o->customer_name }}</div>
                                        <div class="text-muted small">{{ $o->phone }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary text-capitalize">{{ $o->status }}</span>
                                    </td>
                                    <td>{{ (int)($o->quantity ?? 0) }}</td>
                                    <td>Rp {{ number_format((float)($o->total_price ?? 0)) }}</td>
                                    <td>{{ $o->created_at?->format('d M Y H:i') }}</td>
                                    <td>{{ $o->completed_at?->format('d M Y H:i') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="text-center text-muted">Tidak ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div>
                    {{ $orders->links() }}
                </div>
            </div>
        </div>

        <!-- Harian -->
        <div class="card">
            <div class="card-header">Ringkasan Harian</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Pesanan</th>
                                <th>Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($daily as $d)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($d->day)->format('d M Y') }}</td>
                                    <td>{{ $d->orders }}</td>
                                    <td>Rp {{ number_format($d->income) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>