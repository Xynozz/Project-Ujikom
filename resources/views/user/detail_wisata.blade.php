<!-- filepath: /C:/laragon/www/Hetra_Pemesanan_Tiket/resources/views/detail.blade.php -->
@extends('layouts.user.frontend.template')

@push('css')
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    :root {
        --primary-color: #2563eb;
        --secondary-color: #475569;
        --accent-color: #f59e0b;
    }

    /* Hero Section */
    .hero-section {
        position: relative;
        height: 80vh;
        background: #000;
    }

    .hero-slider {
        height: 100%;
    }

    .hero-slide {
        position: relative;
        height: 100%;
    }

    .hero-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.7;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
    }

    .hero-content {
        position: absolute;
        bottom: 10%;
        left: 0;
        width: 100%;
        padding: 2rem;
        color: white;
        z-index: 2;
    }

    .hero-title {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .hero-subtitle {
        font-size: 1.25rem;
        max-width: 600px;
        margin-bottom: 2rem;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
        margin-top: 1rem;
    }

    .status-open {
        background: var(--primary-color);
        color: white;
    }

    .status-closed {
        background: var(--secondary-color);
        color: white;
    }

    /* Quick Stats */
    .quick-stats {
        margin-top: -100px;
        position: relative;
        z-index: 10;
        padding: 0 1rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        background: var(--primary-color);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .stat-icon i {
        font-size: 1.5rem;
        color: white;
    }

    /* Content Section */
    .content-section {
        padding: 4rem 0;
        background: #f8fafc;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .ticket-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 15px rgba(0,0,0,0.05);
    }

    .ticket-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .ticket-badge {
        background: var(--accent-color);
        color: white;
        padding: 0.25rem 1rem;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .ticket-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    /* Floating Action Button */
    .booking-fab {
        position: fixed;
        bottom: 2rem;
        right: 2rem;
        background: var(--primary-color);
        color: white;
        width: 60px;
        height: 60px;
        border-radius: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
        z-index: 1000;
        text-decoration: none;
    }

    .booking-fab:hover {
        width: 180px;
        border-radius: 30px;
    }

    .booking-fab span {
        display: none;
        margin-left: 0.5rem;
        font-weight: 500;
    }

    .booking-fab:hover span {
        display: block;
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }

        .hero-subtitle {
            font-size: 1rem;
        }

        .content-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section with Slider -->
<section class="hero-section">
    <div class="swiper hero-slider">
        <div class="swiper-wrapper">
            <div class="swiper-slide hero-slide">
                <img src="{{ Storage::url($wisata->thumbnail) }}" alt="{{ $wisata->nama_wisata }}">
                <div class="hero-overlay"></div>
                <div class="hero-content">
                    <h1 class="hero-title">{{ $wisata->nama_wisata }}</h1>
                    <p class="hero-subtitle">{{ $wisata->deskripsi }}</p>
                    <span class="status-badge {{ $wisata->status === 'aktif' ? 'status-open' : 'status-closed' }}">
                        {{ $wisata->status === 'aktif' ? 'Buka' : 'Tutup' }}
                    </span>
                </div>
            </div>
            @foreach($wisata->galeri ?? [] as $foto)
            <div class="swiper-slide hero-slide">
                <img src="{{ Storage::url($foto->path) }}" alt="Gallery">
                <div class="hero-overlay"></div>
            </div>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
    </div>
</section>

<!-- Quick Stats -->
<section class="quick-stats">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class='bx bx-time-five'></i>
            </div>
            <div>
                <h6 class="mb-1">Jam Operasional</h6>
                <p class="mb-0">{{ \Carbon\Carbon::parse($wisata->jam_buka)->format('H:i') }} - {{ \Carbon\Carbon::parse($wisata->jam_tutup)->format('H:i') }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class='bx bx-map'></i>
            </div>
            <div>
                <h6 class="mb-1">Lokasi</h6>
                <p class="mb-0">{{ $wisata->lokasi }}</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class='bx bx-group'></i>
            </div>
            <div>
                <h6 class="mb-1">Kapasitas</h6>
                <p class="mb-0">Unlimited</p>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="content-section">
    <div class="content-grid">
        <!-- Left Column: Description & Features -->
        <div>
            <h2 class="mb-4">Tentang Wisata</h2>
            <p>{{ $wisata->deskripsi }}</p>
            
            <div class="mt-5">
                <h3 class="mb-4">Tiket Tersedia</h3>
                @foreach ($wisata->tiket as $tiket)
                <div class="ticket-card">
                    <div class="ticket-header">
                        <span class="ticket-badge">{{ $tiket->nama_tiket }}</span>
                        <span class="ticket-price">Rp{{ number_format($tiket->harga, 0, ',', '.') }}</span>
                    </div>
                    <p class="mb-0">{{ $tiket->deskripsi }}</p>
                </div>
                @endforeach
            </div>
        </div>
        
        <!-- Right Column: Additional Info -->
        <div>
            <div class="ticket-card">
                <h4 class="mb-3">Informasi Penting</h4>
                <ul class="list-unstyled">
                    <li class="mb-2">✓ Tiket berlaku sesuai tanggal pemesanan</li>
                    <li class="mb-2">✓ Pembayaran aman & terpercaya</li>
                    <li class="mb-2">✓ Konfirmasi instan</li>
                    <li class="mb-2">✓ Reschedule tersedia</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Floating Action Button -->
<a href="#" class="booking-fab">
    <i class='bx bx-cart'></i>
    <span>Pesan Sekarang</span>
</a>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    // Initialize Swiper
    const swiper = new Swiper('.swiper', {
        effect: 'fade',
        autoplay: {
            delay: 3000,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        },
    });
</script>
@endpush