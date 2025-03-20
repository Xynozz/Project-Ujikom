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