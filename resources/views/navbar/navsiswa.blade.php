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
                    <a class="nav-link {{ Request::routeIs('home') || Request::routeIs('siswa.dashboard')  ? 'active' : '' }}"
                        href="{{ route('home') }}">
                        Home
                    </a>

                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('siswa.lihat-nilai') ? 'active fw-bold' : '' }}"
                        href="{{ route('siswa.lihat-nilai') }}"">
                        lihat nilai
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