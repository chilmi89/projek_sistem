<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manajemen Kuota Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">


</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="#">Kode</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">

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
    <div class="container py-4">
        <h2 class="mb-4">Manajemen Kuota Kelas</h2>
        {{-- Alert --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Tombol Tambah --}}
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">+ Tambah
            Kuota</button>

        {{-- Tabel --}}
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Jumlah Kelas</th>
                    <th>Kapasitas/Kelas</th>
                    <th>total_kapasitas</th>
                    <th>Nama Kriteria</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kuotaKelasData as $kuota)
                    <tr>
                        <td>{{ $kuota->kode }}</td>
                        <td>{{ $kuota->jumlah_kelas }}</td>
                        <td>{{ $kuota->kapasitas_per_kelas }}</td>
                        <td>{{ $kuota->total_kapasitas }}</td>

                        <td>{{ $kuota->nama_kriteria }}</td>

                        <td>
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                data-bs-target="#editModal_{{ $kuota->kode }}">Edit</button>

                            <form action="{{ route('guru.hasil.kuota-kelas.destroy', $kuota->kode) }}" method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Yakin ingin menghapus?')"
                                    class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Modal Tambah --}}
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('guru.hasil.kuota-kelas.store') }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLabel">Tambah Kuota Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Kode</label>
                        <input type="text" name="kode" class="form-control" required maxlength="10">
                    </div>
                    <div class="mb-3">
                        <label>Jumlah Kelas</label>
                        <input type="number" name="jumlah_kelas" class="form-control" required min="1"
                            max="10">
                    </div>
                    <div class="mb-3">
                        <label>Kapasitas per Kelas</label>
                        <input type="number" name="kapasitas_per_kelas" class="form-control" required min="1"
                            max="50">
                    </div>
                    <div class="mb-3">
                        <label>Nama Kriteria</label>
                        <input type="text" name="nama_kriteria" class="form-control" required maxlength="100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Simpan</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit per Data --}}
    @foreach ($kuotaKelasData as $kuota)
        <div class="modal fade" id="editModal_{{ $kuota->kode }}" tabindex="-1"
            aria-labelledby="editLabel_{{ $kuota->kode }}" aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('guru.hasil.kuota-kelas.update', $kuota->kode) }}" method="POST"
                    class="modal-content">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editLabel_{{ $kuota->kode }}">Edit Kuota: {{ $kuota->kode }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="jumlah_kelas_{{ $kuota->kode }}" class="form-label">Jumlah Kelas</label>
                            <input type="number" id="jumlah_kelas_{{ $kuota->kode }}" name="jumlah_kelas"
                                class="form-control jumlah-kelas" value="{{ $kuota->jumlah_kelas }}" required
                                min="1" max="10" data-kode="{{ $kuota->kode }}">
                        </div>

                        <div class="mb-3">
                            <label for="kapasitas_per_kelas_{{ $kuota->kode }}" class="form-label">Kapasitas per
                                Kelas</label>
                            <input type="number" id="kapasitas_per_kelas_{{ $kuota->kode }}"
                                name="kapasitas_per_kelas" class="form-control kapasitas-per-kelas"
                                value="{{ $kuota->kapasitas_per_kelas }}">
                        </div>

                        <div class="mb-3">
                            <label for="total_kapasitas_{{ $kuota->kode }}" class="form-label">Total
                                Kapasitas</label>
                            <input type="number" id="total_kapasitas_{{ $kuota->kode }}" name="total_kapasitas"
                                class="form-control total-kapasitas"
                                value="{{ $kuota->jumlah_kelas * $kuota->kapasitas_per_kelas }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="nama_kriteria_{{ $kuota->kode }}" class="form-label">Nama Kriteria</label>
                            <input type="text" id="nama_kriteria_{{ $kuota->kode }}" name="nama_kriteria"
                                class="form-control" value="{{ $kuota->nama_kriteria }}" required maxlength="100">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Simpan</button>
                        <button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
