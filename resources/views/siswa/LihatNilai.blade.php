<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Nilai Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

        body {
            background: #4b79a1;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .custom-card {
            border-radius: 15px;
            background: #ffffff;
            padding: 2rem;
            margin-top: 2rem; /* Sesuaikan jika navbar fixed */
            margin-bottom: 2rem;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%; /* Memastikan card mengambil lebar dalam max-width */
        }

        .form-select,
        .form-control {
            border-radius: 8px;
        }

        .btn-primary {
            font-weight: 600;
        }

        /* .min-vh-100 dan .content-container untuk layout jika diperlukan */
        .content-container {
            padding-top: 1rem; /* Beri sedikit jarak dari navbar jika statis */
            padding-bottom: 2rem;
        }
    </style>
</head>

<body>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                // text: '{{ session('success') }}', // Sesuai kode asli Anda, ini dikomentari
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
    @include('navbar.navsiswa')

    <div class="container d-flex justify-content-center align-items-start min-vh-100 content-container">
        <div class="custom-card mx-auto" style="max-width: 700px;">
            <div class="text-center mb-4">
                <h3 class="text-primary"><i class="fas fa-chart-line"></i> Lihat Nilai Siswa</h3>
            </div>

            @foreach ($data_rekomendasi as $index => $data)
                {{-- Hanya tampilkan nama sekali, jika $data_rekomendasi berisi data siswa yg sedang dilihat --}}
                @if ($loop->first)
                <div class="mb-3 text-center">
                    <label class="fw-bold fs-5 text-uppercase text-dark">
                        <i class="fas fa-user-graduate me-2"></i>{{ $data['nama'] }}
                    </label>
                </div>
                @endif
            @endforeach

            @php
                $minat = \App\Models\MinatAwalSiswa::where('user_id', auth()->id())->first();
            @endphp

            <form id="formMinat" method="POST" action="{{ route('siswa.simpan-minat') }}">
                @csrf
                <div class="mb-3">
                    <label for="kelas" class="form-label">Minat Awal</label>

                    @if ($minat)
                        <input type="hidden" name="kelas" value="{{ $minat->kode_kelas }}">
                        <input type="hidden" name="nama_kriteria" value="{{ $minat->nama_kriteria }}">
                        <select id="kelas" class="form-select" disabled>
                            <option selected>
                                {{ $minat->nama_kriteria }}
                            </option>
                        </select>
                    @else
                        <select name="kelas" id="kelas" class="form-select" required onchange="setKriteria(this)">
                            <option value="">Pilih Kelas</option>
                            @foreach ($kode_kelas as $kelas)
                                <option value="{{ $kelas->kode }}" data-kriteria="{{ $kelas->nama_kriteria }}">
                                    Kelompok ({{ strtoupper($kelas->nama_kriteria) }})
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
                        Kamu telah memilih: <strong> {{ $minat->nama_kriteria }}</strong>
                    </div>
                @endif
            </form>

            <div class="d-grid mt-3">
                <button type="button" class="btn btn-primary" onclick="lihatNilai()">
                    <i class="fas fa-search"></i> Lihat Nilai
                </button>
            </div>

            <div id="hasilRekomendasi" class="mt-4 d-none">
                @if (!empty($data_rekomendasi))
                    {{-- Loop ini akan menampilkan data untuk setiap item di $data_rekomendasi.
                         Jika $data_rekomendasi hanya berisi satu siswa, maka hanya satu set data yang muncul. --}}
                    @foreach ($data_rekomendasi as $index => $data)
                        {{-- Bagian Data Nilai Mentah --}}
                        <h5 class="text-center mb-3 mt-4">Data Nilai Mentah Anda:</h5>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-striped align-middle">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>Kriteria</th>
                                        <th>Nilai Mentah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Nilai Matematika</td>
                                        <td class="text-center">{{ $data['matematika'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nilai IPA</td>
                                        <td class="text-center">{{ $data['ipa'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nilai IPS</td>
                                        <td class="text-center">{{ $data['ips'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Nilai Bahasa Inggris</td>
                                        <td class="text-center">{{ $data['b_ing'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tes IQ</td>
                                        <td class="text-center">{{ $data['tes_iq'] ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Bagian Rekomendasi Kelompok Mata Pelajaran --}}
                        <h5 class="text-center mb-3">Rekomendasi Kelompok Mata Pelajaran untuk Anda:</h5>
                        <div class="text-center p-3 mb-3" style="background-color: #e9ecef; border-radius: 8px;">
                            {{-- <strong class="fs-5">{{ $data['rekomendasi_kriteria'] ?? 'Belum ada rekomendasi' }}</strong> --}}
                            <p class="fw-bold">
                                {!! $data['kelas_rekomendasi_html'] !!}
                                {{-- {{ $data['kelas_rekomendasi'] }}
                                {{ $data['kelas_rekomendasi_html'] }} --}}
                            </p> 
                        </div>
                    @endforeach
                @else
                    {{-- Pesan ini akan muncul jika $data_rekomendasi kosong. --}}
                    <div class="alert alert-info text-center mt-4">
                        Data rekomendasi tidak ditemukan atau belum tersedia. <br>
                        Silakan pilih/simpan minat awal, kemudian klik "Lihat Nilai".
                    </div>
                @endif
            </div>

        </div> </div> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- JavaScript kembali ke versi asli Anda --}}
    <script>
        function setKriteria(select) {
            const selected = select.options[select.selectedIndex];
            document.getElementById('nama_kriteria').value = selected.dataset.kriteria;
        }

        function lihatNilai() {
            const kelas = document.getElementById('kelas').value;
            // Kondisi if menggunakan Blade untuk mengecek $minat, persis seperti kode awal Anda
            if (kelas || {{ $minat ? 'true' : 'false' }}) {
                document.getElementById('hasilRekomendasi').classList.remove('d-none');
            } else {
                alert('Silakan pilih minat awal/kriteria terlebih dahulu.');
            }
        }
    </script>
</body>
</html>