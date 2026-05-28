@extends('layouts.app')

@section('title', 'Dashboard SDM')

@section('content')
<ul class="nav nav-tabs mb-4">
    <li class="nav-item">
        <a class="nav-link active" data-bs-toggle="tab" href="#tabPending">
            <i class="bi bi-hourglass-split me-1"></i>Menunggu Persetujuan
            @if(count($pendingList) > 0)
                <span class="badge bg-warning text-dark ms-1">{{ count($pendingList) }}</span>
            @endif
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-bs-toggle="tab" href="#tabHistori">
            <i class="bi bi-clock-history me-1"></i>Histori
        </a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="tabPending">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="h5 fw-bold mb-3 pb-2 border-bottom"><i class="bi bi-clipboard-check me-2"></i>Pengajuan Menunggu Persetujuan</h2>

                @if(count($pendingList) === 0)
                    <div class="text-center py-4">
                        <i class="bi bi-emoji-smile" style="font-size: 2rem;"></i>
                        <p class="text-muted mb-0 mt-2">Tidak ada pengajuan yang menunggu persetujuan saat ini.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Pegawai</th>
                                    <th>Kota Asal</th>
                                    <th>Kota Tujuan</th>
                                    <th>Durasi</th>
                                    <th class="text-center" style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingList as $index => $perdin)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="fw-semibold">{{ $perdin->user->name }}</span>
                                            <span class="d-block text-muted small">{{ $perdin->user->username }}</span>
                                        </td>
                                        <td>{{ $perdin->kotaAsal->nama }}</td>
                                        <td>
                                            {{ $perdin->kotaTujuan->nama }}
                                            @if($perdin->kotaTujuan->is_overseas)
                                                <span class="badge bg-secondary">LN</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="fw-semibold">{{ $perdin->durasi }} Hari</span>
                                            <span class="d-block text-muted small">{{ $perdin->tanggal_berangkat->format('d M') }} - {{ $perdin->tanggal_pulang->format('d M Y') }}</span>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-dark btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#reviewModal"
                                                    data-id="{{ $perdin->id }}"
                                                    data-nama="{{ $perdin->user->name }}"
                                                    data-asal="{{ $perdin->kotaAsal->nama }}"
                                                    data-tujuan="{{ $perdin->kotaTujuan->nama }}"
                                                    data-berangkat="{{ $perdin->tanggal_berangkat->format('d M Y') }}"
                                                    data-pulang="{{ $perdin->tanggal_pulang->format('d M Y') }}"
                                                    data-durasi="{{ $perdin->durasi }}"
                                                    data-jarak="{{ number_format($perdin->jarak, 2, ',', '.') }}"
                                                    data-tarif="{{ $perdin->tarif_harian_format }}"
                                                    data-total="{{ $perdin->total_uang_saku_format }}"
                                                    data-keterangan="{{ $perdin->keterangan }}">
                                                <i class="bi bi-search me-1"></i>Tinjau
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="tabHistori">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h2 class="h5 fw-bold mb-3 pb-2 border-bottom"><i class="bi bi-clock-history me-2"></i>Histori Perdin yang Sudah Diproses</h2>

                @if(count($processedList) === 0)
                    <div class="text-center py-4">
                        <i class="bi bi-archive" style="font-size: 2rem;"></i>
                        <p class="text-muted mb-0 mt-2">Belum ada perdin yang diproses.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;">No</th>
                                    <th>Pegawai</th>
                                    <th>Rute</th>
                                    <th>Durasi</th>
                                    <th>Jarak</th>
                                    <th>Total Uang Saku</th>
                                    <th class="text-center" style="width: 120px;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($processedList as $index => $perdin)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="fw-semibold">{{ $perdin->user->name }}</span>
                                            <span class="d-block text-muted small">{{ $perdin->user->username }}</span>
                                        </td>
                                        <td>{{ $perdin->kotaAsal->nama }} <i class="bi bi-arrow-right"></i> {{ $perdin->kotaTujuan->nama }}</td>
                                        <td>{{ $perdin->durasi }} Hari</td>
                                        <td>{{ number_format($perdin->jarak, 2, ',', '.') }} km</td>
                                        <td class="fw-semibold">{{ $perdin->total_uang_saku_format }}</td>
                                        <td class="text-center">
                                            @if($perdin->status === 'APPROVED')
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
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="reviewModalLabel"><i class="bi bi-search me-2"></i>Tinjau Perjalanan Dinas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless mb-3">
                    <tr><td class="text-muted"><i class="bi bi-person me-1"></i>Pegawai</td><td class="fw-semibold" id="mNama"></td></tr>
                    <tr><td class="text-muted"><i class="bi bi-signpost-split me-1"></i>Rute</td><td><span id="mAsal"></span> <i class="bi bi-arrow-right"></i> <span class="fw-semibold" id="mTujuan"></span></td></tr>
                    <tr><td class="text-muted"><i class="bi bi-calendar-range me-1"></i>Masa Tugas</td><td><span id="mBerangkat"></span> s/d <span id="mPulang"></span></td></tr>
                    <tr><td class="text-muted"><i class="bi bi-chat-left-text me-1"></i>Keterangan</td><td class="fst-italic" id="mKeterangan"></td></tr>
                </table>
                <hr>
                <div class="row g-3 text-center mb-3">
                    <div class="col-4">
                        <div class="border rounded p-2 bg-light">
                            <small class="text-muted d-block"><i class="bi bi-clock me-1"></i>Total Hari</small>
                            <span class="fw-bold" id="mDurasi"></span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2 bg-light">
                            <small class="text-muted d-block"><i class="bi bi-signpost-split me-1"></i>Jarak</small>
                            <span class="fw-bold" id="mJarak"></span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-2 bg-light">
                            <small class="text-muted d-block"><i class="bi bi-cash me-1"></i>Tarif/Hari</small>
                            <span class="fw-bold" id="mTarif"></span>
                        </div>
                    </div>
                </div>
                <div class="border rounded p-3 text-center bg-light">
                    <small class="text-muted d-block"><i class="bi bi-wallet2 me-1"></i>Total Uang Saku</small>
                    <span class="fs-5 fw-bold" id="mTotal"></span>
                </div>
            </div>
            <div class="modal-footer">
                <form id="rejectForm" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger"><i class="bi bi-x-circle me-1"></i>Tolak</button>
                </form>
                <form id="approveForm" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-dark px-4"><i class="bi bi-check-circle me-1"></i>Setujui</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var reviewModal = document.getElementById('reviewModal');
        reviewModal.addEventListener('show.bs.modal', function(event) {
            var btn = event.relatedTarget;
            var id = btn.getAttribute('data-id');
            document.getElementById('mNama').textContent = btn.getAttribute('data-nama');
            document.getElementById('mAsal').textContent = btn.getAttribute('data-asal');
            document.getElementById('mTujuan').textContent = btn.getAttribute('data-tujuan');
            document.getElementById('mBerangkat').textContent = btn.getAttribute('data-berangkat');
            document.getElementById('mPulang').textContent = btn.getAttribute('data-pulang');
            document.getElementById('mDurasi').textContent = btn.getAttribute('data-durasi') + ' Hari';
            document.getElementById('mJarak').textContent = btn.getAttribute('data-jarak') + ' km';
            document.getElementById('mTarif').textContent = btn.getAttribute('data-tarif');
            document.getElementById('mTotal').textContent = btn.getAttribute('data-total');
            document.getElementById('mKeterangan').textContent = btn.getAttribute('data-keterangan');

            document.getElementById('approveForm').action = '/perdin/' + id + '/approve';
            document.getElementById('rejectForm').action = '/perdin/' + id + '/reject';
        });
    });
</script>
@endsection
