<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />

    <title>Login Page</title>
    <style>
        body, html {
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
                <form class="p-4 p-md-5 border rounded-3 bg-light border" method="POST" action="{{ route('login') }}">
                    @csrf
                    <h3 class="text-center mb-4">Halaman Login</h3>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="form-floating mb-3" style="border: 2px solid #007bff; border-radius: 0.375rem;">
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                            id="floatingInput" name="email" value="{{ old('email') }}"
                            placeholder="name@example.com" style="border-radius: 0.375rem;" required />
                        <label for="floatingInput">Masukkan Nama Email</label>
                    </div>

                    <div class="form-floating mb-3" style="border: 2px solid #007bff; border-radius: 0.375rem;">
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            id="floatingPassword" name="password" placeholder="Password"
                            style="border-radius: 0.375rem;" required />
                        <label for="floatingPassword">Masukkan Password</label>
                    </div>

                    <div class="checkbox mb-3">
                        <label>
                            <input type="checkbox" name="remember" value="remember-me" /> Remember me
                        </label>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <a href="{{ route('register') }}" class="btn btn-warning">Register</a>
                        <button type="submit" class="btn btn-primary">Sign in</button>
                    </div>

                    <hr class="my-4" />
                    <small class="text-muted">By clicking Sign in, you agree to the terms of use.</small>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>
</html>
