@extends('layouts.app')

@section('title', 'Dashboard Pegawai')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
            <h2 class="h5 fw-bold mb-0"><i class="bi bi-journal-text me-2"></i>Riwayat Perjalanan Dinas</h2>
            <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                <i class="bi bi-plus-lg me-1"></i>Ajukan Perdin
            </button>
        </div>

        @if(count($perdinList) === 0)
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size: 2.5rem;" class="text-muted"></i>
                <h3 class="h6 fw-bold mt-2">Belum Ada Data</h3>
                <p class="text-muted small mb-3">Anda belum pernah mengajukan perjalanan dinas.</p>
                <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                    <i class="bi bi-plus-lg me-1"></i>Ajukan Perdin
                </button>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Kota Asal</th>
                            <th>Kota Tujuan</th>
                            <th>Berangkat</th>
                            <th>Pulang</th>
                            <th>Keterangan</th>
                            <th class="text-center" style="width: 130px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($perdinList as $index => $perdin)
                            <tr @if($perdin->status === 'APPROVED') role="button" data-bs-toggle="modal" data-bs-target="#detailModal"
                                data-asal="{{ $perdin->kotaAsal->nama }}"
                                data-tujuan="{{ $perdin->kotaTujuan->nama }}"
                                data-berangkat="{{ $perdin->tanggal_berangkat->format('d M Y') }}"
                                data-pulang="{{ $perdin->tanggal_pulang->format('d M Y') }}"
                                data-durasi="{{ $perdin->durasi }}"
                                data-jarak="{{ number_format($perdin->jarak, 2, ',', '.') }}"
                                data-tarif="{{ $perdin->tarif_harian_format }}"
                                data-total="{{ $perdin->total_uang_saku_format }}"
                                data-keterangan="{{ $perdin->keterangan }}"
                                class="table-success" @endif>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-semibold">{{ $perdin->kotaAsal->nama }}</td>
                                <td>
                                    {{ $perdin->kotaTujuan->nama }}
                                    @if($perdin->kotaTujuan->is_overseas)
                                        <span class="badge bg-secondary">LN</span>
                                    @endif
                                </td>
                                <td>{{ $perdin->tanggal_berangkat->format('d M Y') }}</td>
                                <td>{{ $perdin->tanggal_pulang->format('d M Y') }}</td>
                                <td class="text-truncate" style="max-width: 200px;" title="{{ $perdin->keterangan }}">{{ $perdin->keterangan }}</td>
                                <td class="text-center">
                                    @if($perdin->status === 'PENDING')
                                        <span class="badge bg-warning text-dark"><i class="bi bi-hourglass-split me-1"></i>PENDING</span>
                                    @elseif($perdin->status === 'APPROVED')
                                        <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>DISETUJUI</span>
                                    @else
                                        <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>DITOLAK</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="text-muted small mt-2 mb-0 fst-italic"><i class="bi bi-hand-index me-1"></i>Klik baris berstatus DISETUJUI untuk melihat rincian uang saku.</p>
        @endif
    </div>
</div>

<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="perdinForm" action="{{ route('perdin.tambah') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="tambahModalLabel"><i class="bi bi-plus-circle me-2"></i>Ajukan Perjalanan Dinas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="jsErrorAlert" class="alert alert-danger d-flex align-items-center gap-2" style="display: none;">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <span id="jsErrorMessage"></span>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <div class="small"><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="kota_asal_id" class="form-label"><i class="bi bi-geo-alt me-1"></i>Kota Asal</label>
                        <select id="kota_asal_id" name="kota_asal_id" class="form-select" required>
                            <option value="" disabled selected>Pilih kota keberangkatan...</option>
                            @foreach($kotaList as $kota)
                                <option value="{{ $kota->id }}" {{ old('kota_asal_id') == $kota->id ? 'selected' : '' }}>
                                    {{ $kota->nama }} ({{ $kota->provinsi ?? 'Luar Negeri' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="kota_tujuan_id" class="form-label"><i class="bi bi-geo-fill me-1"></i>Kota Tujuan</label>
                        <select id="kota_tujuan_id" name="kota_tujuan_id" class="form-select" required>
                            <option value="" disabled selected>Pilih kota tujuan...</option>
                            @foreach($kotaList as $kota)
                                <option value="{{ $kota->id }}" {{ old('kota_tujuan_id') == $kota->id ? 'selected' : '' }}>
                                    {{ $kota->nama }} ({{ $kota->provinsi ?? 'Luar Negeri' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label for="tanggal_berangkat" class="form-label"><i class="bi bi-calendar-event me-1"></i>Berangkat</label>
                            <input type="date" id="tanggal_berangkat" name="tanggal_berangkat" value="{{ old('tanggal_berangkat') }}" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label for="tanggal_pulang" class="form-label"><i class="bi bi-calendar-check me-1"></i>Pulang</label>
                            <input type="date" id="tanggal_pulang" name="tanggal_pulang" value="{{ old('tanggal_pulang') }}" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label"><i class="bi bi-chat-left-text me-1"></i>Keterangan</label>
                        <textarea id="keterangan" name="keterangan" rows="3" class="form-control" placeholder="Detail agenda perjalanan..." required>{{ old('keterangan') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark"><i class="bi bi-send me-1"></i>Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="detailModalLabel"><i class="bi bi-info-circle me-2"></i>Rincian Perjalanan Dinas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless mb-3">
                    <tr><td class="text-muted"><i class="bi bi-geo-alt me-1"></i>Kota Asal</td><td class="fw-semibold" id="dAsal"></td></tr>
                    <tr><td class="text-muted"><i class="bi bi-geo-fill me-1"></i>Kota Tujuan</td><td class="fw-semibold" id="dTujuan"></td></tr>
                    <tr><td class="text-muted"><i class="bi bi-calendar-event me-1"></i>Berangkat</td><td id="dBerangkat"></td></tr>
                    <tr><td class="text-muted"><i class="bi bi-calendar-check me-1"></i>Pulang</td><td id="dPulang"></td></tr>
                    <tr><td class="text-muted"><i class="bi bi-chat-left-text me-1"></i>Keterangan</td><td class="fst-italic" id="dKeterangan"></td></tr>
                </table>
                <hr>
                <div class="row g-3 text-center mb-3">
                    <div class="col-4">
                        <div class="border rounded p-2 bg-light">
                            <small class="text-muted d-block"><i class="bi bi-clock me-1"></i>Durasi</small>
                            <span class="fw-bold" id="dDurasi"></span> Hari
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2 bg-light">
                            <small class="text-muted d-block"><i class="bi bi-signpost-split me-1"></i>Jarak</small>
                            <span class="fw-bold" id="dJarak"></span> km
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2 bg-light">
                            <small class="text-muted d-block"><i class="bi bi-cash me-1"></i>Tarif/Hari</small>
                            <span class="fw-bold" id="dTarif"></span>
                        </div>
                    </div>
                </div>
                <div class="border rounded p-3 text-center bg-light">
                    <small class="text-muted d-block"><i class="bi bi-wallet2 me-1"></i>Total Uang Saku</small>
                    <span class="fs-5 fw-bold" id="dTotal"></span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var detailModal = document.getElementById('detailModal');
        detailModal.addEventListener('show.bs.modal', function(event) {
            var row = event.relatedTarget;
            document.getElementById('dAsal').textContent = row.getAttribute('data-asal');
            document.getElementById('dTujuan').textContent = row.getAttribute('data-tujuan');
            document.getElementById('dBerangkat').textContent = row.getAttribute('data-berangkat');
            document.getElementById('dPulang').textContent = row.getAttribute('data-pulang');
            document.getElementById('dDurasi').textContent = row.getAttribute('data-durasi');
            document.getElementById('dJarak').textContent = row.getAttribute('data-jarak');
            document.getElementById('dTarif').textContent = row.getAttribute('data-tarif');
            document.getElementById('dTotal').textContent = row.getAttribute('data-total');
            document.getElementById('dKeterangan').textContent = row.getAttribute('data-keterangan');
        });

        var perdinForm = document.getElementById('perdinForm');
        perdinForm.addEventListener('submit', function(e) {
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
                errorAlert.style.display = 'flex';
                return;
            }

            if (tglBerangkat && tglPulang) {
                if (new Date(tglPulang) < new Date(tglBerangkat)) {
                    e.preventDefault();
                    errorMessage.textContent = 'Tanggal pulang tidak boleh sebelum tanggal berangkat.';
                    errorAlert.style.display = 'flex';
                    return;
                }
            }
        });

        @if($errors->any())
            var tambahModal = new bootstrap.Modal(document.getElementById('tambahModal'));
            tambahModal.show();
        @endif
    });
</script>
@endsection
