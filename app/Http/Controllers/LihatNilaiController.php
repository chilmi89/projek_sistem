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
            'nilai_wp' => $hasil_wp->c1_bagi ?? '-',
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
