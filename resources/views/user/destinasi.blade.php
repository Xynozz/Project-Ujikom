@extends('layouts.user.frontend.template')

@push('css')
<link rel="stylesheet" href="{{ asset('user/css/detail-wisata.css') }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
<style>
    .search-container {
        background: #fff;
        padding: 1rem;
        position: sticky;
        top: 0;
        z-index: 10;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 1rem;
    }
    .location-pill {
        background: #16a34a;
        color: #fff;
        border-radius: 20px;
        padding: 0.5rem 1.25rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .close-icon {
        cursor: pointer;
        margin-left: 0.5rem;
        font-size: 1.1rem;
    }
    .search-box {
        background: #f1f5f9;
        border-radius: 12px;
        padding: 0.5rem 1rem;
        display: flex;
        align-items: center;
        flex: 1;
        min-width: 200px;
    }
    .search-icon {
        margin-right: 0.5rem;
        color: #64748b;
    }
    .search-input {
        border: none;
        background: transparent;
        width: 100%;
        font-size: 1rem;
        outline: none;
        color: #1e293b;
    }
    .filter-section {
        background: #fff;
        padding: 0.75rem 1rem;
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        align-items: center;
        border-bottom: 1px solid #e5e7eb;
    }
    .filter-button, .category-button, .date-pill {
        border-radius: 20px;
        padding: 0.5rem 1.25rem;
        font-size: 0.95rem;
        font-weight: 500;
        background: #f1f5f9;
        color: #374151;
        border: none;
        cursor: pointer;
        transition: background 0.2s;
        margin-bottom: 0.25rem;
    }
    .filter-button:hover, .category-button:hover, .date-pill:hover {
        background: #e0e7ef;
    }
    .results-count {
        margin: 1.5rem 1rem 0.5rem 1rem;
        color: #64748b;
        font-size: 1rem;
        font-weight: 500;
    }
    /* Product Grid */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        padding: 2rem;
        max-width: 1440px;
        margin: 0 auto;
    }

    /* Product Card */
    .product-card {
        background: #ffffff;
        border-radius: 1.25rem;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: all 0.3s ease;
        border: 1px solid #e5e7eb;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1),
                   0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    /* Image Container */
    .product-image-container {
        position: relative;
        padding-top: 60%; /* 5:3 Aspect Ratio */
        background: #f1f5f9;
        overflow: hidden;
    }

    .product-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.1);
    }

    /* Badges */
    .product-badge, .promo-badge {
        position: absolute;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.875rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .product-badge {
        bottom: 1rem;
        left: 1rem;
        background: rgba(255, 255, 255, 0.95);
        color: #16a34a;
    }

    .promo-badge {
        top: 1rem;
        right: 1rem;
        background: #ef4444;
        color: white;
    }

    /* Content Section */
    .product-content {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
        flex: 1;
    }

    .content-main {
        flex: 1;
    }

    .product-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .product-meta {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .product-location, .product-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #64748b;
        font-size: 0.875rem;
    }

    .rating-stars {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        color: #f59e0b;
    }

    .review-count {
        color: #94a3b8;
    }

    /* Price Section */
    .price-section {
        margin-top: auto;
    }

    .original-price {
        color: #94a3b8;
        font-size: 0.875rem;
        text-decoration: line-through;
        margin-bottom: 0.25rem;
    }

    .product-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: #16a34a;
        display: flex;
        align-items: baseline;
        gap: 0.25rem;
    }

    .currency {
        font-size: 1rem;
    }

    /* Button */
    .view-details-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        width: 100%;
        background: linear-gradient(135deg, #16a34a, #22c55e);
        color: #ffffff;
        border-radius: 0.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .view-details-btn:hover {
        background: linear-gradient(135deg, #15803d, #16a34a);
        transform: translateY(-2px);
    }

    /* No Results */
    .no-results {
        grid-column: 1 / -1;
        text-align: center;
        padding: 3rem;
    }

    .no-results-content {
        max-width: 400px;
        margin: 0 auto;
    }

    .no-results i {
        font-size: 3rem;
        color: #94a3b8;
        margin-bottom: 1rem;
    }

    .no-results h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .no-results p {
        color: #64748b;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
            padding: 1.5rem;
        }
    }

    @media (max-width: 640px) {
        .product-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
            padding: 1rem;
        }

        .product-image-container {
            padding-top: 56.25%; /* 16:9 Aspect Ratio */
        }

        .product-content {
            padding: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<div class="search-container">
    <div class="location-pill">
        <i class="bx bx-map"></i> Semua lokasi
        <span class="close-icon">✕</span>
    </div>
    <div class="search-box">
        <span class="search-icon">🔍</span>
        <input type="text" class="search-input" placeholder="Cari aktivitas">
    </div>
</div>

<div class="filter-section">
    <button class="filter-button">
        <span class="filter-icon">🛒</span>
        Urutkan & Filter
    </button>
    <button class="date-pill">Terdekat</button>
    <button class="date-pill">Today</button>
</div>

<div class="results-count">Menampilkan {{ $wisata->count() }} hasil</div>

<div class="product-grid">
    @foreach ($tiket as $data)
    <article class="product-card">
        <!-- Image Section -->
        <div class="product-image-container">
            <img src="{{ asset('storage/' . $data->wisata->thumbnail) }}"
                 alt="{{ $data->wisata->nama_wisata }}"
                 class="product-image"
                 loading="lazy"
                 onerror="this.src='{{ asset('images/placeholder.jpg') }}'">

            <div class="product-badge">
                <i class='bx bx-check-circle'></i>
                <span>Konfirmasi Instan</span>
            </div>

            @if($data->is_promo)
            <div class="promo-badge">
                <i class='bx bx-tag'></i>
                <span>Promo</span>
            </div>
            @endif
        </div>

        <!-- Content Section -->
        <div class="product-content">
            <div class="content-main">
                <h3 class="product-title" title="{{ $data->wisata->nama_wisata }}">
                    {{ $data->wisata->nama_wisata }}
                </h3>

                <div class="product-meta">
                    <div class="product-location" title="Lokasi">
                        <i class='bx bx-map'></i>
                        <span>{{ $data->wisata->jam_buka }} - {{ $data->wisata->jam_tutup }}</span>
                    </div>

                    @if($data->wisata->ulasan)
                    <div class="product-rating" title="Rating">
                        <div class="rating-stars">
                            <i class='bx bxs-star'></i>
                            <span>{{ number_format($data->rating, 1) }}</span>
                        </div>
                        <span class="review-count">({{ $data->jumlah_ulasan ?? 0 }})</span>
                    </div>
                    @endif
                </div>

                <div class="price-section">
                    @if($data->harga_promo)
                    <div class="original-price">
                        <s>Rp {{ number_format($data->harga_tiket, 0, ',', '.') }}</s>
                    </div>
                    @endif
                    <div class="product-price">
                        <span class="currency">Rp</span>
                        <span class="amount">{{ number_format($data->harga_promo ?? $data->harga_tiket, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <a href="{{ route('detail-wisata', $data->wisata->id) }}" class="view-details-btn">
                <span>Lihat Detail</span>
                <i class='bx bx-right-arrow-alt'></i>
            </a>
        </div>
    </article>
    @endforeach
</div>
@endsection
@push('scripts')

<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
<script>
    function getAddressFromCoordinates(lat, lon) {
    fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`)
        .then(response => response.json())
        .then(data => {
            if (data && data.display_name) {
                document.getElementById('alamat').textContent = data.display_name;
            } else {
                document.getElementById('alamat').textContent = 'Alamat tidak ditemukan';
            }
        })
        .catch(error => {
            console.error('Gagal mendapatkan alamat:', error);
        });
}

// Misal ambil dari blade
let latitude = {{ $wisata->latitude }};
let longitude = {{ $wisata->longitude }};
getAddressFromCoordinates(latitude, longitude);
</script>
@endpush