<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hasil Per Alternatif</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1 class="mb-4">Hasil Per Alternatif (Rekap Vektor S)</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($hasilPerhitunganByAlternatif->isEmpty())
        <div class="alert alert-warning">Data hasil perhitungan belum tersedia.</div>
    @else
        <table class="table table-bordered table-striped">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Alternatif</th>
                    <th>C1</th>
                    <th>C2</th>
                    <th>C3</th>
                    <th>C4</th>
                    <th>C5</th>
                    <th>Hasil S</th>
                </tr>
            </thead>
            <tbody>
                {{-- Modifikasi dimulai di sini --}}
                @foreach($hasilPerhitunganByAlternatif as $item)
                    @if($loop->iteration > 36) 
                        @break
                    @endif
                    <tr>
                        {{-- Gunakan $loop->iteration untuk nomor urut 1-based --}}
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $item->nama_alternatif }}</td>
                        <td class="text-center">{{ number_format($item->c1, 4) }}</td>
                        <td class="text-center">{{ number_format($item->c2, 4) }}</td>
                        <td class="text-center">{{ number_format($item->c3, 4) }}</td>
                        <td class="text-center">{{ number_format($item->c4, 4) }}</td>
                        <td class="text-center">{{ number_format($item->c5, 4) }}</td>
                        <td class="text-center fw-bold">{{ number_format($item->hasil_s, 6) }}</td>
                    </tr>
                @endforeach
                {{-- Modifikasi selesai di sini --}}
            </tbody>
        </table>

    @endif

    <a href="{{ route('guru.hasil.index') }}" class="btn btn-secondary mt-3">Kembali ke Daftar Hasil</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>