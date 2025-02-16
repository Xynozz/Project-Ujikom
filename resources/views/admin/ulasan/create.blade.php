@extends('layouts.admin.frontend.template')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Ulasan /</span> Tambah Ulasan</h5>
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Ulasan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('ulasan.store') }}" method="POST" multipart enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="basic-default-fullname">Wisata</label>
                            <select class="form-select" name="wisata_id" id="wisata_id">
                                <option value="" selected disabled>-- Pilih Wisata --</option>
                                @foreach($wisata as $data)
                                <option value="{{ $data->id }}">
                                    {{ $data->nama_wisata }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-default-fullname">Username</label>
                            <select class="form-select" name="user_id" id="user_id">
                                <option value="" selected disabled>-- Pilih Username --</option>
                                @foreach($user as $data)
                                <option value="{{ $data->id }}">
                                    {{ $data->username }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label for="basic-default-company">Ulasan</label>
                            <textarea class="form-control" name="ulasan" id="basic-default-company" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-default-company">Rating</label>
                            <select class="form-select" name="rating" id="">
                                <option value="" selected disabled>-- Pilih Rating --</option>
                                <option value="1">Bintang 1</option>
                                <option value="2">Bintang 2</option>
                                <option value="3">Bintang 3</option>
                                <option value="4">Bintang 4</option>
                                <option value="5">Bintang 5</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('kategori.index') }}" class="btn btn-danger">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
