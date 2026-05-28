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

                    <div id="jsErrorAlert" class="alert alert-danger" style="display: none;">
                        <span id="jsErrorMessage"></span>
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
    document.getElementById('perdinForm').addEventListener('submit', function(e) {
        var asal = document.getElementById('kota_asal_id').value;
        var tujuan = document.getElementById('kota_tujuan_id').value;
        var tglBerangkat = document.getElementById('tanggal_berangkat').value;
        var tglPulang = document.getElementById('tanggal_pulang').value;
        var errorAlert = document.getElementById('jsErrorAlert');
        var errorMessage = document.getElementById('jsErrorMessage');

        errorAlert.style.display = 'none';

        if (asal && tujuan && asal === tujuan) {
            e.preventDefault();
            errorMessage.textContent = 'Kota tujuan tidak boleh sama dengan kota asal.';
            errorAlert.style.display = 'block';
            window.scrollTo({ top: 0, behavior: 'smooth' });
            return;
        }

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
</script>
@endsection
