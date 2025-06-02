<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Siswa;
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
            'password' => 'required|min:6','confirmed',

        ]);

        try {
            // Simpan user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Simpan data siswa
            Siswa::create([
                'user_id' => $user->id,
                'nama' => $request->name,
            ]);

            // Berikan role siswa (pastikan role 'siswa' sudah dibuat)
            $user->assignRole('siswa');


            // ✅ JANGAN login di sini!
            return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Registrasi gagal! ' . $e->getMessage());
        }
    }

}
