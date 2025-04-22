@extends('layouts.admin.frontend.template')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<style>
    /* Status Badge Styles */
    .status-badge {
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 0.375rem;
    }

    /* Action Button Styles */
    .action-buttons {
        display: flex;
        gap: 5px;
    }

    .btn-icon {
        padding: 5px 8px;
        font-size: 14px;
        border-radius: 0.375rem;
    }

    /* Modal Styles */
    .modal-header {
        border-bottom: 2px solid #696cff;
        background: linear-gradient(135deg, #696cff, #4f46e5);
        color: white;
    }

    .modal-content {
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }

    .modal .card {
        box-shadow: none;
        border: 1px solid rgba(0, 0, 0, .125);
        border-radius: 0.5rem;
    }

    .modal .card-header {
        background: linear-gradient(135deg, #696cff, #4f46e5);
        color: white;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem 0.5rem 0 0;
    }

    /* Scanner Styles */
    .scanner-area {
        aspect-ratio: 4/3;
        background: #000;
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .scanner-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100%25' height='100%25'%3E%3Crect width='100%25' height='100%25' fill='none' stroke='%23696cff' stroke-width='2' stroke-dasharray='20,20' rx='10' ry='10'/%3E%3C/svg%3E") center/calc(100% - 40px) calc(100% - 40px) no-repeat;
    }

    .scanner-line {
        position: absolute;
        width: calc(100% - 40px);
        height: 2px;
        background: #696cff;
        top: 50%;
        left: 20px;
        transform: translateY(-50%);
        animation: scan 2s linear infinite;
    }

    @keyframes scan {
        0% {
            transform: translateY(calc(-50% - 100px));
        }

        50% {
            transform: translateY(calc(-50% + 100px));
        }

        100% {
            transform: translateY(calc(-50% - 100px));
        }
    }

    /* Tab Styles */
    .nav-tabs {
        border: none;
        background: #f8f9fa;
    }

    .nav-tabs .nav-link {
        border: none;
        padding: 1rem;
        color: #697a8d;
        border-radius: 0;
    }

    .nav-tabs .nav-link.active {
        color: #696cff;
        background: transparent;
        border-bottom: 2px solid #696cff;
    }

    /* Upload Area Styles */
    .upload-area {
        border: 2px dashed #d9dee3;
        transition: all 0.3s ease;
    }

    .upload-area:hover {
        border-color: #696cff;
    }

    .file-upload-input {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }

    /* Table Styles */
    .table-responsive {
        margin: 0;
    }

    .table> :not(caption)>*>* {
        padding: 1rem 1.25rem;
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(105, 108, 255, 0.04);
    }

    /* DataTables Customization */
    .dataTables_wrapper .dataTables_length select {
        min-width: 5rem;
        padding: 0.375rem 2rem 0.375rem 0.75rem;
        background-position: right 0.75rem center;
        border: 1px solid #d9dee3;
        border-radius: 0.375rem;
    }

    .dataTables_wrapper .dataTables_filter input {
        min-width: 15rem;
        padding: 0.375rem 0.75rem;
        border: 1px solid #d9dee3;
        border-radius: 0.375rem;
    }

    .dataTables_wrapper .dataTables_info {
        padding-top: 0.5rem;
        font-size: 0.875rem;
        color: #697a8d;
    }

    /* Info Card Styles */
    .info-card {
        height: 100%;
        transition: transform 0.3s ease;
        border: none;
        border-radius: 12px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
    }

    .info-card:hover {
        transform: translateY(-5px);
    }

    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .info-list-item {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .info-list-item:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #697a8d;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .info-value {
        font-weight: 500;
        color: #566a7f;
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header Section -->
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Transaksi /</span> Detail Pemesanan
    </h4>

    <!-- Main Card -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Pemesanan Tiket</h5>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#scanQrModal">
                    <i class="bx bx-qr-scan me-1"></i>Scan QR
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="detailPemesananTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kode E-Tiket</th>
                            <th>Pemesan</th>
                            <th>Wisata</th>
                            <th>Total Tiket</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detailPemesanan as $data)
                        <tr>
                            <td><strong>{{ $loop->iteration }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-calendar text-primary me-2"></i>
                                    {{ $data->created_at->format('d M Y') }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-barcode text-primary me-2"></i>
                                    {{ $data->pemesanan->tiket->kode_tiket }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-user text-primary me-2"></i>
                                    {{ $data->pemesanan->user->username }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bx bx-map-pin text-primary me-2"></i>
                                    {{ $data->pemesanan->wisata->nama_wisata }}
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-label-info">
                                    <i class="bx bx-group me-1"></i>
                                    {{ $data->pemesanan->jumlah_tiket }} Orang
                                </span>
                            </td>
                            <td>
                                @if($data->status == 'Activate')
                                <span class="badge bg-success status-badge">
                                    <i class="bx bx-check-circle me-1"></i>Sudah Digunakan
                                </span>
                                @elseif($data->status == 'Expired')
                                <span class="badge bg-danger status-badge">
                                    <i class="bx bx-x-circle me-1"></i>Kadaluarsa
                                </span>
                                @else
                                <span class="badge bg-warning status-badge">
                                    <i class="bx bx-time me-1"></i>Belum Digunakan
                                </span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#detailModal{{ $data->id }}">
                                            <i class="bx bx-show me-1"></i> Detail
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

<!-- QR Scanner Modal -->
<div class="modal fade" id="scanQrModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white">
                    <i class="bx bx-qr-scan me-2"></i>Scan QR Code
                </h5>
            </div>
            <div class="modal-body p-0">
                <!-- Scanner Tabs -->
                <ul class="nav nav-tabs nav-fill" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#cameraTab" type="button"
                            role="tab">
                            <i class="bx bx-camera me-2"></i>Kamera
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#uploadTab" type="button"
                            role="tab">
                            <i class="bx bx-upload me-2"></i>Upload
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#urlTab" type="button" role="tab">
                            <i class="bx bx-link me-2"></i>URL
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Camera Scanner Tab -->
                    <div class="tab-pane fade show active p-4" id="cameraTab" role="tabpanel">
                        <div class="qr-container mb-3">
                            <div class="scanner-area position-relative">
                                <video id="qrScanner" class="w-100 rounded"></video>
                                <div class="scanner-overlay">
                                    <div class="scanner-line"></div>
                                </div>
                                <!-- Camera Switch Button -->
                                <button id="switchCamera"
                                    class="btn btn-icon btn-secondary position-absolute top-0 end-0 m-3">
                                    <i class="bx bx-refresh"></i>
                                </button>
                            </div>
                            <!-- Camera Selection -->
                            <div class="mt-3">
                                <select id="cameraSelect" class="form-select">
                                    <option value="">Pilih Kamera</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Tab -->
                    <div class="tab-pane fade p-4" id="uploadTab" role="tabpanel">
                        <div class="qr-container">
                            <div class="upload-area text-center p-4 rounded border-2 border-dashed position-relative">
                                <input type="file" class="file-upload-input" id="qrImageInput" accept="image/*" hidden>
                                <div class="upload-content">
                                    <i class="bx bx-image-add display-4 text-primary mb-2"></i>
                                    <h6>Upload Gambar QR Code</h6>
                                    <p class="text-muted small mb-0">Upload gambar dalam format JPG, PNG, atau GIF</p>
                                    <button type="button" class="btn btn-primary mt-3"
                                        onclick="document.getElementById('qrImageInput').click()">
                                        <i class="bx bx-upload me-1"></i>Pilih File
                                    </button>
                                </div>
                                <div id="uploadPreview" class="mt-3 d-none">
                                    <img src="" alt="Preview" class="img-fluid rounded">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- URL Tab -->
                    <div class="tab-pane fade p-4" id="urlTab" role="tabpanel">
                        <div class="qr-container">
                            <div class="form-group">
                                <label class="form-label">URL Gambar QR Code</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bx bx-link"></i>
                                    </span>
                                    <input type="url" class="form-control" id="qrImageUrl"
                                        placeholder="https://example.com/qr-code.png">
                                    <button class="btn btn-primary" onclick="scanQrFromUrl()">
                                        <i class="bx bx-search me-1"></i>Scan
                                    </button>
                                </div>
                                <div class="form-text">
                                    Masukkan URL gambar QR Code yang valid
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Result Area -->
                <div id="scanResult" class="d-none p-4 border-top">
                    <div id="loadingResult" class="text-center py-3">
                        <div class="spinner-border text-primary">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-muted mt-2 mb-0">Memvalidasi tiket...</p>
                    </div>
                    <div id="successResult" class="alert alert-success d-none mb-0">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-check-circle fs-4 me-2"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Tiket Valid!</h6>
                                <p class="mb-0" id="successMessage"></p>
                            </div>
                        </div>
                    </div>
                    <div id="errorResult" class="alert alert-danger d-none mb-0">
                        <div class="d-flex align-items-center">
                            <i class="bx bx-error-circle fs-4 me-2"></i>
                            <div>
                                <h6 class="alert-heading mb-1">Gagal Memvalidasi</h6>
                                <p class="mb-0" id="errorMessage"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i>Tutup
                </button>
                <button type="button" class="btn btn-primary" onclick="resetScanner()">
                    <i class="bx bx-refresh me-1"></i>Scan Ulang
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Detail Modals -->
@foreach($detailPemesanan as $data)
<div class="modal fade" id="detailModal{{ $data->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-white">
                    <i class="bx bx-info-circle me-2"></i>Detail Tiket #{{ $data->pemesanan->tiket->kode_tiket }}
                </h5>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <!-- Informasi Tiket -->
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="card-header">
                                <h6 class="mb-0 text-white">
                                    <i class="bx bx-ticket me-2"></i>Informasi Tiket
                                </h6>
                            </div>
                            <div class="card-body">
                                <ul class="info-list">
                                    <li class="info-list-item">
                                        <span class="info-label">
                                            <i class="bx bx-barcode text-primary"></i>Kode Tiket
                                        </span>
                                        <strong class="info-value">{{ $data->pemesanan->tiket->kode_tiket }}</strong>
                                    </li>
                                    <li class="info-list-item">
                                        <span class="info-label">
                                            <i class="bx bx-calendar text-primary"></i>Tanggal
                                        </span>
                                        <span class="info-value">{{ $data->created_at->format('d M Y H:i') }}</span>
                                    </li>
                                    <li class="info-list-item">
                                        <span class="info-label">
                                            <i class="bx bx-map-pin text-primary"></i>Wisata
                                        </span>
                                        <span class="info-value">{{ $data->pemesanan->wisata->nama_wisata }}</span>
                                    </li>
                                    <li class="info-list-item">
                                        <span class="info-label">
                                            <i class="bx bx-group text-primary"></i>Jumlah
                                        </span>
                                        <span class="info-value">{{ $data->pemesanan->jumlah_tiket }} Orang</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- QR Code -->
                    <div class="col-md-6">
                        <div class="info-card">
                            <div class="card-header">
                                <h6 class="mb-0 text-white">
                                    <i class="bx bx-qr me-2"></i>QR Code
                                </h6>
                            </div>
                            <div class="card-body text-center">
                                @if($data->qr_path && file_exists(public_path($data->qr_path)))
                                <img src="{{ asset($data->qr_path) }}" alt="QR Code" class="img-fluid mb-3"
                                    style="max-width: 200px;">
                                <div class="input-group">
                                    <input type="text" class="form-control"
                                        value="{{ $data->pemesanan->tiket->kode_tiket }}" readonly>
                                    <button class="btn btn-primary" onclick="copyTicketCode('{{ $data->id }}')">
                                        <i class="bx bx-copy"></i>
                                    </button>
                                </div>
                                @else
                                <p class="text-muted mb-0">QR Code tidak tersedia</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<script>
    // Initialize DataTable
$(document).ready(function() {
    const table = $('#detailPemesananTable').DataTable({
        responsive: true,
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
        pageLength: 10
    });
});

// Camera handling
let currentStream;
let cameras = [];
let currentCameraIndex = 0;

// Get available cameras
async function getCameras() {
    try {
        const devices = await navigator.mediaDevices.enumerateDevices();
        cameras = devices.filter(device => device.kind === 'videoinput');

        const cameraSelect = document.getElementById('cameraSelect');
        cameraSelect.innerHTML = '<option value="">Pilih Kamera</option>';

        cameras.forEach((camera, index) => {
            const option = document.createElement('option');
            option.value = camera.deviceId;
            option.text = camera.label || `Kamera ${index + 1}`;
            cameraSelect.appendChild(option);
        });
    } catch (error) {
        console.error('Error getting cameras:', error);
    }
}

// Start camera
async function startCamera(deviceId = null) {
    try {
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
        }

        const constraints = {
            video: deviceId ? { deviceId: { exact: deviceId } } : { facingMode: "environment" }
        };

        const stream = await navigator.mediaDevices.getUserMedia(constraints);
        currentStream = stream;
        const video = document.getElementById('qrScanner');
        video.srcObject = stream;
        await video.play();

        // Start QR scanning
        startQRScanning(video);
    } catch (error) {
        console.error('Error starting camera:', error);
        showError('Tidak dapat mengakses kamera. Pastikan memberikan izin kamera.');
    }
}

@if(session('qr_error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '{{ session('qr_error') }}',
        });
    @endif

    @if(session('qr_success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '{{ session('qr_success') }}',
        });
    @endif

// Switch camera
function switchCamera() {
    currentCameraIndex = (currentCameraIndex + 1) % cameras.length;
    if (cameras[currentCameraIndex]) {
        startCamera(cameras[currentCameraIndex].deviceId);
    }
}

function scanQrFromUrl() {
    const url = document.getElementById('qrImageUrl').value;
    if (!url) {
        Swal.fire('Error', 'URL tidak boleh kosong!', 'error');
        return;
    }

    // Kirim GET request ke endpoint aktivasi
    window.location.href = `/qr/activate/url?url=${encodeURIComponent(url)}`;
}


// File upload handling
document.getElementById('qrImageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Show preview
            const preview = document.getElementById('uploadPreview');
            preview.querySelector('img').src = e.target.result;
            preview.classList.remove('d-none');

            // Process QR code
            processUploadedImage(e.target.result);
        };
        reader.readAsDataURL(file);
    }
});

// Process uploaded image
function processUploadedImage(dataUrl) {
    const image = new Image();
    image.src = dataUrl;
    image.onload = function() {
        const canvas = document.createElement('canvas');
        canvas.width = image.width;
        canvas.height = image.height;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(image, 0, 0);

        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imageData.data, imageData.width, imageData.height);

        if (code) {
            processQRCode(code.data);
        } else {
            showError('Tidak dapat menemukan QR code pada gambar');
        }
    };
}

// Show success message
function showSuccess(message) {
    const resultArea = document.getElementById('scanResult');
    const successArea = document.getElementById('successResult');
    const loadingArea = document.getElementById('loadingResult');
    const errorArea = document.getElementById('errorResult');

    resultArea.classList.remove('d-none');
    successArea.classList.remove('d-none');
    loadingArea.classList.add('d-none');
    errorArea.classList.add('d-none');

    document.getElementById('successMessage').textContent = message;
}

// Show error message
function showError(message) {
    const resultArea = document.getElementById('scanResult');
    const errorArea = document.getElementById('errorResult');
    const loadingArea = document.getElementById('loadingResult');
    const successArea = document.getElementById('successResult');

    resultArea.classList.remove('d-none');
    errorArea.classList.remove('d-none');
    loadingArea.classList.add('d-none');
    successArea.classList.add('d-none');

    document.getElementById('errorMessage').textContent = message;
}

// Initialize scanner
document.addEventListener('DOMContentLoaded', function() {
    getCameras();

    // Camera selection change handler
    document.getElementById('cameraSelect').addEventListener('change', function(e) {
        if (e.target.value) {
            startCamera(e.target.value);
        }
    });

    // Switch camera button handler
    document.getElementById('switchCamera').addEventListener('click', switchCamera);

    // Modal events
    const scannerModal = document.getElementById('scanQrModal');
    scannerModal.addEventListener('shown.bs.modal', function() {
        startCamera();
    });

    scannerModal.addEventListener('hidden.bs.modal', function() {
        if (currentStream) {
            currentStream.getTracks().forEach(track => track.stop());
        }
    });
});
</script>
@endpush