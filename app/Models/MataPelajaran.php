<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    // Kolom yang bisa diisi
    protected $fillable = [
        'nama_mapel',
        'kriteria_id',
        'kode_kriteria',
    ];

    // Relasi: satu mata pelajaran memiliki satu kriteria
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }

    // Relasi: satu mata pelajaran bisa punya banyak nilai
    public function nilai()
    {
        return $this->hasMany(Nilai::class, 'mata_pelajaran_id', 'id');
    }

}

