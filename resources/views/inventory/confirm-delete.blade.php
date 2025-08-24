@extends('layouts.app')

@section('title', 'Konfirmasi Hapus Transaksi - Drass Bird Shop')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h3 class="mb-0">Konfirmasi Hapus Transaksi</h3>
                </div>

                <div class="card-body">
                    <p>Apakah Anda yakin ingin menghapus transaksi ini?</p>
                    
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>Detail Transaksi</h5>
                            <p><strong>Produk:</strong> {{ $transaction->product->name }}</p>
                            <p><strong>Tipe:</strong> {{ $transaction->type == 'in' ? 'Barang Masuk' : 'Barang Keluar' }}</p>
                            <p><strong>Jumlah:</strong> {{ $transaction->quantity }}</p>
                            <p><strong>Tanggal:</strong> 
                                @if($transaction->transaction_date instanceof \Carbon\Carbon)
                                    {{ $transaction->transaction_date->format('d/m/Y H:i') }}
                                @else
                                    {{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d/m/Y H:i') }}
                                @endif
                            </p>
                            @if($transaction->notes)
                                <p><strong>Catatan:</strong> {{ $transaction->notes }}</p>
                            @endif
                        </div>
                    </div>

                    <form action="{{ route('inventory.destroy', $transaction->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('inventory.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Hapus Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection