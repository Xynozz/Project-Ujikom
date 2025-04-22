@extends('layouts.user.frontend.template')
@push('css')
<link rel="stylesheet" href="{{ asset('user/css/detail-wisata.css') }}">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <style>
        #map-id {
            height: 350px;
            width: 100%;
        }
        #debug-info {
            margin-top: 20px;
            padding: 10px;
            background-color: #f5f5f5;
        }
    </style>
@endpush
@section('content')
<main class="container">
    <!-- Breadcrumb -->
    <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Beranda</a></li>
        <li class="breadcrumb-item"><a href="#">Wisata</a></li>
        <li class="breadcrumb-item active">{{ $wisata->nama_wisata }}</li>
    </ul>

    <!-- Attraction Header -->
    <div class="attraction-header">
        <div class="attraction-title">
            <h1>{{ $wisata->nama_wisata }}</h1>
            <div class="attraction-meta">
                <div class="attraction-rating">
                    <i class="fas fa-star rating-star"></i>
                    <span>4.5</span>
                    <span class="rating-count">(2,456 ulasan)</span>
                </div>
            </div>
        </div>
        <div class="attraction-gallery">
            <div class="gallery-item gallery-main">
                <video src="{{ Storage::url($wisata->short_video) }}" controls
                    poster="{{ Storage::url($wisata->thumbnail) }}"></video>
            </div>
            <div class="gallery-item">
                <img src="{{ Storage::url($wisata->gambar) }}" alt="Pantai Kuta">
            </div>
            <div class="gallery-item">
                <img src="{{ Storage::url($wisata->thumbnail) }}" alt="Pantai Kuta">
            </div>
            <div class="gallery-item">
                <img src="{{ Storage::url($wisata->thumbnail) }}" alt="Pantai Kuta">
            </div>
            <div class="gallery-item">
                <img src="{{ Storage::url($wisata->thumbnail) }}" alt="Pantai Kuta">
                <div class="more-photos">+24 Foto Lainnya</div>
            </div>
        </div>
    </div>

    <!-- Content Layout -->
    <div class="content-layout">
        <!-- Left Column -->
        <div class="content-left">
            <!-- Attraction Details -->
            <div class="attraction-details">
                <h2 class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Tentang {{ $wisata->nama_wisata }}
                </h2>
                <div class="attraction-description">
                    <p>{{ $wisata->deskripsi }}</p>
                    <span class="read-more">Baca selengkapnya</span>
                </div>
            </div>
        </div>

        <!-- Add this inside the content-layout div, after the content-left div -->
        <div class="content-right">
            <div class="ticket-purchase-box">
                <form action="{{ url('/detail-wisata/' . $wisata->id) }}" method="POST" class="ticket-order-form">
                    @csrf

                    <h4>Pemesanan Tiket</h4>

                    {{-- (Opsional) Hidden input untuk wisata_id --}}
                    <input type="hidden" name="wisata_id" value="{{ $wisata->id }}">

                    {{-- Pilih Tanggal Kunjungan --}}
                    <div class="form-group">
                        <label for="tanggal_pemesanan"><strong>Tanggal Kunjungan</strong></label>
                        <input type="date" class="form-control" name="tanggal_pemesanan" id="tanggal_pemesanan"
                            min="{{ date('Y-m-d') }}" required>
                    </div>

                    {{-- Dropdown Tiket --}}
                    <div class="form-group">
                        <label for="tiket_id"><strong>Pilih Jenis Tiket</strong></label>
                        <select name="tiket_id" id="tiket_id" class="form-control" required onchange="updateHarga()">
                            <option value="" disabled selected>-- Pilih Tiket --</option>
                            @foreach ($tiket as $data)
                                <option value="{{ $data->id }}" data-harga="{{ $data->harga_tiket }}">
                                    {{ $data->nama_tiket }} - Rp {{ number_format($data->harga_tiket, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Jumlah Tiket --}}
                    <div class="form-group">
                        <label for="jumlah_tiket"><strong>Jumlah Tiket</strong></label>
                        <div class="quantity-controls">
                            <button type="button" class="qty-btn minus">-</button>
                            <input type="number" name="jumlah_tiket" id="jumlah_tiket" class="form-control" value="1"
                                min="1" max="10" readonly required>
                            <button type="button" class="qty-btn plus">+</button>
                        </div>
                    </div>

                    {{-- Total Harga --}}
                    <div class="total-section">
                        <strong>Total Harga: </strong>
                        <span id="totalHarga">Rp 0</span>
                    </div>

                    {{-- Tombol Submit --}}
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-ticket-alt"></i> Pesan Sekarang
                    </button>
                </form>

            </div>
        </div>
    </div>

    <!-- Left Column -->
    <div class="content-left">

        <!-- Location Highlights -->
        <div class="highlights">
            <h2 class="section-title">
                <i class="fas fa-star"></i>
                Lokasi
            </h2>
            <div id="map-id"></div>
        </div>

        <!-- Add this after the Facilities section in your content-left div -->
        <div class="attraction-reviews">
            <div class="reviews-header">
                <h2 class="section-title">
                    <i class="fas fa-comment-alt"></i>
                    Ulasan Pengunjung
                </h2>
                <div class="reviews-summary">
                    <div class="rating-overall">
                        <div class="rating-number">4.5</div>
                        <div class="rating-stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                        <div class="rating-count">2,456 ulasan</div>
                    </div>
                    <div class="rating-bars">
                        <div class="rating-bar-item">
                            <span class="rating-bar-label">5</span>
                            <div class="rating-bar-container">
                                <div class="rating-bar-fill" style="width: 75%"></div>
                            </div>
                            <span class="rating-bar-percent">75%</span>
                        </div>
                        <div class="rating-bar-item">
                            <span class="rating-bar-label">4</span>
                            <div class="rating-bar-container">
                                <div class="rating-bar-fill" style="width: 20%"></div>
                            </div>
                            <span class="rating-bar-percent">20%</span>
                        </div>
                        <div class="rating-bar-item">
                            <span class="rating-bar-label">3</span>
                            <div class="rating-bar-container">
                                <div class="rating-bar-fill" style="width: 3%"></div>
                            </div>
                            <span class="rating-bar-percent">3%</span>
                        </div>
                        <div class="rating-bar-item">
                            <span class="rating-bar-label">2</span>
                            <div class="rating-bar-container">
                                <div class="rating-bar-fill" style="width: 1%"></div>
                            </div>
                            <span class="rating-bar-percent">1%</span>
                        </div>
                        <div class="rating-bar-item">
                            <span class="rating-bar-label">1</span>
                            <div class="rating-bar-container">
                                <div class="rating-bar-fill" style="width: 1%"></div>
                            </div>
                            <span class="rating-bar-percent">1%</span>
                        </div>
                    </div>
                </div>
            </div>

            @foreach ($ulasan as $data)
            <div class="reviews-list">
                <!-- Review Item 1 -->
                <div class="review-item">
                    <div class="review-user">
                        <div class="user-avatar">
                            <img src="{{ $data->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($data->user->username) }}"
                                alt="User Avatar">
                        </div>
                        <div class="user-info">
                            <h4 class="user-name">{{ $data->user->username }}</h4>
                            <div class="review-meta">
                                <div class="review-rating">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </div>
                                <span class="review-date">{{ $data->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="review-verified">
                            <i class="fas fa-check-circle"></i>
                            <span>Kunjungan terverifikasi</span>
                        </div>
                    </div>
                    <div class="review-content">
                        <p>{{ $data->ulasan }}</p>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="reviews-pagination">
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn">2</button>
                <button class="pagination-btn">3</button>
                <button class="pagination-btn">4</button>
                <button class="pagination-btn">5</button>
                <span class="pagination-dots">...</span>
                <button class="pagination-btn">246</button>
                <button class="pagination-btn next">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>

            <div class="write-review">
                @if (Auth::check())
                <form action="{{ url('/detail-wisata/' . $wisata->id) }}" method="POST">
                    @csrf
                    <h3 class="write-review-title">Bagikan Pengalaman Anda</h3>

                    <!-- Rating Input Section -->
                    <div class="rating-input">
                        <span class="rating-label">Beri Rating:</span>
                        <div class="star-rating">
                            <input type="radio" name="rating" id="rating1" value="1" class="star-rating-input" required>
                            <label for="rating1" class="star-rating-label">★</label>

                            <input type="radio" name="rating" id="rating2" value="2" class="star-rating-input" required>
                            <label for="rating2" class="star-rating-label">★</label>

                            <input type="radio" name="rating" id="rating3" value="3" class="star-rating-input" required>
                            <label for="rating3" class="star-rating-label">★</label>

                            <input type="radio" name="rating" id="rating4" value="4" class="star-rating-input" required>
                            <label for="rating4" class="star-rating-label">★</label>

                            <input type="radio" name="rating" id="rating5" value="5" class="star-rating-input" required>
                            <label for="rating5" class="star-rating-label">★</label>
                        </div>
                        @error('rating')
                            <div class="text-red-500">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Review Input Section -->
                    <div class="review-form">
                        <input type="hidden" name="wisata_id" value="{{ $wisata->id }}">
                        <textarea class="review-textarea" name="ulasan" placeholder="Ceritakan pengalaman Anda di tempat wisata ini..." required></textarea>
                        @error('ulasan')
                            <div class="text-red-500">{{ $message }}</div>
                        @enderror

                        <div class="review-form-footer">
                            <button type="submit" class="submit-review-btn">Kirim Ulasan</button>
                        </div>
                    </div>
                </form>

                @endif
            </div>
        </div>
    </div>

</main>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>

    // Inisialisasi peta
    document.addEventListener('DOMContentLoaded', function() {
            // Dapatkan data lokasi dari PHP (yang dikirim controller) ke JavaScript
            var wisataData = {
                id: {{ $wisata->id }},
                name: "{{ $wisata->nama_wisata }}",
                latitude: {{ $wisata->latitude }},
                longitude: {{ $wisata->longitude }}
            };

            // Inisialisasi peta
            var map = L.map('map-id').setView([wisataData.latitude, wisataData.longitude], 15);

            // Tambahkan layer peta
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Tambahkan marker untuk lokasi
            var marker = L.marker([wisataData.latitude, wisataData.longitude]).addTo(map);
            marker.bindPopup("<b>" + wisataData.name + "</b>").openPopup();
        });


    // Gallery functionality
    document.addEventListener('DOMContentLoaded', function() {
    // Create modal for gallery
    const modal = document.createElement('div');
    modal.className = 'gallery-modal';
    modal.innerHTML = `
        <div class="gallery-modal-content">
            <span class="gallery-close">&times;</span>
            <img src="" class="gallery-main-image" alt="Gallery image">
            <div class="gallery-thumbnails"></div>
        </div>
    `;
    document.body.appendChild(modal);

    // Sample gallery images - replace with your actual images
    const galleryImages = [
        'https://via.placeholder.com/800x600',
        'https://via.placeholder.com/400x300',
        'https://via.placeholder.com/400x300',
        'https://via.placeholder.com/400x300',
        'https://via.placeholder.com/400x300',
        'https://via.placeholder.com/400x300'
    ];

    // Gallery functionality
    const galleryItems = document.querySelectorAll('.gallery-item');
    const morePhotos = document.querySelector('.more-photos');
    const modalClose = document.querySelector('.gallery-close');
    const mainImage = document.querySelector('.gallery-main-image');
    const thumbnailsContainer = document.querySelector('.gallery-thumbnails');

    // Populate thumbnails
    galleryImages.forEach((src, index) => {
        const thumbnail = document.createElement('div');
        thumbnail.className = 'gallery-thumbnail';
        thumbnail.innerHTML = `<img src="${src}" alt="Thumbnail ${index + 1}">`;
        thumbnail.addEventListener('click', () => {
            mainImage.src = src;
            document.querySelectorAll('.gallery-thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            thumbnail.classList.add('active');
        });
        thumbnailsContainer.appendChild(thumbnail);
    });

    // Open modal and show selected image
    function openModal(imgSrc) {
        modal.classList.add('active');
        mainImage.src = imgSrc;
        document.body.style.overflow = 'hidden';

        // Set the first thumbnail as active
        const thumbnails = document.querySelectorAll('.gallery-thumbnail');
        thumbnails.forEach((thumb, index) => {
            if (thumb.querySelector('img').src === imgSrc) {
                thumb.classList.add('active');
            } else {
                thumb.classList.remove('active');
            }
        });
    }

    // Click on gallery item
    galleryItems.forEach(item => {
        item.addEventListener('click', function() {
            const imgSrc = this.querySelector('img').src;
            openModal(imgSrc);
        });
    });

    // Click on "more photos"
    if (morePhotos) {
        morePhotos.addEventListener('click', function() {
            const firstImgSrc = galleryImages[0];
            openModal(firstImgSrc);
        });
    }

    // Close modal
    modalClose.addEventListener('click', function() {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    });

    // Close when clicking outside content
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
    });

});

document.addEventListener('DOMContentLoaded', function() {
    // Star rating functionality
    const starRating = document.querySelector('.star-rating');
    const stars = starRating ? starRating.querySelectorAll('i') : [];
    let selectedRating = 0;

    stars.forEach(star => {
        star.addEventListener('mouseover', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            highlightStars(rating);
        });

        star.addEventListener('mouseout', function() {
            highlightStars(selectedRating);
        });

        star.addEventListener('click', function() {
            selectedRating = parseInt(this.getAttribute('data-rating'));
            highlightStars(selectedRating);
        });
    });

    function highlightStars(rating) {
        stars.forEach(star => {
            const starRating = parseInt(star.getAttribute('data-rating'));
            if (starRating <= rating) {
                star.classList.remove('far');
                star.classList.add('fas');
                star.classList.add('active');
            } else {
                star.classList.remove('fas');
                star.classList.add('far');
                star.classList.remove('active');
            }
        });
    }

    // Filter buttons
    const filterButtons = document.querySelectorAll('.filter-btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            // Here you would add code to filter reviews based on selection
        });
    });

    // Pagination
    const paginationButtons = document.querySelectorAll('.pagination-btn');
    paginationButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (!this.classList.contains('next')) {
                paginationButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                // Here you would add code to show the selected page of reviews
            } else {
                // Handle next button click
                let activePage = document.querySelector('.pagination-btn.active');
                if (activePage && activePage.nextElementSibling &&
                    activePage.nextElementSibling.classList.contains('pagination-btn')) {
                    activePage.classList.remove('active');
                    activePage.nextElementSibling.classList.add('active');
                }
            }
        });
    });

});

const qtyInput = document.getElementById('jumlah_tiket');
    const selectTiket = document.getElementById('tiket_id');
    const totalHarga = document.getElementById('totalHarga');

    function updateHarga() {
        hitungTotal();
    }

    function hitungTotal() {
        const harga = selectTiket.options[selectTiket.selectedIndex]?.dataset?.harga || 0;
        const jumlah = parseInt(qtyInput.value) || 0;
        const total = harga * jumlah;
        totalHarga.textContent = 'Rp ' + parseInt(total).toLocaleString('id-ID');
    }

    document.querySelector('.qty-btn.plus').addEventListener('click', () => {
        let val = parseInt(qtyInput.value);
        if (val < 10) {
            qtyInput.value = val + 1;
            hitungTotal();
        }
    });

    document.querySelector('.qty-btn.minus').addEventListener('click', () => {
        let val = parseInt(qtyInput.value);
        if (val > 1) {
            qtyInput.value = val - 1;
            hitungTotal();
        }
    });

    selectTiket.addEventListener('change', hitungTotal);


</script>
@endsection