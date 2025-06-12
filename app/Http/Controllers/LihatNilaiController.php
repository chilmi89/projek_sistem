<?php

namespace App\Http\Controllers;

use App\Models\KuotaKelas;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\MinatAwalSiswa;

use App\Models\HasilWeightProduct;

use Illuminate\Support\Facades\Auth;
use App\Models\Student;
class LihatNilaiController extends Controller
{
    public function index()
    {
        return view("siswa.LihatNilai", compact('kode_kelas', 'data_rekomendasi'));
    }

    // public function normalizeName($name)
    // {
    //     // Hilangkan spasi awal/akhir
    //     $name = trim($name);
    //     // Ubah spasi lebih dari 1 jadi 1 spasi
    //     $name = preg_replace('/\s+/', ' ', $name);
    //     // Lowercase
    //     return strtolower($name);
    // }
    public function hitungRekomendasi()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        if (!$user) {
            // Kalau belum login, redirect atau tampilkan error
            return redirect()->route('login')->with('error', 'Silahkan login terlebih dahulu.');
        }

        // Ambil data siswa yang terhubung dengan user login (asumsi siswa ada relasi user_id)
        $siswa = Siswa::where('user_id', $user->id)->first();

        if (!$siswa) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        // Normalisasi nama siswa
        $nama_siswa_bersih = strtolower(trim(preg_replace('/\s+/', ' ', $siswa->nama)));

        // Cari hasil WP berdasarkan nama siswa
        $hasil_wp = HasilWeightProduct::all()->first(function ($item) use ($nama_siswa_bersih) {
            $nama_wp_bersih = strtolower(trim(preg_replace('/\s+/', ' ', $item->nama_siswa)));
            return $nama_wp_bersih === $nama_siswa_bersih;
        });

        // Cari skor siswa dari tabel Student berdasarkan nama
        $student_skor = Student::all()->first(function ($item) use ($nama_siswa_bersih) {
            $nama_student_bersih = strtolower(trim(preg_replace('/\s+/', ' ', $item->nama)));
            return $nama_student_bersih === $nama_siswa_bersih;
        });

        $kode_kelas = KuotaKelas::all();
        $minat_awal = MinatAwalSiswa::where('user_id', auth()->id())->first();
        // Siapkan data untuk dikirim ke view

        
                // Ambil list alokasi_kelas dari DB
        $alokasiKelasList = HasilWeightProduct::pluck('alokasi_kelas');
        $kodeKelompok = [];

        // Mapping Cx-y => Kelompok y
        foreach ($alokasiKelasList as $item) {
            $parts = explode(' / ', $item);
            if (count($parts) >= 1) {
                $kode = trim($parts[0]); // Contoh: C1-3
                if (preg_match('/C\d+-(\d+)/', $kode, $matches)) {
                    $nomorKelompok = $matches[1];
                    $kodeKelompok[$kode] = 'Kelompok ' . $nomorKelompok;
                }
            }
        }

        // Pecah alokasi_kelas milik siswa
        $parts = $hasil_wp ? explode(' / ', $hasil_wp->alokasi_kelas) : [];
        $kode_awal = $parts[0] ?? null;  // C1-3
        $kelas_bagian = $parts[2] ?? null; // misal 'kelas 1'

        // Ambil nomor kelompok
        $nomorKelompok = null;
        if ($kode_awal && preg_match('/C\d+-(\d+)/', $kode_awal, $matches)) {
            $nomorKelompok = $matches[1];
        }

        // Ambil nomor kelas
        $nomorKelas = null;
        if ($kelas_bagian && preg_match('/(\d+)/', $kelas_bagian, $matches)) {
            $nomorKelas = $matches[1];
        }

        // Variabel terpisah
        $kelompok_text = $nomorKelompok !== null ? "Kelompok $nomorKelompok" : '-';
        $kelas_text = $nomorKelas !== null ? "kelas $nomorKelas" : '-';

        // Output gabungan dengan newline untuk text/plain atau textarea
        $kelas_rekomendasi = ($kelompok_text !== '-' ? $kelompok_text : '') 
                            . ($kelas_text !== '-' ? "\n" . $kelas_text : '');

        // Output gabungan dengan <br> untuk HTML view
        $kelas_rekomendasi_html = trim($kelompok_text) . ($kelas_text ? "<br>" . $kelas_text : '');

        $data_rekomendasi = [
            'siswa_id' => $siswa->id,
            'nama' => $siswa->nama,
            'minat_awal' => $minat_awal->kode_kelas ?? '-',
            'rekomendasi_kriteria' => $hasil_wp->rekomendasi_kriteria ?? '-',
            'matematika' => $student_skor->mtk_um ?? '-',
            'ipa' => $student_skor->ipa ?? '-',
            'ips' => $student_skor->ips ?? '-',
            'b_ing' => $student_skor->b_ing ?? '-',
            'tes_iq' => $student_skor->tes_iq ?? '-',
            'alokasi_kelas' => $hasil_wp->alokasi_kelas ?? '-',
            // 'kelas_rekomendasi' => $hasil_wp ? implode(' / ', array_slice(explode(' / ', $hasil_wp->alokasi_kelas), 1)) : '-',
            'nilai_wp' => $hasil_wp->c1_bagi ?? '-',
            'kelas_rekomendasi' => $kelas_rekomendasi,
            'kelas_rekomendasi_html' => $kelas_rekomendasi_html,
        ];




        $skor_siswa = $student_skor ? [
            'id_siswa' => $student_skor->id,
            'nama' => $student_skor->nama,
            'matematika' => $student_skor->mtk_um,
            'ipa' => $student_skor->ipa,
            'ips' => $student_skor->ips,
            'bahasa_inggris' => $student_skor->b_ing,
            'tes_iq' => $student_skor->tes_iq,
        ] : [];

        return view('siswa.LihatNilai', [
            'data_rekomendasi' => [$data_rekomendasi],
            'kode_kelas' => $kode_kelas,
            'skor_siswa' => $skor_siswa
        ]);
    }


    public function simpanMinat(Request $request)
    {
        $request->validate([
            'kelas' => 'required',
            'nama_kriteria' => 'required'
        ]);

        $sudahAda = MinatAwalSiswa::where('user_id', auth()->id())->exists();

        if ($sudahAda) {
            return redirect()->back()->with('error', 'Kamu sudah memilih minat awal.');
        }

        MinatAwalSiswa::create([
            'user_id' => auth()->id(),
            'kode_kelas' => $request->kelas,
            'nama_kriteria' => $request->nama_kriteria,
        ]);

        return redirect()->back()->with('success', 'Minat awal berhasil disimpan.');
    }


}
