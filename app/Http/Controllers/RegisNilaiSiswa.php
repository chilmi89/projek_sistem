<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use Illuminate\Http\Request;

class RegisNilaiSiswa extends Controller
{
    // public function index()
    // {
    //     $user = auth()->user();
    //     // Get siswa record for the logged-in user
    //     $siswa = Siswa::where('user_id', $user->id)->first();
    //     $mata_pelajaran = MataPelajaran::all();

    //     return view('siswa.Registrasi_Nilai', compact('mata_pelajaran', 'siswa'));
    // }

    public function index()
    {
        $user = auth()->user();
        $siswa = Siswa::where('user_id', $user->id)->with('nilai')->first(); // Preload nilai
        $mata_pelajaran = MataPelajaran::all();

        if (!$siswa) {
            return redirect()->route('dashboard')->with('error', 'Data siswa tidak ditemukan.');
        }

        return view('siswa.Registrasi_Nilai', compact('mata_pelajaran', 'siswa'));
    }


    // public function store(Request $request)
    // {
    //     try {
    //         $request->validate([
    //             'id_siswa' => 'required',
    //             'nilai' => 'required|array',
    //         ]);

    //         $user = auth()->user();

    //         // Get the siswa record
    //         $siswa = Siswa::where('user_id', $user->id)->first();

    //         if (!$siswa) {
    //             throw new \Exception('Data siswa tidak ditemukan.');
    //         }

    //         // Loop through each nilai and save it
    //         foreach ($request->nilai as $mata_pelajaran_id => $nilai_mapel) {
    //             // Verify that mata_pelajaran_id exists
    //             $mapel = MataPelajaran::find($mata_pelajaran_id);
    //             if (!$mapel) {
    //                 continue; // Skip if mata pelajaran doesn't exist
    //             }

    //             if ($nilai_mapel !== null) {
    //                 Nilai::updateOrCreate(
    //                     [
    //                         'siswa_id' => $siswa->id, // Use siswa->id instead of request->id_siswa
    //                         'mata_pelajaran_id' => $mata_pelajaran_id,
    //                     ],
    //                     [
    //                         'nilai_mapel' => $nilai_mapel,
    //                     ]
    //                 );
    //             }
    //         }

    //         return response()->json([
    //             'status' => 'success',
    //             'message' => 'Nilai berhasil disimpan!'
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'id_siswa' => 'required',
                'nilai' => 'required|array',
            ]);

            $user = auth()->user();

            // Get the siswa record
            $siswa = Siswa::where('user_id', $user->id)->first();

            if (!$siswa) {
                throw new \Exception('Data siswa tidak ditemukan.');
            }

            // Cek apakah siswa sudah pernah mengisi nilai
            $existingNilai = Nilai::where('siswa_id', $siswa->id)->exists();

            if ($existingNilai) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda sudah mengisi nilai, tidak dapat mengedit kembali!'
                ], 403);
            }

            // Loop through each nilai and save it
            foreach ($request->nilai as $mata_pelajaran_id => $nilai_mapel) {
                // Verify that mata_pelajaran_id exists
                $mapel = MataPelajaran::find($mata_pelajaran_id);
                if (!$mapel) {
                    continue; // Skip if mata pelajaran doesn't exist
                }

                if ($nilai_mapel !== null) {
                    Nilai::create([
                        'siswa_id' => $siswa->id,
                        'mata_pelajaran_id' => $mata_pelajaran_id,
                        'nilai_mapel' => $nilai_mapel,
                    ]);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Nilai berhasil disimpan!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
