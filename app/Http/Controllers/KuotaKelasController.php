<?php

namespace App\Http\Controllers;

use App\Models\KuotaKelas;
use Illuminate\Http\Request;

class KuotaKelasController extends Controller
{
    public function index()
    {
        $kuotaKelasData = KuotaKelas::all();

        $daftarKelas = [];

        foreach ($kuotaKelasData as $kuota) {
            for ($i = 1; $i <= $kuota->jumlah_kelas; $i++) {
                $kodeKelas = $kuota->kode . '-' . $i;
                $daftarKelas[$kodeKelas] = [
                    'kapasitas' => $kuota->kapasitas_per_kelas,
                    'terisi' => 0,
                    'siswa' => [],
                ];
            }
        }

        return view('guru.kuota_kelas_index', compact('kuotaKelasData', 'daftarKelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|unique:kuota_kelas,kode|max:10',
            'jumlah_kelas' => 'required|integer|min:1|max:10',
            'kapasitas_per_kelas' => 'required|integer|min:1|max:50',
            'nama_kriteria' => 'required|string|max:100',
        ]);

        $data = $request->only('kode', 'jumlah_kelas', 'kapasitas_per_kelas', 'nama_kriteria');
        $data['total_kapasitas'] = $data['jumlah_kelas'] * $data['kapasitas_per_kelas'];

        KuotaKelas::create($data);

        return redirect()->back()->with('success', 'Kuota kelas berhasil ditambahkan.');
    }


    public function update(Request $request, $kode)
    {
        $request->validate([
            'jumlah_kelas' => 'required|integer|min:1|max:10',
            'kapasitas_per_kelas' => 'nullable|integer|min:1|max:50',
            'nama_kriteria' => 'required|string|max:100',
        ]);

        $kuota = KuotaKelas::where('kode', $kode)->first();

        if (!$kuota) {
            return redirect()->back()->with('error', 'Kuota kelas tidak ditemukan.');
        }

        // Ambil nilai dari form
        $jumlah_kelas_baru = $request->jumlah_kelas;
        $kapasitas_per_kelas_baru = $request->kapasitas_per_kelas;

        // Hitung total kapasitas awal (atau gunakan nilai default jika belum ada)
        $total_kapasitas = $kuota->total_kapasitas ?? ($kuota->jumlah_kelas * $kuota->kapasitas_per_kelas);

        // Jika user mengubah jumlah_kelas, hitung ulang kapasitas per kelas
        if ($jumlah_kelas_baru != $kuota->jumlah_kelas) {
            $kapasitas_per_kelas_baru = floor($total_kapasitas / $jumlah_kelas_baru);
        }
        // Jika kapasitas_per_kelas diubah, hitung ulang total_kapasitas
        elseif ($kapasitas_per_kelas_baru && $kapasitas_per_kelas_baru != $kuota->kapasitas_per_kelas) {
            $total_kapasitas = $jumlah_kelas_baru * $kapasitas_per_kelas_baru;
        } else {
            // Jika tidak ada perubahan, gunakan nilai lama
            $kapasitas_per_kelas_baru = $kuota->kapasitas_per_kelas;
        }

        // Simpan perubahan
        $kuota->jumlah_kelas = $jumlah_kelas_baru;
        $kuota->kapasitas_per_kelas = $kapasitas_per_kelas_baru;
        $kuota->total_kapasitas = $total_kapasitas;
        $kuota->nama_kriteria = $request->nama_kriteria;
        $kuota->save();

        return redirect()->back()->with('success', 'Kuota kelas berhasil diperbarui dengan perhitungan otomatis.');
    }




    public function destroy($kode)
    {
        $kuota = KuotaKelas::where('kode', $kode)->first();

        if (!$kuota) {
            return redirect()->back()->with('error', 'Kuota kelas tidak ditemukan.');
        }

        $kuota->delete();

        return redirect()->back()->with('success', 'Kuota kelas berhasil dihapus.');
    }


}
