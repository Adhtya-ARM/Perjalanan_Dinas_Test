@extends('layouts.app')

@section('title', 'Admin Panel')

@section('content')
<div class="mb-4 pb-2 border-bottom d-flex align-items-center justify-content-between">
    <div>
        <h2 class="h4 fw-bold mb-1"><i class="bi bi-shield-lock-fill me-2"></i>Panel Admin</h2>
        <p class="text-muted small mb-0">Kelola seluruh data pengguna, kota, dan perjalanan dinas.</p>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h3 class="h6 fw-bold mb-0"><i class="bi bi-people-fill me-2"></i>Manajemen User</h3>
                    <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="bi bi-person-plus-fill me-1"></i>Tambah User
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>Nama / Username</th>
                                <th style="width: 140px;">Role</th>
                                <th style="width: 60px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $u)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">{{ $u->name }}</span>
                                        <span class="d-block text-muted" style="font-size: 0.75rem;">{{ $u->username }}</span>
                                    </td>
                                    <td>
                                        @if($u->id === Auth::user()->id)
                                            <span class="badge bg-secondary w-100 py-1">ANDA</span>
                                        @else
                                            <form action="{{ route('admin.updateRole', $u->id) }}" method="POST" class="m-0">
                                                @csrf
                                                <select name="role" class="form-select form-select-sm" style="font-size: 0.75rem;" onchange="this.form.submit()">
                                                    <option value="PEGAWAI" {{ $u->role === 'PEGAWAI' ? 'selected' : '' }}>PEGAWAI</option>
                                                    <option value="DIVISI-SDM" {{ $u->role === 'DIVISI-SDM' ? 'selected' : '' }}>DIVISI-SDM</option>
                                                    <option value="ADMIN" {{ $u->role === 'ADMIN' ? 'selected' : '' }}>ADMIN</option>
                                                </select>
                                            </form>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($u->id !== Auth::user()->id)
                                            <form action="{{ route('admin.deleteUser', $u->id) }}" method="POST" class="m-0" onsubmit="return confirm('Hapus user {{ $u->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" style="font-size: 0.7rem;">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h3 class="h6 fw-bold mb-0"><i class="bi bi-globe-americas me-2"></i>Master Data Kota</h3>
                    <button class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#addKotaModal">
                        <i class="bi bi-plus-lg me-1"></i>Tambah Kota
                    </button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle mb-0 small">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Provinsi</th>
                                <th>Pulau</th>
                                <th class="text-center">Tipe</th>
                                <th style="width: 120px;" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kotaList as $kota)
                                <tr>
                                    <td class="fw-semibold">{{ $kota->nama }}</td>
                                    <td>{{ $kota->latitude }}</td>
                                    <td>{{ $kota->longitude }}</td>
                                    <td>{{ $kota->provinsi ?? '-' }}</td>
                                    <td>{{ $kota->pulau ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($kota->is_overseas)
                                            <span class="badge bg-secondary">LN</span>
                                        @else
                                            <span class="badge bg-dark">Domestik</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-outline-secondary btn-sm btn-edit-kota"
                                                data-bs-toggle="modal" data-bs-target="#editKotaModal"
                                                data-id="{{ $kota->id }}"
                                                data-nama="{{ $kota->nama }}"
                                                data-lat="{{ $kota->latitude }}"
                                                data-lng="{{ $kota->longitude }}"
                                                data-prov="{{ $kota->provinsi }}"
                                                data-pulau="{{ $kota->pulau }}"
                                                data-overseas="{{ $kota->is_overseas ? '1' : '0' }}"
                                                style="font-size: 0.7rem;">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <form action="{{ route('admin.deleteKota', $kota->id) }}" method="POST" class="d-inline m-0" onsubmit="return confirm('Hapus kota {{ $kota->nama }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm" style="font-size: 0.7rem;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <h3 class="h6 fw-bold mb-3 pb-2 border-bottom"><i class="bi bi-journal-text me-2"></i>Semua Data Perjalanan Dinas</h3>

        @if(count($perdinList) === 0)
            <p class="text-muted text-center py-3 mb-0">Belum ada data perdin.</p>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0 small">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;">No</th>
                            <th>Pegawai</th>
                            <th>Rute</th>
                            <th>Waktu</th>
                            <th>Durasi / Jarak</th>
                            <th>Uang Saku</th>
                            <th class="text-center" style="width: 130px;">Status</th>
                            <th style="width: 60px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($perdinList as $index => $perdin)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="fw-semibold">{{ $perdin->user->name }}</span>
                                    <span class="d-block text-muted" style="font-size: 0.75rem;">{{ $perdin->user->username }}</span>
                                </td>
                                <td>{{ $perdin->kotaAsal->nama }} <i class="bi bi-arrow-right"></i> {{ $perdin->kotaTujuan->nama }}</td>
                                <td>{{ $perdin->tanggal_berangkat->format('d M') }} - {{ $perdin->tanggal_pulang->format('d M Y') }}</td>
                                <td>{{ $perdin->durasi }} Hari / {{ number_format($perdin->jarak, 2, ',', '.') }} km</td>
                                <td class="fw-semibold">{{ $perdin->total_uang_saku_format }}</td>
                                <td class="text-center">
                                    <form action="{{ route('admin.updatePerdinStatus', $perdin->id) }}" method="POST" class="m-0">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" class="form-select form-select-sm" style="font-size: 0.7rem;" onchange="this.form.submit()">
                                            <option value="PENDING" {{ $perdin->status === 'PENDING' ? 'selected' : '' }}>PENDING</option>
                                            <option value="APPROVED" {{ $perdin->status === 'APPROVED' ? 'selected' : '' }}>APPROVED</option>
                                            <option value="REJECTED" {{ $perdin->status === 'REJECTED' ? 'selected' : '' }}>REJECTED</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('admin.deletePerdin', $perdin->id) }}" method="POST" class="m-0" onsubmit="return confirm('Hapus data perdin ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" style="font-size: 0.7rem;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.storeUser') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i>Tambah User Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Nama lengkap..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Username..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="PEGAWAI">PEGAWAI</option>
                            <option value="DIVISI-SDM">DIVISI-SDM</option>
                            <option value="ADMIN">ADMIN</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark"><i class="bi bi-save me-1"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addKotaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('admin.storeKota') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle-fill me-2"></i>Tambah Kota Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kota</label>
                        <input type="text" name="nama" class="form-control" placeholder="Nama kota..." required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Latitude</label>
                            <input type="number" step="any" name="latitude" class="form-control" placeholder="Latitude..." required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Longitude</label>
                            <input type="number" step="any" name="longitude" class="form-control" placeholder="Longitude..." required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Provinsi <span class="text-muted small">(opsional)</span></label>
                        <input type="text" name="provinsi" class="form-control" placeholder="Provinsi...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pulau <span class="text-muted small">(opsional)</span></label>
                        <input type="text" name="pulau" class="form-control" placeholder="Pulau...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Luar Negeri?</label>
                        <select name="is_overseas" class="form-select" required>
                            <option value="0" selected>Tidak (Domestik)</option>
                            <option value="1">Ya (Luar Negeri)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark"><i class="bi bi-save me-1"></i>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editKotaModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editKotaForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Kota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Kota</label>
                        <input type="text" name="nama" id="editNama" class="form-control" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label">Latitude</label>
                            <input type="number" step="any" name="latitude" id="editLat" class="form-control" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Longitude</label>
                            <input type="number" step="any" name="longitude" id="editLng" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Provinsi</label>
                        <input type="text" name="provinsi" id="editProv" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pulau</label>
                        <input type="text" name="pulau" id="editPulau" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Luar Negeri?</label>
                        <select name="is_overseas" id="editOverseas" class="form-select" required>
                            <option value="0">Tidak (Domestik)</option>
                            <option value="1">Ya (Luar Negeri)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark"><i class="bi bi-save me-1"></i>Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var editModal = document.getElementById('editKotaModal');
        editModal.addEventListener('show.bs.modal', function(event) {
            var btn = event.relatedTarget;
            var id = btn.getAttribute('data-id');
            document.getElementById('editKotaForm').action = '/admin/kota/' + id;
            document.getElementById('editNama').value = btn.getAttribute('data-nama');
            document.getElementById('editLat').value = btn.getAttribute('data-lat');
            document.getElementById('editLng').value = btn.getAttribute('data-lng');
            document.getElementById('editProv').value = btn.getAttribute('data-prov') || '';
            document.getElementById('editPulau').value = btn.getAttribute('data-pulau') || '';
            document.getElementById('editOverseas').value = btn.getAttribute('data-overseas');
        });
    });
</script>
@endsection
