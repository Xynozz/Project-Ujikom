<!-- filepath: /C:/laragon/www/Hetra_Pemesanan_Tiket/resources/views/admin/dashboard.blade.php -->
@extends('layouts.admin.frontend.template')

@push('css')
<style>
    .summary-card {
        transition: transform 0.2s;
    }
    .summary-card:hover {
        transform: translateY(-5px);
    }
    .summary-card .card-body {
        padding: 1.5rem;
    }
    .summary-card .card-title {
        color: #566a7f;
        font-size: 0.95rem;
    }
    .summary-card .card-text {
        color: #697a8d;
        font-weight: 600;
    }
    .chart-card {
        box-shadow: 0 0.25rem 1rem rgba(161, 172, 184, 0.15);
        border-radius: 0.5rem;
    }
    .chart-card .card-body {
        padding: 1.5rem;
    }
    .table-card .card-header {
        background-color: transparent;
        border-bottom: 1px solid rgba(0,0,0,.125);
        padding: 1rem 1.5rem;
    }
    .table-card .table {
        margin-bottom: 0;
    }
    .table-card .table th {
        border-top: 0;
        font-weight: 600;
        color: #566a7f;
    }
    .table-card .table td {
        color: #697a8d;
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6">
            <div class="card summary-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bx bx-user text-primary fs-3 me-2"></i>
                        <h5 class="card-title mb-0">Total Users</h5>
                    </div>
                    <h2 class="card-text mb-0">{{ $summary['total_users'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card summary-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bx bx-map-alt text-success fs-3 me-2"></i>
                        <h5 class="card-title mb-0">Total Wisata</h5>
                    </div>
                    <h2 class="card-text mb-0">{{ $summary['total_wisata'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card summary-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bx bx-bar-chart text-info fs-3 me-2"></i>
                        <h5 class="card-title mb-0">Pemesanan Bulan Ini</h5>
                    </div>
                    <h2 class="card-text mb-0">{{ $summary['total_pemesanan'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card summary-card">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bx bx-money text-warning fs-3 me-2"></i>
                        <h5 class="card-title mb-0">Pendapatan Bulan Ini</h5>
                    </div>
                    <h2 class="card-text mb-0">Rp {{ number_format($summary['total_pendapatan'], 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card chart-card">
                <div class="card-body">
                    {!! $bookingsChart->container() !!}
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card chart-card">
                <div class="card-body">
                    {!! $destinationsChart->container() !!}
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card table-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Destinasi Terpopuler</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nama Wisata</th>
                                <th class="text-end">Total Pemesanan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($popularWisata as $wisata)
                            <tr>
                                <td>{{ $wisata->nama_wisata }}</td>
                                <td class="text-end">{{ number_format($wisata->total_bookings, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{ $bookingsChart->script() }}
{{ $destinationsChart->script() }}
@endpush
