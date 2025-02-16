@extends('layouts.admin.frontend.template')

@push('css')
<!-- Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pemesanan /</span> Edit Pemesanan</h5>

    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit Pemesanan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pemesanan.update', $pemesanan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="user_id">Username</label>
                                    <select class="form-select" name="user_id" id="searchable-select">
                                        @foreach($user as $data)
                                        <option value="{{ $data->id }}" {{ $pemesanan->user_id == $data->id ? 'selected' : '' }}>
                                            {{ $data->username }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="wisata_id">Wisata</label>
                                    <select class="form-select" name="wisata_id" id="wisata_id">
                                        @foreach($wisata as $data)
                                        <option value="{{ $data->id }}" {{ $pemesanan->wisata_id == $data->id ? 'selected' : '' }}>
                                            {{ $data->nama_wisata }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="tiket_id">Tiket</label>
                                    <select class="form-select" name="tiket_id" id="tiket_id">
                                        @foreach($tiket as $data)
                                        <option value="{{ $data->id }}" data-harga="{{ $data->harga_tiket }}" {{ $pemesanan->tiket->wisata->nama_wisata == $data->id ? 'selected' : '' }}>
                                            {{ $data->wisata->nama_wisata }} --- Rp:{{ $data->harga_tiket }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="jumlah_tiket">Jumlah Tiket</label>
                                    <input type="number" class="form-control" id="jumlah_tiket" value="{{ $pemesanan->jumlah_tiket }}" name="jumlah_tiket" required />
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="total_harga">Total Harga</label>
                                    <strong id="total_harga">Rp:{{ number_format($pemesanan->total_harga, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('pemesanan.index') }}" class="btn btn-danger">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
      $('#searchable-select').select2({
        placeholder: "Cari username...", // Placeholder untuk pencarian
        allowClear: true // Opsi untuk menghapus pilihan
      });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const tiketSelect = document.getElementById('tiket_id');
        const jumlahTiketInput = document.getElementById('jumlah_tiket');
        const totalHargaElement = document.getElementById('total_harga');

        function calculateTotalHarga() {
            const selectedTiket = tiketSelect.options[tiketSelect.selectedIndex];
            const hargaTiket = selectedTiket ? parseFloat(selectedTiket.getAttribute('data-harga')) : 0;
            const jumlahTiket = parseInt(jumlahTiketInput.value) || 0;
            const totalHarga = hargaTiket * jumlahTiket;

            totalHargaElement.textContent = `Rp.${totalHarga.toLocaleString()}`;
        }

        tiketSelect.addEventListener('change', calculateTotalHarga);
        jumlahTiketInput.addEventListener('input', calculateTotalHarga);
    });
</script>
@endpush
