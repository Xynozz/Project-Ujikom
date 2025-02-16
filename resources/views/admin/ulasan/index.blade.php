@extends('layouts.admin.frontend.template')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Ulasan</span></h5>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Tabel Ulasan</h5>
            <a href="{{ route('ulasan.create') }}" class="btn btn-primary">Tambah</a>
        </div>
        <div class="table-responsive text-nowrap">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Wisata</th>
                        <th>Username</th>
                        <th>Rating</th>
                        <th>Tanggal Ulasan</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @foreach ($ulasan as $data)
                    <tr>
                        <td><strong>{{ $loop->index + 1 }}</strong></td>
                        <td>{{ $data->wisata->nama_wisata }}</td>
                        <td>{{ $data->user->username }}</td>
                        <td>⭐{{ $data->rating }}</td>
                        <td>{{ Carbon\Carbon::parse($data->tanggal_ulasan)->translatedFormat('d F Y') }}</td>
                        <td>
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('ulasan.edit', $data->id) }}">
                                        <i class="bx bx-edit me-1"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ route('ulasan.destroy', $data->id) }}" method="POST" style="display:inline;">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="dropdown-item" onclick="return confirm('Apakah kamu yakin ingin menghapus Kategori ini?')">
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
