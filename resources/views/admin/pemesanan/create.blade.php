@extends('layouts.admin.frontend.template')

@push('css')
<style>
    #search-user, #search-wisata {
        margin-bottom: 0.5rem;
    }
</style>
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
                                    <input type="text" id="search-user" class="form-control mb-2" placeholder="Cari Username...">
                                    <select class="form-select" name="user_id" id="user_id">
                                        <option value="" selected disabled>-- Pilih Username --</option>
                                        @foreach($user as $data)
                                        <option value="{{ $data->id }}">{{ $data->username }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="wisata_id">Wisata</label>
                                    <input type="text" id="search-wisata" class="form-control mb-2" placeholder="Cari Wisata...">
                                    <select class="form-select" name="wisata_id" id="wisata_id">
                                        <option value="" selected disabled>-- Pilih Wisata --</option>
                                        @foreach($wisata as $data)
                                        <option value="{{ $data->id }}">{{ $data->nama_wisata }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="mb-3">
                                    <label class="form-label" for="tanggal_pemesanan">Tanggal Pemesanan</label>
                                    <input type="date"
                                        class="form-control @error('tanggal_pemesanan') is-invalid @enderror"
                                        id="tanggal_pemesanan"
                                        name="tanggal_pemesanan"
                                        value="{{ old('tanggal_pemesanan', date('Y-m-d')) }}"
                                        min="{{ date('Y-m-d') }}" />
                                    @error('tanggal_pemesanan')
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

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Reusable function for filtering dropdown options
        function setupSearchFilter(inputId, selectId) {
            const searchInput = document.getElementById(inputId);
            const selectElement = document.getElementById(selectId);

            searchInput.addEventListener('input', function () {
                const filter = searchInput.value.toLowerCase();
                for (let i = 0; i < selectElement.options.length; i++) {
                    const option = selectElement.options[i];
                    const text = option.textContent.toLowerCase();
                    option.style.display = text.includes(filter) ? 'block' : 'none';
                }
            });
        }

        // Apply the search filter to the User and Wisata dropdowns
        setupSearchFilter('search-user', 'user_id');
        setupSearchFilter('search-wisata', 'wisata_id');
    });
</script>
@endpush
