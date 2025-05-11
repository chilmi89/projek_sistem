<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="{{ asset('css/regis.css') }}" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Dashboard</title>
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
                        <a class="nav-link {{ Request::is('nilai*') ? 'active' : '' }}"
                            href="{{ route('siswa.nilai') }}">Input-Nilai</a>
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
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <div class="card shadow-lg">
                    <div class="card-header py-4 text-center text-dark">
                        <h4>Nilai SAS Mapel</h4>
                    </div>
                    <div class="card-body">
                        @if ($siswa->nilai->isEmpty())
                            <form id="nilaiForm" action="{{ route('siswa.nilai.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Nama Siswa</label>
                                    <input type="text" class="form-control" value="{{ auth()->user()->name }}"
                                        readonly>
                                    <input type="hidden" name="id_siswa" value="{{ $siswa->id }}">
                                </div>

                                <div class="row">
                                    @php
                                        $totalMapel = $mata_pelajaran->count();
                                        $split = ceil($totalMapel / 2);
                                    @endphp

                                    <!-- Kolom Kiri -->
                                    <div class="col-md-6">
                                        @foreach ($mata_pelajaran->slice(0, $split) as $mapel)
                                            <div class="mb-3">
                                                <label for="nilai_{{ $mapel->id }}" class="form-label">
                                                    {{ $mapel->nama_mapel }}
                                                </label>
                                                <input type="number" class="form-control"
                                                    id="nilai_{{ $mapel->id }}" name="nilai[{{ $mapel->id }}]"
                                                    min="0" max="100" required>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Kolom Kanan -->
                                    <div class="col-md-6">
                                        @foreach ($mata_pelajaran->slice($split) as $mapel)
                                            <div class="mb-3">
                                                <label for="nilai_{{ $mapel->id }}" class="form-label">
                                                    {{ $mapel->nama_mapel }}
                                                </label>
                                                <input type="number" class="form-control"
                                                    id="nilai_{{ $mapel->id }}" name="nilai[{{ $mapel->id }}]"
                                                    min="0" max="100" required>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="mt-3 d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                        data-bs-target="#infoModal">
                                        Informasi Pengisian Nilai
                                    </button>
                                    <button type="submit" class="btn btn-primary">Simpan Nilai</button>
                                </div>

                            </form>
                        @else
                            <div class="alert alert-danger text-center">
                                Anda sudah mengisi nilai. Tidak dapat mengisi kembali.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Informasi -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="infoModalLabel">
                        Cara Pengisian Nilai
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-black">
                    <p>Silakan isi nilai sesuai dengan format berikut:</p>
                    <ul>
                        <li>
                            <strong>Matematika-Umum:</strong> Masukkan nilai matematika umum
                            Anda dalam skala 0-100.
                        </li>
                        <li>
                            <strong>IPA:</strong> Masukkan nilai IPA Anda dalam skala 0-100.
                        </li>
                        <li>
                            <strong>Minal Awal:</strong> Masukkan nilai minimum awal atau
                            hasil awal evaluasi Anda.
                        </li>
                        <li>
                            <strong>IPS:</strong> Masukkan nilai IPS Anda dalam skala 0-100.
                        </li>
                        <li>
                            <strong>Bahasa Inggris:</strong> Masukkan nilai Bahasa Inggris
                            Anda dalam skala 0-100.
                        </li>
                        <li>
                            <strong>Test IQ:</strong> Masukkan hasil test IQ Anda, biasanya
                            dalam skala tertentu (misalnya 50-200).
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Tutup
                    </button>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="text-center mt-5 text-white bg-dark py-4" id="contact">
        <div class="container">
            <p>&copy; 2024 MyDashboard. All rights reserved.</p>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="#" class="text-white">Privacy Policy</a></li>
                <li class="list-inline-item"><a href="#" class="text-white">Terms of Service</a></li>
                <li class="list-inline-item"><a href="#" class="text-white">Contact Us</a></li>
            </ul>
        </div>
    </footer>
    <script>
        document.getElementById('nilaiForm').addEventListener('submit', function(e) {
            e.preventDefault();

            fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            // Optional: refresh the page or redirect
                            window.location.reload();
                        });
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: error.message || 'Terjadi kesalahan saat menyimpan nilai',
                    });
                });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>
