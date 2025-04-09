@extends('layouts.admin.frontend.template')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<style>
    .card-header {
        padding: 1.5rem 1.5rem;
        margin-bottom: 0;
        background-color: transparent;
    }

    .table-responsive {
        padding: 0 1.5rem 1.5rem;
    }

    .thumbnail-preview {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 0.375rem;
    }

    .description-text {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .operating-hours {
        white-space: nowrap;
    }

    .table > :not(caption) > * > * {
        vertical-align: middle;
    }

    .modal-content {
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    .modal-header .btn-close {
        filter: brightness(0.5);
    }

    .modal-body img {
        max-height: 300px;
        object-fit: cover;
        border-radius: 8px;
    }

    .modal-body #map {
        height: 300px;
        width: 100%;
        border-radius: 8px;
    }

    .leaflet-container {
        height: 100%;
        width: 100%;
    }

    .modal-footer {
        border-top: 1px solid #dee2e6;
    }

    #map {
        height: 300px;
        width: 100%;
        border-radius: 8px;
    }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Menu /</span> Wisata
    </h4>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Wisata</h5>
            <a href="{{ route('wisata.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> Tambah Wisata
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover" id="example">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Nama Wisata</th>
                        <th width="20%">Deskripsi</th>
                        <th width="10%">Thumbnail</th>
                        <th width="15%">Jam Operasional</th>
                        <th width="10%">Status</th>
                        <th width="15%">Kategori</th>
                        <th width="10%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($wisata as $data)
                    <tr>
                        <td class="text-center"><strong>{{ $loop->iteration }}</strong></td>
                        <td>{{ $data->nama_wisata }}</td>
                        <td>
                            <span class="description-text" title="{{ $data->deskripsi }}">
                                {{ Str::limit($data->deskripsi, 50) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <img src="{{ Storage::url($data->thumbnail) }}"
                                class="thumbnail-preview"
                                alt="{{ $data->nama_wisata }}">
                        </td>
                        <td class="text-center operating-hours">
                            {{ \Carbon\Carbon::parse($data->jam_buka)->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($data->jam_tutup)->format('H:i') }}
                        </td>
                        <td class="text-center">
                            <span class="badge {{ $data->status == 'aktif' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($data->status) }}
                            </span>
                        </td>
                        <td>{{ $data->kategori->nama_kategori }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="bx bx-dots-vertical-rounded"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('wisata.edit', $data->id) }}">
                                        <i class="bx bx-edit-alt me-1"></i> Edit
                                    </a>
                                    <form action="{{ route('wisata.destroy', $data->id) }}"
                                          method="POST"
                                          id="delete-form-{{ $data->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                class="dropdown-item"
                                                onclick="confirmDelete({{ $data->id }})">
                                            <i class="bx bx-trash me-1"></i> Delete
                                        </button>
                                    </form>
                                    <!-- Add Detail Button -->
                                    <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#detailModal{{ $data->id }}">
                                        <i class="bx bx-info-circle me-1"></i> Detail
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <!-- Modal for Detail -->
                    <div class="modal fade" id="detailModal{{ $data->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $data->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detailModalLabel{{ $data->id }}">
                                        <i class="bx bx-info-circle me-2"></i>Detail Wisata
                                    </h5>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <img src="{{ Storage::url($data->thumbnail) }}" alt="{{ $data->nama_wisata }}" class="img-fluid rounded">
                                        </div>
                                        <div class="col-md-6">
                                            <h5>{{ $data->nama_wisata }}</h5>
                                            <p class="text-muted">{{ $data->deskripsi }}</p>
                                            <p><strong>Jam Operasional:</strong> {{ \Carbon\Carbon::parse($data->jam_buka)->format('H:i') }} - {{ \Carbon\Carbon::parse($data->jam_tutup)->format('H:i') }}</p>
                                            <p><strong>Status:</strong>
                                                <span class="badge {{ $data->status == 'aktif' ? 'bg-success' : 'bg-danger' }}">
                                                    {{ ucfirst($data->status) }}
                                                </span>
                                            </p>
                                            <p><strong>Kategori:</strong> {{ $data->kategori->nama_kategori }}</p>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-12">
                                                <h6><i class="bx bx-map me-2"></i>Lokasi</h6>
                                                <div id="map{{ $data->id }}" style="height: 300px; border-radius: 8px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script>
$(document).ready(function() {
    $('#example').DataTable({
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(disaring dari _MAX_ total data)",
            zeroRecords: "Tidak ada data yang cocok",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        },
        pageLength: 10,
        order: [[0, 'asc']]
    });
});

function confirmDelete(id) {
    Swal.fire({
        title: 'Yakin hapus data?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(`delete-form-${id}`).submit();
        }
    });
}

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});

@if(session('success'))
    Toast.fire({
        icon: 'success',
        title: "{{ session('success') }}"
    });
@endif

@if(session('error'))
    Toast.fire({
        icon: 'error',
        title: "{{ session('error') }}"
    });
@endif

document.addEventListener('DOMContentLoaded', function () {
    @foreach ($wisata as $data)
        const modalId{{ $data->id }} = '#detailModal{{ $data->id }}';
        let map{{ $data->id }} = null;

        $(modalId{{ $data->id }}).on('shown.bs.modal', function () {
            if (!map{{ $data->id }}) {
                const latitude = {{ $data->latitude }};
                const longitude = {{ $data->longitude }};
                const mapId = 'map{{ $data->id }}';

                map{{ $data->id }} = L.map(mapId).setView([latitude, longitude], 15);

                // Add OpenStreetMap tiles
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map{{ $data->id }});

                // Add a marker
                L.marker([latitude, longitude]).addTo(map{{ $data->id }})
                    .bindPopup('<strong>{{ $data->nama_wisata }}</strong><br>{{ $data->kategori->nama_kategori }}');
            }

            map{{ $data->id }}.invalidateSize(); // Fix map rendering
        });
    @endforeach

    // ==== PETA UTAMA UNTUK INPUT KOORDINAT ====
    // const mainMap = L.map('map').setView([-6.200000, 106.816666], 13); // Jakarta

    // Tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap'
    }).addTo(mainMap);

    // Marker utama (draggable)
    let marker = L.marker([-6.200000, 106.816666], { draggable: true }).addTo(mainMap);

    // Fungsi untuk update form dan lokasi
    function updateForm(lat, lng) {
        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = lng;

        // Reverse geocoding pakai OpenStreetMap Nominatim
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.display_name || 'Lokasi tidak ditemukan';
            })
            .catch(() => {
                document.getElementById('name').value = 'Lokasi tidak ditemukan';
            });
    }

    // Saat marker digeser
    marker.on('dragend', function () {
        const pos = marker.getLatLng();
        updateForm(pos.lat.toFixed(6), pos.lng.toFixed(6));
    });

    // Saat klik peta
    mainMap.on('click', function (e) {
        const lat = e.latlng.lat.toFixed(6);
        const lng = e.latlng.lng.toFixed(6);

        marker.setLatLng(e.latlng);
        updateForm(lat, lng);
    });
});
</script>
@endpush
