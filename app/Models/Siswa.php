<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
    public function student()
    {
        return $this->hasOne(Student::class, 'siswa_id', 'id');
    }

    public function nilai(): HasOne
    {
        return $this->hasOne(Nilai::class);
    }
    // Relasi ke MataPelajaran melalui tabel nilai
}
