<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">MyDashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('home') ? 'active' : '' }}"
                            href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('siswa*') ? 'active' : '' }}"
                            href="{{ route('siswa.index') }}">regis-data</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('nilai*') ? 'active' : '' }}" href="{{ route('siswa.nilai') }}">Input-Nilai</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('hasil*') ? 'active' : '' }}" href="#contact">Hasil</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link border-0 bg-transparent">
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
        <div class="container-center justify-content-center">
            <div class="container mt-4">
                <div class="row justify-content-center">
                    <div class="col-lg-8"> <!-- Lebar lebih besar untuk menampung dua kolom -->
                        <div class="card shadow-lg">
                            <div class="card-header py-4 text-center text-dark">
                                <h4>Data Siswa</h4>
                            </div>
                            <div class="card-body">
                                <form id="siswaForm" action="{{ route('siswa.store') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="nama" class="form-label">Nama</label>
                                            <input type="text" class="form-control" id="nama" name="nama"
                                                value="{{ Auth::user()->name }}" readonly />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                value="{{ Auth::user()->email }}" readonly />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="nisn" class="form-label">NISN</label>
                                            <input type="text" class="form-control" id="nisn" name="nisn"
                                                placeholder="Masukkan NISN Anda" required />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="kelas" class="form-label">Kelas</label>
                                            <input type="text" class="form-control" id="kelas" name="kelas"
                                                placeholder="Masukkan kelas Anda" required />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="no_absen" class="form-label">No. Absen</label>
                                            <input type="number" class="form-control" id="no_absen" name="no_absen"
                                                placeholder="Masukkan nomor absen Anda" required />
                                        </div>
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Kirim</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <footer class="text-center mt-5" id="contact">
        <p>&copy; 2024 MyDashboard. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('siswaForm').addEventListener('submit', function(event) {
            // event.preventDefault(); // HAPUS INI agar form tetap terkirim ke backend

            Swal.fire({
                icon: 'success',
                title: 'Sukses!',
                text: 'Data siswa berhasil dikirim.',
                confirmButtonText: 'OK'
            });
        });
    </script>
</body>

</html>
