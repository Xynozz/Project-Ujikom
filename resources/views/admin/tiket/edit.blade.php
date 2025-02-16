@extends('layouts.admin.frontend.template')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Tiket /</span> Edit Tiket</h5>
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Tiket</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('tiket.update', $tiket->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label" for="wisata_id">Wisata</label>
                            <select class="form-select" name="wisata_id" id="wisata_id">
                                @foreach($wisata as $data)
                                <option value="{{ $data->id }}" {{ $tiket->wisata_id == $data->id ? 'selected' : '' }}>
                                    {{ $data->nama_wisata }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="harga_tiket">Harga Tiket</label>
                            <input type="number" class="form-control" name="harga_tiket" id="harga_tiket" value="{{ $tiket->harga_tiket }}" placeholder="Masukan Harga Tiket" required />
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('tiket.index') }}" class="btn btn-danger">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
