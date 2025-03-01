@extends('layouts.admin.frontend.template')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css">
<style>
    .filter-buttons {
        margin-top: 28px; /* Aligns with input fields */
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .action-buttons .btn {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
    }

    .table {
        margin-bottom: 0;
    }

    .table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        padding: 12px 8px;
        font-weight: 600;
    }

    .table tbody td {
        padding: 12px 8px;
        vertical-align: middle;
    }

    .badge {
        padding: 6px 12px;
        font-weight: 500;
        text-transform: capitalize;
    }

    .card {
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.04);
    }

    .card-header {
        background-color: #fff;
        border-bottom: 1px solid rgba(0,0,0,0.125);
        padding: 1.25rem;
    }

    @media print {
        .no-print, .action-buttons, .date-range-filter,
        .dt-buttons, .dataTables_filter, .dataTables_length,
        .dataTables_paginate, .dataTables_info {
            display: none !important;
        }
        .card {
            box-shadow: none !important;
            border: none !important;
        }
        .table th, .table td {
            padding: 8px !important;
        }
        body {
            background-color: white !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold m-0">
            <span class="text-muted fw-light">Laporan /</span> Data User
        </h4>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="date-range-filter no-print">
                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <h5>Laporan User</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="filter-buttons">
                            <button id="filter_date" class="btn btn-primary">
                                <i class="bx bx-filter-alt me-1"></i> Filter
                            </button>
                            <button id="reset_filter" class="btn btn-outline-secondary">
                                <i class="bx bx-reset me-1"></i> Reset
                            </button>
                            <a href="{{ route('laporan.user.export') }}" class="btn btn-success">
                                <i class="bx bx-download me-1"></i> PDF
                            </a>
                            <a href="{{ route('laporan.user.excel') }}" class="btn btn-primary">
                                <i class="bx bx-file me-1"></i> Excel
                            </a>
                            <button class="btn btn-secondary" onclick="window.print()">
                                <i class="bx bx-printer me-1"></i> Print
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body mt-2">
            <div class="table-responsive">
                <table class="table table-hover" id="user_table">
                    <thead>
                        <tr>
                            <th class="text-center" width="50">No</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th class="text-center">Tanggal Registrasi</th>
                            <th class="text-center">Total Pemesanan</th>
                            <th class="text-end">Total Pembayaran</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $key => $user)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->no_hp ?? '-' }}</td>
                            <td class="text-center" data-sort="{{ $user->created_at->format('Y-m-d') }}">
                                {{ $user->created_at->format('d/m/Y') }}
                            </td>
                            <td class="text-center">{{ $user->pemesanan_count }}</td>
                            <td class="text-end" data-sort="{{ $user->total_pembayaran }}">
                                Rp {{ number_format($user->total_pembayaran, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="5" class="text-end">Total:</th>
                            <th class="text-center" id="total_pemesanan"></th>
                            <th class="text-end" id="total_pembayaran"></th>
                            <th></th>
                        </tr>
                    </tfoot>
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
<script>
    $(document).ready(function() {
        // Initialize DataTable with server-side date filtering
        const table = $('#user_table').DataTable({
            dom: '<"d-flex justify-content-between mb-3"B<"d-flex align-items-center"lf>>rtip',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="bx bx-file me-1"></i> Excel',
                    className: 'btn btn-success btn-sm',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7]
                    },
                    title: 'Laporan User - ' + new Date().toLocaleDateString('id-ID'),
                    footer: true
                },
                {
                    extend: 'pdf',
                    text: '<i class="bx bx-download me-1"></i> PDF',
                    className: 'btn btn-danger btn-sm',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7]
                    },
                    title: 'Laporan User - ' + new Date().toLocaleDateString('id-ID'),
                    footer: true,
                    orientation: 'landscape'
                },
                {
                    extend: 'print',
                    text: '<i class="bx bx-printer me-1"></i> Print',
                    className: 'btn btn-secondary btn-sm',
                    exportOptions: {
                        columns: [0,1,2,3,4,5,6,7]
                    },
                    title: 'Laporan User - ' + new Date().toLocaleDateString('id-ID'),
                    footer: true
                }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json',
                emptyTable: "Tidak ada data user"
            },
            order: [[4, 'desc']], // Sort by registration date (newest first)
            responsive: true,
            pageLength: 10,
            footerCallback: function(row, data, start, end, display) {
                let totalPemesanan = 0;
                let totalPembayaran = 0;

                // Calculate total for visible rows
                for (let i = 0; i < display.length; i++) {
                    const rowData = this.api().row(display[i]).data();
                    totalPemesanan += parseInt(rowData[5].replace(/[^\d]/g, '')) || 0;
                    totalPembayaran += parseInt(rowData[6].replace(/[^\d]/g, '')) || 0;
                }

                $('#total_pemesanan').html(totalPemesanan);
                $('#total_pembayaran').html('Rp ' + new Intl.NumberFormat('id-ID').format(totalPembayaran));
            }
        });

        // Server-side date filtering
        $('#filter_date').on('click', function() {
            let dateFrom = $('#date_from').val();
            let dateTo = $('#date_to').val();

            if (dateFrom && dateTo) {
                window.location.href = `{{ route('laporan.user') }}?date_from=${dateFrom}&date_to=${dateTo}`;
            } else {
                alert('Silakan pilih rentang tanggal terlebih dahulu');
            }
        });

        // Reset filter
        $('#reset_filter').on('click', function() {
            window.location.href = `{{ route('laporan.user') }}`;
        });

        // Set input tanggal dari URL
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('date_from')) {
            $('#date_from').val(urlParams.get('date_from'));
        }
        if (urlParams.has('date_to')) {
            $('#date_to').val(urlParams.get('date_to'));
        }

        // Hide DataTables controls if no data
        if (table.data().count() === 0) {
            $('.dt-buttons, .dataTables_filter, .dataTables_info, .dataTables_paginate, .dataTables_length').hide();
        }
    });
</script>
@endpush
