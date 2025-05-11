<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <form class="card p-4 shadow-sm">
            <div class="form-group mb-3">
                <label for="mata_pelajaran" class="form-label">Mata Pelajaran</label>
                <select name="mata_pelajaran" id="mata_pelajaran" class="form-select">
                    <option value="">-- Pilih Salah Satu --</option>
                    {{-- @foreach ($mataPelajaran as $item)
                        <option value="{{ $item->id_mapel }}">{{ $item->nama_mapel }}</option>
                    @endforeach --}}
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="nilai" class="form-label">Nilai</label>
                <input type="number" name="nilai" id="nilai" class="form-control" min="0" max="100" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

 