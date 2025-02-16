@extends('layouts.admin.frontend.template')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Menu /</span> Wisata</h5>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tabel Wisata</h5>
            <a href="{{ route('wisata.create') }}" class="btn btn-primary">Tambah</a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
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
                        <td>{{ $data->jam_operasional }}</td>
                        <td>
                            <span class="badge {{ $data->status == 'aktif' ? 'bg-success' : 'bg-danger' }}">{{ $data->status }}</span>
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
                                    <form action="{{ route('wisata.destroy', $data->id) }}" method="POST"
                                        style="display:inline;">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="dropdown-item"
                                            onclick="return confirm('Apakah kamu yakin ingin menghapus Wisata ini?')">
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
