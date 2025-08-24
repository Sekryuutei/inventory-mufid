@extends('layouts.app')

@section('title', 'Riwayat Transaksi - Drass Bird Shop')

@section('content')
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Riwayat Transaksi Stok</h2>
            <a href="{{ route('inventory.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle-fill me-1"></i>
                Tambah Transaksi
            </a>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col">Produk</th>
                        <th scope="col">Tipe</th>
                        <th scope="col">Jumlah</th>
                        <th scope="col">Catatan</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <th scope="row">{{ $transactions->firstItem() + $loop->index }}</th>
                            <td>{{ \Carbon\Carbon::parse($transaction->transaction_date)->isoFormat('D MMM YYYY, HH:mm') }}</td>
                            <td>{{ $transaction->product->name }}</td>
                            <td>
                                @if ($transaction->type == 'in')
                                    <span class="badge text-bg-success"><i class="bi bi-arrow-down-circle me-1"></i> Masuk</span>
                                @else
                                    <span class="badge text-bg-danger"><i class="bi bi-arrow-up-circle me-1"></i> Keluar</span>
                                @endif
                            </td>
                            <td>{{ $transaction->quantity }}</td>
                            <td>{{ $transaction->notes ?? '-' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('inventory.edit', $transaction->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="{{ route('inventory.confirm-delete', $transaction->id) }}" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada data transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection