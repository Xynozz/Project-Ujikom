@extends('layouts.user.frontend.template')

@push('css')
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
<style>
    body {
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    .container-fluid {
        padding-left: 0;
        padding-right: 0;
    }
</style>
@endpush

@section('content')
<!-- Carousel Section -->
<section class="hero-carousel-section">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="custom-indicator active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" class="custom-indicator" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" class="custom-indicator" aria-label="Slide 3"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item">
                <div class="carousel-img-wrapper">
                    <img src="{{ asset('user/image/image1 (2).jpg') }}" class="carousel-img" alt="Booking Tiket">
                </div>
                <div class="carousel-caption animate__animated animate__fadeIn">
                    <h2 class="display-4 fw-bold">Liburan Tanpa Khawatir</h2>
                    <p class="lead">Booking tiket wisata dengan mudah dan aman</p>
                    <a href="#booking" class="btn btn-primary btn-lg">
                        <i class='bx bx-ticket me-2'></i>Pesan Tiket
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Banner Section -->
<section class="banner-section">
    <div class="container">
        <div class="banner-grid">
            <div class="banner-card">
                <div class="banner-content">
                    <i class='bx bx-calendar-star banner-icon'></i>
                    <div class="banner-text">
                        <h4>Flash Sale</h4>
                        <p class="mb-0">Diskon hingga 50%</p>
                    </div>
                </div>
            </div>
            <div class="banner-card">
                <div class="banner-content">
                    <i class='bx bx-gift banner-icon'></i>
                    <div class="banner-text">
                        <h4>Promo Spesial</h4>
                        <p class="mb-0">Dapatkan voucher gratis</p>
                    </div>
                </div>
            </div>
            <div class="banner-card">
                <div class="banner-content">
                    <i class='bx bx-card banner-icon'></i>
                    <div class="banner-text">
                        <h4>Cicilan 0%</h4>
                        <p class="mb-0">Pembayaran lebih ringan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ad Banner Section -->
<section class="ad-banner-section">
    <div class="container">
        <div class="ad-banner">
            <div class="ad-content">
                <h2 class="ad-title">Promo Liburan Akhir Tahun!</h2>
                <p class="ad-text">Dapatkan diskon hingga 50% untuk pemesanan tiket di berbagai destinasi wisata populer</p>
            </div>
            <img src="{{ asset('user/image/image1 (3).jpg') }}" alt="Promo Banner" class="ad-image">
        </div>
    </div>
</section>


<!-- Categories Section -->
<section class="section-wrapper categories-section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Kategori Wisata</h2>
        </div>
        <div class="categories-grid">
            <!-- Wisata Alam -->
            <a href="#" class="category-card">
                <div class="category-img">
                    <img src="{{ asset('user/image/categories/wisata-alam.jpg') }}" alt="Wisata Alam">
                </div>
                <div class="category-content">
                    <h5 class="card-title">Wisata Alam</h5>
                    <p class="text-muted small">Jelajahi keindahan alam Indonesia</p>
                    <span class="category-count">24 Tempat</span>
                </div>
            </a>
            <!-- Wisata Pantai -->
            <a href="#" class="category-card">
                <div class="category-img">
                    <img src="{{ asset('/') }}" alt="Wisata Pantai">
                </div>
                <div class="category-content">
                    <h5 class="card-title">Wisata Pantai</h5>
                    <p class="text-muted small">Nikmati keindahan pantai</p>
                    <span class="category-count">18 Tempat</span>
                </div>
            </a>
            <!-- Only show 6 more categories... -->
        </div>
        <div class="view-all-wrapper">
            <a href="#" class="btn btn-outline-primary view-all-btn">
                <span>Lihat Semua</span>
                <i class='bx bx-right-arrow-alt'></i>
            </a>
        </div>
    </div>
</section>

<!-- Ticket Deals Section -->
<section class="section-wrapper ticket-promo-section">
    <div class="container">
        <h2>Wisata Terbaru</h2>
        <div class="tickets-slider position-relative">
            <!-- Navigation buttons moved inside slider container -->
            <button class="slider-nav prev" id="prevBtn">
                <i class='bx bx-chevron-left'></i>
            </button>
            <button class="slider-nav next" id="nextBtn">
                <i class='bx bx-chevron-right'></i>
            </button>

            <div class="tickets-wrapper" id="ticketsWrapper">
                <a href="#" class="ticket-card-link">
                    <div class="ticket-card">
                        <div class="ticket-stub">
                            <div class="stub-content">
                                <h5 class="ticket-title">Pantai Kuta</h5>
                                <span class="location">
                                    <i class='bx bx-map'></i> Bali
                                </span>
                            </div>
                        </div>
                        <div class="ticket-body">
                            <div class="time-info">
                                <i class='bx bx-time'></i> 09:00 - 17:00
                            </div>
                            <div class="price-tag">
                                <span class="price">Rp 75.000</span>
                                <span class="price-suffix">/orang</span>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="#" class="ticket-card-link">
                    <div class="ticket-card">
                        <div class="ticket-stub">
                            <div class="stub-content">
                                <h5 class="ticket-title">Pantai Kuta</h5>
                                <span class="location">
                                    <i class='bx bx-map'></i> Bali
                                </span>
                            </div>
                        </div>
                        <div class="ticket-body">
                            <div class="time-info">
                                <i class='bx bx-time'></i> 09:00 - 17:00
                            </div>
                            <div class="price-tag">
                                <span class="price">Rp 75.000</span>
                                <span class="price-suffix">/orang</span>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="#" class="ticket-card-link">
                    <div class="ticket-card">
                        <div class="ticket-stub">
                            <div class="stub-content">
                                <h5 class="ticket-title">Pantai Kuta</h5>
                                <span class="location">
                                    <i class='bx bx-map'></i> Bali
                                </span>
                            </div>
                        </div>
                        <div class="ticket-body">
                            <div class="time-info">
                                <i class='bx bx-time'></i> 09:00 - 17:00
                            </div>
                            <div class="price-tag">
                                <span class="price">Rp 75.000</span>
                                <span class="price-suffix">/orang</span>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="#" class="ticket-card-link">
                    <div class="ticket-card">
                        <div class="ticket-stub">
                            <div class="stub-content">
                                <h5 class="ticket-title">Pantai Kuta</h5>
                                <span class="location">
                                    <i class='bx bx-map'></i> Bali
                                </span>
                            </div>
                        </div>
                        <div class="ticket-body">
                            <div class="time-info">
                                <i class='bx bx-time'></i> 09:00 - 17:00
                            </div>
                            <div class="price-tag">
                                <span class="price">Rp 75.000</span>
                                <span class="price-suffix">/orang</span>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="#" class="ticket-card-link">
                    <div class="ticket-card">
                        <div class="ticket-stub">
                            <div class="stub-content">
                                <h5 class="ticket-title">Pantai Kuta</h5>
                                <span class="location">
                                    <i class='bx bx-map'></i> Bali
                                </span>
                            </div>
                        </div>
                        <div class="ticket-body">
                            <div class="time-info">
                                <i class='bx bx-time'></i> 09:00 - 17:00
                            </div>
                            <div class="price-tag">
                                <span class="price">Rp 75.000</span>
                                <span class="price-suffix">/orang</span>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="#" class="ticket-card-link">
                    <div class="ticket-card">
                        <div class="ticket-stub">
                            <div class="stub-content">
                                <h5 class="ticket-title">Pantai Kuta</h5>
                                <span class="location">
                                    <i class='bx bx-map'></i> Bali
                                </span>
                            </div>
                        </div>
                        <div class="ticket-body">
                            <div class="time-info">
                                <i class='bx bx-time'></i> 09:00 - 17:00
                            </div>
                            <div class="price-tag">
                                <span class="price">Rp 75.000</span>
                                <span class="price-suffix">/orang</span>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="#" class="ticket-card-link">
                    <div class="ticket-card">
                        <div class="ticket-stub">
                            <div class="stub-content">
                                <h5 class="ticket-title">Pantai Kuta</h5>
                                <span class="location">
                                    <i class='bx bx-map'></i> Bali
                                </span>
                            </div>
                        </div>
                        <div class="ticket-body">
                            <div class="time-info">
                                <i class='bx bx-time'></i> 09:00 - 17:00
                            </div>
                            <div class="price-tag">
                                <span class="price">Rp 75.000</span>
                                <span class="price-suffix">/orang</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Promo Banner -->
<section class="ad-banner-section">
    <div class="container">
        <div class="ad-banner">
            <img src="{{ asset('user/image/image1 (2).jpg') }}" alt="Promo Banner" class="ad-banner-img">
        </div>
    </div>
</section>

<!-- Featured Destinations -->
<section class="section-wrapper destinations-section">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="section-title">Destinasi Populer</h2>
        </div>
        <div class="destinations-grid">
            <!-- First Row -->
            @for ($i = 0; $i < 8; $i++)
            <div class="destination-card">
                <div class="destination-img">
                    <img src="{{ asset('user/image/destinations/kuta.jpg') }}" alt="Pantai Kuta">
                    <span class="status-badge">Buka</span>
                </div>
                <div class="destination-content">
                    <h5 class="destination-title">Pantai Kuta</h5>
                    <div class="destination-info">
                        <div class="info-item">
                            <i class='bx bx-time'></i>
                            <span>06:00 - 18:00</span>
                        </div>
                        <a href="#" class="btn-detail">Lihat Detail</a>
                    </div>
                </div>
            </div>
            @endfor
        </div>
        <div class="view-more-wrapper text-center">
            <a href="#" class="btn-view-more">
                <span>Lihat Semua Destinasi</span>
                {{-- <i class='bx bx-right-arrow-alt'></i> --}}
            </a>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="section-wrapper">
    <div class="container">
        <h2 class="section-title">Mengapa Memilih Kami?</h2>
        <div class="grid-container features-grid">
            <!-- Feature Cards -->
            <div class="features-card text-center">
                <i class="bx bx-check-shield feature-icon"></i>
                <h4>Aman & Terpercaya</h4>
                <p class="text-muted">Pembayaran dan transaksi dijamin aman</p>
            </div>
            <div class="features-card text-center">
                <i class="bx bx-support feature-icon"></i>
                <h4>Layanan 24/7</h4>
                <p class="text-muted">Dukungan pelanggan selama 24 jam</p>
            </div>
            <div class="features-card text-center">
                <i class="bx bx-wallet feature-icon"></i>
                <h4>Pembayaran Mudah</h4>
                <p class="text-muted">Berbagai metode pembayaran tersedia</p>
            </div>
        </div>
    </div>
</section>


<!-- Newsletter Section -->
<section class="section-wrapper newsletter-section">
    <div class="container">
        <div class="newsletter-grid">
            <div class="newsletter-info">
                <h2>Dapatkan Info Promo Terbaru</h2>
                <p class="newsletter-desc">Berlangganan newsletter kami untuk mendapatkan informasi promo dan update terbaru.</p>
                <div class="benefits-grid">
                    <div class="benefit-item">
                        <i class='bx bx-gift'></i>
                        <span>Promo Eksklusif</span>
                    </div>
                    <div class="benefit-item">
                        <i class='bx bx-bell'></i>
                        <span>Update Terbaru</span>
                    </div>
                    <div class="benefit-item">
                        <i class='bx bx-discount'></i>
                        <span>Diskon Spesial</span>
                    </div>
                </div>
            </div>
            <div class="newsletter-form-wrapper">
                <form class="newsletter-form">
                    <div class="form-group">
                        <label for="emailInput">Email</label>
                        <input type="email" class="form-control" id="emailInput" placeholder="Masukkan email Anda">
                    </div>
                    <button class="btn btn-primary w-100" type="submit">
                        <i class='bx bx-paper-plane'></i>
                        Berlangganan Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('user/js/home.js') }}"></script>
@endpush