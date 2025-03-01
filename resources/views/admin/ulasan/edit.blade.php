@extends('layouts.admin.frontend.template')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Ulasan /</span> Edit Ulasan</h5>
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Ulasan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('ulasan.update', $ulasan->id) }}" method="POST" multipart
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label" for="wisata_id">Wisata</label>
                            <select class="form-select @error('wisata_id') is-invalid @enderror" name="wisata_id"
                                id="wisata_id">
                                @foreach($wisata as $data)
                                <option value="{{ $data->id }}" {{ old('wisata_id')==$data->id ? 'selected' : '' }}>
                                    {{ $data->nama_wisata }}</option>
                                @endforeach
                            </select>
                            @error('wisata_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="user_id">Username</label>
                            <select class="form-select @error('user_id') is-invalid @enderror" name="user_id"
                                id="user_id">
                                @foreach($user as $data)
                                <option value="{{ $data->id }}" {{ old('user_id')==$data->id ? 'selected' : '' }}>
                                    {{ $data->nama_lengkap }}</option>
                                @endforeach
                            </select>
                            @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="ulasan">Ulasan</label>
                            <textarea class="form-control @error('ulasan') is-invalid @enderror" name="ulasan"
                                id="ulasan" rows="3">{{ old('ulasan', $ulasan->ulasan) }}</textarea>
                            @error('ulasan')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="basic-default-company">Rating</label>
                            <select class="form-select" name="rating" id="">
                                <option value="1" {{ $ulasan->rating == '1' ? 'selected' : '' }}>Bintang 1 ⭐</option>
                                <option value="2" {{ $ulasan->rating == '2' ? 'selected' : '' }}>Bintang 2 ⭐</option>
                                <option value="3" {{ $ulasan->rating == '3' ? 'selected' : '' }}>Bintang 3 ⭐</option>
                                <option value="4" {{ $ulasan->rating == '4' ? 'selected' : '' }}>Bintang 4 ⭐</option>
                                <option value="5" {{ $ulasan->rating == '5' ? 'selected' : '' }}>Bintang 5 ⭐</option>
                            </select>
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
