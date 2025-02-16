@extends('layouts.admin.frontend.template')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Menu /</span> Tambah Wisata</h5>

    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Wisata</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('wisata.update', $wisata->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="nama_wisata">Nama Wisata</label>
                                    <input type="text" class="form-control" id="nama_wisata" name="nama_wisata"
                                        value="{{ $wisata->nama_wisata }}" placeholder="Masukan Nama Wisata" required />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="deskripsi">Deskripsi</label>
                                    <textarea class="form-control" name="deskripsi" id="deskripsi" cols="30" rows="3"
                                        value="{{ $wisata->deskripsi }}" placeholder="Masukan Deskripsi" required></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="lokasi">Lokasi</label>
                                    <input type="text" class="form-control" id="lokasi" name="lokasi"
                                        value="{{ $wisata->lokasi }}" placeholder="Masukan Lokasi" required />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="gambar">Gambar</label>
                                    <input type="file" class="form-control" id="gambar" name="gambar" required />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="thumbnail">Thumbnail</label>
                                    <input type="file" class="form-control" id="thumbnail" name="thumbnail" required />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="short_video">Short Video</label>
                                    <input type="file" class="form-control" id="short_video" name="short_video"
                                        required />
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="jam_operasional">Jam Operasional</label>
                                    <input type="time" class="form-control" id="jam_operasional" name="jam_operasional"
                                        value="{{ $wisata->jam_operasional }}" placeholder="Masukan Lokasi" required />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="aktif" {{ $wisata->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="tidak_aktif" {{ $wisata->status == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="kategori_id">Kategori</label>
                                    <select class="form-select" id="kategori_id" name="kategori_id" required>
                                        @foreach ($kategori as $data)
                                        <option value="{{ $data->id }}" {{ $wisata->kategori_id == $data->id ? 'selected' : '' }}>{{ $data->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('wisata.index') }}" class="btn btn-danger">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
