<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinatAwalSiswa extends Model
{
    use HasFactory;
    protected $table = 'minat_awal_siswa';

    protected $fillable = ['user_id', 'kode_kelas','nama_kriteria'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
