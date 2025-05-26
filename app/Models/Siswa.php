<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'user_id',
        'nama',
        'nisn',
    ];
 // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Nilai
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'siswa_id');
    }

    // Relasi ke MataPelajaran melalui tabel nilai
    public function mataPelajaran()
    {
        return $this->hasManyThrough(
            MataPelajaran::class, // Model yang dituju
            Nilai::class,         // Model perantara
            'siswa_id',           // Foreign key di tabel nilai
            'id',                 // Foreign key di tabel mata_pelajaran
            'id',                 // Local key di tabel siswa
            'mata_pelajaran_id'   // Local key di tabel nilai
        );
    }
}
