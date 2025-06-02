<?php
// 1. Cek error di Laravel Log
// File: storage/logs/laravel.log

// 2. Enable debugging di .env
// APP_DEBUG=true
// APP_ENV=local

// 3. Controller yang diperbaiki untuk menghindari error
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubKriteria;
use App\Models\Kriteria;
use Illuminate\Support\Facades\Log;

class SubKriteriaController extends Controller
{
    public function index()
    {
        try {
            // Inisialisasi dengan collection kosong sebagai fallback
            $subKriterias = collect();
            $kriterias = collect();

            // Coba query dengan error handling
            try {
                $subKriterias = SubKriteria::with('kriteria')->get();
            } catch (\Exception $e) {
                Log::error('Error getting subKriterias: ' . $e->getMessage());
                // Fallback ke query sederhana jika relasi bermasalah
                $subKriterias = SubKriteria::all();
            }

            try {
                $kriterias = Kriteria::all();
            } catch (\Exception $e) {
                Log::error('Error getting kriterias: ' . $e->getMessage());
            }

            // Debug log
            Log::info('SubKriterias count: ' . $subKriterias->count());
            Log::info('Kriterias count: ' . $kriterias->count());

            return view('guru.dashboard', compact('subKriterias', 'kriterias'));

        } catch (\Exception $e) {
            Log::error('Fatal error in SubKriteriaController@index: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());

            // Return view dengan data kosong dan error message
            return view('guru.dashboard', [
                'subKriterias' => collect(),
                'kriterias' => collect(),
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validasi dengan penanganan error yang lebih baik
            $validated = $request->validate([
                'kode_kriteria' => 'required|string|max:10',
                'sub_kriteria' => 'required|string|max:255',
                'nilai' => 'required|integer|min:1|max:5',
                'nilai_min' => 'required|integer',
                'nilai_max' => 'required|integer|gte:nilai_min',
            ]);

            SubKriteria::create($validated);

            return redirect()->route('sub-kriteria.index')
                ->with('success', 'Sub Kriteria berhasil ditambahkan!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error storing sub-kriteria: ' . $e->getMessage());
            return back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())
                        ->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $subKriteria = SubKriteria::findOrFail($id);

            $validated = $request->validate([
                'kode_kriteria' => 'required|string|max:10',
                'sub_kriteria' => 'required|string|max:255',
                'nilai' => 'required|integer|min:1|max:5',
                'nilai_min' => 'required|integer',
                'nilai_max' => 'required|integer|gte:nilai_min',
            ]);

            $subKriteria->update($validated);

            return redirect()->route('sub-kriteria.index')
                ->with('success', 'Sub Kriteria berhasil diperbarui!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error updating sub-kriteria: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $subKriteria = SubKriteria::findOrFail($id);
            $subKriteria->delete();

            return redirect()->route('sub-kriteria.index')
                ->with('success', 'Sub Kriteria berhasil dihapus!');

        } catch (\Exception $e) {
            Log::error('Error deleting sub-kriteria: ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
