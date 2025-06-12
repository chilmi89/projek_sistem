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
    
    @include('navbar.nav')

    <!-- Content Wrapper -->
    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center p-3">
                        <h5 class="card-title mb-0">Sub Kriteria</h5>
                        <div>
                            <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal"
                                data-bs-target="#addKriteriaModal">
                                <i class="fas fa-list"></i> Tambah Kriteria
                            </button>
                            <button class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#addSubKriteriaModal">
                                <i class="fas fa-layer-group"></i> Tambah Sub Kriteria
                            </button>
                            <a href="{{ route('sub-kriteria.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show m-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                        @endif
                        {{-- @if (session('error') || isset($error))
                            <div class="alert alert-danger alert-dismissible fade show m-3" role="alert">
                                {{ session('error') ?? $error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                    @endif --}}
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 20%;">id</th>
                                    <th style="width: 20%;">Kode Kriteria</th>
                                    <th style="width: 40%;">Sub Kriteria</th>
                                    <th style="width: 20%;">Nilai</th>
                                    <th style="width: 20%;">Nilai min</th>
                                    <th style="width: 20%;">Nilai max</th>
                                    <th style="width: 20%;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($subKriterias) && $subKriterias->count() > 0)
                                @foreach ($subKriterias as $item)
                                <tr>
                                    <td>{{ $item->id ?? 'N/A' }}</td>
                                    <td>{{ $item->kode_kriteria ?? 'N/A' }}</td>
                                    <td>{{ $item->sub_kriteria ?? 'N/A' }}</td>
                                    <td>{{ $item->nilai ?? 'N/A' }}</td>
                                    <td>{{ $item->nilai_min ?? 'N/A' }}</td>
                                    <td>{{ $item->nilai_max ?? 'N/A' }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <!-- Tambahkan data-bs-toggle="modal" supaya modal muncul -->
                                            <button class="btn btn-warning btn-sm btn-edit me-2"
                                                data-id="{{ $item->id }}"
                                                data-kode="{{ $item->kode_kriteria }}"
                                                data-sub="{{ $item->sub_kriteria }}"
                                                data-nilai="{{ $item->nilai }}"
                                                data-min="{{ $item->nilai_min }}"
                                                data-max="{{ $item->nilai_max }}" data-bs-toggle="modal"
                                                data-bs-target="#editModal">
                                                Edit
                                            </button>

                                            </form>
                                        </div>

                                    </td>

                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="6" class="text-center">Data Sub Kriteria tidak tersedia.
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Modal Tambah Kriteria -->
    <div class="modal fade" id="addKriteriaModal" tabindex="-1" aria-labelledby="addKriteriaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKriteriaModalLabel">Tambah Kriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('kriteria.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Dashboard Guru</label>
                            <input type="text" class="form-control" name="kode" placeholder="Contoh: C1"
                                required>
                            @error('kode')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" name="nama"
                                placeholder="Contoh: Matematika" required>
                            @error('nama')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis</label>
                            <select name="jenis" class="form-select" required>
                                <option value="">Pilih Jenis</option>
                                <option value="Benefit">Benefit</option>
                                <option value="Cost">Cost</option>
                            </select>
                            @error('jenis')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Sub Kriteria -->
    <div class="modal fade" id="addSubKriteriaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Sub Kriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('sub-kriteria.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Kode Kriteria</label>
                            <select name="kode_kriteria" class="form-select" required>
                                <option value="">Pilih...</option>
                                @if (isset($kriterias))
                                @foreach ($kriterias as $k)
                                <option value="{{ $k->kode ?? $k->id }}">
                                    {{ $k->kode ?? $k->id }} -
                                    {{ $k->nama ?? ($k->nama_kriteria ?? 'Unnamed') }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Sub Kriteria</label>
                            <input type="text" name="sub_kriteria" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Nilai (1-5)</label>
                            <input type="number" name="nilai" class="form-control" min="1" max="5"
                                required>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label>Nilai Min</label>
                                <input type="number" name="nilai_min" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label>Nilai Max</label>
                                <input type="number" name="nilai_max" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Sub Kriteria -->
    <!-- Modal Edit Sub Kriteria -->
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Sub Kriteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Kode Kriteria</label>
                            <select id="edit_kode" name="kode_kriteria" class="form-select" required>
                                <option value="">Pilih...</option>
                                @if (isset($kriterias))
                                @foreach ($kriterias as $k)
                                <option value="{{ $k->kode ?? $k->id }}">
                                    {{ $k->kode ?? $k->id }} -
                                    {{ $k->nama ?? ($k->nama_kriteria ?? 'Unnamed') }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Sub Kriteria</label>
                            <input type="text" id="edit_sub" name="sub_kriteria" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Nilai (1-5)</label>
                            <input type="number" id="edit_nilai" name="nilai" class="form-control"
                                min="1" max="5" required>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <label>Nilai Min</label>
                                <input type="number" id="edit_min" name="nilai_min" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label>Nilai Max</label>
                                <input type="number" id="edit_max" name="nilai_max" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light text-center py-3 mt-auto">
        <p class="mb-0">© 2025 WP - ROC. All rights reserved.</p>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-…"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/handler_sweet.js') }}"></script>
    <script>
        // Edit modal handler
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.dataset.id;
                document.getElementById('editForm').action = `/guru/sub-kriteria/${id}`;

                document.getElementById('edit_kode').value = this.dataset.kode;
                document.getElementById('edit_sub').value = this.dataset.sub;
                document.getElementById('edit_nilai').value = this.dataset.nilai;
                document.getElementById('edit_min').value = this.dataset.min;
                document.getElementById('edit_max').value = this.dataset.max;
            });
        });
    </script>
    </script>
    <script>
        // SweetAlert for success/error messages
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('
                success ') }}',
                timer: 3000,
                showConfirmButton: false
            });
            // @endif
            // @if (session('error') || isset($error))
            //     Swal.fire({
            //         icon: 'error',
            //         title: 'Gagal',
            //         text: '{{ session('error') ?? $error }}',
            //         timer: 3000,
            //         showConfirmButton: false
            //     });
            // @endif
        });
    </script>
</body>

</html>