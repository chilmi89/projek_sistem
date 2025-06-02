<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubKriteria;

class Kriteria extends Model
{
    use HasFactory;
    protected $table = 'kriteria'; // optional, kalau nama tabel tidak plural

    protected $fillable = [
        'kode',
        'nama',
        'bobot_roc',
        'jenis',
    ];

    // Relasi: Kriteria punya banyak SubKriteria
    // Relasi: Kriteria punya banyak SubKriteria
    public function subKriterias()
    {
        return $this->hasMany(SubKriteria::class, 'kode_kriteria', 'kode');
    }

    public function hasilPerhitungans()
    {
        return $this->hasMany(HasilPerhitungan::class, 'kode_kriteria', 'kode');
    }

}
