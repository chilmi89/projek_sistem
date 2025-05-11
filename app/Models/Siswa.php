<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{

    use HasFactory;

    protected $table = 'siswa'; // Pastikan menggunakan nama tabel yang benar

    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'nisn',
        'kelas',
        'no_absen',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'siswa_id'); // Sesuaikan dengan kolom foreign key
    }
}
