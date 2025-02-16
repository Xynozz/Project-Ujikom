@extends('layouts.admin.frontend.template')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pemesanan</span></h5>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tabel Pemesanan</h5>
            <a href="{{ route('pemesanan.create') }}" class="btn btn-primary">Tambah</a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Tiket</th>
                        <th>Wisata</th>
                        <th>Tanggal Pemesanan</th>
                        <th>Jumlah Tiket</th>
                        <th>Total Harga</th>
                        <th>Status Pemesanan</th>
                        <th>Status Pembayaran</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($pemesanan as $data)
                    <tr>
                        <td><strong>{{ $loop->iteration }}</strong></td>
                        <td>{{ $data->user->username }}</td>
                        <td>{{ $data->tiket->kode_tiket }}</td>
                        <td>{{ $data->wisata->nama_wisata }}</td>
                        <td>{{ Carbon\Carbon::parse($data->tanggal_pemesanan)->translatedFormat('d F Y') }}</td>
                        <td>{{ $data->jumlah_tiket }}</td>
                        <td>Rp {{ number_format($data->total_harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $data->status == 'proses' ? 'bg-warning' :
                                              ($data->status == 'selesai' ? 'bg-success' : 'bg-danger') }}">
                                {{ ucfirst($data->status) }}
                            </span>
                        </td>
                        <td>
                            <span
                                class="badge {{ $data->pembayaran?->status == 'sudah_bayar' ? 'bg-success' :
                                              ($data->pembayaran?->status == 'belum_bayar' ? 'bg-warning' :
                                              ($data->pembayaran?->status == 'gagal' ? 'bg-danger' : 'bg-secondary')) }}">
                                {{ $data->pembayaran ? ucfirst(str_replace('_', ' ', $data->pembayaran->status)) :
                                'Belum Ada' }}
                            </span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                    data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('pemesanan.edit', $data->id) }}">
                                            <i class="bx bx-edit-alt me-1"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('pemesanan.destroy', $data->id) }}" method="POST"
                                            class="delete-form">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="bx bx-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                    @if(!$data->pembayaran || $data->pembayaran->status == 'belum_bayar' ||
                                    $data->pembayaran->status == 'gagal')
                                    <li>
                                        <button class="dropdown-item pay-button" data-id="{{ $data->id }}">
                                            <i class="bx bx-credit-card me-1"></i> Bayar
                                        </button>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- Midtrans -->
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Handle Delete Confirmation
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin menghapus pemesanan ini?')) {
                this.submit();
            }
        });
    });

    // Handle Payment Buttons
    const payButtons = document.querySelectorAll('.pay-button');
    payButtons.forEach(button => {
        button.addEventListener('click', async function() {
            try {
                const pemesananId = this.getAttribute('data-id');
                button.disabled = true;
                button.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Memproses...';

                const response = await fetch(`/pembayaran/${pemesananId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Terjadi kesalahan pada server');
                }

                if (!data.snap_token) {
                    throw new Error('Gagal mendapatkan token pembayaran');
                }

                // Trigger Midtrans Snap
                window.snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        showAlert('success', 'Pembayaran berhasil!');
                        setTimeout(() => location.reload(), 2000);
                    },
                    onPending: function(result) {
                        showAlert('warning', 'Pembayaran sedang diproses. Silakan cek email Anda untuk instruksi pembayaran.');
                        setTimeout(() => location.reload(), 2000);
                    },
                    onError: function(result) {
                        showAlert('danger', 'Pembayaran gagal: ' + result.status_message);
                        button.disabled = false;
                        button.innerHTML = '<i class="bx bx-credit-card me-1"></i> Bayar';
                    },
                    onClose: function() {
                        button.disabled = false;
                        button.innerHTML = '<i class="bx bx-credit-card me-1"></i> Bayar';
                    }
                });

                // Di bagian snap.pay callback
snap.pay(data.snap_token, {
    onSuccess: function(result) {
        console.log('Success:', result);
        // ... rest of the code
    },
    onPending: function(result) {
        console.log('Pending:', result);
        // ... rest of the code
    },
    onError: function(result) {
        console.error('Error:', result);
        // ... rest of the code
    }
});

            } catch (error) {
                showAlert('danger', error.message);
                button.disabled = false;
                button.innerHTML = '<i class="bx bx-credit-card me-1"></i> Bayar';
                console.error('Error:', error);
            }
        });
    });
});

// Fungsi untuk menampilkan alert
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    const container = document.querySelector('.container-xxl');
    container.insertBefore(alertDiv, container.firstChild);

    // Auto hide after 5 seconds
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}
</script>
@endpush
