<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Siswa;


use Illuminate\Http\Request;

class InputSiswaController extends Controller
{
    public function create()
    {
        $users = User::all(); // menampilkan user untuk di-relasi-kan
        return view('siswa.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama' => 'required|string|max:255',
            'nisn' => 'required|string|max:20|unique:siswa',
        ]);

        Siswa::create([
            'user_id' => $request->user_id,
            'nama' => $request->nama,
            'nisn' => $request->nisn,
        ]);

        return redirect()->route('siswa.create')->with('success', 'Data siswa berhasil ditambahkan!');
    }
}
