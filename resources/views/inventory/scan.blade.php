@extends('layouts.app')

@section('title', 'Pindai QR Code')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Pindai QR Code Produk</div>
                <div class="card-body text-center">
                    <p>Arahkan kamera ke QR code produk untuk membuat transaksi baru.</p>
                    <div id="qr-reader" style="width: 100%;"></div>
                    <div id="qr-reader-results" class="mt-3"></div>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Kembali</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Library untuk memindai QR Code --}}
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const resultContainer = document.getElementById('qr-reader-results');

        function onScanSuccess(decodedText, decodedResult) {
            // decodedText sekarang berisi ID produk (contoh: "5")
            console.log(`Hasil Pindai: ${decodedText}`);

            // Hentikan pemindaian
            html5QrcodeScanner.clear();

            // Validasi sederhana untuk memastikan hasil pindaian adalah angka
            if (isNaN(parseInt(decodedText))) {
                resultContainer.innerHTML = `<div class="alert alert-danger">QR Code tidak valid.</div>`;
                return;
            }

            // Tampilkan pesan dan redirect
            resultContainer.innerHTML = `<div class="alert alert-success">QR Code berhasil dipindai! Mengarahkan...</div>`;

            // Bangun URL tujuan secara manual menggunakan route dari Laravel
            const baseUrl = "{{ route('inventory.create') }}";
            const redirectUrl = `${baseUrl}?product_id=${decodedText}`;

            // Arahkan ke URL yang sudah dibangun
            window.location.href = redirectUrl;
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader",
            { fps: 10, qrbox: { width: 250, height: 250 } },
            /* verbose= */ false
        );

        function onScanFailure(error) {
            // Tidak melakukan apa-apa saat gagal memindai, agar tidak ada log yang mengganggu
        }

        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    });
</script>
@endpush
