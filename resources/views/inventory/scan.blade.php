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
        function onScanSuccess(decodedText, decodedResult) {
            // decodedText berisi URL dari QR code
            console.log(`Scan result: ${decodedText}`, decodedResult);

            // Hentikan pemindaian
            html5QrcodeScanner.clear();

            // Tampilkan pesan dan redirect
            const resultContainer = document.getElementById('qr-reader-results');
            resultContainer.innerHTML = `<div class="alert alert-success">QR Code berhasil dipindai! Mengarahkan...</div>`;

            // Redirect ke URL yang ada di QR code
            window.location.href = decodedText;
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "qr-reader",
            { fps: 10, qrbox: { width: 250, height: 250 } },
            /* verbose= */ false
        );
        html5QrcodeScanner.render(onScanSuccess, (error) => {});
    });
</script>
@endpush
