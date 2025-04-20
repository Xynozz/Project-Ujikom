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
<!-- Hero Section with Background -->
<div class="hero">
    <div class="hero-text">
        <h1>Hallo Guys, Mau healing kemana nih?</h1>
        <p>Rencanakan liburanmu dengan satu aplikasi pemesanan tiket.</p>
    </div>
</div>

<!-- Banner Section -->
<section class="banner-section">
    <div class="container">
        <div class="banner-grid">
            <div class="banner-card">
                <div class="banner-content">
                    <i class='bx bx-calendar-star banner-icon'></i>
                    <div class="banner-text">
                        <h4>Tanggal Keberangkatan</h4>
                        <p class="mb-0">Pilih tanggal keberangkatan</p>
                    </div>
                </div>
            </div>
            <div class="banner-card">
                <div class="banner-content">
                    <i class='bx bx-credit-card banner-icon'></i>
                    <div class="banner-text">
                        <h4>Pembayaran Mudah</h4>
                        <p class="mb-0">Pengalaman pembayaran lebih mudah</p>
                    </div>
                </div>
            </div>
            <div class="banner-card">
                <div class="banner-content">
                    <i class='bx bx-card banner-icon'></i>
                    <div class="banner-text">
                        <h4>Mudah diakses</h4>
                        <p class="mb-0">Pemesanan tiket lebih mudah</p>
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
                <p class="ad-text">Dapatkan diskon hingga 50% untuk pemesanan tiket di berbagai destinasi wisata populer
                </p>
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
            @foreach ($kategori as $data)
            <a href="#" class="category-card">
                <div class="category-img">
                    <img src="{{ Storage::url($data->icon) }}" alt="{{ $data->nama_kategori }}" alt="Kategori">
                </div>
                <div class="category-content">
                    <h5 class="card-title">{{ $data->nama_kategori }}</h5>
                </div>
            </a>
            @endforeach
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
            @foreach ($wisata as $destinasi)
            <div class="destination-card">
                <div class="destination-img">
                    <img src="{{ Storage::url($destinasi->thumbnail) }}" alt="{{ $destinasi->nama_wisata }}">
                    <span class="status-badge">
                        {{ $destinasi->status === 'aktif' ? 'Buka' : 'Tutup' }}
                    </span>
                </div>
                <div class="destination-content">
                    <h5 class="destination-title">{{ $destinasi->nama_wisata }}</h5>
                    <div class="destination-info">
                        <div class="info-item">
                            <i class='bx bx-time'></i>
                            <span>
                                {{ \Carbon\Carbon::parse($destinasi->jam_buka)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($destinasi->jam_tutup)->format('H:i') }}
                            </span>
                        </div>
                        <a href="{{ route('detail-wisata', $destinasi->id) }}" class="btn-detail">Lihat Detail</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="view-more-wrapper text-center">
            <a href="#" class="btn-view-more">
                <span>Lihat Semua Destinasi</span>
                <i class='bx bx-right-arrow-alt'></i>
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
    <footer class="footer bg-light pt-5 pb-4">
        <div class="container">
            <div class="row align-items-start newsletter-grid">
                <!-- Tentang Kami -->
                <div class="col-md-6">
                    <h2 class="mb-3">Tentang Kami</h2>
                    <p style="max-width: 500px; text-align: justify;">
                        Kami adalah platform pemesanan tiket wisata yang hadir untuk memudahkan perjalanan Anda ke berbagai destinasi menarik di seluruh Indonesia. Dengan sistem pemesanan yang mudah, cepat, dan aman, kami berkomitmen untuk memberikan pengalaman terbaik bagi setiap pengguna. Didukung oleh teknologi terkini dan tim profesional yang berdedikasi, kami siap membantu Anda menjelajahi keindahan alam, budaya, dan atraksi lokal dengan penawaran terbaik dan promo eksklusif.
                    </p>
                </div>

                <!-- Kategori Wisata & Destinasi Populer -->
                <div class="col-md-6 benefits-grid">
                    <div class="d-flex justify-content-between">
                        <!-- Kategori Wisata -->
                        <div class="me-4" style="min-width: 48%;">
                            <h4 class="mb-3 benefit-item">Kategori Wisata</h4>
                            <ul class="list-unstyled">
                                @foreach ($kategori->take(5) as $item)
                                    <li>
                                        <a href="#" class="plain-link text-decoration-none text-dark">{{ $item->nama_kategori }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Destinasi Populer -->
                        <div style="min-width: 48%;">
                            <h4 class="mb-3 benefit-item">Destinasi Populer</h4>
                            <ul class="list-unstyled">
                                @foreach ($wisata->where('status', 'aktif')->take(5) as $item)
                                    <li>
                                        <a href="{{ route('detail-wisata', $item->id) }}" class="plain-link text-decoration-none text-dark">{{ $item->nama_wisata }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <div class="container">
        <div class="newsletter-grid">
            <div class="newsletter-info">
                <h2>Dapatkan Info Promo Terbaru</h2>
                <p class="newsletter-desc">Berlangganan newsletter kami untuk mendapatkan informasi promo dan update
                    terbaru.</p>
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
                <form class="newsletter-form-horizontal">
                    <label for="emailInput" class="form-label" style="margin-bottom: 10px;">Email</label>
                    <input type="email" class="form-control" id="emailInput" placeholder="Masukkan email Anda">
                    <button class="btn btn-primary" type="submit">
                        <i class='bx bx-paper-plane'></i> Berlangganan
                    </button>
                </form>
            </div>
        </div>
        <hr class="my-4">

            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} BookingTiket. Semua Hak Dilindungi.</p>
            </div>
    </div>
</section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('user/js/home.js') }}"></script>
@endpush