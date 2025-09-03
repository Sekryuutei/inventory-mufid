@extends('layouts.app')

@section('title', 'Tambah Transaksi - Drass Bird Shop')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">Tambah Transaksi Baru</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('inventory.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="transaction_date" class="form-label">Tanggal Transaksi</label>
                        <input type="datetime-local" class="form-control @error('transaction_date') is-invalid @enderror"
                            id="transaction_date" name="transaction_date"
                            value="{{ old('transaction_date', now()->format('Y-m-d\TH:i')) }}" required>
                        @error('transaction_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Produk</label>
                        <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                            {{-- Opsi placeholder, terpilih jika tidak ada produk yang dipilih dari QR atau validasi lama --}}
                            <option value="" disabled {{ old('product_id', $selectedProductId ?? null) ? '' : 'selected' }}>Pilih Produk...</option>
                            @foreach ($products as $product)
                            {{-- Cek old('product_id') dulu (jika ada error validasi), baru cek $selectedProductId (dari QR) --}}
                            <option value="{{ $product->id }}"
                                {{ (old('product_id', $selectedProductId ?? null) == $product->id) ? 'selected' : '' }}>
                                {{ $product->name }} (Stok: {{ $product->stock }})
                            </option>
                            @endforeach
                        </select>
                        @error('product_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Tipe Transaksi</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                            <option value="in" {{ old('type', 'in') == 'in' ? 'selected' : '' }}>Barang Masuk</option>
                            <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>Barang Keluar</option>
                        </select>
                        @error('type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="quantity" class="form-label">Jumlah</label>
                        <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" required min="1">
                        @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label">Catatan <span class="text-muted">(Opsional)</span></label>
                        <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Hapus field transaction_date dari form karena akan di-set otomatis -->

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('inventory.index') }}" class="btn btn-link text-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save-fill me-1"></i>
                            Simpan Transaksi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Validasi client-side untuk stok saat memilih transaksi keluar
    document.getElementById('type').addEventListener('change', function() {
        const type = this.value;
        const productSelect = document.getElementById('product_id');
        const quantityInput = document.getElementById('quantity');

        if (type === 'out' && productSelect.value) {
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const stockText = selectedOption.text.match(/Stok: (\d+)/);

            if (stockText) {
                const stock = parseInt(stockText[1]);
                quantityInput.setAttribute('max', stock);

                if (quantityInput.value > stock) {
                    quantityInput.value = stock;
                    alert('Jumlah melebihi stok yang tersedia. Nilai telah disesuaikan.');
                }
            }
        } else {
            quantityInput.removeAttribute('max');
        }
    });

    document.getElementById('product_id').addEventListener('change', function() {
        // Trigger change event pada type untuk memvalidasi stok
        document.getElementById('type').dispatchEvent(new Event('change'));
    });

    // Tambahan: Trigger event saat halaman pertama kali dimuat
    // untuk menangani kasus produk sudah terpilih dari QR code atau error validasi
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('product_id').value) {
            document.getElementById('type').dispatchEvent(new Event('change'));
        }
    });
</script>
@endsection