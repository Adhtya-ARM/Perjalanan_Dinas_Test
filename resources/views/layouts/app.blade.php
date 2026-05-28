<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perjalanan Dinas') - PERDIN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @yield('styles')
</head>
<body class="bg-white text-dark d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                <i class="bi bi-airplane-fill"></i> PERDIN
            </a>

            @auth
            <div class="d-flex align-items-center gap-3">
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark btn-sm">
                        <i class="bi bi-gear-fill me-1"></i>Admin Panel
                    </a>
                @endif

                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-person-circle"></i>
                    <span class="fw-semibold">{{ Auth::user()->name }}</span>
                    <span class="badge bg-dark">
                        @if(Auth::user()->isPegawai()) PEGAWAI @endif
                        @if(Auth::user()->isSdm()) DIVISI-SDM @endif
                        @if(Auth::user()->isAdmin()) ADMIN @endif
                    </span>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i>Logout
                    </button>
                </form>
            </div>
            @endauth
        </div>
    </nav>

    <main class="container my-4 flex-grow-1">
        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
