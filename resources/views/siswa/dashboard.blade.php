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
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('css/index.css') }}"> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            margin-top: 90px;
            background: linear-gradient(45deg, #4b79a1, #283e51);
            color: #333;
            font-family: "Arial", sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .glass-navbar {
            background-color: black;
            transition: background 0.3s ease, backdrop-filter 0.3s ease;
            color: white;
        }

        /* Saat discroll, tambahkan efek glassmorphism */
        .glass-navbar.scrolled {
            background: rgba(0, 0, 0, 0.6);
            /* tetap hitam tapi transparan */
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
        }

        .glass-navbar .nav-link {
            color: #fff;
            transition: all 0.3s ease;
        }

        .glass-navbar .nav-link:hover,
        .glass-navbar .nav-link.active {
            color: #0dcaf0 !important;
        }

        .glass-navbar .navbar-toggler {
            border: none;
        }

        /* Tambahan efek underline saat hover */
        .glass-navbar .nav-link::after {
            content: "";
            display: block;
            height: 2px;
            width: 0;
            background: #0dcaf0;
            transition: width 0.3s ease-in-out;
        }

        .glass-navbar .nav-link:hover::after,
        .glass-navbar .nav-link.active::after {
            width: 100%;
        }


        /* Content Wrapper */
        .content-wrapper {
            flex: 1 0 auto;
            padding: 1.5rem 0;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 56px - 38px);
            /* Subtract navbar and footer heights */
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

    @include('navbar.navsiswa')

    <!-- Main Content -->
    <div class="content-wrapper">
        <div class="container text-center">
            <div class="jumbotron py-5 text-light">
                <h1 class="display-4">Selamat Datang, {{ Auth::user()->name }}</h1>
                <p class="lead">Ini adalah halaman dashboard untuk siswa.</p>
                <hr class="my-4" />
                <p>Gunakan tombol di bawah untuk menjelajahi fitur-fitur menarik.</p>
                <a href="{{ route('siswa.lihat-nilai') }}" class="btn btn-primary">Lihat Nilai</a>

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