@extends('layouts.admin.frontend.template')

@push('css')
<!-- Select2 CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
@endpush
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h5 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Pemesanan /</span> Tambah Pemesanan</h5>

    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tambah Pemesanan</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pemesanan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="user_id">Username</label>
                                    <select class="form-select @error('user_id') is-invalid @enderror"
                                        name="user_id" id="searchable-select">
                                        <option value="" selected disabled>-- Pilih Username --</option>
                                        @foreach($user as $data)
                                        <option value="{{ $data->id }}" {{ old('user_id') == $data->id ? 'selected' : '' }}>
                                            {{ $data->username }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="wisata_id">Wisata</label>
                                    <select class="form-select @error('wisata_id') is-invalid @enderror"
                                        name="wisata_id" id="wisata_id">
                                        <option value="" selected disabled>-- Pilih Wisata --</option>
                                        @foreach($wisata as $data)
                                        <option value="{{ $data->id }}"
                                            data-status="{{ $data->status }}"
                                            {{ old('wisata_id') == $data->id ? 'selected' : '' }}>
                                            {{ $data->nama_wisata }}
                                            @if($data->status !== 'aktif')
                                            (Tidak Aktif)
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('wisata_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="tiket_id">Tiket</label>
                                    <select class="form-select @error('tiket_id') is-invalid @enderror"
                                        name="tiket_id" id="tiket_id">
                                        <option value="" selected disabled>-- Tiket --</option>
                                        @foreach($tiket as $data)
                                        <option value="{{ $data->id }}"
                                            data-wisata-id="{{ $data->wisata_id }}"
                                            data-harga="{{ $data->harga_tiket }}"
                                            {{ old('tiket_id') == $data->id ? 'selected' : '' }}>
                                            {{ $data->wisata->nama_wisata }} --- Rp:{{ number_format($data->harga_tiket, 0, ',', '.') }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('tiket_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="jumlah_tiket">Jumlah Tiket</label>
                                    <input type="number"
                                        class="form-control @error('jumlah_tiket') is-invalid @enderror"
                                        id="jumlah_tiket"
                                        name="jumlah_tiket"
                                        value="{{ old('jumlah_tiket') }}"
                                        min="1" />
                                    @error('jumlah_tiket')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label">Total Harga</label>
                                    <div>
                                        <strong id="total_harga">Rp:0</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('wisata.index') }}" class="btn btn-danger">Kembali</a>
                        <button type="submit" class="btn btn-primary">Submit</button>
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

{{-- Select Searchable --}}
<script>
    $(document).ready(function() {
      $('#searchable-select').select2({
        placeholder: "Pilih Username...", // Placeholder untuk pencarian
        allowClear: true // Opsi untuk menghapus pilihan
      });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const tiketSelect = document.getElementById('tiket_id');
    const jumlahTiketInput = document.getElementById('jumlah_tiket');
    const totalHargaElement = document.getElementById('total_harga');

    function calculateTotalHarga() {
        const selectedTiket = tiketSelect.options[tiketSelect.selectedIndex];
        const hargaTiket = selectedTiket ? parseFloat(selectedTiket.getAttribute('data-harga')) : 0;
        const jumlahTiket = parseInt(jumlahTiketInput.value) || 0;
        const totalHarga = hargaTiket * jumlahTiket;

        totalHargaElement.textContent = `Rp:${totalHarga.toLocaleString()}`;
    }

    tiketSelect.addEventListener('change', calculateTotalHarga);
    jumlahTiketInput.addEventListener('input', calculateTotalHarga);
});
</script>

{{-- Auto Select Tiket --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const wisataSelect = document.getElementById('wisata_id');
        const tiketSelect = document.getElementById('tiket_id');
        const jumlahTiketInput = document.getElementById('jumlah_tiket');
        const totalHargaElement = document.getElementById('total_harga');

        function autoSelectTiketByWisata() {
            const selectedWisataId = wisataSelect.value;

            // Loop through options in tiketSelect
            let firstMatchingOption = null;
            for (let i = 0; i < tiketSelect.options.length; i++) {
                const option = tiketSelect.options[i];
                const wisataIdInOption = option.getAttribute('data-wisata-id');

                if (wisataIdInOption === selectedWisataId) {
                    option.style.display = 'block'; // Show matching option
                    if (!firstMatchingOption) {
                        firstMatchingOption = option; // Save the first matching option
                    }
                } else {
                    option.style.display = 'none'; // Hide non-matching option
                }
            }

            // Automatically select the first matching option
            if (firstMatchingOption) {
                tiketSelect.value = firstMatchingOption.value;
            } else {
                tiketSelect.value = ''; // Reset if no matching option found
            }

            calculateTotalHarga(); // Recalculate total price after selection
        }

        function calculateTotalHarga() {
            const selectedTiket = tiketSelect.options[tiketSelect.selectedIndex];
            const hargaTiket = selectedTiket ? parseFloat(selectedTiket.getAttribute('data-harga')) : 0;
            const jumlahTiket = parseInt(jumlahTiketInput.value) || 0;
            const totalHarga = hargaTiket * jumlahTiket;

            totalHargaElement.textContent = `Rp:${totalHarga.toLocaleString()}`;
        }

        // Event Listener
        wisataSelect.addEventListener('change', autoSelectTiketByWisata);
        jumlahTiketInput.addEventListener('input', calculateTotalHarga);
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const wisataSelect = document.getElementById('wisata_id');

        function disableInactiveWisataOptions() {
            for (let i = 0; i < wisataSelect.options.length; i++) {
                const option = wisataSelect.options[i];
                const status = option.getAttribute('data-status');

                // Disable option if status is not "aktif"
                if (status !== "aktif" && status !== null) {
                    option.disabled = true;
                    option.style.color = "gray"; // Optional: make inactive options visually distinct
                } else {
                    option.disabled = false;
                    option.style.color = "black"; // Reset style for active options
                }
            }
        }

        // Run the function on page load
        disableInactiveWisataOptions();
    });
</script>

@endpush
