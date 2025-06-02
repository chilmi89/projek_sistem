<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    // Tidak pakai index, karena datanya akan diambil di controller Guru

    // Simpan kriteria baru, lalu redirect ke dashboard guru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:kriteria,kode',
            'nama' => 'required|string',
            'jenis' => 'required|in:Benefit,Cost',
        ]);

        Kriteria::create($validated);

        $this->hitungBobotROC();

        return redirect()->route('guru.dashboard')->with('success', 'Kriteria berhasil dibuat');
    }

    // Update kriteria, redirect ke dashboard guru
    public function update(Request $request, Kriteria $kriteria)
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:kriteria,kode,' . $kriteria->id,
            'nama' => 'required|string',
            'jenis' => 'required|in:Benefit,Cost',
        ]);

        $kriteria->update($validated);

        $this->hitungBobotROC();

        return redirect()->route('guru.dashboard')->with('success', 'Kriteria berhasil diperbarui');
    }

    // Hapus kriteria, redirect ke dashboard guru
    public function destroy(Kriteria $kriteria)
    {
        $kriteria->delete();

        return redirect()->route('guru.dashboard')->with('success', 'Kriteria berhasil dihapus');
    }

    private function hitungBobotROC()
    {
        $kriterias = Kriteria::orderBy('id', 'asc')->get();
        $n = $kriterias->count();

        $bobotROC = [];

        for ($i = 1; $i <= $n; $i++) {
            $sum = 0;
            for ($j = $i; $j <= $n; $j++) {
                $sum += 1 / $j;
            }
            $bobotROC[] = round($sum / $n, 2);  // 2 digit di belakang koma
        }

        foreach ($kriterias as $index => $kriteria) {
            $kriteria->update(['bobot_roc' => $bobotROC[$index]]);
        }
    }



}
