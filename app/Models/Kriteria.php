<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;
    protected $table = 'kriteria'; // optional, kalau nama tabel tidak plural

    protected $fillable = [
        'kode',
        'nama',
        'jenis',
    ];

    // Relasi: Kriteria punya banyak SubKriteria
    public function subKriterias()
    {
        return $this->hasMany(BobotKriteria::class, 'kriteria_id');
    }
    public function mataPelajaran()
    {
        return $this->belongsToMany(MataPelajaran::class, 'kriteria_mata_pelajaran')
            ->withPivot('bobot')
            ->withTimestamps();
    }
}
