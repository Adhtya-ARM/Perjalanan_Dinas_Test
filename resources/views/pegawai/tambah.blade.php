@extends('layouts.app')

@section('title', 'Tambah Perjalanan Dinas')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h2 class="h5 fw-bold mb-0">Ajukan Perjalanan Dinas</h2>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
                </div>

                <form id="perdinForm" action="{{ route('perdin.tambah') }}" method="POST">
                    @csrf
                    <div class="alert alert-danger" id="jsErrorAlert" style="display: none;">
                        <div class="small"><i class="bi bi-exclamation-circle me-1"></i><span id="jsErrorMessage"></span></div>
                    </div>
                    <div class="mb-3">
                        <label for="kota_asal_id" class="form-label">Kota Asal</label>
                        <select id="kota_asal_id" name="kota_asal_id" class="form-select" required>
                            <option value="" disabled selected>Pilih kota keberangkatan...</option>
                            @foreach($kotaList as $kota)
                                <option value="{{ $kota->id }}" {{ old('kota_asal_id') == $kota->id ? 'selected' : '' }}>
                                    {{ $kota->nama }} ({{ $kota->provinsi ?? 'Luar Negeri' }})
                                </option>
                            @endforeach
                        </select>
                        @error('kota_asal_id')
                            <span class="text-danger small d-block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="kota_tujuan_id" class="form-label">Kota Tujuan</label>
                        <select id="kota_tujuan_id" name="kota_tujuan_id" class="form-select" required>
                            <option value="" disabled selected>Pilih kota tujuan...</option>
                            @foreach($kotaList as $kota)
                                <option value="{{ $kota->id }}" {{ old('kota_tujuan_id') == $kota->id ? 'selected' : '' }}>
                                    {{ $kota->nama }} ({{ $kota->provinsi ?? 'Luar Negeri' }})
                                </option>
                            @endforeach
                        </select>
                        @error('kota_tujuan_id')
                            <span class="text-danger small d-block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="alert alert-warning py-2 mb-3" id="sameCityNotice" style="display: none;">
                        <div class="small fw-semibold"><i class="bi bi-exclamation-triangle-fill me-1"></i>Kota asal dan tujuan sama. Perjalanan dinas ini tidak mendapatkan uang saku (Rp 0).</div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="tanggal_berangkat" class="form-label">Tanggal Berangkat</label>
                            <input type="date" id="tanggal_berangkat" name="tanggal_berangkat" value="{{ old('tanggal_berangkat') }}" class="form-control" required>
                            @error('tanggal_berangkat')
                                <span class="text-danger small d-block mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="tanggal_pulang" class="form-label">Tanggal Pulang</label>
                            <input type="date" id="tanggal_pulang" name="tanggal_pulang" value="{{ old('tanggal_pulang') }}" class="form-control" required>
                            @error('tanggal_pulang')
                                <span class="text-danger small d-block mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="keterangan" class="form-label">Keterangan / Maksud Perjalanan</label>
                        <textarea id="keterangan" name="keterangan" rows="4" class="form-control" placeholder="Detail agenda..." required>{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <span class="text-danger small d-block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Batal</a>
                        <button type="submit" class="btn btn-dark px-4">Kirim Pengajuan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var asalSelect = document.getElementById('kota_asal_id');
        var tujuanSelect = document.getElementById('kota_tujuan_id');
        var sameCityNotice = document.getElementById('sameCityNotice');

        function checkSameCity() {
            var asal = asalSelect.value;
            var tujuan = tujuanSelect.value;
            if (asal && tujuan && asal === tujuan) {
                sameCityNotice.style.display = 'block';
            } else {
                sameCityNotice.style.display = 'none';
            }
        }

        asalSelect.addEventListener('change', checkSameCity);
        tujuanSelect.addEventListener('change', checkSameCity);
        checkSameCity();

        document.getElementById('perdinForm').addEventListener('submit', function(e) {
            var tglBerangkat = document.getElementById('tanggal_berangkat').value;
            var tglPulang = document.getElementById('tanggal_pulang').value;
            var errorAlert = document.getElementById('jsErrorAlert');
            var errorMessage = document.getElementById('jsErrorMessage');

            errorAlert.style.display = 'none';

            if (tglBerangkat && tglPulang) {
                if (new Date(tglPulang) < new Date(tglBerangkat)) {
                    e.preventDefault();
                    errorMessage.textContent = 'Tanggal pulang tidak boleh sebelum tanggal berangkat.';
                    errorAlert.style.display = 'block';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    return;
                }
            }
        });
    });
</script>
@endsection
