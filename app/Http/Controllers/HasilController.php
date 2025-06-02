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

        // Jika data penting kosong, return data default agar tidak error
        if ($kriteriaList->isEmpty() || $hasilBobots->isEmpty() || $kuotaKelasFromDB->isEmpty()) {
            return [
                'hasilBobots' => $hasilBobots,
                'daftarKelas' => [],
                'konfigKelas' => [],
                'kuotaTerisi' => [],
                'kuotaKelasData' => $kuotaKelasFromDB,
                'kuotaTotal' => [],
                'pesan' => 'Data kriteria, hasil bobot, atau kuota kelas belum lengkap.',
                'status_simpan' => false,
            ];
        }

        // Validasi bobot_roc pada kriteria
        $requiredKriteria = ['C1', 'C2', 'C3', 'C4', 'C5'];
        foreach ($requiredKriteria as $kode) {
            if (!isset($kriteriaList[$kode]) || !isset($kriteriaList[$kode]->bobot_roc) || $kriteriaList[$kode]->bobot_roc === null) {
                return [
                    'hasilBobots' => $hasilBobots,
                    'daftarKelas' => [],
                    'konfigKelas' => [],
                    'kuotaTerisi' => [],
                    'kuotaKelasData' => $kuotaKelasFromDB,
                    'kuotaTotal' => [],
                    'pesan' => "Bobot ROC untuk kriteria $kode belum diatur. Silakan periksa konfigurasi kriteria.",
                    'status_simpan' => false,
                ];
            }
        }

        // Inisialisasi konfigurasi kelas dan kuota total serta kuota terisi
        $konfigKelas = [];
        $kuotaTotal = [];
        $kuotaTerisi = [];
        $daftarKelas = [];

        // Buat konfigurasi kelas, total kuota, dan daftar kelas per kriteria
        foreach ($kuotaKelasFromDB as $kode => $kuota) {
            $konfigKelas[$kode] = [
                'jumlah_kelas' => $kuota->jumlah_kelas,
                'kapasitas' => $kuota->kapasitas_per_kelas,
            ];
            $kuotaTotal[$kode] = $kuota->jumlah_kelas * $kuota->kapasitas_per_kelas;
            $kuotaTerisi[$kode] = 0;

            for ($i = 1; $i <= $kuota->jumlah_kelas; $i++) {
                $kelasKode = "$kode-$i";
                $daftarKelas[$kelasKode] = [
                    'kode' => $kelasKode,
                    'kapasitas' => $kuota->kapasitas_per_kelas,
                    'terisi' => 0,
                    'siswa' => [],
                ];
            }
        }

        // Validasi apakah ada kelas untuk kriteria yang direkomendasikan
        $availableKriteria = array_keys($konfigKelas);
        if (!in_array('C1', $availableKriteria)) {
            return [
                'hasilBobots' => $hasilBobots,
                'daftarKelas' => [],
                'konfigKelas' => [],
                'kuotaTerisi' => [],
                'kuotaKelasData' => $kuotaKelasFromDB,
                'kuotaTotal' => [],
                'pesan' => "Tidak ada kelas untuk kriteria C1. Silakan tambahkan konfigurasi kuota kelas untuk C1.",
                'status_simpan' => false,
            ];
        }

        // PERHITUNGAN WEIGHTED PRODUCT (WP)
        foreach ($hasilBobots as $hasil) {
            try {
                // Hitung perpangkatan (c^bobot) untuk setiap kriteria
                $hasil->c1_pow = pow($hasil->c1, $kriteriaList['C1']->bobot_roc);
                $hasil->c2_pow = pow($hasil->c2, $kriteriaList['C2']->bobot_roc);
                $hasil->c3_pow = pow($hasil->c3, $kriteriaList['C3']->bobot_roc);
                $hasil->c4_pow = pow($hasil->c4, $kriteriaList['C4']->bobot_roc);
                $hasil->c5_pow = pow($hasil->c5, $kriteriaList['C5']->bobot_roc);

                // Hitung nilai S (perkalian semua nilai perpangkatan) untuk masing-masing siswa
                $hasil->nilai_s = $hasil->c1_pow * $hasil->c2_pow * $hasil->c3_pow * $hasil->c4_pow * $hasil->c5_pow;

                // Handle jika nilai_s adalah 0 atau tidak valid
                if ($hasil->nilai_s == 0 || !is_finite($hasil->nilai_s)) {
                    $hasil->nilai_s = 0.0001; // Set nilai minimum
                }

                // Hitung nilai bagi hasil (C^bobot / nilai_s) untuk masing-masing siswa
                $hasil->c1_bagi = $hasil->c1_pow / $hasil->nilai_s;
                $hasil->c2_bagi = $hasil->c2_pow / $hasil->nilai_s;
                $hasil->c3_bagi = $hasil->c3_pow / $hasil->nilai_s;
                $hasil->c4_bagi = $hasil->c4_pow / $hasil->nilai_s;
                $hasil->c5_bagi = $hasil->c5_pow / $hasil->nilai_s;

            } catch (\Exception $e) {
                // Log error untuk debugging
                \Log::error("Error calculating WP for student {$hasil->nama}: " . $e->getMessage());

                // Set nilai default yang lebih realistis
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

        // PEMBAGIAN KELAS BERDASARKAN NILAI HASIL BAGI (C/S) TERTINGGI

        // Urutkan SEMUA siswa berdasarkan nilai_bagi_tertinggi (descending)
        $siswaUrut = $hasilBobots->sortByDesc(function ($siswa) {
            return $siswa->nilai_bagi_tertinggi;
        })->values();

        // Alokasikan siswa berdasarkan nilai_bagi_tertinggi dan rekomendasi kriteria
        foreach ($siswaUrut as $siswa) {
            $alokasiBerhasil = false;
            $rekomendasiKriteria = $siswa->rekomendasi_kriteria ?? 'C1';

            // Validasi apakah rekomendasi kriteria ada di kuota kelas
            if (!isset($konfigKelas[$rekomendasiKriteria])) {
                \Log::warning("Kriteria {$rekomendasiKriteria} tidak ditemukan di kuota kelas untuk siswa {$siswa->nama}.");
                continue;
            }

            // Coba alokasikan ke kelas sesuai rekomendasi kriteria terlebih dahulu
            foreach ($daftarKelas as $kodeKelas => $kelas) {
                if (str_starts_with($kodeKelas, $rekomendasiKriteria) && $kelas['terisi'] < $kelas['kapasitas']) {
                    $daftarKelas[$kodeKelas]['siswa'][] = $siswa->nama;
                    $daftarKelas[$kodeKelas]['terisi']++;
                    $kuotaTerisi[$rekomendasiKriteria] = ($kuotaTerisi[$rekomendasiKriteria] ?? 0) + 1;

                    $kriteria = explode('-', $kodeKelas)[0]; // Ambil C1, C2, dst
                    $namaKriteria = $kuotaKelasFromDB[$kriteria]->nama_kriteria ?? '';

                    preg_match('/\(\s*(.*?)\s*\)/', $namaKriteria, $match);
                    $labelKriteria = $match[1] ?? '';

                    // Format alokasi_kelas sebagai "C1-1 / Kelompok 1 / Kelas 1"
                    $kelompokNumber = explode('-', $kodeKelas)[1];
                    $formattedKelas = "$kodeKelas / $kriteria ($labelKriteria) / Kelas $kelompokNumber";

                    $siswa->alokasi_kelas = $formattedKelas;
                    $siswa->status_alokasi = 'Sesuai';

                    $alokasiBerhasil = true;
                    break;
                }
            }

            // Jika tidak berhasil, coba alokasikan ke kelas lain dengan kapasitas tersedia
            if (!$alokasiBerhasil) {
                foreach ($daftarKelas as $kodeKelas => $kelas) {
                    if ($kelas['terisi'] < $kelas['kapasitas']) {
                        $kriteria = explode('-', $kodeKelas)[0]; // Ambil C1, C2, dst
                        $namaKriteria = $kuotaKelasFromDB[$kriteria]->nama_kriteria ?? '';

                        // Ambil isi dalam tanda kurung, contoh: ( MIPA ) → MIPA
                        preg_match('/\(\s*(.*?)\s*\)/', $namaKriteria, $match);
                        $labelKriteria = $match[1] ?? '';

                        $kelompokNumber = explode('-', $kodeKelas)[1];
                        $formattedKelas = "$kodeKelas / $kriteria ($labelKriteria) / Kelas $kelompokNumber";
                        $daftarKelas[$kodeKelas]['siswa'][] = $siswa->nama;
                        $daftarKelas[$kodeKelas]['terisi']++;
                        $kuotaTerisi[$kriteria] = ($kuotaTerisi[$kriteria] ?? 0) + 1;

                        // Format alokasi_kelas
                        $kelompokNumber = explode('-', $kodeKelas)[1];
                        $formattedKelas = "$kodeKelas / $kriteria ($labelKriteria) / Kelas $kelompokNumber";
                        $siswa->alokasi_kelas = $formattedKelas;
                        $siswa->status_alokasi = 'Dialihkan';

                        $alokasiBerhasil = true;
                        break;
                    }
                }
            }

            // Jika masih belum berhasil, paksa alokasi ke kelas dengan sisa kapasitas terbanyak
            if (!$alokasiBerhasil) {
                $kelasTersedia = collect($daftarKelas)
                    ->sortByDesc(function ($kelas) {
                        return $kelas['kapasitas'] - $kelas['terisi'];
                    })
                    ->first();

                if ($kelasTersedia) {
                    $kodeKelas = $kelasTersedia['kode'];
                    $kriteria = explode('-', $kodeKelas)[0]; // Ambil C1, C2, dst
                    $namaKriteria = $kuotaKelasFromDB[$kriteria]->nama_kriteria ?? '';
                    $daftarKelas[$kodeKelas]['siswa'][] = $siswa->nama;
                    $daftarKelas[$kodeKelas]['terisi']++;
                    $kuotaTerisi[$kriteria] = ($kuotaTerisi[$kriteria] ?? 0) + 1;

                    preg_match('/\(\s*(.*?)\s*\)/', $namaKriteria, $match);
                    $labelKriteria = $match[1] ?? '';

                    $kelompokNumber = explode('-', $kodeKelas)[1];
                    $formattedKelas = "$kodeKelas / $kriteria ($labelKriteria) / Kelas $kelompokNumber";

                    $siswa->alokasi_kelas = $formattedKelas;
                    $siswa->status_alokasi = 'dialihkan';
                } else {
                    // Fallback terakhir
                    $kelasFallback = 'C1-1';
                    if (!isset($daftarKelas[$kelasFallback])) {
                        $daftarKelas[$kelasFallback] = [
                            'kode' => $kelasFallback,
                            'kapasitas' => 0,
                            'terisi' => 0,
                            'siswa' => [],
                        ];
                    }
                    $daftarKelas[$kelasFallback]['siswa'][] = $siswa->nama;
                    $daftarKelas[$kelasFallback]['terisi']++;
                    $kuotaTerisi['C1'] = ($kuotaTerisi['C1'] ?? 0) + 1;

                    $formattedKelas = "C1-1 / Kelompok 1 / Kelas 1";
                    $siswa->alokasi_kelas = $formattedKelas;
                    $siswa->status_alokasi = 'Over';
                }
            }
        }

        // Urutkan hasil berdasarkan nilai_bagi_tertinggi untuk tampilan
        $hasilAkhir = $siswaUrut;

        return [
            'hasilBobots' => $hasilAkhir,
            'daftarKelas' => $daftarKelas,
            'konfigKelas' => $konfigKelas,
            'kuotaTerisi' => $kuotaTerisi,
            'kuotaKelasData' => $kuotaKelasFromDB,
            'kuotaTotal' => $kuotaTotal,
            'pesan' => null,
            'status_simpan' => false, // Belum disimpan
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
