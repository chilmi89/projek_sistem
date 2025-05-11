<?php

namespace App\Http\Controllers;

use App\Models\MataPelajaran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GuruController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $mataPelajaran = MataPelajaran::all();

        // Debugging: Log hasil query
        Log::info("Data Mata Pelajaran: ", ['data' => $mataPelajaran]);

        return view('guru.dashboard', compact('mataPelajaran'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0|max:100',
        ]);

        try {
            DB::transaction(function () use ($request) {
                MataPelajaran::create([
                    'nama_mapel' => $request->nama_mapel,
                    'bobot' => $request->bobot,
                ]);
            });

            return redirect()->back()->with('success', 'Mata pelajaran berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error("Gagal menyimpan mata pelajaran: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan mata pelajaran.');
        }
    }

    public function update(Request $request, $id)
    {
        Log::info("ID yang diterima untuk update: " . $id);

        // Validasi input
        $request->validate([
            'nama_mapel' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0|max:100',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $mataPelajaran = MataPelajaran::find($id);

                if (!$mataPelajaran) {
                    Log::error("Mata pelajaran dengan ID {$id} tidak ditemukan.");
                    throw new \Exception("Mata pelajaran tidak ditemukan.");
                }

                $mataPelajaran->update([
                    'nama_mapel' => $request->nama_mapel,
                    'bobot' => $request->bobot,
                ]);
            });

            return redirect()->back()->with('success', 'Mata pelajaran berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error("Gagal memperbarui mata pelajaran: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui mata pelajaran.');
        }
    }





    public function destroy($id)
    {
        try {
            $mataPelajaran = MataPelajaran::findOrFail($id);

            // Pastikan tidak ada data lain yang tergantung sebelum menghapus
            if ($mataPelajaran->nilai()->exists()) {
                return redirect()->back()->with('error', 'Mata pelajaran tidak dapat dihapus karena memiliki data nilai terkait.');
            }

            $mataPelajaran->delete();

            return redirect()->back()->with('success', 'Mata pelajaran berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error("Gagal menghapus mata pelajaran: " . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus mata pelajaran.');
        }
    } 




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
