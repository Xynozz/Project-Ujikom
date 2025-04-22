@extends('layouts.user.frontend.template')
@push('css')
<link rel="stylesheet" href="{{ asset('user/css/detail-wisata.css') }}">
<style>
    .main-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        padding: 1rem 1rem;
    }

    .success-container {
        background-color: white;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 600px;
        margin: 2rem auto;
        padding: 2.5rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .success-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(to right, #16a34a, #22c55e);
    }

    .success-icon {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 0 auto 2rem;
        box-shadow: 0 10px 20px rgba(22, 163, 74, 0.15);
        animation: pulseIcon 2s infinite;
    }

    .checkmark {
        color: #16a34a;
        font-size: 3.5rem;
        line-height: 1;
    }

    h1 {
        color: #111827;
        font-size: 1.875rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .success-message {
        color: #64748b;
        margin-bottom: 2rem;
        font-size: 1.125rem;
        line-height: 1.6;
        max-width: 80%;
        margin-left: auto;
        margin-right: auto;
    }

    .payment-details {
        background: #f8fafc;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 2rem;
        border: 1px solid #e2e8f0;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px dashed #e2e8f0;
    }

    .detail-row:last-child {
        margin-bottom: 0;
        padding-bottom: 1rem;
        border-bottom: none;
        background: #f0fdf4;
        border-radius: 12px;
    }

    .detail-label {
        color: #64748b;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-label i {
        font-size: 1.25rem;
        color: #16a34a;
    }

    .detail-value {
        color: #1f2937;
        font-weight: 600;
    }

    .total-label,
    .total-value {
        font-size: 1.25rem;
        color: #16a34a;
        font-weight: 700;
    }

    .button-group {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        justify-content: center;
    }

    .primary-button {
        background: linear-gradient(135deg, #16a34a, #22c55e);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 1rem 2rem;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(22, 163, 74, 0.2);
    }

    .primary-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(22, 163, 74, 0.3);
    }

    .secondary-button {
        background: transparent;
        color: #16a34a;
        border: 2px solid #16a34a;
        border-radius: 12px;
        padding: 1rem 2rem;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .secondary-button:hover {
        background: #f0fdf4;
        transform: translateY(-2px);
    }

    .transaction-id {
        font-size: 0.875rem;
        color: #64748b;
        margin-top: 1.5rem;
        padding: 0.75rem;
        background: #f8fafc;
        border-radius: 8px;
        display: inline-block;
    }

    @keyframes pulseIcon {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    @media (max-width: 640px) {
        .success-container {
            padding: 1.5rem;
        }

        .button-group {
            flex-direction: column;
        }

        .success-message {
            max-width: 100%;
        }
    }
</style>
@endpush

@section('content')
<main class="main-container">
    <div class="success-container">
        <div class="success-icon">
            <span class="checkmark">✓</span>
        </div>

        <h1>Pembayaran Berhasil!</h1>
        <p class="success-message">
            Terima kasih atas pembayaran Anda. E-tiket telah dikirim ke email Anda.
        </p>

        <div class="payment-details">
            <div class="detail-row">
                <span class="detail-label">
                    <i class='bx bx-calendar'></i>
                    Tanggal Pemesanan
                </span>
                <span class="detail-value">{{ \Carbon\Carbon::now()->format('d M Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">
                    <i class='bx bx-map'></i>
                    Wisata
                </span>
                <span class="detail-value">{{ $pemesanan->wisata->nama_wisata }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">
                    <i class='bx bx-purchase-tag'></i>
                    Jumlah Tiket
                </span>
                <span class="detail-value">{{ $pemesanan->jumlah_tiket }} Tiket</span>
            </div>
            <div class="detail-row">
                <span class="total-label">
                    <i class='bx bx-money'></i>
                    Total Pembayaran
                </span>
                <span class="total-value">Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="button-group">
            {{-- <a href="{{ route('pemesanan.download', $pemesanan->kode_pemesanan) }}" class="primary-button">
                <i class='bx bx-download'></i>
                Unduh E-Tiket
            </a> --}}
            <a href="{{ url('/') }}" class="secondary-button">
                <i class='bx bx-home'></i>
                Kembali ke Beranda
            </a>
        </div>

        <p class="transaction-id">
            <i class='bx bx-hash'></i>
            {{-- Kode Pemesanan: {{ $pemesanan->kode_pemesanan }} --}}
        </p>
    </div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 }
        });
    });
</script>
@endpush