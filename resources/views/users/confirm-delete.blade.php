@extends('layouts.app')

@section('title', 'Konfirmasi Hapus Pengguna - Drass Bird Shop')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h3 class="mb-0">Konfirmasi Hapus Pengguna</h3>
                </div>

                <div class="card-body">
                    <p>Apakah Anda yakin ingin menghapus pengguna ini?</p>
                    
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>Detail Pengguna</h5>
                            <p><strong>Nama:</strong> {{ $user->name }}</p>
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                            <p><strong>Role:</strong> {{ Str::ucfirst($user->role) }}</p>
                            <p><strong>Tanggal Dibuat:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash"></i> Hapus Pengguna
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 