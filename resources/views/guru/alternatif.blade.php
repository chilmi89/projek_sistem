<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guru Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="#">Kode</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('home') }}"> home </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('import.index') }}">Dashboard 2</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('guru.hasil.kuota-kelas.index') }}">kuota kelas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('guru.hasil.index') }}">Dashboard 3</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('guru.metodologi') }}">metodolgi </a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link text-light">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div class="container mt-4">

        {{-- Header dan Upload Form --}}
        <div class="card p-4 mb-4">
            <h4 class="mb-3 text-center">📥 Import Nilai Alternatif</h4>

            {{-- Pesan sukses atau error --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('import.excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3 align-items-center">
                    <div class="col-md-6">
                        <input type="file" class="form-control" name="file" accept=".xls,.xlsx" required>
                        @error('file')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-upload"></i> Upload File
                        </button>
                    </div>
                    <div class="col-md-3 text-muted small">
                        Hanya file .xls atau .xlsx yang diperbolehkan.
                    </div>
                </div>
            </form>
        </div>

        {{-- Tabel Data Siswa --}}
        <div class="card p-4">
            <h5 class="mb-3 text-center">📋 Data Siswa yang Telah Diimpor</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>MTK UM</th>
                            <th>IPA</th>
                            <th>IPS</th>
                            <th>Bahasa Inggris</th>
                            <th>TES IQ</th>
                            <th>C1</th>
                            <th>C2</th>
                            <th>C3</th>
                            <th>C4</th>
                            <th>C5</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($students) && count($students) > 0)
                            @foreach ($students as $index => $student)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $student->nama }}</td>
                                    <td>{{ $student->mtk_um }}</td>
                                    <td>{{ $student->ipa }}</td>
                                    <td>{{ $student->ips }}</td>
                                    <td>{{ $student->b_ing }}</td>
                                    <td>{{ $student->tes_iq }}</td>
                                    <td>{{ $student->hasilBobot->c1 ?? '-' }}</td>
                                    <td>{{ $student->hasilBobot->c2 ?? '-' }}</td>
                                    <td>{{ $student->hasilBobot->c3 ?? '-' }}</td>
                                    <td>{{ $student->hasilBobot->c4 ?? '-' }}</td>
                                    <td>{{ $student->hasilBobot->c5 ?? '-' }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="12" class="text-muted">Belum ada data siswa.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                @if (isset($students) && count($students) > 0)
                    <div class="mt-3 d-flex justify-content-center">
                        {{ $students->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </div>


    <!-- Modal Hapus Sub Kriteria -->




    <!-- Footer -->
    <footer class="bg-dark text-light text-center py-3 mt-auto">
        <p class="mb-0">© 2025 Kode. All rights reserved.</p>
    </footer>

    <!-- Scripts -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/handler_sweet.js') }}"></script>

    </script>
    <script>
        // SweetAlert for success/error messages
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif

            @if (session('error') || isset($error))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: '{{ session('error') ?? $error }}',
                    timer: 3000,
                    showConfirmButton: false
                });
            @endif
        });
    </script>
</body>

</html>
