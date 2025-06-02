<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;

class GuruController extends Controller
{
    /**
     * Tampilkan halaman dashboard guru
     */
    public function index()
    {
        $user = Auth::user();
        try {

            $kriterias = Kriteria::all();
            $subKriterias = SubKriteria::with('kriteria')->get(); // Tambahkan ini

            Log::info('Jumlah mata pelajaran: ');

            return view('guru.dashboard', compact('subKriterias', 'kriterias'));
        } catch (\Exception $e) {
            Log::error('Gagal mengambil data: ' . $e->getMessage());
            return view('guru.dashboard', [

                'kriterias' => collect(),
                'subKriterias' => collect(), // kosong juga kalau error
                'error' => 'Gagal memuat data. Silakan coba lagi nanti.'
            ]);
        }
    }


    /**
     * Simpan mata pelajaran baru
     */


    public function store(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'kriteria_id' => 'required|exists:kriteria,id',
        ]);

        try {
            $kriteria = Kriteria::findOrFail($request->kriteria_id);

            $data = [
                'nama_mapel' => $request->nama_mapel,
                'kriteria_id' => $kriteria->id,
                'kode_kriteria' => $kriteria->kode,
            ];

            // Debug data yang akan disimpan
            Log::info('Data untuk disimpan:', $data);

            MataPelajaran::create($data);

            return redirect()->back()->with('success', 'Mata pelajaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error("Gagal menambahkan mata pelajaran: {$e->getMessage()}");
            return redirect()->back()->with('error', 'Gagal menambahkan mata pelajaran.');
        }
    }




    /**
     * Perbarui mata pelajaran berdasarkan ID
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_mapel' => 'required|string|max:255',
        ]);

        try {
            $mataPelajaran = MataPelajaran::findOrFail($id);

            $mataPelajaran->update([
                'nama_mapel' => $request->nama_mapel,
            ]);

            return redirect()->back()->with('success', 'Mata pelajaran berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error("Gagal memperbarui mata pelajaran: {$e->getMessage()}");
            return redirect()->back()->with('error', 'Gagal memperbarui mata pelajaran.');
        }
    }

    /**
     * Hapus mata pelajaran berdasarkan ID
     */
    public function destroy($id)
    {
        try {
            $mataPelajaran = MataPelajaran::findOrFail($id);

            if ($mataPelajaran->nilai()->exists()) {
                return redirect()->back()->with('error', 'Mata pelajaran tidak dapat dihapus karena memiliki data nilai terkait.');
            }

            $mataPelajaran->delete();

            return redirect()->back()->with('success', 'Mata pelajaran berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error("Gagal menghapus mata pelajaran: {$e->getMessage()}");
            return redirect()->back()->with('error', 'Gagal menghapus mata pelajaran.');
        }
    }

    /**
     * Endpoint debug untuk melihat user yang sedang login
     */
    public function debugUser(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna belum login.'
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->roles->pluck('name')->toArray(),
            ]
        ]);
    }
}
