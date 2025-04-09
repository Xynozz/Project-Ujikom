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
                    <form action="{{ route('wisata.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="nama_wisata">Nama Wisata</label>
                                    <input type="text" class="form-control @error('nama_wisata') is-invalid @enderror"
                                        id="nama_wisata" name="nama_wisata" placeholder="Masukan Nama Wisata" />
                                    @error('nama_wisata')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="deskripsi">Deskripsi</label>
                                    <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                                        name="deskripsi" id="deskripsi" cols="30" rows="3"></textarea>
                                    @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="lokasi">Lokasi</label>
                                    <div class="form-control" id="map"></div>
                                    <input class="form-control" type="hidden" id="lat" name="latitude">
                                    <input class="form-control" type="hidden" id="lng" name="longitude">
                                    <div class="input-group mt-3">
                                        <input type="text" id="search-location" class="form-control" placeholder="Masukkan nama lokasi">
                                        <button class="btn btn-primary" id="search-button" type="button">
                                            <i class="bx bx-search"></i> Cari
                                        </button>
                                    </div>
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
                                        id="jam_buka" name="jam_buka" />
                                    @error('jam_buka')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label" for="jam_tutup">Jam Tutup</label>
                                    <input type="time" class="form-control @error('jam_tutup') is-invalid @enderror"
                                        id="jam_tutup" name="jam_tutup" />
                                    @error('jam_tutup')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="status">Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status"
                                        name="status">
                                        <option value="aktif"> Aktif </option>
                                        <option value="tidak_aktif"> Tidak Aktif </option>
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
                                        <option value="">Pilih Kategori</option>
                                        @foreach ($kategori as $data)
                                        <option value="{{ $data->id }}"> {{ $data->nama_kategori }} </option>
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

@push('scripts')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<style>
    #search-location {
        border-radius: 0.375rem 0 0 0.375rem;
    }

    #search-button {
        border-radius: 0 0.375rem 0.375rem 0;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize the map
        const map = L.map('map').setView([-2.5489, 118.0149], 5); // Default to Indonesia
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Add a draggable marker
        let marker = L.marker([-2.5489, 118.0149], { draggable: true }).addTo(map);

        // Update latitude and longitude fields when the marker is dragged
        marker.on('dragend', function (e) {
            const lat = marker.getLatLng().lat.toFixed(6);
            const lng = marker.getLatLng().lng.toFixed(6);
            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;
        });

        // Search for a location
        document.getElementById('search-button').addEventListener('click', function () {
            const query = document.getElementById('search-location').value;

            if (!query) {
                alert('Masukkan nama lokasi untuk mencari!');
                return;
            }

            // Use OpenStreetMap's Nominatim API for geocoding
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        const lat = parseFloat(data[0].lat).toFixed(6);
                        const lng = parseFloat(data[0].lon).toFixed(6);

                        // Move the map and marker to the searched location
                        map.setView([lat, lng], 15);
                        marker.setLatLng([lat, lng]);

                        // Update latitude and longitude fields
                        document.getElementById('lat').value = lat;
                        document.getElementById('lng').value = lng;
                    } else {
                        alert('Lokasi tidak ditemukan. Coba kata kunci lain.');
                    }
                })
                .catch(error => {
                    console.error('Error fetching location:', error);
                    alert('Terjadi kesalahan saat mencari lokasi.');
                });
        });
    });
</script>
@endpush