<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SiswaController extends Controller
{
    public function index()
    {
        $siswas = Siswa::all();
        return view('siswa.regis_data_siswa', compact('siswas')); // Tidak dalam folder siswa
    }

    /**
     * Menampilkan form untuk menambahkan data siswa.
     */
    /**
     * Menyimpan data siswa yang baru dibuat ke database.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'nama' => 'required|string|max:255', // Tambahkan validasi untuk nama
            'nisn' => 'required|unique:siswa,nisn',
            'kelas' => 'required',
            'no_absen' => 'required|integer',
        ]);

        Siswa::create([
            'user_id' => Auth::id(), // Ambil ID user yang login
            'nama' => $request->nama, // Tambahkan nama
            'nisn' => $request->nisn,
            'kelas' => $request->kelas,
            'no_absen' => $request->no_absen,
        ]);

        return redirect()->route('siswa.index')->with('success', 'Data siswa berhasil ditambahkan!');
    }
}
