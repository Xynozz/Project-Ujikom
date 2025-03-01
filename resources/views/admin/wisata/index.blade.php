@extends('layouts.admin.frontend.template')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
@endpush
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Menu /</span> Wisata</h5>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tabel Wisata</h5>
            <a href="{{ route('wisata.create') }}" class="btn btn-primary">Tambah</a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover" id="example">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Wisata</th>
                        <th>Lokasi</th>
                        <th>Thumbnail</th>
                        <th>Jam Operasional</th>
                        <th>Status</th>
                        <th>Kategori</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($wisata as $data)
                    <tr>
                        <td><strong>{{ $loop->index + 1 }}</strong></td>
                        <td>{{ $data->nama_wisata }}</td>
                        <td>{{ $data->lokasi }}</td>
                        <td>
                            <img src="{{ Storage::url($data->thumbnail) }}" width="50px" alt="">
                        </td>
                        <td>{{ $data->jam_buka }} --- {{ $data->jam_tutup }}</td>
                        <td>
                            <span class="badge {{ $data->status == 'aktif' ? 'bg-success' : 'bg-danger' }}">{{
                                $data->status
                                }}</span>
                        </td>
                        <td>{{ $data->kategori->nama_kategori }}</td>
                        <td>
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('wisata.edit', $data->id) }}">
                                        <i class="bx bx-edit me-1"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <form id="delete-form" action="{{ route('wisata.destroy', $data->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="dropdown-item" onclick="confirmDelete(event)">
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
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

{{-- Toast --}}
<script>
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
</script>

{{-- Confirm Delete --}}
<script>
    function confirmDelete() {
        Swal.fire({
            title: "Yakin?",
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Ya, hapus!"
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById("delete-form").submit();
            }
        });
    }
</script>

<script>
    new DataTable('#example')
</script>
@endpush
