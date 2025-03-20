@extends('layouts.admin.frontend.template')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<style>
    .card-header {
        padding: 1.5rem 1.5rem;
        margin-bottom: 0;
        background-color: transparent;
        border-bottom: 0 solid #d9dee3;
    }

    .table-responsive {
        padding: 0 1.5rem 1.5rem;
    }

    .dt-buttons {
        margin-bottom: 1rem;
    }

    .btn-add {
        margin-bottom: 0.5rem;
    }

    .table > :not(caption) > * > * {
        vertical-align: middle;
    }

    .icon-preview {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 0.375rem;
    }

    .description-text {
        max-width: 250px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Menu /</span> Kategori
    </h4>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Kategori</h5>
            <a href="{{ route('kategori.create') }}" class="btn btn-primary btn-add">
                <i class="bx bx-plus me-1"></i> Tambah Kategori
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="example">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Nama Kategori</th>
                        <th width="35%">Deskripsi</th>
                        <th class="text-center" width="20%">Icon</th>
                        <th class="text-center" width="20%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kategori as $data)
                    <tr>
                        <td class="text-center"><strong>{{ $loop->iteration }}</strong></td>
                        <td>{{ $data->nama_kategori }}</td>
                        <td>
                            <span class="description-text" title="{{ $data->deskripsi }}">
                                {{ Str::limit($data->deskripsi, 50) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <img src="{{ Storage::url($data->icon) }}" class="icon-preview" alt="{{ $data->nama_kategori }}">
                        </td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('kategori.edit', $data->id) }}">
                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('kategori.destroy', $data->id) }}"
                                          method="POST"
                                          id="delete-form-{{ $data->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="dropdown-item"
                                                onclick="confirmDelete({{ $data->id }})">
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </button>
                                    </form>
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
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>

<script>
$(document).ready(function() {
    $('#example').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            zeroRecords: "Tidak ada data yang cocok",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        },
        pageLength: 10,
        order: [[0, 'asc']]
    });
});

function confirmDelete(id) {
    Swal.fire({
        title: 'Yakin hapus data?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    });
}
</script>

<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
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
            icon: 'success',
            title: "{{ session('success') }}"
        });
    @endif

    @if(session('error'))
        Toast.fire({
            icon: 'error',
            title: "{{ session('error') }}"
        });
    @endif
</script>
@endpush
