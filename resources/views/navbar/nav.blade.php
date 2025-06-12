<nav class="navbar navbar-expand-lg glass-navbar fixed-top">
    <div class="container-fluid px-4">
        <a class="navbar-brand fw-bold text-white" href="#">
            <i class="bi bi-speedometer2 me-2"></i>Dashboard Guru
        </a>
        <button class="navbar-toggler text-white border-white" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active fw-bold' : '' }}" href="{{ route('home') }}">
                        <i class="bi bi-house-door me-1"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('import.index') ? 'active fw-bold' : '' }}" href="{{ route('import.index') }}">
                        <i class="bi bi-people me-1"></i>Data Siswa
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('guru.hasil.kuota-kelas.index') ? 'active fw-bold' : '' }}" href="{{ route('guru.hasil.kuota-kelas.index') }}">
                        <i class="bi bi-boxes me-1"></i>Kuota Kelas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('guru.hasil.index') ? 'active fw-bold' : '' }}" href="{{ route('guru.hasil.index') }}">
                        <i class="bi bi-play-circle me-1"></i>Proses
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('guru.metodologi') ? 'active fw-bold' : '' }}" href="{{ route('guru.metodologi') }}">
                        <i class="bi bi-lightbulb me-1"></i>Metodologi
                    </a>
                </li>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-white p-0 d-flex align-items-center">
                            <i class="bi bi-box-arrow-right me-1"></i>Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>