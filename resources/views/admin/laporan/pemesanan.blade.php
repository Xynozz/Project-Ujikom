@extends('layouts.admin.frontend.template')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css">
<style>
    .dataTables_empty {
        text-align: center !important;
        padding: 20px !important;
        color: #6c757d;
    }

    @media print {
        .dt-buttons,
        .dataTables_filter,
        .dataTables_length,
        .dataTables_paginate,
        .dataTables_info {
            display: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Laporan /</span> Data Pemesanan
    </h4>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Laporan Pemesanan</h5>
            <div class="d-flex gap-2">
                <a href="{{ route('laporan.user.export') }}" class="btn btn-success">
                    <i class="bx bx-download me-1"></i>PDF
                </a>
                <a href="{{ route('laporan.pemesanan.excel') }}" class="btn btn-primary">
                    <i class="bx bx-file me-1"></i>Excel
                </a>
                <button class="btn btn-secondary" onclick="window.print()">
                    <i class="bx bx-printer me-1"></i> Print
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="pemesananTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kode</th>
                            <th>Pemesan</th>
                            <th>Wisata</th>
                            <th>Jumlah Tiket</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pemesanan as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                <td>{{ $item->tiket->kode_tiket ?? '-' }}</td>
                                <td>{{ $item->user->username ?? '-' }}</td>
                                <td>{{ $item->wisata->nama_wisata ?? '-' }}</td>
                                <td class="text-center">{{ $item->jumlah_tiket }}</td>
                                <td class="text-end">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <span class="badge bg-{{ $item->status === 'success' ? 'success' : 'warning' }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data pemesanan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/datetime/1.5.1/js/dataTables.dateTime.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#pemesananTable').DataTable({
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="bx bx-file me-1"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7]
                    },
                    title: 'Laporan Pemesanan - ' + new Date().toLocaleDateString()
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                emptyTable: "Tidak ada data pemesanan"
            },
            pageLength: 10,
            responsive: true
        });

        // Sembunyikan kontrol DataTables jika data kosong
        if (table.data().count() === 0) {
            $('.dt-buttons, .dataTables_filter, .dataTables_info, .dataTables_paginate, .dataTables_length').hide();
        }

        // Filter rentang tanggal
        $('#date_from, #date_to').on('change', function() {
            let date_from = $('#date_from').val();
            let date_to = $('#date_to').val();

            if (date_from && date_to) {
                window.location.href = `{{ route('laporan.pemesanan') }}?date_from=${date_from}&date_to=${date_to}`;
            }
        });

        // Set input tanggal dari URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('date_from')) {
            $('#date_from').val(urlParams.get('date_from'));
        }
        if (urlParams.has('date_to')) {
            $('#date_to').val(urlParams.get('date_to'));
        }
    });
</script>
@endpush
