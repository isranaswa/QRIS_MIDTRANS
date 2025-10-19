<!DOCTYPE html>
<html>
<head>
    <title>Pembayaran QRIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow p-4 text-center" style="width: 350px;">
        <h4>Scan untuk Bayar</h4>
        <p class="text-muted">Order ID: {{ $order_id }}</p>

        <img src="https://api.qrserver.com/v1/create-qr-code/?data={{ urlencode($qris_url) }}&size=200x200"
             alt="QRIS" class="img-fluid mb-3">

        <h5 class="text-success">Rp {{ number_format($amount, 0, ',', '.') }}</h5>

        <a href="{{ $qris_url }}" target="_blank" class="btn btn-primary w-100 mb-2">Buka Link Pembayaran</a>
        <a href="{{ route('bayar') }}" class="btn btn-outline-secondary w-100">Kembali</a>

        <small class="text-muted mt-3 d-block">
            *Sandbox tidak bisa bayar real, gunakan link untuk demo.
        </small>
    </div>
</body>
</html>
