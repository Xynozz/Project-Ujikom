@extends('layouts.user.frontend.template')

@push('css')
<link rel="stylesheet" href="{{ asset('user/css/detail-wisata.css') }}">
<style>
    /* Main Container */
    .order-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1.5rem;
    }

    /* Header Section */
    .order-header {
        background: linear-gradient(135deg, #3067ff, #0084d6);
        padding: 2.5rem 2rem;
        border-radius: 1rem;
        color: white;
        margin-bottom: 2rem;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .order-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        backdrop-filter: blur(4px);
    }

    /* Content Grid */
    .order-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }

    /* Order Details Card */
    .order-card {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    /* Ticket Details */
    .ticket-details {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .ticket-header {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .ticket-image {
        width: 120px;
        height: 120px;
        border-radius: 0.75rem;
        object-fit: cover;
    }

    .ticket-info h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .ticket-meta {
        display: flex;
        gap: 2rem;
        padding: 1.5rem;
        background: #f8fafc;
        border-radius: 0.75rem;
        margin-top: 1rem;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .meta-label {
        font-size: 0.875rem;
        color: #64748b;
    }

    .meta-value {
        font-weight: 600;
        color: #0f172a;
    }

    /* Customer Details */
    .customer-details {
        padding: 1.5rem;
    }

    .section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .detail-label {
        font-size: 0.875rem;
        color: #64748b;
    }

    .detail-value {
        font-weight: 500;
        color: #0f172a;
    }

    /* Payment Summary Card */
    .payment-summary {
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 2rem;
    }

    .summary-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .summary-content {
        padding: 1.5rem;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
    }

    .summary-row:not(:last-child) {
        border-bottom: 1px dashed #e5e7eb;
    }

    .summary-total {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 2px solid #e5e7eb;
        font-weight: 600;
        color: #2483ff;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .action-button {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.875rem;
        border-radius: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .primary-button {
        background: linear-gradient(135deg, #3067ff, #0084d6);
        color: white;
    }

    .secondary-button {
        background: #f1f5f9;
        color: #0f172a;
    }

    .action-button:hover {
        transform: translateY(-2px);
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .order-content {
            grid-template-columns: 1fr;
        }

        .payment-summary {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .ticket-meta {
            flex-direction: column;
            gap: 1rem;
        }
    }

    @media (max-width: 640px) {
        .order-container {
            padding: 1rem;
        }

        .header-content {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .action-buttons {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="order-container">
    <!-- Header Section -->
    <div class="order-header">
        <div class="header-content">
            <div>
                <h1 class="text-2xl font-bold mb-2">Detail Pemesanan #{{ $pemesanan->tiket->kode_tiket }}</h1>
                <p class="text-white/80">{{ $pemesanan->created_at->format('d F Y, H:i') }}</p>
            </div>
            <div class="order-status">
                <i class='bx bx-check-circle'></i>
                <span>{{ ucfirst($pemesanan->status) }}</span>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="order-content">
        <!-- Left Column -->
        <div>
            <!-- Ticket Details -->
            <div class="order-card mb-6">
                <div class="ticket-details">
                    <div class="ticket-header">
                        <img src="{{ asset('storage/' . $pemesanan->wisata->thumbnail) }}"
                            alt="{{ $pemesanan->wisata->nama_wisata }}" class="ticket-image">
                        <div class="ticket-info">
                            <h3>{{ $pemesanan->wisata->nama_wisata }}</h3>
                            <p class="text-gray-600">{{ $pemesanan->wisata->lokasi }}</p>
                        </div>
                    </div>

                    <div class="ticket-meta">
                        <div class="meta-item">
                            <span class="meta-label">Tanggal Kunjungan</span>
                            <span class="meta-value">{{ $tanggal_kunjungan->format('d F Y') }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Jumlah Tiket</span>
                            <span class="meta-value">{{ $pemesanan->jumlah_tiket }} Tiket</span>
                        </div>
                    </div>
                </div>

                <!-- Customer Details -->
                <div class="customer-details">
                    <h4>
                        <i class='bx bx-user'></i>
                        Informasi Pemesan
                    </h4>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <span class="detail-label">Nama Lengkap</span>
                            <span class="detail-value">{{ $pemesanan->user->username }}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Email</span>
                            <span class="detail-value">{{ $pemesanan->user->email }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="payment-summary">
            <div class="summary-header">
                <h4 class="section-title">Ringkasan Pembayaran</h4>
            </div>
            <div class="summary-content">
                <div class="summary-row">
                    <span>Harga Tiket</span>
                    <span>Rp {{ number_format($pemesanan->tiket->harga_tiket, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span>Jumlah Tiket</span>
                    <span>× {{ $pemesanan->jumlah_tiket }}</span>
                </div>
                <div class="summary-row">
                    <span>Biaya Layanan</span>
                    <span>Rp 0</span>
                </div>
                <div class="summary-row summary-total">
                    <span>Total Pembayaran</span>
                    <span>Rp {{ number_format($pemesanan->total_harga, 0, ',', '.') }}</span>
                </div>

                <div class="action-buttons">
                    <button class="action-button primary-button pay-button" data-id="{{ $pemesanan->id }}">Bayar
                        Sekarang</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<!-- Midtrans -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const payButtons = document.querySelectorAll('.pay-button');
        payButtons.forEach(button => {
            button.addEventListener('click', function() {
                const pemesananId = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Memproses Pembayaran',
                    text: 'Mohon tunggu...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch('/api/midtrans/create-transaction', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        pemesanan_id: pemesananId
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.snap_token) {
                        Swal.close();

                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result) {
                                Swal.fire({
                                    title: 'Success',
                                    text: 'Pembayaran berhasil!',
                                    icon: 'success',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = `/pembayaran/success/${result.order_id}`;
                                });
                            },
                            onPending: function(result) {
                                Swal.fire({
                                    title: 'Info',
                                    text: 'Pembayaran sedang diproses',
                                    icon: 'info',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                }).then(() => location.reload());
                            },
                            onError: function(result) {
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Pembayaran gagal!',
                                    icon: 'error',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                });
                            },
                            onClose: function() {
                                Swal.fire({
                                    title: 'Info',
                                    text: 'Pembayaran dibatalkan',
                                    icon: 'info',
                                    timer: 2000,
                                    timerProgressBar: true,
                                    showConfirmButton: false
                                });
                            }
                        });
                    } else {
                        throw new Error(data.error || 'Failed to get payment token');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Terjadi kesalahan: ' + error.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                });
            });
        });
    });
</script>
@endpush