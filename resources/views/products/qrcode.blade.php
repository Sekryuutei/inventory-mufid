@extends('layouts.app')

@section('title', 'QR Code untuk ' . $product->name)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>QR Code untuk: {{ $product->name }}</h4>
                </div>
                <div class="card-body text-center">
                    <p>Pindai QR code ini untuk menambahkan transaksi inventaris (stok masuk/keluar) untuk produk ini.</p>
                    <div class="my-3">
                        {!! $qrCode !!}
                    </div>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">Kembali ke Daftar Produk</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection