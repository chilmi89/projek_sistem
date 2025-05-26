<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SiswaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\RegisNilaiSiswa;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\BobotKriteriaController;


// Auth Ronilutes
Route::get('/', function () {
    return redirect()->route('register'); // Jika belum login, ke register
});

// Auth Routes
Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Home Redirect dengan Role Check
Route::get('/home', function () {
    if (auth()->user()->hasRole('guru')) {
        return redirect()->route('guru.dashboard');
    } elseif (auth()->user()->hasRole('siswa')) {
        return redirect()->route('siswa.dashboard');
    }
    return redirect('/login')->with('error', 'Role tidak dikenali.');
})->name('home')->middleware('auth');


// Middleware Auth untuk Semua Route
Route::middleware(['auth'])->group(function () {
    // Dashboard Guru
    Route::middleware(['role:guru'])->group(function () {
        Route::get('/guru/dashboard', [GuruController::class, 'index'])->name('guru.dashboard');
    });

    // Dashboard Siswa
    Route::middleware(['role:siswa'])->group(function () {
        Route::get('/siswa/dashboard', function () {
            return view('siswa.dashboard');
        })->name('siswa.dashboard');
    });

    // Route CRUD Siswa (Hanya untuk Role Siswa)
    Route::middleware(['role:siswa'])->group(function () {
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
    });

    // Guru: CRUD Mata Pelajaran
    Route::middleware(['role:guru'])->prefix('guru/mata-pelajaran')->group(function () {
        Route::get('/', [GuruController::class, 'index'])->name('guru.mata-pelajaran.index');
        Route::post('/', [GuruController::class, 'store'])->name('guru.mata-pelajaran.store');
        Route::put('/{id}', [GuruController::class, 'update'])->name('guru.mata-pelajaran.update');
        Route::delete('/{id}', [GuruController::class, 'destroy'])->name('guru.mata-pelajaran.destroy');
    });

    // Siswa: Nilai Mata Pelajaran
    Route::middleware(['role:siswa'])->group(function () {
        Route::get('/siswa/nilai', [RegisNilaiSiswa::class, 'index'])->name('siswa.nilai');
        Route::post('/siswa/nilai/store', [RegisNilaiSiswa::class, 'store'])->name('siswa.nilai.store');
    });

    // Guru: CRUD Kriteria
    Route::middleware(['role:guru'])->group(function () {
        Route::post('/kriteria', [KriteriaController::class, 'store'])->name('kriteria.store');
        Route::put('/kriteria/{kriteria}', [KriteriaController::class, 'update'])->name('kriteria.update');
        Route::delete('/kriteria/{kriteria}', [KriteriaController::class, 'destroy'])->name('kriteria.destroy');
    });

    // CRUD Sub Kriteria
    Route::resource('bobot-kriteria', BobotKriteriaController::class);
});