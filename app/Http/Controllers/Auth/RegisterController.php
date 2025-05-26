<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:6',
            'nisn' => 'required|unique:siswa,nisn', // validasi nisn
        ]);

        try {
            // Simpan user ke tabel users
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Beri role siswa (jika bukan guru)
            $user->assignRole('siswa');

            // Simpan ke tabel siswa
            \App\Models\Siswa::create([
                'user_id' => $user->id,
                'nama' => $request->name, // atau bisa input terpisah
                'nisn' => $request->nisn,
            ]);

            // Redirect
            return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Registrasi gagal! ' . $e->getMessage());
        }
    }
}
