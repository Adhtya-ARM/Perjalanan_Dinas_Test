@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-airplane-fill" style="font-size: 2.5rem;"></i>
                    <h1 class="h4 fw-bold mt-2">Perjalanan Dinas</h1>
                    <p class="text-muted small">Silakan masuk untuk melanjutkan</p>
                </div>

                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="username" class="form-label"><i class="bi bi-person me-1"></i>Username</label>
                        <input type="text"
                               id="username"
                               name="username"
                               value="{{ old('username') }}"
                               class="form-control"
                               placeholder="Masukkan username..."
                               required
                               autofocus>
                        @error('username')
                            <span class="text-danger small d-block mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label"><i class="bi bi-lock me-1"></i>Password</label>
                        <div class="input-group">
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-control"
                                   placeholder="Masukkan password..."
                                   required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-dark w-100 py-2 fw-semibold">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Masuk
                    </button>
                </form>

                <div class="mt-4 p-3 bg-light border rounded small">
                    <span class="d-block fw-bold text-center mb-2"><i class="bi bi-info-circle me-1"></i>Akun Uji Coba</span>
                    <div class="row g-1">
                        <div class="col-4 text-muted">Pegawai:</div>
                        <div class="col-8 fw-semibold">pegawai / password</div>
                        <div class="col-4 text-muted">SDM:</div>
                        <div class="col-8 fw-semibold">sdm / password</div>
                        <div class="col-4 text-muted">Admin:</div>
                        <div class="col-8 fw-semibold">admin / password</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        var input = document.getElementById('password');
        var icon = document.getElementById('toggleIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'bi bi-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'bi bi-eye';
        }
    });
</script>
@endsection
