<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BobotKriteria extends Model
{
    use HasFactory;
    protected $table = 'bobot_kriteria';

    protected $fillable = [
        'kriteria_id',
        'kategori', 
        'rentang_nilai',
        'nilai_min',
        'nilai_max',
        'bobot',
        'roc',
        'keterangan',
    ];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }
}
