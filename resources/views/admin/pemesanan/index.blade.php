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
        border: 1px solid rgba(0,0,0,.125);
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
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('pemesanan.edit', $data->id) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                        <form id="delete-form-{{ $data->id }}" action="{{ route('pemesanan.destroy', $data->id) }}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button type="button" class="dropdown-item" onclick="confirmDelete({{ $data->id }})">
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
                                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#detailModal{{ $data->id }}">
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pemesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Pemesanan Details -->
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Informasi Pemesanan</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="40%">Kode Tiket</td>
                                        <td>: {{ $data->tiket->kode_tiket }}</td>
                                    </tr>
                                    <tr>
                                        <td>Wisata</td>
                                        <td>: {{ $data->wisata->nama_wisata }}</td>
                                    </tr>
                                    <tr>
                                        <td>Jumlah Tiket</td>
                                        <td>: {{ $data->jumlah_tiket }}</td>
                                    </tr>
                                    <tr>
                                        <td>Total Harga</td>
                                        <td>: Rp {{ number_format($data->total_harga, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Status</td>
                                        <td>: <span class="badge bg-{{ $data->status == 'proses' ? 'warning' : ($data->status == 'selesai' ? 'success' : 'danger') }}">
                                            {{ ucfirst($data->status) }}
                                        </span></td>
                                    </tr>
                                    <tr>
                                        <td>Tanggal Pesan</td>
                                        <td>: {{ $data->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- User & Payment Details -->
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">Informasi Pembayaran</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless mb-3">
                                    <tr>
                                        <td width="40%">Pemesan</td>
                                        <td>: {{ $data->user->username }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td>: {{ $data->user->email }}</td>
                                    </tr>
                                </table>

                                <h6 class="border-bottom pb-2">Status Pembayaran</h6>
                                @if(!$data->pembayaran)
                                    <p class="text-muted mb-0">Belum ada pembayaran</p>
                                @else
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="40%">Order ID</td>
                                            <td>: {{ $data->pembayaran->order_id }}</td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td>: <span class="badge bg-{{
                                                $data->pembayaran->status == 'sudah_bayar' ? 'success' :
                                                ($data->pembayaran->status == 'pending' ? 'info' :
                                                ($data->pembayaran->status == 'belum_bayar' ? 'warning' : 'danger'))
                                            }}">
                                                {{ ucfirst($data->pembayaran->status) }}
                                            </span></td>
                                        </tr>
                                        <tr>
                                            <td>Metode</td>
                                            <td>: {{ ucfirst($data->pembayaran->metode_pembayaran ?? '-') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Bayar</td>
                                            <td>: {{ $data->pembayaran->update_at ? $data->pembayaran->update_at->format('d/m/Y H:i') : '-' }}</td>
                                        </tr>
                                    </table>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
</script>
@endpush