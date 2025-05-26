<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BobotKriteria;
use App\Models\Kriteria;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\Log;
class BobotKriteriaController extends Controller
{
    public function index()
    {
        try {
            $bobotKriterias = BobotKriteria::with('kriteria')->get();
            $kriterias = Kriteria::all();
            $mataPelajaran = MataPelajaran::all(); // Tambahkan
            Log::info('BobotKriteriaController@index - Jumlah mata pelajaran: ' . $mataPelajaran->count());
            return view('guru.dashboard', compact('bobotKriterias', 'kriterias', 'mataPelajaran'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat data di index: ' . $e->getMessage());
            return view('guru.dashboard', [
                'bobotKriterias' => collect(),
                'kriterias' => collect(),
                'mataPelajaran' => collect(),
                'error' => 'Gagal memuat data: ' . $e->getMessage()
            ]);
        }
    }

    // Form tambah bobot_kriteria baru
    public function create()
    {
        try {
            $kriterias = Kriteria::all();
            $mataPelajaran = MataPelajaran::all(); // Tambahkan
            return view('guru.dashboard', compact('kriterias', 'mataPelajaran'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat data di create: ' . $e->getMessage());
            return view('guru.dashboard', [
                'kriterias' => collect(),
                'mataPelajaran' => collect(),
                'error' => 'Gagal memuat data: ' . $e->getMessage()
            ]);
        }
    }

    // Simpan bobot_kriteria baru
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'kriteria_id' => 'required|exists:kriteria,id',
                'kategori' => 'required|in:mata_pelajaran,iq',
                'rentang_nilai' => 'required|string',
                'nilai_min' => 'required|integer',
                'nilai_max' => 'required|integer',
            ]);

            [$bobot, $roc, $keterangan] = $this->hitungBobotROC(
                $validated['kategori'],
                $validated['nilai_min'],
                $validated['nilai_max']
            );

            $validated['bobot'] = $bobot;
            $validated['roc'] = $roc;
            $validated['keterangan'] = $keterangan;

            BobotKriteria::create($validated);

            return redirect()->route('bobot-kriteria.index')->with('success', 'Bobot Kriteria berhasil dibuat');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan bobot kriteria: ' . $e->getMessage());
            $kriterias = Kriteria::all();
            $mataPelajaran = MataPelajaran::all();
            return view('guru.dashboard', compact('kriterias', 'mataPelajaran'))
                ->with('error', 'Gagal menambahkan bobot kriteria: ' . $e->getMessage());
        }
    }

    // Form edit bobot_kriteria
    public function edit(BobotKriteria $bobotKriteria)
    {
        try {
            $kriterias = Kriteria::all();
            $mataPelajaran = MataPelajaran::all(); // Tambahkan
            return view('guru.dashboard', compact('bobotKriteria', 'kriterias', 'mataPelajaran'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat data di edit: ' . $e->getMessage());
            return view('guru.dashboard', [
                'bobotKriteria' => $bobotKriteria,
                'kriterias' => collect(),
                'mataPelajaran' => collect(),
                'error' => 'Gagal memuat data: ' . $e->getMessage()
            ]);
        }
    }

    // Update bobot_kriteria
   public function update(Request $request, BobotKriteria $bobotKriteria)
    {
        try {
            $validated = $request->validate([
                'kriteria_id' => 'required|exists:kriteria,id',
                'kategori' => 'required|in:mata_pelajaran,iq',
                'rentang_nilai' => 'required|string',
                'nilai_min' => 'required|integer',
                'nilai_max' => 'required|integer',
            ]);

            [$bobot, $roc, $keterangan] = $this->hitungBobotROC(
                $validated['kategori'],
                $validated['nilai_min'],
                $validated['nilai_max']
            );

            $validated['bobot'] = $bobot;
            $validated['roc'] = $roc;
            $validated['keterangan'] = $keterangan;

            $bobotKriteria->update($validated);

            return redirect()->route('bobot-kriteria.index')->with('success', 'Bobot Kriteria berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui bobot kriteria: ' . $e->getMessage());
            $kriterias = Kriteria::all();
            $mataPelajaran = MataPelajaran::all();
            return view('guru.dashboard', compact('kriterias', 'mataPelajaran'))
                ->with('error', 'Gagal memperbarui bobot kriteria: ' . $e->getMessage());
        }
    }

    // Hapus bobot_kriteria
    public function destroy(BobotKriteria $bobotKriteria)
    {
        try {
            $bobotKriteria->delete();
            return redirect()->route('bobot-kriteria.index')->with('success', 'Bobot Kriteria berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus bobot kriteria: ' . $e->getMessage());
            $kriterias = Kriteria::all();
            $mataPelajaran = MataPelajaran::all();
            return view('guru.dashboard', compact('kriterias', 'mataPelajaran'))
                ->with('error', 'Gagal menghapus bobot kriteria: ' . $e->getMessage());
        }
    }

    // Tampilkan detail bobot_kriteria (optional)
    public function show(BobotKriteria $bobotKriteria)
    {
        try {
            $bobotKriteria->load('kriteria');
            $kriterias = Kriteria::all();
            $mataPelajaran = MataPelajaran::all(); // Tambahkan
            return view('guru.dashboard', compact('bobotKriteria', 'kriterias', 'mataPelajaran'));
        } catch (\Exception $e) {
            Log::error('Gagal memuat detail bobot kriteria: ' . $e->getMessage());
            return view('guru.dashboard', [
                'bobotKriteria' => $bobotKriteria,
                'kriterias' => collect(),
                'mataPelajaran' => collect(),
                'error' => 'Gagal memuat data: ' . $e->getMessage()
            ]);
        }
    }

    private function hitungBobotROC($kategori, $nilai_min, $nilai_max)
    {
        // Untuk kategori mata pelajaran (C1 - C4)
        if ($kategori === 'mata_pelajaran') {
            if ($nilai_min >= 85 && $nilai_max <= 100) {
                return [5, 0.46, 'Sangat Baik'];
            } elseif ($nilai_min >= 75 && $nilai_max <= 85) {
                return [4, 0.26, 'Baik'];
            } elseif ($nilai_min >= 61 && $nilai_max <= 74) {
                return [3, 0.16, 'Cukup'];
            } elseif ($nilai_min >= 20 && $nilai_max <= 60) {
                return [2, 0.09, 'Buruk'];
            } 
        }

        // Untuk kategori IQ (C5)
        if ($kategori === 'iq') {
            if ($nilai_min >= 130) {
                return [5, 0.46, 'Superior'];
            } elseif ($nilai_min >= 115 && $nilai_max <= 129) {
                return [4, 0.26, 'Di Atas Rata-rata'];
            } elseif ($nilai_min >= 85 && $nilai_max <= 114) {
                return [3, 0.16, 'Rata-rata'];
            } elseif ($nilai_min >= 70 && $nilai_max <= 84) {
                return [2, 0.09, 'Rendah'];
            } 
        }

        return [null, null, 'Tidak Dikenal'];
    }
}
