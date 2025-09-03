@extends('layouts.app')

@section('title', 'Riwayat Transaksi')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Riwayat Transaksi</h1>
        <div>
            <a href="{{ route('inventory.scan') }}" class="btn btn-info">
                <i class="bi bi-qr-code-scan"></i> Pindai QR
            </a>
            <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Transaksi
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Ringkasan Total Sesuai Filter --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">Total Nilai Keluar (Penjualan)</h5>
                    <p class="card-text fs-4 fw-bold">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Nilai Masuk (Pembelian)</h5>
                    <p class="card-text fs-4 fw-bold">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Form --}}
    <div class="card mb-4">
        <div class="card-header">Filter Transaksi</div>
        <div class="card-body">
            <form action="{{ route('inventory.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label for="product_id" class="form-label">Produk</label>
                    <select name="product_id" id="product_id" class="form-select">
                        <option value="">Semua Produk</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ (isset($filters['product_id']) && $filters['product_id'] == $product->id) ? 'selected' : '' }}>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="type" class="form-label">Tipe</label>
                    <select name="type" id="type" class="form-select">
                        <option value="">Semua Tipe</option>
                        <option value="in" {{ (isset($filters['type']) && $filters['type'] == 'in') ? 'selected' : '' }}>Masuk</option>
                        <option value="out" {{ (isset($filters['type']) && $filters['type'] == 'out') ? 'selected' : '' }}>Keluar</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Dari Tanggal</label>
                    <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $filters['start_date'] ?? '' }}">
                </div>
                <div class="col-md-2">
                    <label for="end_date" class="form-label">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $filters['end_date'] ?? '' }}">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
                <div class="col-auto">
                    <a href="{{ route('inventory.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Transactions Table --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Produk</th>
                            <th>Tipe</th>
                            <th class="text-end">Jumlah</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Total Harga</th>
                            <th>Tanggal</th>
                            <th>Catatan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->id }}</td>
                            <td>{{ $transaction->product->name ?? 'N/A' }}</td>
                            <td>
                                @if ($transaction->type == 'in')
                                    <span class="badge bg-success">Masuk</span>
                                @else
                                    <span class="badge bg-danger">Keluar</span>
                                @endif
                            </td>
                            <td class="text-end">{{ $transaction->quantity }}</td>
                            <td class="text-end">Rp {{ number_format($transaction->product->price ?? 0, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($transaction->quantity * ($transaction->product->price ?? 0), 0, ',', '.') }}</td>
                            <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y, H:i') }}</td>
                            <td>{{ $transaction->notes ?: '-' }}</td>
                            <td class="text-nowrap text-end">
                                <a href="{{ route('inventory.edit', $transaction->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <a href="{{ route('inventory.confirm-delete', $transaction->id) }}" class="btn btn-sm btn-danger">Hapus</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">Tidak ada data transaksi yang cocok dengan filter.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
</div>
@endsection