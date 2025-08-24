@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Detail Produk') }}</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nama Produk') }}</label>

                        <div class="col-md-6">
                            <p class="form-control-plaintext">{{ $product->name }}</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="col-md-4 col-form-label text-md-right">{{ __('Deskripsi') }}</label>

                        <div class="col-md-6">
                            <p class="form-control-plaintext">{{ $product->description ?? 'Tidak ada deskripsi' }}</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="stock" class="col-md-4 col-form-label text-md-right">{{ __('Stok') }}</label>

                        <div class="col-md-6">
                            <p class="form-control-plaintext">{{ $product->stock }}</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="price" class="col-md-4 col-form-label text-md-right">{{ __('Harga') }}</label>

                        <div class="col-md-6">
                            <p class="form-control-plaintext">Rp {{ number_format($product->price, 2, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">{{ __('Dibuat pada') }}</label>

                        <div class="col-md-6">
                            <p class="form-control-plaintext">{{ $product->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right">{{ __('Diperbarui pada') }}</label>

                        <div class="col-md-6">
                            <p class="form-control-plaintext">{{ $product->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">
                                {{ __('Edit Produk') }}
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                {{ __('Kembali ke Daftar') }}
                            </a>
                            
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                    {{ __('Hapus Produk') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection