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
                            <select class="form-select @error('wisata_id') is-invalid @enderror" name="wisata_id" id="wisata_id">
                                @foreach($wisata as $data)
                                <option value="{{ $data->id }}" {{ old('wisata_id') == $data->id ? 'selected' : '' }}>
                                    {{ $data->nama_wisata }}
                                </option>
                                @endforeach
                            </select>
                            @error('wisata_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="harga_tiket">Harga Tiket</label>
                            <input type="number" class="form-control @error('harga_tiket') is-invalid @enderror" name="harga_tiket"
                                id="harga_tiket" value="{{ old('harga_tiket', $tiket->harga_tiket) }}" placeholder="Masukan Harga Tiket" />
                            @error('harga_tiket')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <a href="{{ route('tiket.index') }}" class="btn btn-danger">Kembali</a>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
