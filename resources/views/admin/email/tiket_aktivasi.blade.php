<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tiket Diaktivasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 {
            color: #333;
        }
        p {
            color: #555;
        }
        .qr-button {
            display: inline-block;
            background-color: #1d72b8;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 4px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h2>Tiket Anda Telah Diaktivasi 🎫</h2>
        <p>Halo, <strong>{{ $detail->pemesanan->user->name }}</strong>,</p>

        <p>Tiket Anda dengan ID <strong>{{ $detail->id }}</strong> telah berhasil diaktivasi.</p>

        <ul>
            <li><strong>Status:</strong> {{ $detail->status }}</li>
            <li><strong>Tanggal Aktivasi:</strong> {{ $detail->expired_at }}</li>
        </ul>

        @if ($detail->qr_path)
            <a class="qr-button" href="{{ url('storage/' . $detail->qr_path) }}" target="_blank">Lihat QR Code</a>
        @endif

        <div class="footer">
            <p>Terima kasih telah menggunakan layanan kami.</p>
            <p><strong>Aplikasi Pemesanan Tiket</strong></p>
        </div>
    </div>
</body>
</html>
