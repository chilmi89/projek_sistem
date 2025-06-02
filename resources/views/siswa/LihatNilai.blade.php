<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Nilai Siswa</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background: #4b79a1;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .custom-card {
            border-radius: 15px;
            background: #ffffff;
            padding: 2rem;
            margin-top: 2rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-select,
        .form-control {
            border-radius: 8px;
        }

        .btn-primary {
            font-weight: 600;
        }

        table {
            white-space: nowrap;
        }
    </style>
</head>

<body>
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
                        <a class="nav-link {{ Request::routeIs('home') || Request::routeIs('siswa.dashboard') ? 'active' : '' }}"
                            href="{{ route('home') }}">
                            Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ route('siswa.lihat-nilai') }}" href="">Input
                            Nilai</a>
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
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="custom-card mx-auto" style="max-width: 700px;">
            <!-- Header -->
            <div class="text-center mb-4">
                <h3 class="text-primary"><i class="fas fa-chart-line"></i> Lihat Nilai Siswa</h3>
            </div>

            <!-- Nama Siswa -->
            @foreach ($data_rekomendasi as $index => $data)
                <div class="mb-3 text-center">
                    <label class="fw-bold fs-5 text-uppercase text-dark">
                        <i class="fas fa-user-graduate me-2"></i>{{ $data['nama'] }}
                    </label>
                </div>
            @endforeach

            @php
                $minat = \App\Models\MinatAwalSiswa::where('user_id', auth()->id())->first();
            @endphp

            <!-- FORM Minat Awal -->
            <form id="formMinat" method="POST" action="{{ route('siswa.simpan-minat') }}">
                @csrf
                <div class="mb-3">
                    <label for="kelas" class="form-label">Minat Awal</label>

                    @if ($minat)
                        <input type="hidden" name="kelas" value="{{ $minat->kode_kelas }}">
                        <input type="hidden" name="nama_kriteria" value="{{ $minat->nama_kriteria }}">
                        <select id="kelas" class="form-select" disabled>
                            <option selected>
                                {{ $minat->kode_kelas }} - {{ $minat->nama_kriteria }}
                            </option>
                        </select>
                    @else
                        <select name="kelas" id="kelas" class="form-select" required onchange="setKriteria(this)">
                            <option value="">Pilih Kelas</option>
                            @foreach ($kode_kelas as $kelas)
                                <option value="{{ $kelas->kode }}" data-kriteria="{{ $kelas->nama_kriteria }}">
                                    {{ $kelas->kode }} - Kelompok ({{ strtoupper($kelas->nama_kriteria) }})
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="nama_kriteria" id="nama_kriteria">
                    @endif
                </div>

                @if (!$minat)
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary">Simpan Minat Awal</button>
                    </div>
                @else
                    <div class="alert alert-success text-center">
                        Kamu telah memilih: <strong>{{ $minat->kode_kelas }} - {{ $minat->nama_kriteria }}</strong>
                    </div>
                @endif
            </form>

            <!-- Tombol Lihat Nilai -->
            <div class="d-grid mt-3">
                <button type="button" class="btn btn-primary" onclick="lihatNilai()">
                    <i class="fas fa-search"></i> Lihat Nilai
                </button>
            </div>

            <!-- Hasil Rekomendasi -->
            <div id="hasilRekomendasi" class="mt-4 d-none">
                <h5 class="text-center mb-3">Hasil Rekomendasi Kelas</h5>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Minat Awal</th>
                                <th>Nilai WP</th>
                                <th>Matematika</th>
                                <th>IPA</th>
                                <th>IPS</th>
                                <th>Bahasa Inggris</th>
                                <th>Tes IQ</th>
                                <th>Rekomendasi</th>
                                <th>Alokasi Kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data_rekomendasi as $index => $data)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $data['nama'] }}</td>
                                    <td>{{ $data['minat_awal'] }}</td>
                                    <td>{{ $data['nilai_wp'] }}</td>
                                    <td>{{ $data['matematika'] }}</td>
                                    <td>{{ $data['ipa'] }}</td>
                                    <td>{{ $data['ips'] }}</td>
                                    <td>{{ $data['b_ing'] }}</td>
                                    <td>{{ $data['tes_iq'] ?? 'tidak ada' }}</td>
                                    <td>{{ $data['rekomendasi_kriteria'] }}</td>
                                    <td>{{ $data['alokasi_kelas'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function setKriteria(select) {
            const selected = select.options[select.selectedIndex];
            document.getElementById('nama_kriteria').value = selected.dataset.kriteria;
        }

        function lihatNilai() {
            const kelas = document.getElementById('kelas').value;
            if (kelas || {{ $minat ? 'true' : 'false' }}) {
                document.getElementById('hasilRekomendasi').classList.remove('d-none');
            } else {
                alert('Silakan pilih minat awal/kriteria terlebih dahulu.');
            }
        }
    </script>
</body>

</html>
