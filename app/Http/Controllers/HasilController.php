<?php

namespace App\Http\Controllers;

use App\Models\HasilBobot;
use App\Models\Kriteria;
use App\Models\KuotaKelas;
use App\Models\HasilWeightProduct;
use App\Models\AlokasiKelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HasilController extends Controller
{
    public function index()
    {
        // Ambil data kuota kelas dari database
        $kuotaKelasFromDB = KuotaKelas::all();

        // Ambil data hasil bobot dari database
        $hasilBobots = HasilBobot::all();

        // Cek jika kuota kelas kosong, kirim pesan dan data kosong ke view
        if ($kuotaKelasFromDB->isEmpty()) {
            return view('guru.hasil', [
                'kuotaKelasData' => [],
                'pesan' => 'Silakan tambahkan konfigurasi kuota kelas terlebih dahulu.',
                'hasilBobots' => [],
                'daftarKelas' => [],
                'konfigKelas' => [],
                'kuotaTerisi' => [],
                'kuotaTotal' => [],
            ]);
        }

        // Cek jika hasil bobot kosong, kirim pesan dan data kosong ke view
        if ($hasilBobots->isEmpty()) {
            return view('guru.hasil', [
                'kuotaKelasData' => $kuotaKelasFromDB,
                'pesan' => 'Data hasil bobot masih kosong. Silakan input data terlebih dahulu.',
                'hasilBobots' => [],
                'daftarKelas' => [],
                'konfigKelas' => [],
                'kuotaTerisi' => [],
                'kuotaTotal' => [],
            ]);
        }

        // Jika ada data kuota kelas dan hasil bobot, proses pembagian kelas
        $data = $this->prosesPembagianKelas();

        // Kirim data lengkap ke view
        return view('guru.hasil', $data);
    }


    private function prosesPembagianKelas()
{
    // Ambil semua data kriteria, hasil bobot, dan kuota kelas dari DB
    $kriteriaList = Kriteria::all()->keyBy('kode');
    $hasilBobots = HasilBobot::all();
    $kuotaKelasFromDB = KuotaKelas::all()->keyBy('kode');

    // Cek jika ada data penting yang kosong
    if ($kriteriaList->isEmpty() || $hasilBobots->isEmpty() || $kuotaKelasFromDB->isEmpty()) {
        return [
            'hasilBobots' => [],
            'daftarKelas' => [],
            'konfigKelas' => [],
            'kuotaTerisi' => [],
            'kuotaKelasData' => $kuotaKelasFromDB,
            'kuotaTotal' => [],
            'pesan' => 'Data kriteria, hasil bobot, atau kuota kelas belum lengkap.',
            'status_simpan' => false, // Status simpan diatur false jika ada data kosong
        ];
    }

    // Inisialisasi konfigurasi kelas, kuota total, dan daftar kelas
    $konfigKelas = [];
    $kuotaTotal = [];
    $kuotaTerisi = [];
    $daftarKelas = [];
    $kelasCounter = 1;

    // Membuat konfigurasi kelas, total kuota, dan daftar kelas per kriteria
    foreach ($kuotaKelasFromDB as $kode => $kuota) {
        $konfigKelas[$kode] = [
            'jumlah_kelas' => $kuota->jumlah_kelas,
            'kapasitas' => $kuota->kapasitas_per_kelas,
        ];
        $kuotaTotal[$kode] = $kuota->jumlah_kelas * $kuota->kapasitas_per_kelas;
        $kuotaTerisi[$kode] = 0;

        // Tentukan urutan kelas berdasarkan kriteria
        for ($i = 1; $i <= $kuota->jumlah_kelas; $i++) {
            $kelasKode = "$kode (MIPA) / Kelas $kelasCounter";
            $daftarKelas[$kelasKode] = [
                'kode' => $kelasKode,
                'kapasitas' => $kuota->kapasitas_per_kelas,
                'terisi' => 0,
                'siswa' => [],
            ];
            $kelasCounter++; // Mengatur urutan kelas sesuai kuota
        }
    }

    // Proses WP untuk menentukan rekomendasi
    foreach ($hasilBobots as $hasil) {
        try {
            // Hitung perpangkatan untuk setiap kriteria
            $hasil->c1_pow = pow($hasil->c1, $kriteriaList['C1']->bobot_roc);
            $hasil->c2_pow = pow($hasil->c2, $kriteriaList['C2']->bobot_roc);
            $hasil->c3_pow = pow($hasil->c3, $kriteriaList['C3']->bobot_roc);
            $hasil->c4_pow = pow($hasil->c4, $kriteriaList['C4']->bobot_roc);
            $hasil->c5_pow = pow($hasil->c5, $kriteriaList['C5']->bobot_roc);

            // Hitung nilai S
            $hasil->nilai_s = $hasil->c1_pow * $hasil->c2_pow * $hasil->c3_pow * $hasil->c4_pow * $hasil->c5_pow;

            // Pastikan nilai_s valid
            if ($hasil->nilai_s == 0 || !is_finite($hasil->nilai_s)) {
                $hasil->nilai_s = 0.0001;
            }

            // Bagi hasil per kriteria
            $hasil->c1_bagi = $hasil->c1_pow / $hasil->nilai_s;
            $hasil->c2_bagi = $hasil->c2_pow / $hasil->nilai_s;
            $hasil->c3_bagi = $hasil->c3_pow / $hasil->nilai_s;
            $hasil->c4_bagi = $hasil->c4_pow / $hasil->nilai_s;
            $hasil->c5_bagi = $hasil->c5_pow / $hasil->nilai_s;
        } catch (\Exception $e) {
            \Log::error("Error calculating WP for student {$hasil->nama}: " . $e->getMessage());
            $hasil->c1_pow = $hasil->c2_pow = $hasil->c3_pow = $hasil->c4_pow = $hasil->c5_pow = 1.0;
            $hasil->nilai_s = 1.0;
            $hasil->c1_bagi = $hasil->c2_bagi = $hasil->c3_bagi = $hasil->c4_bagi = $hasil->c5_bagi = 0.2;
        }
    }

    // Tentukan rekomendasi berdasarkan C/S tertinggi
    foreach ($hasilBobots as $hasil) {
        $bagiArray = [
            'C1' => $hasil->c1_bagi,
            'C2' => $hasil->c2_bagi,
            'C3' => $hasil->c3_bagi,
            'C4' => $hasil->c4_bagi,
            'C5' => $hasil->c5_bagi,
        ];

        $maxKey = array_search(max($bagiArray), $bagiArray);
        $hasil->rekomendasi_kriteria = $maxKey;
        $hasil->nilai_bagi_tertinggi = max($bagiArray);
    }

    // Pembagian kelas berdasarkan nilai_bagi_tertinggi
    $siswaUrut = $hasilBobots->sortByDesc(function ($siswa) {
        return $siswa->nilai_bagi_tertinggi;
    })->values();

    // Alokasikan siswa ke kelas sesuai rekomendasi kriteria
    foreach ($siswaUrut as $siswa) {
        $alokasiBerhasil = false;
        $rekomendasiKriteria = $siswa->rekomendasi_kriteria ?? 'C1';

        foreach ($daftarKelas as $kodeKelas => $kelas) {
            // Periksa jika kelas sesuai dengan rekomendasi dan ada ruang
            if (str_starts_with($kodeKelas, $rekomendasiKriteria) && $kelas['terisi'] < $kelas['kapasitas']) {
                $daftarKelas[$kodeKelas]['siswa'][] = $siswa->nama;
                $daftarKelas[$kodeKelas]['terisi']++;
                $siswa->alokasi_kelas = $kodeKelas;
                $siswa->status_alokasi = 'Sesuai'; // Update status alokasi menjadi 'Sesuai'
                $alokasiBerhasil = true;
                break;
            }
        }

        // Jika alokasi tidak sesuai dengan rekomendasi, cari kelas yang masih tersedia
        if (!$alokasiBerhasil) {
            foreach ($daftarKelas as $kodeKelas => $kelas) {
                if ($kelas['terisi'] < $kelas['kapasitas']) {
                    $daftarKelas[$kodeKelas]['siswa'][] = $siswa->nama;
                    $daftarKelas[$kodeKelas]['terisi']++;
                    $siswa->alokasi_kelas = $kodeKelas;
                    $siswa->status_alokasi = 'Dialihkan'; // Update status alokasi menjadi 'Dialihkan'
                    break;
                }
            }
        }

        // Jika tidak ada kelas yang bisa diisi, alokasikan kelas paksa
        if (!$alokasiBerhasil && !isset($siswa->alokasi_kelas)) {
            foreach ($daftarKelas as $kodeKelas => $kelas) {
                if ($kelas['terisi'] < $kelas['kapasitas']) {
                    $daftarKelas[$kodeKelas]['siswa'][] = $siswa->nama;
                    $daftarKelas[$kodeKelas]['terisi']++;
                    $siswa->alokasi_kelas = $kodeKelas;
                    $siswa->status_alokasi = 'Paksa'; // Update status alokasi menjadi 'Paksa'
                    break;
                }
            }
        }
    }

    // Menentukan status simpan berdasarkan apakah alokasi kelas berhasil
    $statusSimpan = true; // Jika semua siswa berhasil dialokasikan
    foreach ($daftarKelas as $kelas) {
        if (count($kelas['siswa']) < 1) { // Cek jika ada kelas yang kosong
            $statusSimpan = false;
            break;
        }
    }

    
    \Log::info("Status simpan: " . ($statusSimpan ? 'Berhasil' : 'Gagal'));

    // // Pesan untuk hasil pembagian
    $pesan = $statusSimpan ? "Proses pembagian kelas selesai dengan sukses." : "Beberapa kelas tidak terisi, perlu pemeriksaan lebih lanjut.";

    return [
        'hasilBobots' => $siswaUrut,
        'daftarKelas' => $daftarKelas,
        'konfigKelas' => $konfigKelas,
        'kuotaTerisi' => $kuotaTerisi,
        'kuotaKelasData' => $kuotaKelasFromDB,
        'kuotaTotal' => $kuotaTotal,
        'pesan' => $pesan,
        'status_simpan' => $statusSimpan, // Status simpan diatur true jika pembagian berhasil
    ];
}

    private function simpanHasilWeightedProduct($hasilBobots)
    {
        try {
            // // Hapus data lama sebelum menyimpan yang baru
            // HasilWeightProduct::truncate();

            foreach ($hasilBobots as $hasil) {
                HasilWeightProduct::create([
                    'nama_siswa' => $hasil->nama,
                    'c1' => $hasil->c1,
                    'c2' => $hasil->c2,
                    'c3' => $hasil->c3,
                    'c4' => $hasil->c4,
                    'c5' => $hasil->c5,
                    'c1_pow' => $hasil->c1_pow ?? 0,
                    'c2_pow' => $hasil->c2_pow ?? 0,
                    'c3_pow' => $hasil->c3_pow ?? 0,
                    'c4_pow' => $hasil->c4_pow ?? 0,
                    'c5_pow' => $hasil->c5_pow ?? 0,
                    'nilai_s' => $hasil->nilai_s ?? 0,
                    'c1_bagi' => $hasil->c1_bagi ?? 0,
                    'c2_bagi' => $hasil->c2_bagi ?? 0,
                    'c3_bagi' => $hasil->c3_bagi ?? 0,
                    'c4_bagi' => $hasil->c4_bagi ?? 0,
                    'c5_bagi' => $hasil->c5_bagi ?? 0,
                    'rekomendasi_kriteria' => $hasil->rekomendasi_kriteria ?? 'C1',
                    'nilai_bagi_tertinggi' => $hasil->nilai_bagi_tertinggi ?? 0,
                    'alokasi_kelas' => $hasil->alokasi_kelas ?? null,
                    'status_alokasi' => $hasil->status_alokasi ?? 'Belum Dialokasi',
                ]);
            }

            return true;
        } catch (\Exception $e) {
            \Log::error("Error menyimpan hasil Weighted Product: " . $e->getMessage());
            return false;
        }
    }


    // Kamu bisa tambah fungsi CRUD kuota kelas di sini jika perlu...

    public function refreshHasilWeightedProduct()
    {
        try {
            // Cegah simpan ulang jika data sudah ada
            if (HasilWeightProduct::exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data sudah tersimpan sebelumnya. Silakan kosongkan data terlebih dahulu untuk menyimpan ulang.'
                ]);
            }

            $hasil = $this->prosesPembagianKelas();

            if (!empty($hasil['hasilBobots'])) {
                $berhasilSimpan = $this->simpanHasilWeightedProduct($hasil['hasilBobots']);

                if ($berhasilSimpan) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Data hasil Weighted Product berhasil disimpan ke database!',
                        'data' => $hasil,
                        'total_data' => count($hasil['hasilBobots'])
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal menyimpan data ke database.',
                        'data' => $hasil
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data untuk disimpan.',
                    'data' => $hasil
                ]);
            }

        } catch (\Exception $e) {
            \Log::error("Error refresh hasil weighted product: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

    public function clearWeightedProduct()
    {
        try {
            HasilWeightProduct::truncate(); // Mengosongkan semua data di tabel
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dikosongkan.'
            ]);
        } catch (\Exception $e) {
            \Log::error("Gagal mengosongkan data: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengosongkan data.'
            ]);
        }
    }

}
