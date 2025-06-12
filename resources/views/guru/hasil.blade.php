<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Hasil Pembagian Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- <link rel="stylesheet" href="{{ asset('css/style.css') }}">  -->
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

        .table-responsive {
            font-size: 0.875rem;
        }

        .card-body {
            padding: 1rem;
        }

        .badge {
            font-size: 0.75rem;
        }

        .progress {
            border-radius: 4px;
        }

        .modal-xl {
            max-width: 95%;
        }

        #results-table th {
            font-size: 0.75rem;
            font-weight: 600;
            text-align: center;
            vertical-align: middle;
            padding: 0.5rem 0.25rem;
        }

        #results-table td {
            font-size: 0.75rem;
            padding: 0.375rem 0.25rem;
            vertical-align: middle;
        }

        .btn-group .btn {
            border-radius: 0.375rem;
            margin-left: 0.25rem;
        }

        .btn-group .btn:first-child {
            margin-left: 0;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
        }

        @media (max-width: 768px) {
            .btn-group {
                flex-direction: column;
                width: 100%;
            }

            .btn-group .btn {
                margin-left: 0;
                margin-bottom: 0.25rem;
            }

            .card-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .card-header h4 {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>

<body>

    @include('navbar.nav')
    <div class="container-fluid">
        {{-- Alert untuk pesan --}}
        @if ($pesan)
        <div class="alert alert-warning alert-dismissible fade show mt-4" role="alert">
            {{ $pesan }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        {{-- Alert untuk pesan dinamis dari AJAX --}}
        <div id="alert-container" style="display: none;">
            <div class="alert alert-dismissible fade show" id="alert-message" role="alert">
                <span id="alert-text"></span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>

        {{-- Card Header dengan Tombol --}}
        <div class="card mb-4 mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Hasil Perhitungan Weighted Product</h4>
                <div class="btn-group" role="group">
                    {{-- Tombol Refresh Data --}}
                    <button type="button" class="btn btn-outline-primary" onclick="refreshData()" id="btn-refresh">
                        <i class="fas fa-sync-alt me-1"></i> Refresh Data
                    </button>

                    {{-- Tombol Save ke Database --}}
                    <button type="button" class="btn btn-success" onclick="saveToDatabase()" id="btn-save">
                        <i class="fas fa-save me-1"></i> Save to Database
                    </button>




                </div>
            </div>
        </div>

        {{-- Loading indicator --}}
        <div id="loading" style="display: none;" class="text-center mb-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memproses data...</p>
        </div>

        {{-- Tabel Hasil Perhitungan --}}
        <div class="card mb-4">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-striped table-sm" id="results-table">
                    <thead class="bg-light">
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Nama Siswa</th>
                            <th colspan="5" class="text-center">Nilai Asli</th>
                            <th colspan="5" class="text-center">Perpangkatan (c^bobot)</th>
                            <th rowspan="2">Nilai S</th>
                            <th colspan="5" class="text-center">Hasil Bagi (c / S)</th>
                            <th rowspan="2">Rekomendasi</th>
                            <th rowspan="2">Alokasi Kelas</th>
                            <th rowspan="2">Status</th>
                        </tr>
                        <tr>
                            <th>C1</th>
                            <th>C2</th>
                            <th>C3</th>
                            <th>C4</th>
                            <th>C5</th>
                            <th>C1</th>
                            <th>C2</th>
                            <th>C3</th>
                            <th>C4</th>
                            <th>C5</th>
                            <th>C1</th>
                            <th>C2</th>
                            <th>C3</th>
                            <th>C4</th>
                            <th>C5</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        @forelse ($hasilBobots ?? [] as $index => $siswa)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $siswa->nama ?? '-' }}</td>

                            {{-- Nilai Asli --}}
                            <td>{{ number_format($siswa->c1 ?? 0, 2) }}</td>
                            <td>{{ number_format($siswa->c2 ?? 0, 2) }}</td>
                            <td>{{ number_format($siswa->c3 ?? 0, 2) }}</td>
                            <td>{{ number_format($siswa->c4 ?? 0, 2) }}</td>
                            <td>{{ number_format($siswa->c5 ?? 0, 2) }}</td>

                            {{-- Perpangkatan --}}
                            <td>{{ number_format($siswa->c1_pow ?? 0, 4) }}</td>
                            <td>{{ number_format($siswa->c2_pow ?? 0, 4) }}</td>
                            <td>{{ number_format($siswa->c3_pow ?? 0, 4) }}</td>
                            <td>{{ number_format($siswa->c4_pow ?? 0, 4) }}</td>
                            <td>{{ number_format($siswa->c5_pow ?? 0, 4) }}</td>

                            {{-- Nilai S --}}
                            <td>{{ number_format($siswa->nilai_s ?? 0, 6) }}</td>

                            {{-- Hasil Bagi --}}
                            <td>{{ number_format($siswa->c1_bagi ?? 0, 4) }}</td>
                            <td>{{ number_format($siswa->c2_bagi ?? 0, 4) }}</td>
                            <td>{{ number_format($siswa->c3_bagi ?? 0, 4) }}</td>
                            <td>{{ number_format($siswa->c4_bagi ?? 0, 4) }}</td>
                            <td>{{ number_format($siswa->c5_bagi ?? 0, 4) }}</td>

                            {{-- Rekomendasi dan Alokasi --}}
                            <td class="text-center">
                                <span class="badge bg-primary">{{ $siswa->rekomendasi_kriteria ?? '-' }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info">{{ $siswa->alokasi_kelas ?? '-' }}</span>
                            </td>
                            <td class="text-center">
                                @php
                                $status = $siswa->status_alokasi ?? 'belum';
                                @endphp
                                @if (strtolower($status) == 'sesuai')
                                <span class="badge bg-success">Sesuai</span>
                                @elseif(strtolower($status) == 'dialihkan')
                                <span class="badge bg-warning">Dialihkan</span>
                                @elseif(strtolower($status) == 'paksa')
                                <span class="badge bg-secondary">Paksa</span>
                                @elseif(strtolower($status) == 'over')
                                <span class="badge bg-danger">Over</span>
                                @else
                                <span class="badge bg-light text-dark">{{ ucfirst($status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="20" class="text-center">Belum ada data hasil.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Statistik --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title" id="total-siswa">{{ count($hasilBobots ?? []) }}</h4>
                                <p class="card-text">Total Siswa</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title" id="sesuai-count">
                                    @php
                                    $sesuaiCount = collect($hasilBobots ?? [])
                                    ->where('status_alokasi', 'Sesuai')
                                    ->count();
                                    @endphp
                                    {{ $sesuaiCount }}
                                </h4>
                                <p class="card-text">Sesuai Rekomendasi</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title" id="dialihkan-count">
                                    @php
                                    $dialihkanCount = collect($hasilBobots ?? [])
                                    ->where('status_alokasi', 'Dialihkan')
                                    ->count();
                                    @endphp
                                    {{ $dialihkanCount }}
                                </h4>
                                <p class="card-text">Dialihkan</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-exchange-alt fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="card-title" id="over-count">
                                    @php
                                    $overCount = collect($hasilBobots ?? [])
                                    ->where('status_alokasi', 'Over')
                                    ->count();
                                    @endphp
                                    {{ $overCount }}
                                </h4>
                                <p class="card-text">Over Kapasitas</p>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Summary Alokasi per Kelas --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Summary Alokasi per Kelas</h5>
            </div>
            <div class="card-body">
                <div class="row" id="kelas-summary">
                    @php
                    $kelasStats = collect($hasilBobots ?? [])
                    ->groupBy('alokasi_kelas')
                    ->map(function ($group, $kelas) {
                    return [
                    'kelas' => $kelas,
                    'total' => $group->count(),
                    'sesuai' => $group->where('status_alokasi', 'Sesuai')->count(),
                    'dialihkan' => $group->where('status_alokasi', 'Dialihkan')->count(),
                    // 'over' => $group->where('status_alokasi', 'Over')->count(),
                    ];
                    });
                    @endphp

                    @foreach ($kelasStats as $stat)
                    <div class="col-md-4 mb-3">
                        <div class="card border-primary">
                            <div class="card-body">
                                <h6 class="card-title text-primary">{{ $stat['kelas'] }}</h6>
                                <p class="card-text mb-1">
                                    <strong>Total: {{ $stat['total'] }}</strong>
                                </p>
                                <small class="text-muted">
                                    Sesuai: {{ $stat['sesuai'] }} |
                                    Dialihkan: {{ $stat['dialihkan'] }} |
                                    {{-- Over: {{ $stat['over'] }} --}}
                                </small>
                                <div class="progress mt-2" style="height: 8px;">
                                    @php
                                    $sesuaiPct =
                                    $stat['total'] > 0 ? ($stat['sesuai'] / $stat['total']) * 100 : 0;
                                    $dialihkanPct =
                                    $stat['total'] > 0 ? ($stat['dialihkan'] / $stat['total']) * 100 : 0;
                                    // $overPct = $stat['total'] > 0 ? ($stat['over'] / $stat['total']) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar bg-success" style="width: {{ $sesuaiPct }}%">
                                    </div>
                                    <div class="progress-bar bg-warning" style="width: {{ $dialihkanPct }}%">
                                    </div>
                                    {{-- <div class="progress-bar bg-danger" style="width: {{ $overPct }}%">
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Modal untuk View Saved Data --}}
    <div class="modal fade" id="savedDataModal" tabindex="-1" aria-labelledby="savedDataModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="savedDataModalLabel">Data Tersimpan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="saved-data-content">
                        <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        window.addEventListener("scroll", function() {
            const navbar = document.querySelector(".glass-navbar");
            if (window.scrollY > 10) {
                navbar.classList.add("scrolled");
            } else {
                navbar.classList.remove("scrolled");
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Fungsi untuk refresh data


        function refreshData() {
            const btnRefresh = document.getElementById('btn-refresh');
            const loading = document.getElementById('loading');

            // Disable button dan show loading
            btnRefresh.disabled = true;
            btnRefresh.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Refreshing...';
            loading.style.display = 'block';

            // Reload halaman atau panggil AJAX
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        }

        // Fungsi untuk save to database
        function saveToDatabase() {
            const button = document.getElementById('btn-save');
            button.disabled = true;
            button.innerText = 'Menyimpan...';

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/guru/hasil/refresh-weighted-product', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({})
                })
                .then(res => {
                    if (!res.ok) throw new Error("HTTP error! status: " + res.status);
                    return res.json();
                })
                .then(data => {
                    console.log('Berhasil:', data);
                    if (data.success) {
                        alert("✅ Data Weighted Product berhasil disimpan!");
                        button.innerText = 'Tersimpan ✔️';
                    } else {
                        alert("⚠️ Gagal menyimpan: " + data.message);
                        button.disabled = false;
                        button.innerText = 'Simpan ke Database';
                    }
                })
                .catch(error => {
                    console.error('Gagal:', error);
                    alert("❌ Terjadi kesalahan saat menyimpan data ke database.");
                    button.disabled = false;
                    button.innerText = 'Simpan ke Database';
                });
        }


        // Helper functions
        function showAlert(message, type) {
            const alertContainer = document.getElementById('alert-container');
            const alertMessage = document.getElementById('alert-message');
            const alertText = document.getElementById('alert-text');

            alertMessage.className = `alert alert-${type} alert-dismissible fade show`;
            alertText.textContent = message;
            alertContainer.style.display = 'block';

            // Auto hide after 5 seconds
            setTimeout(() => {
                alertContainer.style.display = 'none';
            }, 5000);
        }

        function getStatusBadgeClass(status) {
            switch (status.toLowerCase()) {
                case 'sesuai':
                    return 'bg-success';
                case 'dialihkan':
                    return 'bg-warning';
                case 'paksa':
                    return 'bg-secondary';
                case 'over':
                    return 'bg-danger';
                default:
                    return 'bg-light text-dark';
            }
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        // Initialize tooltips if needed
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Bootstrap tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>

</body>

</html>