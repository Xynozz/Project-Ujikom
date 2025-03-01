@extends('layouts.admin.frontend.template')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Menu /</span> Edit Wisata</h5>

    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Wisata</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('wisata.update', $wisata->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="nama_wisata">Nama Wisata</label>
                                    <input type="text" class="form-control @error('nama_wisata') is-invalid @enderror"
                                        id="nama_wisata" name="nama_wisata" value="{{ old('nama_wisata', $wisata->nama_wisata) }}"
                                        placeholder="Masukan Nama Wisata" />
                                    @error('nama_wisata')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="deskripsi">Deskripsi</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                        name="deskripsi" id="deskripsi" cols="30" rows="3">{{ old('deskripsi', $wisata->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="lokasi">Lokasi</label>
                                    <input type="text" class="form-control @error('lokasi') is-invalid @enderror"
                                        id="lokasi" name="lokasi" value="{{ old('lokasi', $wisata->lokasi) }}"
                                        placeholder="Masukan Lokasi" />
                                    @error('lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="gambar">Gambar</label>
                                    <input type="file" class="form-control @error('gambar') is-invalid @enderror"
                                        id="gambar" name="gambar" />
                                    @error('gambar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="thumbnail">Thumbnail</label>
                                    <input type="file" class="form-control @error('thumbnail') is-invalid @enderror"
                                        id="thumbnail" name="thumbnail" />
                                    @error('thumbnail')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="short_video">Short Video</label>
                                    <input type="file" class="form-control @error('short_video') is-invalid @enderror"
                                        id="short_video" name="short_video" />
                                    @error('short_video')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="jam_buka">Jam Buka</label>
                                    <input type="time" class="form-control @error('jam_buka') is-invalid @enderror"
                                        id="jam_buka" name="jam_buka" value="{{ old('jam_buka', $wisata->jam_buka) }}" />
                                    @error('jam_buka')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="jam_tutup">Jam Tutup</label>
                                    <input type="time" class="form-control @error('jam_tutup') is-invalid @enderror"
                                        id="jam_tutup" name="jam_tutup" value="{{ old('jam_tutup', $wisata->jam_tutup) }}" />
                                    @error('jam_tutup')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="aktif" {{ old('status', $wisata->status) == 'aktif' ? 'selected' : '' }}>
                                            Aktif
                                        </option>
                                        <option value="tidak_aktif" {{ old('status', $wisata->status) == 'tidak_aktif' ? 'selected' : '' }}>
                                            Tidak Aktif
                                        </option>
                                    </select>
                                    @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="kategori_id">Kategori</label>
                                    <select class="form-select @error('kategori_id') is-invalid @enderror"
                                        id="kategori_id" name="kategori_id">
                                        @foreach($kategori as $k)
                                        <option value="{{ $k->id }}"
                                            {{ old('kategori_id', $wisata->kategori_id) == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama_kategori }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('kategori_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('wisata.index') }}" class="btn btn-danger me-2">Kembali</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
