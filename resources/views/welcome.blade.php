<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @if (session('success'))
    <meta name="success-message" content="{{ session('success') }}">
    @endif
    @if (session('error'))
    <meta name="error-message" content="{{ session('error') }}">
    @endif

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <title>Dashboard Admin</title>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid px-3">
            <a class="navbar-brand" href="#">Kode</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#">Dashboard 1</a></li>
                    <li class="nav-item"><a class="nav-link" href="#features">Dashboard 2</a></li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" id="logout-form">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link p-0">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10 col-sm-12">
                    <div class="card shadow-sm">
                        <div class="d-flex gap-2 p-3">
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addDataModal">
                                <i class="fas fa-plus-circle"></i> Tambah Data
                            </button>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addKriteriaModal">
                                <i class="fas fa-list"></i> Tambah Kriteria
                            </button>
                            <button class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#addSubKriteriaModal">
                                <i class="fas fa-layer-group"></i> Tambah Sub Kriteria
                            </button>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 15%">Kode</th>
                                            <th style="width: 45%">Mata Pelajaran</th>
                                            <th style="width: 20%">Bobot</th>
                                            <th style="width: 20%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mataPelajaran as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->nama_mapel }}</td>
                                            <td>{{ $item->bobot ?? '-' }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-warning" data-bs-toggle="modal"
                                                        data-bs-target="#editDataModal{{ $item->id }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <button class="btn btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#deleteDataModal{{ $item->id }}">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Modal Edit -->
                                        <div class="modal fade" id="editDataModal{{ $item->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Data</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form
                                                            action="{{ route('guru.mata-pelajaran.update', $item->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="mb-3">
                                                                <label class="form-label">Mata Pelajaran</label>
                                                                <input type="text" class="form-control"
                                                                    name="nama_mapel"
                                                                    value="{{ $item->nama_mapel }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Bobot</label>
                                                                <input type="number" step="0.01" class="form-control"
                                                                    name="bobot" value="{{ $item->bobot }}"
                                                                    required>
                                                            </div>
                                                            <button type="submit"
                                                                class="btn btn-primary">Simpan</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Hapus -->
                                        <div class="modal fade" id="deleteDataModal{{ $item->id }}"
                                            tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Hapus Data</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus mata pelajaran
                                                            <strong>{{ $item->nama_mapel }}</strong>?
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form
                                                            action="{{ route('guru.mata-pelajaran.destroy', $item->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-danger">Hapus</button>
                                                        </form>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Sub Kriteria -->
                    <div class="card shadow-sm mt-4">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 10%">Kode Kriteria</th>
                                            <th style="width: 15%">Kategori</th>
                                            <th style="width: 15%">Rentang Nilai</th>
                                            <th style="width: 10%">Nilai Min</th>
                                            <th style="width: 10%">Nilai Max</th>
                                            <th style="width: 10%">Bobot</th>
                                            <th style="width: 10%">ROC</th>
                                            <th style="width: 10%">Keterangan</th>
                                            <th style="width: 10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subKriterias as $sub)
                                        <tr>
                                            <td>{{ $sub->kriteria->kode ?? '-' }}</td>
                                            <td>{{ $sub->kategori }}</td>
                                            <td>{{ $sub->rentang_nilai }}</td>
                                            <td>{{ $sub->nilai_min ?? '-' }}</td>
                                            <td>{{ $sub->nilai_max ?? '-' }}</td>
                                            <td>{{ $sub->bobot }}</td>
                                            <td>{{ $sub->roc }}</td>
                                            <td>{{ $sub->keterangan }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-warning" data-bs-toggle="modal"
                                                        data-bs-target="#editSubKriteriaModal{{ $sub->id }}">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </button>
                                                    <button class="btn btn-danger" data-bs-toggle="modal"
                                                        data-bs-target="#deleteSubKriteriaModal{{ $sub->id }}">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Modal Edit Sub Kriteria -->
                                        <div class="modal fade" id="editSubKriteriaModal{{ $sub->id }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Sub Kriteria</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form
                                                            action="{{ route('subkriteria.update', $sub->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="mb-3">
                                                                <label class="form-label">Kriteria</label>
                                                                <select class="form-select" name="kriteria_id" required>
                                                                    <option value="">Pilih Kriteria</option>
                                                                    @foreach ($kriterias as $kriteria)
                                                                        <option value="{{ $kriteria->id }}"
                                                                            {{ $sub->kriteria_id == $kriteria->id ? 'selected' : '' }}>
                                                                            {{ $kriteria->kode }} - {{ $kriteria->nama }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Kategori</label>
                                                                <select class="form-select" name="kategori" required>
                                                                    <option value="mata_pelajaran"
                                                                        {{ $sub->kategori == 'mata_pelajaran' ? 'selected' : '' }}>
                                                                        Mata Pelajaran
                                                                    </option>
                                                                    <option value="iq"
                                                                        {{ $sub->kategori == 'iq' ? 'selected' : '' }}>
                                                                        IQ
                                                                    </option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Rentang Nilai</label>
                                                                <input type="text" class="form-control"
                                                                    name="rentang_nilai"
                                                                    value="{{ $sub->rentang_nilai }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Nilai Minimum</label>
                                                                <input type="number" class="form-control"
                                                                    name="nilai_min"
                                                                    value="{{ $sub->nilai_min }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Nilai Maksimum</label>
                                                                <input type="number" class="form-control"
                                                                    name="nilai_max"
                                                                    value="{{ $sub->nilai_max }}">
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Bobot</label>
                                                                <input type="number" step="0.01" class="form-control"
                                                                    name="bobot"
                                                                    value="{{ $sub->bobot }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">ROC</label>
                                                                <input type="number" step="0.01" class="form-control"
                                                                    name="roc"
                                                                    value="{{ $sub->roc }}" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Keterangan</label>
                                                                <input type="text" class="form-control"
                                                                    name="keterangan"
                                                                    value="{{ $sub->keterangan }}" required>
                                                            </div>
                                                            <button type="submit"
                                                                class="btn btn-primary">Simpan</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Hapus Sub Kriteria -->
                                        <div class="modal fade" id="deleteSubKriteriaModal{{ $sub->id }}"
                                            tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Hapus Sub Kriteria</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah Anda yakin ingin menghapus sub kriteria dengan rentang
                                                            <strong>{{ $sub->rentang_nilai }}</strong>?
                                                        </p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <form
                                                            action="{{ route('subkriteria.destroy', $sub->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-danger">Hapus</button>
                                                        </form>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Batal</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="addDataModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Mata Pelajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('guru.mata-pelajaran.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Mata Pelajaran</label>
                            <input type="text" class="form-control" name="nama_mapel" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bobot</label>
                            <input type="number" step="0.01" class="form-control" name="bobot" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kriteria -->
    <div class="modal fade" id="addKriteriaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('kriteria.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Kode</label>
                            <input type="text" class="form-control" name="kode" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis</label>
                            <select name="jenis" class="form-control" required>
                                <option value="Benefit">Benefit</option>
                                <option value="Cost">Cost</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Sub Kriteria -->
    <div class="modal fade" id="addSubKriteriaModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Sub Kriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('subkriteria.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Kriteria</label>
                            <select class="form-select" name="kriteria_id" required>
                                <option value="">Pilih Kriteria</option>
                                @foreach ($kriterias as $kriteria)
                                    <option value="{{ $kriteria->id }}">
                                        {{ $kriteria->kode }} - {{ $kriteria->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select class="form-select" name="kategori" required>
                                <option value="mata_pelajaran">Mata Pelajaran</option>
                                <option value="iq">IQ</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rentang Nilai</label>
                            <input type="text" class="form-control" name="rentang_nilai"
                                placeholder="Contoh: 85-100 atau <70" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nilai Minimum</label>
                            <input type="number" class="form-control" name="nilai_min"
                                placeholder="Kosongkan jika tidak ada">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nilai Maksimum</label>
                            <input type="number" class="form-control" name="nilai_max"
                                placeholder="Kosongkan jika tidak ada">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Bobot</label>
                            <input type="number" step="0.01" class="form-control" name="bobot" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ROC</label>
                            <input type="number" step="0.01" class="form-control" name="roc" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Keterangan</label>
                            <input type="text" class="form-control" name="keterangan"
                                placeholder="Contoh: Sangat Baik" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-auto">
        <p class="mb-0">© 2024 Kode. All rights reserved.</p>
    </footer>

    <!-- Bootstrap JS & SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/handler_sweet.js') }}"></script>
</body>

</html>