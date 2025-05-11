<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Dashboard Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .content-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        footer {
            flex-shrink: 0;
            padding: 20px;
            background-color: #343a40;
            color: white;
            text-align: center;
        }

        .jumbotron {
            text-align: center;
        }

        .nav-link.active {
            font-weight: bold;
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="">MyDashboard</a>

            @if (session('success'))
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        // text: '{{ session('success') }}',
                    });
                </script>
            @endif

            @if ($errors->any())
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: '{{ $errors->first() }}',
                    });
                </script>
            @endif

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('home') || Request::routeIs('siswa.dashboard')  ? 'active' : '' }}"
                           href="{{ route('home') }}">
                           Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('siswa.index') ? 'active' : '' }}" href="{{ route('siswa.index') }}">Regis Data</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('nilai.index') ? 'active' : '' }}" href="">Input Nilai</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('hasil.index') ? 'active' : '' }}" href="">Hasil</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link border-0 bg-transparent text-white">
                                Logout
                            </button>
                        </form>
                    </li>


                </ul>
            </div>
        </div>
    </nav>


    <!-- Main Content -->
    <div class="content-wrapper">
        <div class="container text-center">
            <div class="jumbotron py-5">
                <h1 class="display-4">Selamat Datang, {{ Auth::user()->name }}</h1>
                <p class="lead">Ini adalah halaman dashboard interaktif untuk siswa.</p>
                <hr class="my-4" />
                <p>Gunakan tombol di bawah untuk menjelajahi fitur-fitur menarik.</p>
                <a class="btn btn-primary btn-lg" href="{{ route('siswa.index') }}" role="button">regis data siswa</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center bg-dark text-white py-3">
        <p class="mb-0">&copy; 2024 MyDashboard. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
