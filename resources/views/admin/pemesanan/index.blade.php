@extends('layouts.admin.frontend.template')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<style>
    .status-badge {
        font-weight: 500;
        padding: 5px 10px;
    }

    .action-buttons {
        display: flex;
        gap: 5px;
    }

    .btn-icon {
        padding: 5px 8px;
        font-size: 14px;
    }

    .modal-header {
        border-bottom: 2px solid #696cff;
        background-color: #f8f9fa;
    }

    .modal .card {
        box-shadow: none;
        border: 1px solid rgba(0, 0, 0, .125);
    }

    .modal .card-header {
        background: linear-gradient(135deg, #696cff, #4f46e5);
        color: white;
        padding: 0.75rem 1rem;
    }

    .modal .table-borderless td {
        padding: 0.5rem 0;
    }

    .modal .badge {
        font-size: 0.85em;
        padding: 0.4em 0.8em;
    }

    .modal-lg {
        max-width: 900px;
    }

    .e-ticket-wrapper {
        background: linear-gradient(135deg, #fff, #f8f9fa);
        border-radius: 15px;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .e-ticket-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #696cff, #4f46e5);
    }

    .barcode-section {
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }

    .barcode-container {
        padding: 1.5rem;
        background: #f8f9fa;
        border-radius: 8px;
        text-align: center;
    }

    .barcode-container svg {
        max-width: 100%;
        height: auto;
    }

    .barcode-text {
        position: relative;
    }

    .barcode-text input {
        font-weight: 600;
        font-family: monospace;
        background-color: #fff;
        border: 1px solid #dee2e6;
        cursor: pointer;
    }

    .barcode-text input:focus {
        outline: none;
        border-color: #696cff;
        box-shadow: 0 0 0 0.2rem rgba(105, 108, 255, 0.25);
    }

    .btn-outline-primary {
        border-color: #696cff;
        color: #696cff;
    }

    .btn-outline-primary:hover {
        background-color: #696cff;
        color: #fff;
    }

    .ticket-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px dashed #dee2e6;
    }

    .ticket-info-item {
        text-align: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        transition: transform 0.3s ease;
    }

    .ticket-info-item:hover {
        transform: translateY(-3px);
    }

    .ticket-info-item i {
        font-size: 1.5rem;
        color: #696cff;
        margin-bottom: 0.5rem;
    }

    .ticket-info-item .label {
        color: #6c757d;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .ticket-info-item .value {
        font-weight: 600;
        color: #333;
    }

    .validity-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 25px;
        font-size: 0.875rem;
        font-weight: 500;
        margin-top: 1rem;
    }

    .validity-badge.valid {
        background: rgba(113, 221, 55, 0.1);
        color: #71dd37;
    }

    .validity-badge.expired {
        background: rgba(133, 146, 163, 0.1);
        color: #8592a3;
    }

    /* Modal Styles */
    .modal-content {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        background: linear-gradient(135deg, #696cff, #4f46e5);
        color: white;
        border-radius: 15px 15px 0 0;
        padding: 1.25rem;
    }

    .modal-header .btn-close {
        color: white;
        filter: brightness(0) invert(1);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .info-card {
        height: 100%;
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-5px);
    }

    .info-card .card-header {
        background: linear-gradient(135deg, #696cff, #4f46e5);
        color: white;
        border-radius: 12px 12px 0 0;
        padding: 1rem 1.25rem;
    }

    .info-card .card-body {
        padding: 1.25rem;
    }

    .info-table {
        margin: 0;
    }

    .info-table td {
        padding: 0.5rem 0;
        vertical-align: middle;
    }

    .info-table td:first-child {
        color: #6c757d;
        font-weight: 500;
    }

    .info-divider {
        margin: 1rem 0;
        border-top: 1px dashed #dee2e6;
    }

    .modal-footer {
        border-top: 1px solid #dee2e6;
        padding: 1rem 1.5rem;
    }

    .btn-modal-close {
        background-color: #e9ecef;
        color: #495057;
        border: none;
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-modal-close:hover {
        background-color: #dee2e6;
        transform: translateY(-2px);
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Transaksi /</span> Pemesanan
    </h4>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Pemesanan</h5>
            <a href="{{ route('pemesanan.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Tambah Pemesanan
            </a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="pemesanan-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Username</th>
                            <th>Tiket</th>
                            <th>Wisata</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                            <th>Status Pemesanan</th>
                            <th>Status Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pemesanan as $data)
                        <tr>
                            <td><strong>{{ $loop->iteration }}</strong></td>
                            <td>{{ $data->user->username }}</td>
                            <td>{{ $data->tiket->kode_tiket }}</td>
                            <td>{{ $data->wisata->nama_wisata }}</td>
                            <td class="text-center">{{ $data->jumlah_tiket }}</td>
                            <td>Rp {{ number_format($data->total_harga, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <span class="badge status-badge
                                    {{ $data->status == 'proses' ? 'bg-warning' :
                                       ($data->status == 'selesai' ? 'bg-success' : 'bg-danger') }}">
                                    {{ ucfirst($data->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if(!$data->pembayaran)
                                <span class="badge status-badge bg-secondary">Belum Ada</span>
                                @else
                                @if($data->pembayaran->status == 'belum_bayar')
                                <span class="badge status-badge bg-warning">Belum Bayar</span>
                                @elseif($data->pembayaran->status == 'pending')
                                <span class="badge status-badge bg-info">Pending</span>
                                @elseif($data->pembayaran->status == 'sudah_bayar')
                                <span class="badge status-badge bg-success">Sudah Bayar</span>
                                @else
                                <span class="badge status-badge bg-danger">Gagal</span>
                                @endif
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('pemesanan.edit', $data->id) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <form id="delete-form-{{ $data->id }}"
                                            action="{{ route('pemesanan.destroy', $data->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button type="button" class="dropdown-item"
                                                onclick="confirmDelete({{ $data->id }})">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                        @if(!$data->pembayaran ||
                                        $data->pembayaran->status == 'belum_bayar' ||
                                        $data->pembayaran->status == 'gagal' ||
                                        $data->pembayaran->status == 'pending')
                                        <button class="dropdown-item pay-button" data-id="{{ $data->id }}">
                                            <i class="bx bx-credit-card me-1"></i> Bayar
                                        </button>
                                        @endif
                                        <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#detailModal{{ $data->id }}">
                                            <i class="bx bx-detail me-1"></i> Detail
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@foreach($pemesanan as $data)
<!-- Detail Modal -->
<div class="modal fade" id="detailModal{{ $data->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white">
                    <i class='bx bx-info-circle me-2'></i>Detail Pemesanan #{{ $data->tiket->kode_tiket }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <!-- Pemesanan Details -->
                    <div class="col-md-6">
                        <div class="info-card h-100">
                            <div class="card-header d-flex align-items-center">
                                <i class='bx bx-purchase-tag me-2'></i>
                                <h6 class="mb-0 text-white">Informasi Pemesanan</h6>
                            </div>
                            <div class="card-body">
                                <table class="info-table table table-borderless mb-0">
                                    <tr>
                                        <td width="40%"><i class='bx bx-barcode-reader text-primary me-2'></i>Kode Tiket
                                        </td>
                                        <td>: <strong>{{ $data->tiket->kode_tiket }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td><i class='bx bx-map-pin text-primary me-2'></i>Wisata</td>
                                        <td>: {{ $data->wisata->nama_wisata }}</td>
                                    </tr>
                                    <tr>
                                        <td><i class='bx bx-group text-primary me-2'></i>Jumlah Tiket</td>
                                        <td>: {{ $data->jumlah_tiket }} Orang</td>
                                    </tr>
                                    <tr>
                                        <td><i class='bx bx-money text-primary me-2'></i>Total Harga</td>
                                        <td>: <strong>Rp {{ number_format($data->total_harga, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><i class='bx bx-info-circle text-primary me-2'></i>Status</td>
                                        <td>: <span
                                                class="badge bg-{{ $data->status == 'proses' ? 'warning' : ($data->status == 'selesai' ? 'success' : 'danger') }}">
                                                {{ ucfirst($data->status) }}
                                            </span></td>
                                    </tr>
                                    <tr>
                                        <td><i class='bx bx-calendar text-primary me-2'></i>Tanggal Pesan</td>
                                        <td>: {{ $data->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="col-md-6">
                        <div class="info-card h-100">
                            <div class="card-header d-flex align-items-center">
                                <i class='bx bx-credit-card me-2'></i>
                                <h6 class="mb-0 text-white">Informasi Pembayaran</h6>
                            </div>
                            <div class="card-body">
                                <table class="info-table table table-borderless">
                                    <tr>
                                        <td width="40%"><i class='bx bx-user text-primary me-2'></i>Pemesan</td>
                                        <td>: {{ $data->user->username }}</td>
                                    </tr>
                                    <tr>
                                        <td><i class='bx bx-envelope text-primary me-2'></i>Email</td>
                                        <td>: {{ $data->user->email }}</td>
                                    </tr>
                                </table>

                                <div class="info-divider my-3"></div>

                                @if(!$data->pembayaran)
                                <div class="text-center py-3">
                                    <i class='bx bx-credit-card-alt text-secondary' style="font-size: 2rem;"></i>
                                    <p class="text-muted mb-0 mt-2">Belum ada pembayaran</p>
                                </div>
                                @else
                                <table class="info-table table table-borderless mb-0">
                                    <tr>
                                        <td width="40%"><i class='bx bx-receipt text-primary me-2'></i>Order ID</td>
                                        <td>: {{ $data->pembayaran->order_id }}</td>
                                    </tr>
                                    <tr>
                                        <td><i class='bx bx-check-circle text-primary me-2'></i>Status</td>
                                        <td>: <span class="badge bg-{{
                                                $data->pembayaran->status == 'sudah_bayar' ? 'success' :
                                                ($data->pembayaran->status == 'pending' ? 'info' :
                                                ($data->pembayaran->status == 'belum_bayar' ? 'warning' : 'danger'))
                                            }}">{{ ucfirst(str_replace('_', ' ', $data->pembayaran->status)) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><i class='bx bx-wallet text-primary me-2'></i>Metode</td>
                                        <td>: {{ ucfirst($data->pembayaran->metode_pembayaran ?? '-') }}</td>
                                    </tr>
                                </table>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- E-Tiket -->
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex align-items-center">
                                <i class='bx bx-ticket me-2'></i>
                                <h6 class="mb-0 text-white">E-Tiket</h6>
                            </div>
                            <div class="card-body p-0">
                                {{-- <div class="e-ticket-wrapper"> --}}
                                    @if($data->pembayaran && $data->pembayaran->status == 'sudah_bayar')
                                    <div class="barcode-section">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h6 class="mb-0 d-flex align-items-center">
                                                <i class='bx bx-ticket me-2 text-primary'></i>E-Tiket Digital
                                            </h6>
                                            <button class="btn btn-primary btn-sm" onclick="window.print()">
                                                <i class='bx bx-download me-1'></i>Download
                                            </button>
                                        </div>

                                        <div class="barcode-container">
                                            <center>
                                                @php
                                                    $barcodeData = $data->tiket->kode_tiket . '-' . $data->id;
                                                    $barcodeImage = 'data:image/png;base64,' . DNS1D::getBarcodePNG($barcodeData, 'C128', 2, 100);
                                                @endphp
                                                <img src="{{ $barcodeImage }}" alt="Barcode" class="img-fluid" style="max-width: 450px;">
                                                <div class="barcode-text mt-3">
                                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                                        <input type="text" id="barcodeImageLink{{ $data->id }}"
                                                            value="{{ $data->tiket->kode_tiket }}-{{ $data->id }}"
                                                            class="form-control text-center"
                                                            readonly
                                                            style="max-width: 300px;">
                                                        <button class="btn btn-outline-primary btn-sm"
                                                            onclick="copyBarcodeImageLink('{{ $data->id }}')"
                                                            id="copyImageBtn{{ $data->id }}">
                                                            <i class='bx bx-copy'></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </center>
                                        </div>

                                        <div class="ticket-info mt-4">
                                            <div class="ticket-info-item">
                                                <i class='bx bx-calendar text-primary'></i>
                                                <div class="label">Tanggal Kunjungan</div>
                                                <div class="value">
                                                    {{ $data->detail_pemesanan->tanggal_kunjungan ?
                                                    \Carbon\Carbon::parse($data->detail_pemesanan->tanggal_kunjungan)->format('d
                                                    M Y') :
                                                    'Belum ditentukan' }}
                                                </div>
                                            </div>

                                            <div class="ticket-info-item">
                                                <i class='bx bx-group text-primary'></i>
                                                <div class="label">Jumlah Pengunjung</div>
                                                <div class="value">{{ $data->jumlah_tiket }} Orang</div>
                                            </div>

                                            <div class="ticket-info-item">
                                                <i class='bx bx-check-circle text-primary'></i>
                                                <div class="label">Status Tiket</div>
                                                <div class="value">
                                                    @php
                                                    $today = \Carbon\Carbon::now();
                                                    $visitDate =
                                                    \Carbon\Carbon::parse($data->detail_pemesanan->expired_at ??
                                                    $data->created_at);
                                                    $isExpired = $today->isAfter($visitDate);
                                                    @endphp

                                                    @if($data->status == 'batal')
                                                    <span class="badge bg-danger">Dibatalkan</span>
                                                    @elseif($isExpired)
                                                    <span class="badge bg-secondary">Kadaluarsa</span>
                                                    @elseif($data->status == 'selesai')
                                                    <span class="badge bg-success">Dapat Digunakan</span>
                                                    @else
                                                    <span class="badge bg-primary">Aktif</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-center mt-4">
                                            <div class="validity-badge {{ !$isExpired ? 'valid' : 'expired' }}">
                                                <i class='bx {{ !$isExpired ? ' bx-check-circle' : 'bx-time-five'
                                                    }}'></i>
                                                <span>{{ !$isExpired ? 'Tiket masih berlaku' : 'Tiket sudah tidak
                                                    berlaku' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="text-center py-5">
                                        <i class='bx bx-lock-alt text-secondary' style="font-size: 3.5rem;"></i>
                                        <h6 class="mt-3 mb-2">Tiket Belum Tersedia</h6>
                                        <p class="text-muted mb-0">E-Tiket akan tersedia setelah pembayaran selesai</p>

                                        @if(!$data->pembayaran || $data->pembayaran->status == 'belum_bayar' ||
                                        $data->pembayaran->status == 'gagal')
                                        <button class="btn btn-primary mt-3 pay-button" data-id="{{ $data->id }}">
                                            <i class='bx bx-credit-card me-1'></i>Bayar Sekarang
                                        </button>
                                        @elseif($data->pembayaran->status == 'pending')
                                        <div class="alert alert-info d-inline-block mt-3">
                                            <i class='bx bx-info-circle me-1'></i>Menunggu pembayaran dikonfirmasi
                                        </div>
                                        @endif
                                    </div>
                                    @endif
                                    {{--
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modal-close" data-bs-dismiss="modal">
                    <i class='bx bx-x me-1'></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<!-- Midtrans -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<!-- DataTables -->
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#pemesanan-table').DataTable({
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data yang ditampilkan",
                infoFiltered: "(difilter dari _MAX_ total data)",
                zeroRecords: "Tidak ada data yang cocok",
                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            },
            responsive: true,
            order: [[0, 'asc']]
        });
    });

    // SweetAlert for messages
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });

    @if(session('success'))
        Toast.fire({
            icon: "success",
            title: "{{ session('success') }}"
        });
    @endif

    @if(session('error'))
        Toast.fire({
            icon: "error",
            title: "{{ session('error') }}"
        });
    @endif

    // Confirm Delete
    function confirmDelete(id) {
        Swal.fire({
            title: "Yakin?",
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("delete-form-" + id).submit();
            }
        });
    }

    // Payment handling
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
                                }).then(() => location.reload());
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

    // Copy Barcode Text
    function copyBarcode(id) {
        const barcodeInput = document.getElementById(`barcodeText${id}`);
        const copyBtn = document.getElementById(`copyBtn${id}`);

        // Select the text
        barcodeInput.select();
        barcodeInput.setSelectionRange(0, 99999); // For mobile devices

        // Copy the text
        navigator.clipboard.writeText(barcodeInput.value).then(() => {
            // Change button icon and style temporarily
            const originalContent = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="bx bx-check"></i>';
            copyBtn.classList.add('btn-success');
            copyBtn.classList.remove('btn-outline-primary');

            // Reset button after 2 seconds
            setTimeout(() => {
                copyBtn.innerHTML = originalContent;
                copyBtn.classList.remove('btn-success');
                copyBtn.classList.add('btn-outline-primary');
            }, 2000);

            // Show toast notification
            Toast.fire({
                icon: 'success',
                title: 'Kode barcode berhasil disalin!'
            });
        }).catch(err => {
            Toast.fire({
                icon: 'error',
                title: 'Gagal menyalin kode barcode'
            });
        });
    }

    // Copy Barcode Image Link
    function copyBarcodeImageLink(id) {
        const barcodeInput = document.getElementById(`barcodeImageLink${id}`);
        const copyBtn = document.getElementById(`copyImageBtn${id}`);

        // Select the text
        barcodeInput.select();
        barcodeInput.setSelectionRange(0, 99999); // For mobile devices

        // Copy the text
        navigator.clipboard.writeText(barcodeInput.value).then(() => {
            // Change button icon and style temporarily
            const originalContent = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="bx bx-check"></i>';
            copyBtn.classList.add('btn-success');
            copyBtn.classList.remove('btn-outline-primary');

            // Reset button after 2 seconds
            setTimeout(() => {
                copyBtn.innerHTML = originalContent;
                copyBtn.classList.remove('btn-success');
                copyBtn.classList.add('btn-outline-primary');
            }, 2000);

            // Show toast notification
            Toast.fire({
                icon: 'success',
                title: 'Link gambar barcode berhasil disalin!'
            });
        }).catch(err => {
            Toast.fire({
                icon: 'error',
                title: 'Gagal menyalin link gambar barcode'
            });
        });
    }
</script>
@endpush