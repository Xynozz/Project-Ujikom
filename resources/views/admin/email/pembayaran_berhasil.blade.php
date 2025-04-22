<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pemesanan Berhasil</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        p {
            color: #555;
            font-size: 16px;
            line-height: 1.6;
        }

        .details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .details p {
            margin: 5px 0;
        }

        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Pemesanan Tiket Berhasil 🎫</h2>
        <p>Hai <strong>{{ $pemesanan->user->name }}</strong>,</p>

        <p>Terima kasih telah melakukan pemesanan melalui <strong>Aplikasi Pemesanan Tiket</strong>. Berikut detail pemesananmu:</p>

        <div class="details">
            <p><strong>ID Pemesanan:</strong> {{ $pemesanan->id }}</p>
            <p><strong>Nama Wisata:</strong> {{ $pemesanan->wisata->nama_wisata ?? '-' }}</p>
            <p><strong>Tanggal Pemesanan:</strong> {{ $pemesanan->created_at->format('d M Y H:i') }}</p>
            <p><strong>Total Pembayaran:</strong> Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</p>
            <p><strong>Status:</strong> {{ ucfirst($pemesanan->status) }}</p>
        </div>

        <p>Silakan lanjutkan ke pembayaran atau cek status pemesanan di dashboard kamu.</p>

        <div class="footer">
            <p>Salam hangat,</p>
            <p><strong>Tim Aplikasi Pemesanan Tiket</strong></p>
        </div>
    </div>
</body>
</html>
