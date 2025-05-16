<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> {{-- Tambahkan SweetAlert --}}
    <title>Register</title>
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body class="bg-dark">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                {{-- Alert sukses jika registrasi berhasil --}}
                {{-- Menampilkan pesan sukses jika registrasi berhasil --}}
                @if (session('success'))
                <script>
                    Swal.fire({
                        title: "Registrasi Berhasil!",
                        text: "Silakan login untuk melanjutkan.",
                        icon: "success",
                        confirmButtonText: "OK"
                    }).then(() => {
                        window.location.href = "{{ route('login') }}";
                    });
                </script>
                @endif

                {{-- Menampilkan error jika ada kesalahan input --}}
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form class="p-4 p-md-5 border rounded-3 bg-light" method="POST" action="{{ route('register') }}">
                    @csrf
                    <h3 class="text-center mb-4">Registrasi</h3>
                    <hr style="border: 2px solid #0e0f10; border-radius: 0.375rem;">

                    {{-- Nama --}}
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="name" required value="{{ old('name') }}">
                        <label for="name">Masukkan Nama</label>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="name@example.com" required value="{{ old('email') }}">
                        <label for="email">Masukkan Email</label>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Password" required>
                        <label for="password">Masukkan Password</label>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    {{-- NISN --}}
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control @error('nisn') is-invalid @enderror" id="nisn" name="nisn" placeholder="Masukkan NISN" required value="{{ old('nisn') }}">
                        <label for="nisn">Masukkan NISN Anda</label>
                        @error('nisn')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
            
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('login') }}" class="btn btn-secondary">Login</a>
                        <button type="submit" class="btn btn-primary">Register</button>
                    </div>

                    <hr class="my-4">
                    <small class="text-muted">By clicking Sign in, you agree to the terms of use.</small>
                </form>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>