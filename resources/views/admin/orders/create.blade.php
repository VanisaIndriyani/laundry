<x-layouts.admin title="Buat Order">
    <div class="container-fluid py-4">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div>
                <h2 class="fw-bold mb-1">Buat Order</h2>
                <p class="text-muted mb-0">Isi detail pesanan pelanggan dengan lengkap dan jelas.</p>
            </div>
            <div>
                <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <i class="bi bi-exclamation-triangle me-1"></i> Periksa input Anda. Beberapa field belum valid.
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.orders.store') }}" class="row g-3">
                    @csrf
                    <div class="col-12 col-md-6">
                        <label class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" class="form-control" placeholder="Nama lengkap" required>
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label">No. HP</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="Contoh: 081234567890">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi Item</label>
                        <textarea name="items" class="form-control" rows="3" placeholder="Contoh: Cuci kering 5kg, Setrika 2kg">{{ old('items') }}</textarea>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Jumlah (kg)</label>
                        <input type="number" name="quantity" value="{{ old('quantity') }}" class="form-control" placeholder="0" step="0.01" min="0">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Total Harga (Rp)</label>
                        <input type="number" name="total_price" value="{{ old('total_price') }}" class="form-control" placeholder="0" step="1" min="0">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Jatuh Tempo</label>
                        <input type="datetime-local" name="due_at" value="{{ old('due_at') }}" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Instruksi khusus, catatan tambahan">{{ old('notes') }}</textarea>
                    </div>
                    <div class="col-12 d-flex gap-2">
                        <button class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button>
                        <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.admin>