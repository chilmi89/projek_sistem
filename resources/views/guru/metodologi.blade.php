<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Metodologi WP dan ROC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> 
</head>

<body class="bg-light">

    <!-- Navbar -->
    @include('navbar.nav')

    <div class="container pt-4">
        <!-- Tombol Modal -->
        <div class="mb-4 text-center">
            <button class="btn btn-info me-2" data-bs-toggle="modal" data-bs-target="#modal1">Normalisasi Bobot</button>
            <button class="btn btn-info me-2" data-bs-toggle="modal" data-bs-target="#modal2">Vektor S</button>
            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modal3">Vektor V</button>
        </div>
        <!-- Metodologi WP -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                Langkah-Langkah Metodologi Weighted Product (WP)
            </div>
            <div class="card-body">
                <ol>
                    <li>Menentukan kriteria dan alternatif</li>
                    <li>Menentukan bobot awal setiap kriteria</li>
                    <li>Melakukan normalisasi bobot</li>
                    <li>Menghitung nilai vektor S untuk setiap alternatif</li>
                    <li>Menghitung nilai vektor V</li>
                    <li>Melakukan perankingan berdasarkan nilai V</li>
                </ol>
            </div>
        </div>

        <!-- Bobot dan Normalisasi -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                Tabel Bobot dan Normalisasi
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Kriteria</th>
                            <th>Bobot Awal (wj)</th>
                            <th>Normalisasi (Wj)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>K1</td>
                            <td>4</td>
                            <td>0.4</td>
                        </tr>
                        <tr>
                            <td>K2</td>
                            <td>3</td>
                            <td>0.3</td>
                        </tr>
                        <tr>
                            <td>K3</td>
                            <td>3</td>
                            <td>0.3</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Nilai Alternatif -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                Tabel Nilai Alternatif (Xij)
            </div>
            <div class="card-body">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Alternatif</th>
                            <th>K1</th>
                            <th>K2</th>
                            <th>K3</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="table-warning">
                            <td>A1</td>
                            <td>80</td>
                            <td>70</td>
                            <td>90</td>
                        </tr>
                        <tr>
                            <td>A2</td>
                            <td>75</td>
                            <td>85</td>
                            <td>80</td>
                        </tr>
                        <tr class="table-danger">
                            <td>A3</td>
                            <td>60</td>
                            <td>65</td>
                            <td>70</td>
                        </tr>
                    </tbody>
                </table>

                <div class="alert alert-warning mt-3">
                    <strong>NB:</strong> <br>
                    Warna <span class="bg-warning px-2">Kuning</span> menunjukkan mapel yang diminati siswa.<br>
                    Warna <span class="bg-danger text-white px-2">Merah</span> menunjukkan wajib tes IQ.
                </div>
            </div>
        </div>



    </div>

    <!-- Modal Normalisasi -->
    <div class="modal fade" id="modal1" tabindex="-1" aria-labelledby="modal1Label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Normalisasi Bobot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Rumus:</strong></p>
                    <code>Wj = wj / Σwj</code>
                    <p>Wj menyatakan bobot yang telah dinormalisasi berdasarkan jumlah total semua bobot awal wj. Digunakan agar total bobot = 1.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Vektor S -->
    <div class="modal fade" id="modal2" tabindex="-1" aria-labelledby="modal2Label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Perhitungan Vektor S</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Rumus:</strong></p>
                    <code>Si = ∏(xij<sup>Wj</sup>)</code>
                    <p>Si adalah hasil dari perkalian semua nilai kriteria (xij) pada alternatif i, yang dipangkatkan dengan bobot Wj.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Vektor V -->
    <div class="modal fade" id="modal3" tabindex="-1" aria-labelledby="modal3Label" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Perhitungan Vektor V</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Rumus:</strong></p>
                    <code>Vi = Si / ΣSi</code>
                    <p>Vi adalah hasil pembagian dari nilai vektor S terhadap jumlah semua vektor S. Nilai Vi digunakan untuk menentukan ranking akhir setiap alternatif.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
