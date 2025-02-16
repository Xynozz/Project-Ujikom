@extends('layouts.admin.frontend.template')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Menu /</span> Kategori</h5>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tabel Kategori</h5>
            <a href="{{ route('kategori.create') }}" class="btn btn-primary">Tambah</a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover text-center">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Deskripsi</th>
                        <th>Icon</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($kategori as $data)
                    <tr>
                        <td><strong>{{ $loop->index + 1 }}</strong></td>
                        <td>{{ $data->nama_kategori }}</td>
                        <td>{{ Str::limit($data->deskripsi, 30, '...') }}</td>
                        <td>
                            <img src="{{ Storage::url($data->icon) }}" width="50px" alt="">
                        </td>
                        <td>
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('kategori.edit', $data->id) }}">
                                        <i class="bx bx-edit me-1"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ route('kategori.destroy', $data->id) }}" method="POST"
                                        style="display:inline;">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="dropdown-item"
                                            onclick="return confirm('Apakah kamu yakin ingin menghapus Kategori ini?')">
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
