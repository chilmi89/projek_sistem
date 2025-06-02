<?php

use App\Http\Controllers\MetodologiController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SiswaController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\KuotaKelasController;
use App\Http\Controllers\RegisNilaiSiswa;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\SubKriteriaController;
use App\Http\Controllers\DataSiswaController;
use App\Http\Controllers\LihatNilaiController;
use App\Http\Controllers\HasilController;

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

Route::get('/home', function () {
    if (auth()->user()->hasRole('guru')) {
        return redirect()->route('guru.dashboard');
    } elseif (auth()->user()->hasRole('siswa')) {
        return redirect()->route('siswa.dashboard');
    }

    // Solusi aman: logout + redirect
    \Auth::logout();
    return redirect()->route('login')->with('error', 'Akun Anda belum memiliki role.');
})->name('home')->middleware('auth');



// Middleware Auth untuk Semua Route
Route::middleware(['auth'])->group(function () {
    // Dashboard Guru
    Route::get('/guru/dashboard', [SubKriteriaController::class, 'index'])->name('guru.dashboard');

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

    // Siswa: Nilai Mata Pelajaran
    Route::middleware(['role:siswa'])->group(function () {
        Route::get('/siswa/nilai', [RegisNilaiSiswa::class, 'index'])->name('siswa.nilai');

        // Tampilkan halaman lihat nilai
        Route::get('/siswa/lihat-nilai', [LihatNilaiController::class, 'index'])->name('siswa.lihat-nilai');

        Route::get('/siswa/lihat-nilai', [LihatNilaiController::class, 'hitungRekomendasi'])->name('siswa.lihat-nilai');

        Route::post('/siswa/lihat-nilai', [LihatNilaiController::class, 'simpanMinat'])->name('siswa.simpan-minat');


    });

Route::middleware(['role:guru'])->group(function () {
    route::get('/guru/metodologi', [MetodologiController::class, 'index'])->name('guru.metodologi');
});


    // Guru: CRUD Kriteria
    Route::middleware(['role:guru'])->group(function () {
        Route::post('/kriteria', [KriteriaController::class, 'store'])->name('kriteria.store');
        Route::put('/kriteria/{kriteria}', [KriteriaController::class, 'update'])->name('kriteria.update');
        Route::delete('/kriteria/{kriteria}', [KriteriaController::class, 'destroy'])->name('kriteria.destroy');
    });

    // CRUD Sub Kriteria
    // Group route dengan prefix 'guru' dan middleware role guru
    Route::middleware(['role:guru'])->prefix('guru')->group(function () {
        // Sub Kriteria - Index
        Route::get('sub-kriteria', [SubKriteriaController::class, 'index'])->name('sub-kriteria.index');

        // Sub Kriteria - Create (menampilkan form)
        Route::get('sub-kriteria/create', [SubKriteriaController::class, 'create'])->name('sub-kriteria.create');

        // Sub Kriteria - Store (simpan data baru)
        Route::post('sub-kriteria', [SubKriteriaController::class, 'store'])->name('sub-kriteria.store');

        // Sub Kriteria - Show (detail)
        Route::get('sub-kriteria/{sub_kriteria}', [SubKriteriaController::class, 'show'])->name('sub-kriteria.show');

        // Sub Kriteria - Edit (form edit)
        Route::get('sub-kriteria/{sub_kriteria}/edit', [SubKriteriaController::class, 'edit'])->name('sub-kriteria.edit');

        // Sub Kriteria - Update (update data)
        Route::put('sub-kriteria/{sub_kriteria}', [SubKriteriaController::class, 'update'])->name('sub-kriteria.update');

        // Sub Kriteria - Delete
        Route::delete('sub-kriteria/{sub_kriteria}', [SubKriteriaController::class, 'destroy'])->name('sub-kriteria.destroy');

    });



    Route::get('/import', [DataSiswaController::class, 'index'])->name('import.index');
    Route::post('/import-excel', [DataSiswaController::class, 'importExcel'])->name('import.excel');



    Route::prefix('guru/hasil')->name('guru.hasil.')->group(function () {
        Route::get('/', [HasilController::class, 'index'])->name('index');
        Route::get('/create', [HasilController::class, 'create'])->name('create');
        Route::post('/', [HasilController::class, 'store'])->name('store');

        Route::post('/refresh-weighted-product', [HasilController::class, 'refreshHasilWeightedProduct'])->name('refreshWeightedProduct');
        Route::get('/json-weighted-product', [HasilController::class, 'getSavedWeightedProduct'])->name('jsonWeightedProduct');

        Route::delete('/guru/hasil/clear-weighted-product', [HasilController::class, 'clearWeightedProduct'])->name('clearWeightedProduct');



        // ⬅️ RUTE KHUSUS DITARUH SEBELUM {id}
        Route::get('/by-alternatif', [HasilController::class, 'showByAlternatif'])->name('by_alternatif');
        // Tambahkan route untuk batchCalculate:
        Route::get('/batch-calculate', [HasilController::class, 'batchCalculate'])->name('batchCalculate');

        Route::prefix('kuota-kelas')->name('kuota-kelas.')->group(function () {
            Route::get('/', [KuotaKelasController::class, 'index'])->name('index');
            Route::post('/', [KuotaKelasController::class, 'store'])->name('store');
            Route::put('/{kode}', [KuotaKelasController::class, 'update'])->name('update');
            Route::delete('/{kode}', [KuotaKelasController::class, 'destroy'])->name('destroy');
        });
    });




});




