{{-- @extends('layouts.user.template') --}}

{{-- @push('css') --}}
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<style>
    :root {
        --primary-color: #696cff;
        --secondary-color: #4f46e5;
        --gradient-start: #7c3aed;
        --gradient-end: #2563eb;
    }

    /* Hero Section - Updated gradient */
    .hero-section {
        background: linear-gradient(rgba(124, 58, 237, 0.8), rgba(37, 99, 235, 0.8)),
                    url('{{ asset("assets/img/hero-bg.jpg") }}') no-repeat center center;
        background-size: cover;
        min-height: 600px;
        display: flex;
        align-items: center;
        color: white;
        margin-top: -24px;
        position: relative;
    }

    .hero-content {
        max-width: 800px;
        margin: 0 auto;
    }

    .hero-section h1 {
        font-size: 3.5rem;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        margin-bottom: 1.5rem;
    }

    .hero-section .lead {
        font-size: 1.25rem;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
        margin-bottom: 2rem;
    }

    /* Search Bar */
    .search-container {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        max-width: 600px;
        margin: 0 auto;
    }

    .input-group {
        border-radius: 8px;
        overflow: hidden;
    }

    .input-group .form-control {
        padding: 15px 25px;
        font-size: 1.1rem;
        border: none;
    }

    .input-group .btn-primary {
        padding: 15px 30px;
        font-size: 1.1rem;
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        border: none;
        transition: all 0.3s ease;
    }

    .input-group .btn-primary:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    /* Section Styling */
    section {
        padding: 80px 0;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 3rem;
        text-align: center;
        position: relative;
    }

    .section-title::after {
        content: '';
        display: block;
        width: 80px;
        height: 4px;
        background: linear-gradient(to right, var(--gradient-start), var(--gradient-end));
        margin: 20px auto 0;
        border-radius: 2px;
    }

    /* Category Cards */
    .category-card {
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        cursor: pointer;
        border: 1px solid rgba(124, 58, 237, 0.1);
        border-radius: 15px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        background: white;
        height: 100%;
        overflow: hidden;
    }

    .category-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 25px rgba(105, 108, 255, 0.2);
        background: linear-gradient(135deg, rgba(124, 58, 237, 0.05), rgba(37, 99, 235, 0.05));
    }

    .category-icon {
        font-size: 3rem;
        margin-bottom: 1.5rem;
        color: var(--gradient-start);
        transition: all 0.4s ease;
    }

    .category-card:hover .category-icon {
        transform: scale(1.15) rotate(5deg);
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Destination Cards */
    .wisata-card {
        border: 1px solid rgba(124, 58, 237, 0.1);
        border-radius: 15px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        margin-bottom: 30px;
        background: white;
        overflow: hidden;
    }

    .wisata-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 12px 25px rgba(105, 108, 255, 0.2);
        background: linear-gradient(135deg, rgba(124, 58, 237, 0.02), rgba(37, 99, 235, 0.02));
    }

    .wisata-img {
        height: 250px;
        object-fit: cover;
        transition: all 0.6s ease;
    }

    .wisata-card:hover .wisata-img {
        transform: scale(1.08);
    }

    .badge-status {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 600;
        z-index: 2;
        border-radius: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    .badge-status.bg-success {
        background: linear-gradient(135deg, #10B981, #059669) !important;
    }

    .badge-status.bg-danger {
        background: linear-gradient(135deg, #EF4444, #DC2626) !important;
    }

    .card-body {
        padding: 1.5rem;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--gradient-start);
    }

    /* Features Section */
    .feature-icon {
        font-size: 3.5rem;
        margin-bottom: 1.5rem;
        color: var(--gradient-start);
        transition: all 0.4s ease;
    }

    .features-card {
        padding: 40px 20px;
        border-radius: 15px;
        transition: all 0.4s ease;
        border: 1px solid rgba(124, 58, 237, 0.1);
    }

    .features-card:hover {
        background: linear-gradient(135deg, rgba(124, 58, 237, 0.05), rgba(37, 99, 235, 0.05));
    }

    .features-card:hover .feature-icon {
        transform: scale(1.15) rotate(10deg);
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .hero-section {
            min-height: 500px;
            padding: 40px 20px;
        }

        .hero-section h1 {
            font-size: 2.5rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .category-card {
            margin-bottom: 20px;
        }

        .wisata-card {
            margin-bottom: 30px;
        }

        .features-card {
            margin-bottom: 30px;
        }
    }

    /* Text Colors */
    .text-primary {
        color: var(--gradient-start) !important;
    }

    /* Card Title Colors */
    .card-title {
        color: var(--gradient-start);
    }

    /* Hover Transitions */
    .btn, .card, .feature-icon, .category-icon {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Animation for Gradients */
    @keyframes gradient {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }

    .btn-primary, .badge-status {
        background-size: 200% auto;
        animation: gradient 5s ease infinite;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        border: none;
    }

    .btn-primary:hover {
        opacity: 0.9;
        transform: translateY(-1px);
    }

    .btn-outline-primary {
        color: var(--gradient-start);
        border: 2px solid var(--gradient-start);
    }

    .btn-outline-primary:hover {
        background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
        border-color: transparent;
    }
</style>
{{-- @endpush --}}

{{-- @section('content') --}}
<!-- Hero Section -->
<section class="hero-section mb-5">
    <div class="container text-center hero-content">
        <h1 class="display-4 fw-bold mb-4">Jelajahi Wisata Indonesia</h1>
        <p class="lead mb-4">Temukan destinasi wisata terbaik dan pesan tiketmu sekarang!</p>
        <div class="search-container">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Cari tempat wisata...">
                <button class="btn btn-primary" type="button">
                    <i class="bx bx-search"></i> Cari
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="container mb-5">
    <h2 class="section-title">Kategori Wisata</h2>
    <div class="row g-4">
        <!-- Category Card 1 -->
        <div class="col-md-3">
            <div class="card category-card text-center">
                <div class="card-body">
                    <i class='bx bx-landscape category-icon'></i>
                    <h5 class="card-title">Wisata Alam</h5>
                    <p class="text-muted small">Jelajahi keindahan alam Indonesia</p>
                </div>
            </div>
        </div>
        <!-- Category Card 2 -->
        <div class="col-md-3">
            <div class="card category-card text-center">
                <div class="card-body">
                    <i class='bx bx-building category-icon'></i>
                    <h5 class="card-title">Wisata Kota</h5>
                    <p class="text-muted small">Telusuri pesona perkotaan</p>
                </div>
            </div>
        </div>
        <!-- Category Card 3 -->
        <div class="col-md-3">
            <div class="card category-card text-center">
                <div class="card-body">
                    <i class='bx bx-coffee category-icon'></i>
                    <h5 class="card-title">Kuliner</h5>
                    <p class="text-muted small">Nikmati kuliner khas daerah</p>
                </div>
            </div>
        </div>
        <!-- Category Card 4 -->
        <div class="col-md-3">
            <div class="card category-card text-center">
                <div class="card-body">
                    <i class='bx bx-palette category-icon'></i>
                    <h5 class="card-title">Budaya</h5>
                    <p class="text-muted small">Pelajari budaya lokal</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Destinations -->
<section class="container mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Destinasi Populer</h2>
        <a href="#" class="btn btn-outline-primary">
            Lihat Semua <i class="bx bx-right-arrow-alt"></i>
        </a>
    </div>

    <div class="row">
        <!-- Destination Card 1 -->
        <div class="col-md-4">
            <div class="card wisata-card">
                <img src="{{ asset('assets/img/wisata-1.jpg') }}" class="card-img-top wisata-img" alt="Wisata 1">
                <span class="badge bg-success badge-status">Aktif</span>
                <div class="card-body">
                    <h5 class="card-title">Pantai Kuta</h5>
                    <p class="card-text text-muted small">Nikmati keindahan pantai dengan pasir putih dan sunset yang memukau</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-primary">
                            <i class="bx bx-time"></i> 06:00 - 18:00
                        </span>
                        <a href="#" class="btn btn-primary btn-sm">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Destination Card 2 -->
        <div class="col-md-4">
            <div class="card wisata-card">
                <img src="{{ asset('assets/img/wisata-2.jpg') }}" class="card-img-top wisata-img" alt="Wisata 2">
                <span class="badge bg-success badge-status">Aktif</span>
                <div class="card-body">
                    <h5 class="card-title">Candi Borobudur</h5>
                    <p class="card-text text-muted small">Kunjungi candi Budha terbesar di dunia</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-primary">
                            <i class="bx bx-time"></i> 06:00 - 17:00
                        </span>
                        <a href="#" class="btn btn-primary btn-sm">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Destination Card 3 -->
        <div class="col-md-4">
            <div class="card wisata-card">
                <img src="{{ asset('assets/img/wisata-3.jpg') }}" class="card-img-top wisata-img" alt="Wisata 3">
                <span class="badge bg-success badge-status">Aktif</span>
                <div class="card-body">
                    <h5 class="card-title">Kawah Ijen</h5>
                    <p class="card-text text-muted small">Saksikan blue fire dan keindahan danau kawah</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-primary">
                            <i class="bx bx-time"></i> 00:00 - 24:00
                        </span>
                        <a href="#" class="btn btn-primary btn-sm">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="container mb-5">
    <h2 class="section-title">Mengapa Memilih Kami?</h2>
    <div class="row g-4">
        <div class="col-md-4 text-center features-card">
            <i class="bx bx-check-shield feature-icon"></i>
            <h4>Aman & Terpercaya</h4>
            <p class="text-muted">Pembayaran dan transaksi dijamin aman</p>
        </div>
        <div class="col-md-4 text-center features-card">
            <i class="bx bx-support feature-icon"></i>
            <h4>Layanan 24/7</h4>
            <p class="text-muted">Dukungan pelanggan selama 24 jam</p>
        </div>
        <div class="col-md-4 text-center features-card">
            <i class="bx bx-wallet feature-icon"></i>
            <h4>Pembayaran Mudah</h4>
            <p class="text-muted">Berbagai metode pembayaran tersedia</p>
        </div>
    </div>
</section>
{{-- @endsection --}}