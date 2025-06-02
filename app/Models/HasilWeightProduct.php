<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilWeightProduct extends Model
{
    use HasFactory;
    protected $table = 'hasil_weight_product';
    protected $fillable = [
        'nama_siswa',
        'c1', 'c2', 'c3', 'c4', 'c5',
        'c1_pow', 'c2_pow', 'c3_pow', 'c4_pow', 'c5_pow',
        'nilai_s',
        'c1_bagi', 'c2_bagi', 'c3_bagi', 'c4_bagi', 'c5_bagi',
        'rekomendasi_kriteria',
        'nilai_bagi_tertinggi',
        'alokasi_kelas',
    ];
}
