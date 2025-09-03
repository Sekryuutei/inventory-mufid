@extends('layouts.app')

@section('title', 'Kelola Produk')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Kelola Produk</h1>
        <div>
            <a href="{{ route('inventory.scan') }}" class="btn btn-info">
                <i class="bi bi-qr-code-scan"></i> Pindai QR
            </a>
            <a href="{{ route('products.create') }}" class="btn btn-primary">Tambah Produk Baru</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th class="text-end">Stok</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="text-end">Total Nilai Stok</th>
                            <th>Deskripsi</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td class="text-end">{{ $product->stock }}</td>
                            <td class="text-end">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="text-end fw-bold">Rp {{ number_format($product->stock * $product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->description }}</td>
                            <td class="text-nowrap text-end">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-info">View</a>
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                                <a href="{{ route('products.qrcode', $product) }}" class="btn btn-secondary" title="Tampilkan QR Code">
                                    <i class="bi bi-qr-code"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">{{ $products->links() }}</div>
</div>
@endsection