@extends('layouts.admin.frontend.template')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Menu /</span> Kategori</h5>
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Kategori</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('kategori.store') }}" method="POST" multipart enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="basic-default-fullname">Nama Kategori</label>
                            <input type="text" class="form-control" id="basic-default-fullname"
                                name="nama_kategori" placeholder="Masukan Nama Kategori" />
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-default-company">Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" id="" cols="30" rows="3"
                                placeholder="Masukan Deskripsi"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-default-message">Icon</label>
                            <input type="file" class="form-control" name="icon" id="basic-default-message" />
                        </div>
                        <a href="{{ route('kategori.index') }}" class="btn btn-danger">Kembali</a>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
