<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubKriteria;
class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'mtk_um',
        'ipa',
        'ips',
        'b_ing',
        'tes_iq',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function getMappedScores()
    {
        // Ambil max nilai tiap kriteria (bisa diambil dari DB, contoh hardcode)
        return [
            'student_id' => $this->id,
            'nama' => $this->nama,
            'c1' => SubKriteria::getNilaiByStudentScore('C1', $this->mtk_um) ?? 0,
            'c2' => SubKriteria::getNilaiByStudentScore('C2', $this->ipa) ?? 0,
            'c3' => SubKriteria::getNilaiByStudentScore('C3', $this->ips) ?? 0,
            'c4' => SubKriteria::getNilaiByStudentScore('C4', $this->b_ing) ?? 0,
            'c5' => SubKriteria::getNilaiByStudentScore('C5', $this->tes_iq) ?? 0,
            'created_at' => now(),
            'updated_at' => now(),

        ];
    }

    public function hasilBobot()
    {
        return $this->hasOne(HasilBobot::class);
    }
}
