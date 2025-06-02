<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubKriteria extends Model
{
    use HasFactory;
    protected $table = 'sub_kriteria';

    protected $fillable = [
        'kode_kriteria',
        'sub_kriteria',
        'nilai',
        'nilai_min',
        'nilai_max',
    ];

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'kode_kriteria', 'kode');
    }
    public static function getNilaiByStudentScore($kodeKriteria, $studentScore)
    {
        return self::where('kode_kriteria', $kodeKriteria)
            ->where('nilai_min', '<=', $studentScore)
            ->where('nilai_max', '>=', $studentScore)
            ->value('nilai');
    }
}
