@extends('layouts.app')

@section('title', 'Tambah Transaksi Inventaris')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tambah Transaksi Baru</div>
                <div class="card-body">
                    <form action="{{ route('inventory.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="transaction_date" class="form-label">Tanggal Transaksi</label>
                            <input type="datetime-local" class="form-control @error('transaction_date') is-invalid @enderror"
                                id="transaction_date" name="transaction_date" value="{{ old('transaction_date') }}" required>
                            @error('transaction_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="product_id" class="form-label">Produk</label>
                            <select class="form-select @error('product_id') is-invalid @enderror" id="product_id" name="product_id" required>
                                <option value="" disabled {{ old('product_id', $selectedProductId ?? null) ? '' : 'selected' }}>Pilih Produk...</option>
                                @foreach ($products as $product)
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
                                <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>Masuk</option>
                                <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>Keluar</option>
                            </select>
                            @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Jumlah</label>
                            <div class="input-group">
                                <button class="btn btn-outline-secondary" type="button" id="quantity-minus">-</button>
                                <input type="number" class="form-control text-center @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', 1) }}" required min="1">
                                <button class="btn btn-outline-secondary" type="button" id="quantity-plus">+</button>
                            </div>
                            @error('quantity')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Catatan (Opsional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('inventory.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- 1. Set Tanggal & Waktu Lokal Saat Ini ---
        const dateInput = document.getElementById('transaction_date');
        if (!dateInput.value) {
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            dateInput.value = now.toISOString().slice(0, 16);
        }

        // --- 2. Fungsionalitas Tombol Tambah & Kurang Jumlah ---
        const quantityInput = document.getElementById('quantity');
        document.getElementById('quantity-minus').addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value, 10);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
            }
        });
        document.getElementById('quantity-plus').addEventListener('click', function() {
            quantityInput.value = parseInt(quantityInput.value, 10) + 1;
        });
    });
</script>
@endpush