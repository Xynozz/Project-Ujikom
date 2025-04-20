@extends('layouts.admin.frontend.template')
@section('content')
    <style>
        .sidebar {
            min-height: 100vh;
            background-color: #343a40;
            color: #fff;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar .nav-link.active {
            color: #fff;
            background-color: #0d6efd;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .main-content {
            margin-left: 0;
            transition: margin-left 0.3s;
        }

        .card-dashboard {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .card-dashboard:hover {
            transform: translateY(-5px);
        }

        @media (min-width: 768px) {
            .main-content {
                margin-left: 250px;
            }
        }

        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
    </style>
    <div class="container-fluid">
        <div class="row">

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-12 px-md-4 main-content">
                <div
                    class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Dashboard</h1>
                </div>

                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                        <div class="card card-dashboard border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="card-icon bg-primary text-white me-3">
                                        <i class="fas fa-shopping-cart"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0">Total Pesanan</h5>
                                        <p class="fs-3 fw-bold mb-0">{{ $totalPesanan }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                        <div class="card card-dashboard border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="card-icon bg-success text-white me-3">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0">Pendapatan</h5>
                                        <p class="fs-3 fw-bold mb-0">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                        <div class="card card-dashboard border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="card-icon bg-info text-white me-3">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0">Pengunjung</h5>
                                        <p class="fs-3 fw-bold mb-0">{{ $jumlahPengunjung }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                        <div class="card card-dashboard border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="card-icon bg-warning text-white me-3">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div>
                                        <h5 class="card-title mb-0">Rating Rata-rata</h5>
                                        <p class="fs-3 fw-bold mb-0">{{ $ratingRataRata }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders and Popular Destinations -->
                <div class="row mb-4">
                    <div class="col-lg-8 mb-4">
                        <div class="card card-dashboard border-0">
                            <div class="card-header bg-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Pesanan Terbaru</h5>
                                    <a href="{{ route('pemesanan.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th scope="col">ID</th>
                                                <th scope="col">Pelanggan</th>
                                                <th scope="col">Destinasi</th>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Total</th>
                                                <th scope="col">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pesananTerbaru as $pesanan)
                                                <tr>
                                                    <td>#ORD-{{ $pesanan->id }}</td>
                                                    <td>{{ $pesanan->user->name }}</td>
                                                    <td>{{ $pesanan->wisata->nama_wisata }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($pesanan->created_at)->format('d M Y') }}
                                                    </td>
                                                    <td>
                                                        @if ($pesanan->status == 'Lunas')
                                                            <span class="badge bg-success">Lunas</span>
                                                        @elseif ($pesanan->status == 'Menunggu')
                                                            <span class="badge bg-warning text-dark">Menunggu</span>
                                                        @else
                                                            <span class="badge bg-danger">Dibatalkan</span>
                                                        @endif
                                                    </td>
                                                    <td>Rp {{ number_format($pesanan->total_bayar, 0, ',', '.') }}</td>
                                                    <td>
                                                        <a href="{{ route('pemesanan.show', $pesanan->id) }}"
                                                            class="btn btn-sm btn-outline-primary"><i
                                                                class="fas fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card card-dashboard border-0">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Destinasi Populer</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="destinasiPieChart" height="50"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Chart & Calendar -->
                <div class="row mb-4">
                    <div class="col-lg-8 mb-4">
                        <div class="card card-dashboard border-0">
                            <div class="card-header bg-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Statistik Penjualan</h5>
                                    <div>
                                        <select class="form-select form-select-sm">
                                            <option>7 Hari Terakhir</option>
                                            <option selected>30 Hari Terakhir</option>
                                            <option>3 Bulan Terakhir</option>
                                            <option>1 Tahun Terakhir</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="card-header py-3 bg-primary">
                                    <h6 class="m-0 font-weight-bold text-white">Statistik Penjualan Tiket (30 Hari Terakhir)
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="salesChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card card-dashboard border-0">
                            <div class="card-header bg-white">
                                <h5 class="mb-0">Jadwal Tiket Tersedia</h5>
                            </div>
                            <div class="card-body">
                                <div class="list-group">
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Kawah Ijen</h6>
                                            <small>12 Apr</small>
                                        </div>
                                        <p class="mb-1">Kuota: 45/50 tersedia</p>
                                        <small class="text-success">Rp 350.000/orang</small>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Danau Toba</h6>
                                            <small>14 Apr</small>
                                        </div>
                                        <p class="mb-1">Kuota: 28/30 tersedia</p>
                                        <small class="text-success">Rp 450.000/orang</small>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Pantai Kuta</h6>
                                            <small>15 Apr</small>
                                        </div>
                                        <p class="mb-1">Kuota: 15/100 tersedia</p>
                                        <small class="text-warning">Hampir penuh!</small>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">Candi Borobudur</h6>
                                            <small>16 Apr</small>
                                        </div>
                                        <p class="mb-1">Kuota: 80/80 tersedia</p>
                                        <small class="text-success">Rp 300.000/orang</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script>
        // Sales Chart
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('salesChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($labels) !!},
                    datasets: [{
                            label: 'Penjualan Tiket',
                            data: {!! json_encode($jumlahTiket) !!},
                            backgroundColor: 'rgba(13, 110, 253, 0.2)',
                            borderColor: 'rgba(13, 110, 253, 1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Pendapatan (Rp)',
                            data: {!! json_encode($pendapatan) !!},
                            backgroundColor: 'rgba(25, 135, 84, 0.2)',
                            borderColor: 'rgba(25, 135, 84, 1)',
                            borderWidth: 2,
                            tension: 0.3,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });

        const ctxPie = document.getElementById('destinasiPieChart').getContext('2d');
        const destinasiPieChart = new Chart(ctxPie, {
            type: 'doughnut', // Bisa diganti 'pie' jika tidak ingin ada lubang di tengah
            data: {
                labels: {!! json_encode($data->pluck('nama_wisata')) !!},
                datasets: [{
                    label: 'Jumlah Kunjungan',
                    data: {!! json_encode($data->pluck('total')) !!},
                    backgroundColor: [
                        '#0d6efd', '#198754', '#ffc107', '#dc3545', '#6f42c1'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed + ' kunjungan';
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
